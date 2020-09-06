<?php


namespace App\Business;


use App\Integrations\Spotify;
use App\Model\Entity\Musicplaylist;
use App\Model\Entity\Track;
use App\Model\Model;
use Exception;

class Musics
{

    private $musicplaylist;

    /**
     * Musics constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->musicplaylist = new Musicplaylist();
    }

    /**
     * @param $id_playlist
     * @param $id_spotify
     * @return Musicplaylist
     * @throws Exception
     */
    public function createNewMusic($id_playlist, $id_spotify)
    {
        $this->musicplaylist->clearObject();
        $this->musicplaylist->setIdPlaylist($id_playlist);
        $this->musicplaylist->setBlAtivo(1);
        $this->musicplaylist->setDtCreated(Model::nowTime());
        $this->musicplaylist->setDtUpdated(Model::nowTime());
        $this->musicplaylist->setIdSpotify($id_spotify);
        $this->musicplaylist->insert();

        return $this->musicplaylist;

    }

    /**
     * @param $id_playlist
     * @return mixed
     * @throws Exception
     */
    public function getAllbyPlaylist($id_playlist)
    {
        $this->musicplaylist->clearObject();
        $this->musicplaylist->setBlAtivo(1);
        $this->musicplaylist->setIdPlaylist($id_playlist);
        $musics = $this->musicplaylist->find();

        foreach ($musics as &$music) {
            $this->musicplaylist->clearObject();
            $this->musicplaylist->mount($music);
            $music = array_merge($music, (array)$this->getTrackByIdSpotify($this->musicplaylist->getIdSpotify()));
        }

        return $musics;
    }

    /**
     * @param $id_spotify
     * @return Track
     * @throws Exception
     */
    public function getTrackByIdSpotify($id_spotify)
    {

        $track = new Track();
        $track->setIdSpotify($id_spotify);
        $track->mount($track->getFirst($track->find()));
        return $track->toArray();

    }

    /**
     * Busca as músicas pela API do Spotify
     * @param $name
     * @return array
     * @throws Exception
     */
    public function getByName($name)
    {

        $name = str_replace(" ", "%20", $name);

        $params = [
            "q" => $name,
            "type" => "track",
            "limit" => 50
        ];

        $spotify = new Spotify();
        return $spotify->listarMusicasByName($params);
    }

}
