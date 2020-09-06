<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class VwQtdmusicsinplaylists
 * @package App\Model\Entity
 * @table vw_qtdmusicsinplaylists
 */
class VwQtdmusicsinplaylists extends BdAction
{

    /**
     * VwQtdmusicsinplaylists constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $nu_music
     * @required
     * @default 0
     */
    public $nu_music;


    /**
     * @var $id_playlist
     * @required
     */
    public $id_playlist;



    /**
     * @return int
     */
    public function getNuMusic()
    {
        return $this->nu_music;
    }


    /**
     * @return int
     */
    public function getIdPlaylist()
    {
        return $this->id_playlist;
    }



    /**
     * @param int $nu_music
     */
    public function setNuMusic($nu_music)
    {
        $this->nu_music = $nu_music;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $id_playlist
     */
    public function setIdPlaylist($id_playlist)
    {
        $this->id_playlist = $id_playlist;
        $this->atualizaAtributos($this);
    }



}