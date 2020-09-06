<?php


namespace App\Business;


use App\Integrations\Spotify;
use App\Model\Model;
use App\Transformer\TrackSpotify;
use Exception;

class Track
{
    /**
     * @param $id_spotify
     * @return \App\Model\Entity\Track
     * @throws Exception
     */
    public function createTrackIfnotExists($id_spotify)
    {

        $track = new \App\Model\Entity\Track();
        $track->setIdSpotify($id_spotify);
        $track->mount($track->getFirst($track->find()));

        if (!$track->getIdTrack()) {
            $spotify = new Spotify();
            $dataTrack = TrackSpotify::toObject($spotify->getTrack($id_spotify));
            $track->clearObject();
            $track->setIdSpotify($dataTrack->id_spotify);
            $track->setStNome($dataTrack->st_nome);
            $track->setDtCreated(Model::nowTime());
            $track->setStAlbum($dataTrack->st_album);
            $track->setStArtista($dataTrack->st_artista);
            $track->setStPreviewUrl($dataTrack->st_preview_url);
            $track->setStSpotify($dataTrack->st_spotify);
            $track->setStUrispotify($dataTrack->st_urispotify);
            $track->setStUrlimagem($dataTrack->st_urlimagem);
            $track->insert();
        }

        return $track;

    }

    /**
     * @param $id_spotify
     * @return \App\Model\Entity\Track
     * @throws Exception
     */
    public function getTrackByIdSpotify($id_spotify)
    {
        $track = new \App\Model\Entity\Track();
        $track->setIdSpotify($id_spotify);
        $track->findAndMount();
        return $track;
    }

}