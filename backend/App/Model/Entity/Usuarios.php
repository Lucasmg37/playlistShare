<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Usuarios
 * @package App\Model\Entity
 * @table tb_usuarios
 */
class Usuarios extends BdAction
{

    /**
     * Usuarios constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_usuario
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_usuario;


    /**
     * @var $st_nome
     * @required
     */
    public $st_nome;


    /**
     * @var $st_login
     * @required
     */
    public $st_login;


    /**
     * @var $st_senha
     * @required
     */
    public $st_senha;


    /**
     * @var $id_imagem
     */
    public $id_imagem;


    /**
     * @var $id_tipousuario
     * @required
     * @default 2
     */
    public $id_tipousuario;


    /**
     * @var $bl_ativo
     * @required
     * @default 0
     */
    public $bl_ativo;



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
    public function getStNome()
    {
        return $this->st_nome;
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
    public function getStSenha()
    {
        return $this->st_senha;
    }


    /**
     * @return int
     */
    public function getIdImagem()
    {
        return $this->id_imagem;
    }


    /**
     * @return int
     */
    public function getIdTipousuario()
    {
        return $this->id_tipousuario;
    }


    /**
     * @return boolean
     */
    public function getBlAtivo()
    {
        return $this->bl_ativo;
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
     * @param string $st_nome
     */
    public function setStNome($st_nome)
    {
        $this->st_nome = $st_nome;
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
     * @param string $st_senha
     */
    public function setStSenha($st_senha)
    {
        $this->st_senha = $st_senha;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $id_imagem
     */
    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
        $this->atualizaAtributos($this);
    }


    /**
     * @param int $id_tipousuario
     */
    public function setIdTipousuario($id_tipousuario)
    {
        $this->id_tipousuario = $id_tipousuario;
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



}