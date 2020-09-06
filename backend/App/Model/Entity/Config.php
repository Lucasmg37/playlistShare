<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Config
 * @package App\Model\Entity
 * @table tb_config
 */
class Config extends BdAction
{

    /**
     * Config constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_config
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_config;


    /**
     * @var $bl_sincronizaclone
     * @required
     * @default 0
     */
    public $bl_sincronizaclone;


    /**
     * @var $bl_atualizaspotify
     * @required
     * @default 1
     */
    public $bl_atualizaspotify;


    /**
     * @var $bl_buscamudancasspotify
     * @required
     * @default 1
     */
    public $bl_buscamudancasspotify;


    /**
     * @var $id_usuario
     * @required
     */
    public $id_usuario;


    /**
     * @var $bl_deleteplaylistspotify
     * @required
     * @default 0
     */
    public $bl_deleteplaylistspotify;



    /**
     * @return int
     */
    public function getIdConfig()
    {
        return $this->id_config;
    }


    /**
     * @return boolean
     */
    public function getBlSincronizaclone()
    {
        return $this->bl_sincronizaclone;
    }


    /**
     * @return boolean
     */
    public function getBlAtualizaspotify()
    {
        return $this->bl_atualizaspotify;
    }


    /**
     * @return boolean
     */
    public function getBlBuscamudancasspotify()
    {
        return $this->bl_buscamudancasspotify;
    }


    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }


    /**
     * @return boolean
     */
    public function getBlDeleteplaylistspotify()
    {
        return $this->bl_deleteplaylistspotify;
    }



    /**
     * @param int $id_config
     */
    public function setIdConfig($id_config)
    {
        $this->id_config = $id_config;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_sincronizaclone
     */
    public function setBlSincronizaclone($bl_sincronizaclone)
    {
        $this->bl_sincronizaclone = $bl_sincronizaclone;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_atualizaspotify
     */
    public function setBlAtualizaspotify($bl_atualizaspotify)
    {
        $this->bl_atualizaspotify = $bl_atualizaspotify;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_buscamudancasspotify
     */
    public function setBlBuscamudancasspotify($bl_buscamudancasspotify)
    {
        $this->bl_buscamudancasspotify = $bl_buscamudancasspotify;
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
     * @param boolean $bl_deleteplaylistspotify
     */
    public function setBlDeleteplaylistspotify($bl_deleteplaylistspotify)
    {
        $this->bl_deleteplaylistspotify = $bl_deleteplaylistspotify;
        $this->atualizaAtributos($this);
    }



}