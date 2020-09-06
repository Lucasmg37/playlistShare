<?php

namespace App\Integrations;

use App\Business\Musics;
use App\Model\Entity\Track;
use App\Model\Entity\Youtubeapi;
use App\Model\Model;

class YouTube
{

    public function getListVideoYoutube($playlist, $trackId = null)
    {

        if ($trackId) {
            $track = new Track();
            $track->findOne($trackId);
            $musics[] = $track->toArray();
        } else {
            $musicsb = new Musics();
            $musics = $musicsb->getAllbyPlaylist($playlist);
        }

        foreach ($musics as $music) {
            $search = $music['st_nome'] . ' ' . $music['st_artista'];
            $search = str_replace(" ", "%20", $search);

            if ($music['yt_id'] && $music['yt_active']  && $music['yt_conversion']) {
                continue;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, 'http://yt-convert-service:80/search.php?q=' . $search . '&max_results=1');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $response = json_decode($response);
            $url = $response->results[0]->full_link;

            if (empty($response->results[0])) {
                continue;
            }

            $youtuneApi = new Youtubeapi();
            $youtuneApi->setStSearch(str_replace("%20", " ", $search));
            $youtuneApi->setStCall('http://yt-convert-service:80/search.php?q=' . $search . '&max_results=1');
            $youtuneApi->setStFile($url);
            $youtuneApi->setStTrack($music['id_track']);
            $youtuneApi->insert();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, 'http://yt-convert-service:80/convert.php?youtubelink=' . $url . '&format=mp3');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $response = json_decode($response);

            $youtube_id = $response->youtube_id;
            $duration = $response->duration;
            $file = $response->file;

            if ($response->error || !$file) {
                $youtuneApi->setBlIgnored(1);
                $youtuneApi->save();
                continue;
            }

            $youtuneApi->setNuDuration($duration);
            $youtuneApi->setStFile($file);
            $youtuneApi->setStId($youtube_id);
            $youtuneApi->save();

            if (!is_dir('../Files')) {
                mkdir('../Files');
            }

            file_put_contents("../Files/$youtube_id.mp3", fopen($file, 'r'));

            $musicEntity = new Track();
            $musicEntity->findOne($music['id_track']);

            $musicEntity->setYtActive(1);
            $musicEntity->setYtConversion(1);
            $musicEntity->setYtId($youtube_id);
            $musicEntity->setYtDuration($duration);
            $musicEntity->setYtDt(Model::nowTime());
            $musicEntity->save();
        }

        return $musics;
    }
}
