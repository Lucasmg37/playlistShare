<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Youtubeapi
 * @package App\Model\Entity
 * @table tb_youtubeapi
 */
class Youtubeapi extends BdAction
{

    /**
     * Youtubeapi constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_youtubeapi
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_youtubeapi;


    /**
     * @var $st_search
     * @required
     */
    public $st_search;


    /**
     * @var $st_call
     * @required
     */
    public $st_call;


    /**
     * @var $dt_created
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $dt_created;


    /**
     * @var $st_track
     * @required
     */
    public $st_track;


    /**
     * @var $st_id
     */
    public $st_id;


    /**
     * @var $nu_duration
     */
    public $nu_duration;


    /**
     * @var $st_file
     */
    public $st_file;


    /**
     * @var $bl_ignored
     * @required
     * @default 0
     */
    public $bl_ignored;



    /**
     * @return int
     */
    public function getIdYoutubeapi()
    {
        return $this->id_youtubeapi;
    }


    /**
     * @return string
     */
    public function getStSearch()
    {
        return $this->st_search;
    }


    /**
     * @return string
     */
    public function getStCall()
    {
        return $this->st_call;
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
    public function getStTrack()
    {
        return $this->st_track;
    }


    /**
     * @return string
     */
    public function getStId()
    {
        return $this->st_id;
    }


    /**
     * @return int
     */
    public function getNuDuration()
    {
        return $this->nu_duration;
    }


    /**
     * @return string
     */
    public function getStFile()
    {
        return $this->st_file;
    }


    /**
     * @return boolean
     */
    public function getBlIgnored()
    {
        return $this->bl_ignored;
    }



    /**
     * @param int $id_youtubeapi
     */
    public function setIdYoutubeapi($id_youtubeapi)
    {
        $this->id_youtubeapi = $id_youtubeapi;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_search
     */
    public function setStSearch($st_search)
    {
        $this->st_search = $st_search;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_call
     */
    public function setStCall($st_call)
    {
        $this->st_call = $st_call;
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
     * @param string $st_track
     */
    public function setStTrack($st_track)
    {
        $this->st_track = $st_track;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_id
     */
    public function setStId($st_id)
    {
        $this->st_id = $st_id;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $nu_duration
     */
    public function setNuDuration($nu_duration)
    {
        $this->nu_duration = $nu_duration;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_file
     */
    public function setStFile($st_file)
    {
        $this->st_file = $st_file;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_ignored
     */
    public function setBlIgnored($bl_ignored)
    {
        $this->bl_ignored = $bl_ignored;
        $this->atualizaAtributos($this);
    }



}