<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Track
 * @package App\Model\Entity
 * @table tb_track
 */
class Track extends BdAction
{

    /**
     * Track constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_track
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_track;


    /**
     * @var $id_spotify
     * @required
     */
    public $id_spotify;


    /**
     * @var $st_album
     * @required
     */
    public $st_album;


    /**
     * @var $st_artista
     * @required
     */
    public $st_artista;


    /**
     * @var $st_nome
     * @required
     */
    public $st_nome;


    /**
     * @var $st_preview_url
     */
    public $st_preview_url;


    /**
     * @var $st_spotify
     * @required
     */
    public $st_spotify;


    /**
     * @var $st_urispotify
     * @required
     */
    public $st_urispotify;


    /**
     * @var $st_urlimagem
     * @required
     */
    public $st_urlimagem;


    /**
     * @var $dt_created
     * @required
     */
    public $dt_created;


    /**
     * @var $yt_id
     */
    public $yt_id;


    /**
     * @var $yt_duration
     */
    public $yt_duration;


    /**
     * @var $yt_conversion
     * @required
     * @default 0
     */
    public $yt_conversion;


    /**
     * @var $yt_dt
     */
    public $yt_dt;


    /**
     * @var $yt_active
     * @required
     * @default 0
     */
    public $yt_active;



    /**
     * @return int
     */
    public function getIdTrack()
    {
        return $this->id_track;
    }


    /**
     * @return string
     */
    public function getIdSpotify()
    {
        return $this->id_spotify;
    }


    /**
     * @return string
     */
    public function getStAlbum()
    {
        return $this->st_album;
    }


    /**
     * @return string
     */
    public function getStArtista()
    {
        return $this->st_artista;
    }


    /**
     * @return string
     */
    public function getStNome()
    {
        return $this->st_nome;
    }


    /**
     * @return string
     */
    public function getStPreviewUrl()
    {
        return $this->st_preview_url;
    }


    /**
     * @return string
     */
    public function getStSpotify()
    {
        return $this->st_spotify;
    }


    /**
     * @return string
     */
    public function getStUrispotify()
    {
        return $this->st_urispotify;
    }


    /**
     * @return string
     */
    public function getStUrlimagem()
    {
        return $this->st_urlimagem;
    }


    /**
     * @return string
     */
    public function getDtCreated()
    {
        return $this->dt_created;
    }


    /**
     * @return string
     */
    public function getYtId()
    {
        return $this->yt_id;
    }


    /**
     * @return int
     */
    public function getYtDuration()
    {
        return $this->yt_duration;
    }


    /**
     * @return boolean
     */
    public function getYtConversion()
    {
        return $this->yt_conversion;
    }


    /**
     * @return timestamp
     */
    public function getYtDt()
    {
        return $this->yt_dt;
    }


    /**
     * @return boolean
     */
    public function getYtActive()
    {
        return $this->yt_active;
    }



    /**
     * @param int $id_track
     */
    public function setIdTrack($id_track)
    {
        $this->id_track = $id_track;
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
     * @param string $st_album
     */
    public function setStAlbum($st_album)
    {
        $this->st_album = $st_album;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_artista
     */
    public function setStArtista($st_artista)
    {
        $this->st_artista = $st_artista;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_nome
     */
    public function setStNome($st_nome)
    {
        $this->st_nome = $st_nome;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_preview_url
     */
    public function setStPreviewUrl($st_preview_url)
    {
        $this->st_preview_url = $st_preview_url;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_spotify
     */
    public function setStSpotify($st_spotify)
    {
        $this->st_spotify = $st_spotify;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_urispotify
     */
    public function setStUrispotify($st_urispotify)
    {
        $this->st_urispotify = $st_urispotify;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_urlimagem
     */
    public function setStUrlimagem($st_urlimagem)
    {
        $this->st_urlimagem = $st_urlimagem;
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
     * @param string $yt_id
     */
    public function setYtId($yt_id)
    {
        $this->yt_id = $yt_id;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $yt_duration
     */
    public function setYtDuration($yt_duration)
    {
        $this->yt_duration = $yt_duration;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $yt_conversion
     */
    public function setYtConversion($yt_conversion)
    {
        $this->yt_conversion = $yt_conversion;
        $this->atualizaAtributos($this);
    }


    /**
     * @param timestamp $yt_dt
     */
    public function setYtDt($yt_dt)
    {
        $this->yt_dt = $yt_dt;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $yt_active
     */
    public function setYtActive($yt_active)
    {
        $this->yt_active = $yt_active;
        $this->atualizaAtributos($this);
    }



}