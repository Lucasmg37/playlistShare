<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class VwPlaylist
 * @package App\Model\Entity
 * @table vw_playlist
 */
class VwPlaylist extends BdAction
{

    /**
     * VwPlaylist constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $vw_primary_id_playlist
     * @required
     * @default 0
     */
    public $vw_primary_id_playlist;


    /**
     * @var $id_playlist
     * @required
     * @default 0
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
     * @var $dt_create
     * @required
     * @default 0000-00-00 00:00:00
     */
    public $dt_create;


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
     * @var $bl_sincronizado
     * @default 0
     */
    public $bl_sincronizado;


    /**
     * @var $st_idspotify
     */
    public $st_idspotify;


    /**
     * @var $bl_publicedit
     * @required
     * @default 0
     */
    public $bl_publicedit;


    /**
     * @var $st_capa
     */
    public $st_capa;


    /**
     * @var $bl_sincronizar
     * @required
     */
    public $bl_sincronizar;


    /**
     * @var $st_nomeusuario
     * @required
     */
    public $st_nomeusuario;


    /**
     * @var $st_login
     * @required
     */
    public $st_login;


    /**
     * @var $st_displayname
     */
    public $st_displayname;


    /**
     * @var $st_email
     */
    public $st_email;


    /**
     * @var $nu_music
     * @default 0
     */
    public $nu_music;



    /**
     * @return int
     */
    public function getVwPrimaryIdPlaylist()
    {
        return $this->vw_primary_id_playlist;
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
    public function getDtCreate()
    {
        return $this->dt_create;
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
     * @return boolean
     */
    public function getBlSincronizado()
    {
        return $this->bl_sincronizado;
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
    public function getBlPublicedit()
    {
        return $this->bl_publicedit;
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
    public function getBlSincronizar()
    {
        return $this->bl_sincronizar;
    }


    /**
     * @return string
     */
    public function getStNomeusuario()
    {
        return $this->st_nomeusuario;
    }


    /**
     * @return string
     */
    public function getStLogin()
    {
        return $this->st_login;
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
     * @return int
     */
    public function getNuMusic()
    {
        return $this->nu_music;
    }



    /**
     * @param int $vw_primary_id_playlist
     */
    public function setVwPrimaryIdPlaylist($vw_primary_id_playlist)
    {
        $this->vw_primary_id_playlist = $vw_primary_id_playlist;
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
     * @param string $dt_create
     */
    public function setDtCreate($dt_create)
    {
        $this->dt_create = $dt_create;
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
     * @param boolean $bl_sincronizado
     */
    public function setBlSincronizado($bl_sincronizado)
    {
        $this->bl_sincronizado = $bl_sincronizado;
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
     * @param boolean $bl_publicedit
     */
    public function setBlPublicedit($bl_publicedit)
    {
        $this->bl_publicedit = $bl_publicedit;
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
     * @param boolean $bl_sincronizar
     */
    public function setBlSincronizar($bl_sincronizar)
    {
        $this->bl_sincronizar = $bl_sincronizar;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_nomeusuario
     */
    public function setStNomeusuario($st_nomeusuario)
    {
        $this->st_nomeusuario = $st_nomeusuario;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $st_login
     */
    public function setStLogin($st_login)
    {
        $this->st_login = $st_login;
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
     * @param int $nu_music
     */
    public function setNuMusic($nu_music)
    {
        $this->nu_music = $nu_music;
        $this->atualizaAtributos($this);
    }



}