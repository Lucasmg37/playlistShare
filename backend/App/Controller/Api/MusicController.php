<?php


namespace App\Controller\Api;


use App\Business\Musics;
use App\Controller\Controller;
use App\Integrations\Spotify;
use Exception;

class MusicController extends Controller
{

    /**
     * @param $id
     * @return array|void
     * @throws Exception
     */
    public function getAction($id)
    {
        $music = new Musics();
        return $music->getByName($id);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getTopTracksUserAction(){
        $spotify = new Spotify(true);
        return  $spotify->getTopTrack();
    }
}
