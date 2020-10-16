<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class PlaylistFileGenerator
 * @package App\Model\Entity
 * @table tb_playlist_file_generator
 */
class PlaylistFileGenerator extends BdAction
{

    /**
     * PlaylistFileGenerator constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id;


    /**
     * @var $playlist_id
     * @required
     */
    public $playlist_id;


    /**
     * @var $status
     * @required
     */
    public $status;


    /**
     * @var $created
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $created;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getPlaylistId()
    {
        return $this->playlist_id;
    }


    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }



    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $playlist_id
     */
    public function setPlaylistId($playlist_id)
    {
        $this->playlist_id = $playlist_id;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
        $this->atualizaAtributos($this);
    }



}