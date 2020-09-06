<?php


namespace App\Controller\Api;


use App\Integrations\Spotify;
use Exception;

class PlayerController
{
    /**
     * @param $id_trackspotify
     * @return bool|mixed|string
     * @throws Exception
     */
    public function playTrackAction($id_trackspotify)
    {
        $spotify = new Spotify(true);
        return $spotify->playTrack($id_trackspotify);
    }

    /**
     * @param $id_playlistspotify
     * @return mixed
     * @throws Exception
     */
    public function playPlaylistAction($id_playlistspotify)
    {
        $spotify = new Spotify(true);
        return $spotify->playPlaylist($id_playlistspotify);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCurrentAction()
    {
        $spotify = new Spotify(true);
        return $spotify->getCurrentPlayer();
    }
}