<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Spotifyintegracao
 * @package App\Model\Entity
 * @table tb_spotifyintegracao
 */
class Spotifyintegracao extends BdAction
{

    /**
     * Spotifyintegracao constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_spotifyintegracao
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_spotifyintegracao;


    /**
     * @var $st_displayname
     * @required
     */
    public $st_displayname;


    /**
     * @var $st_email
     * @required
     */
    public $st_email;


    /**
     * @var $st_id
     * @required
     */
    public $st_id;


    /**
     * @var $st_uri
     * @required
     */
    public $st_uri;


    /**
     * @var $dt_create
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $dt_create;


    /**
     * @var $st_accesstoken
     */
    public $st_accesstoken;


    /**
     * @var $id_usuario
     * @required
     */
    public $id_usuario;


    /**
     * @var $st_code
     * @required
     */
    public $st_code;


    /**
     * @var $st_refreshtoken
     */
    public $st_refreshtoken;


    /**
     * @var $bl_premium
     * @required
     * @default 0
     */
    public $bl_premium;



    /**
     * @return int
     */
    public function getIdSpotifyintegracao()
    {
        return $this->id_spotifyintegracao;
    }


    /**
     * @return string
     */
    public function getStDisplayname()
    {
        return $this->st_displayname;
    }


    /**
     * @return string
     */
    public function getStEmail()
    {
        return $this->st_email;
    }


    /**
     * @return string
     */
    public function getStId()
    {
        return $this->st_id;
    }


    /**
     * @return string
     */
    public function getStUri()
    {
        return $this->st_uri;
    }


    /**
     * @return string
     */
    public function getDtCreate()
    {
        return $this->dt_create;
    }


    /**
     * @return string
     */
    public function getStAccesstoken()
    {
        return $this->st_accesstoken;
    }


    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }


    /**
     * @return string
     */
    public function getStCode()
    {
        return $this->st_code;
    }


    /**
     * @return string
     */
    public function getStRefreshtoken()
    {
        return $this->st_refreshtoken;
    }


    /**
     * @return boolean
     */
    public function getBlPremium()
    {
        return $this->bl_premium;
    }



    /**
     * @param int $id_spotifyintegracao
     */
    public function setIdSpotifyintegracao($id_spotifyintegracao)
    {
        $this->id_spotifyintegracao = $id_spotifyintegracao;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_displayname
     */
    public function setStDisplayname($st_displayname)
    {
        $this->st_displayname = $st_displayname;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_email
     */
    public function setStEmail($st_email)
    {
        $this->st_email = $st_email;
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
     * @param string $st_uri
     */
    public function setStUri($st_uri)
    {
        $this->st_uri = $st_uri;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $dt_create
     */
    public function setDtCreate($dt_create)
    {
        $this->dt_create = $dt_create;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_accesstoken
     */
    public function setStAccesstoken($st_accesstoken)
    {
        $this->st_accesstoken = $st_accesstoken;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_code
     */
    public function setStCode($st_code)
    {
        $this->st_code = $st_code;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_refreshtoken
     */
    public function setStRefreshtoken($st_refreshtoken)
    {
        $this->st_refreshtoken = $st_refreshtoken;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_premium
     */
    public function setBlPremium($bl_premium)
    {
        $this->bl_premium = $bl_premium;
        $this->atualizaAtributos($this);
    }



}