<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Playlist
 * @package App\Model\Entity
 * @table tb_playlist
 */
class Playlist extends BdAction
{

    /**
     * Playlist constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_playlist
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_playlist;


    /**
     * @var $st_nome
     * @required
     */
    public $st_nome;


    /**
     * @var $bl_privada
     * @required
     * @default 0
     */
    public $bl_privada;


    /**
     * @var $st_senha
     */
    public $st_senha;


    /**
     * @var $dt_create
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $dt_create;


    /**
     * @var $dt_update
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $dt_update;


    /**
     * @var $id_usuario
     * @required
     */
    public $id_usuario;


    /**
     * @var $bl_ativo
     * @required
     * @default 1
     */
    public $bl_ativo;


    /**
     * @var $st_descricao
     */
    public $st_descricao;


    /**
     * @var $st_capa
     */
    public $st_capa;


    /**
     * @var $bl_publicedit
     * @required
     * @default 0
     */
    public $bl_publicedit;


    /**
     * @var $st_idspotify
     */
    public $st_idspotify;


    /**
     * @var $bl_sincronizado
     * @default 0
     */
    public $bl_sincronizado;


    /**
     * @var $bl_sincronizar
     * @required
     */
    public $bl_sincronizar;


    /**
     * @var $st_idownerspotify
     */
    public $st_idownerspotify;


    /**
     * @var $st_nameownerspotify
     */
    public $st_nameownerspotify;


    /**
     * @var $dt_updatespotify
     */
    public $dt_updatespotify;



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
    public function getStNome()
    {
        return $this->st_nome;
    }


    /**
     * @return boolean
     */
    public function getBlPrivada()
    {
        return $this->bl_privada;
    }


    /**
     * @return string
     */
    public function getStSenha()
    {
        return $this->st_senha;
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
    public function getDtUpdate()
    {
        return $this->dt_update;
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
    public function getBlAtivo()
    {
        return $this->bl_ativo;
    }


    /**
     * @return string
     */
    public function getStDescricao()
    {
        return $this->st_descricao;
    }


    /**
     * @return string
     */
    public function getStCapa()
    {
        return $this->st_capa;
    }


    /**
     * @return boolean
     */
    public function getBlPublicedit()
    {
        return $this->bl_publicedit;
    }


    /**
     * @return string
     */
    public function getStIdspotify()
    {
        return $this->st_idspotify;
    }


    /**
     * @return boolean
     */
    public function getBlSincronizado()
    {
        return $this->bl_sincronizado;
    }


    /**
     * @return boolean
     */
    public function getBlSincronizar()
    {
        return $this->bl_sincronizar;
    }


    /**
     * @return string
     */
    public function getStIdownerspotify()
    {
        return $this->st_idownerspotify;
    }


    /**
     * @return string
     */
    public function getStNameownerspotify()
    {
        return $this->st_nameownerspotify;
    }


    /**
     * @return string
     */
    public function getDtUpdatespotify()
    {
        return $this->dt_updatespotify;
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
     * @param string $st_nome
     */
    public function setStNome($st_nome)
    {
        $this->st_nome = $st_nome;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_privada
     */
    public function setBlPrivada($bl_privada)
    {
        $this->bl_privada = $bl_privada;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_senha
     */
    public function setStSenha($st_senha)
    {
        $this->st_senha = $st_senha;
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
     * @param string $dt_update
     */
    public function setDtUpdate($dt_update)
    {
        $this->dt_update = $dt_update;
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
     * @param boolean $bl_ativo
     */
    public function setBlAtivo($bl_ativo)
    {
        $this->bl_ativo = $bl_ativo;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_descricao
     */
    public function setStDescricao($st_descricao)
    {
        $this->st_descricao = $st_descricao;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_capa
     */
    public function setStCapa($st_capa)
    {
        $this->st_capa = $st_capa;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_publicedit
     */
    public function setBlPublicedit($bl_publicedit)
    {
        $this->bl_publicedit = $bl_publicedit;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_idspotify
     */
    public function setStIdspotify($st_idspotify)
    {
        $this->st_idspotify = $st_idspotify;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_sincronizado
     */
    public function setBlSincronizado($bl_sincronizado)
    {
        $this->bl_sincronizado = $bl_sincronizado;
        $this->atualizaAtributos($this);
    }


    /**
     * @param boolean $bl_sincronizar
     */
    public function setBlSincronizar($bl_sincronizar)
    {
        $this->bl_sincronizar = $bl_sincronizar;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_idownerspotify
     */
    public function setStIdownerspotify($st_idownerspotify)
    {
        $this->st_idownerspotify = $st_idownerspotify;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_nameownerspotify
     */
    public function setStNameownerspotify($st_nameownerspotify)
    {
        $this->st_nameownerspotify = $st_nameownerspotify;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $dt_updatespotify
     */
    public function setDtUpdatespotify($dt_updatespotify)
    {
        $this->dt_updatespotify = $dt_updatespotify;
        $this->atualizaAtributos($this);
    }



}