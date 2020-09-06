<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Acesso
 * @package App\Model\Entity
 * @table tb_acesso
 */
class Acesso extends BdAction
{

    /**
     * Acesso constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_acesso
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_acesso;


    /**
     * @var $id_playlist
     * @required
     */
    public $id_playlist;


    /**
     * @var $id_usuario
     */
    public $id_usuario;


    /**
     * @var $dt_acesso
     * @required
     */
    public $dt_acesso;



    /**
     * @return int
     */
    public function getIdAcesso()
    {
        return $this->id_acesso;
    }


    /**
     * @return int
     */
    public function getIdPlaylist()
    {
        return $this->id_playlist;
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
    public function getDtAcesso()
    {
        return $this->dt_acesso;
    }



    /**
     * @param int $id_acesso
     */
    public function setIdAcesso($id_acesso)
    {
        $this->id_acesso = $id_acesso;
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
     * @param int $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        $this->atualizaAtributos($this);
    }


    /**
     * @param string $dt_acesso
     */
    public function setDtAcesso($dt_acesso)
    {
        $this->dt_acesso = $dt_acesso;
        $this->atualizaAtributos($this);
    }



}