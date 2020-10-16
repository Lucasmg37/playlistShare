<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Like
 * @package App\Model\Entity
 * @table tb_like
 */
class Like extends BdAction
{

    /**
     * Like constructor.
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
     * @var $user_id
     * @required
     * @foreign_key_table tb_usuarios
     * @foreign_key_column id_usuario
     */
    public $user_id;


    /**
     * @var $playlist_id
     * @required
     * @foreign_key_table tb_playlist
     * @foreign_key_column id_playlist
     */
    public $playlist_id;


    /**
     * @var $date_liked
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $date_liked;


    /**
     * @var $active
     * @required
     * @default 1
     */
    public $active;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int|Usuarios
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * @return int|Playlist
     */
    public function getPlaylistId()
    {
        return $this->playlist_id;
    }


    /**
     * @return string
     */
    public function getDateLiked()
    {
        return $this->date_liked;
    }


    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
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
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
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
     * @param string $date_liked
     */
    public function setDateLiked($date_liked)
    {
        $this->date_liked = $date_liked;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
        $this->atualizaAtributos($this);
    }



}