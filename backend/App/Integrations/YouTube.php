<?php

namespace App\Integrations;

use App\Business\Musics;
use App\Constants\System\BdAction;
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

    public function downloadAll()
    {
        $trackEntity = new Track();
        $tracksTemp = $trackEntity->findAll();
        $tracks = [];

        foreach ($tracksTemp as $track) {
            if (!empty($track['yt_id'])) {
                $tracks[] = $track;
            }
        }


        if (empty($tracks)) {
            return 'Sem mídias integradas com o YouTube';
        }

        if (!is_dir('../Files')) {
            mkdir('../Files');
        }

        $result = [];

        foreach ($tracks as $track) {

            $yt_id = $track["yt_id"];

            if (is_file("../Files/$yt_id.mp3")) {
                continue;
            }

            $file = fopen("http://yt-convert-service:80//download/$yt_id.mp3", 'r');

            if ($file) {
                file_put_contents("../Files/$yt_id.mp3", $file);
                $result['success'][] = $yt_id;
                continue;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, 'http://yt-convert-service:80/convert.php?youtubelink=https://www.youtube.com/watch?v=' . $yt_id . '&format=mp3');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $response = json_decode($response);

            $file = $response->file;

            if ($response->error || !$file) {
                $result['error'][] = $yt_id;
                continue;
            }

            file_put_contents("../Files/$yt_id.mp3", fopen($file, 'r'));
            $result['success'][] = $yt_id;
        }

        return $result;
    }
}
