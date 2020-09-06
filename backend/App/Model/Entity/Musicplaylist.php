<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Musicplaylist
 * @package App\Model\Entity
 * @table tb_musicplaylist
 */
class Musicplaylist extends BdAction
{

    /**
     * Musicplaylist constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_musicplaylist
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_musicplaylist;


    /**
     * @var $id_spotify
     * @required
     */
    public $id_spotify;


    /**
     * @var $id_playlist
     * @required
     */
    public $id_playlist;


    /**
     * @var $dt_created
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $dt_created;


    /**
     * @var $bl_ativo
     * @required
     * @default 1
     */
    public $bl_ativo;


    /**
     * @var $dt_updated
     */
    public $dt_updated;



    /**
     * @return int
     */
    public function getIdMusicplaylist()
    {
        return $this->id_musicplaylist;
    }


    /**
     * @return string
     */
    public function getIdSpotify()
    {
        return $this->id_spotify;
    }


    /**
     * @return int
     */
    public function getIdPlaylist()
    {
        return $this->id_playlist;
    }


    /**
     * @return string
     */
    public function getDtCreated()
    {
        return $this->dt_created;
    }


    /**
     * @return boolean
     */
    public function getBlAtivo()
    {
        return $this->bl_ativo;
    }


    /**
     * @return string
     */
    public function getDtUpdated()
    {
        return $this->dt_updated;
    }



    /**
     * @param int $id_musicplaylist
     */
    public function setIdMusicplaylist($id_musicplaylist)
    {
        $this->id_musicplaylist = $id_musicplaylist;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $id_spotify
     */
    public function setIdSpotify($id_spotify)
    {
        $this->id_spotify = $id_spotify;
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


    /**
     * @param string $dt_created
     */
    public function setDtCreated($dt_created)
    {
        $this->dt_created = $dt_created;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_ativo
     */
    public function setBlAtivo($bl_ativo)
    {
        $this->bl_ativo = $bl_ativo;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $dt_updated
     */
    public function setDtUpdated($dt_updated)
    {
        $this->dt_updated = $dt_updated;
        $this->atualizaAtributos($this);
    }



}