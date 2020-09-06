<?php

namespace App\Model\Entity;

use App\Model\BdAction;
use Exception;

/**
 * Class Codeuser
 * @package App\Model\Entity
 * @table tb_codeuser
 */
class Codeuser extends BdAction
{

    /**
     * Codeuser constructor.
     * @param null $parameters
     * @throws Exception
     */
    public function __construct($parameters = null)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * @var $id_codeuser
     * @primary_key
     * @required
     * @auto_increment
     */
    public $id_codeuser;


    /**
     * @var $st_code
     * @required
     */
    public $st_code;


    /**
     * @var $id_usuario
     * @required
     */
    public $id_usuario;


    /**
     * @var $dt_create
     * @required
     * @default CURRENT_TIMESTAMP
     */
    public $dt_create;


    /**
     * @var $st_tipo
     * @required
     */
    public $st_tipo;



    /**
     * @return int
     */
    public function getIdCodeuser()
    {
        return $this->id_codeuser;
    }


    /**
     * @return string
     */
    public function getStCode()
    {
        return $this->st_code;
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
    public function getDtCreate()
    {
        return $this->dt_create;
    }


    /**
     * @return string
     */
    public function getStTipo()
    {
        return $this->st_tipo;
    }



    /**
     * @param int $id_codeuser
     */
    public function setIdCodeuser($id_codeuser)
    {
        $this->id_codeuser = $id_codeuser;
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
     * @param int $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
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
     * @param string $st_tipo
     */
    public function setStTipo($st_tipo)
    {
        $this->st_tipo = $st_tipo;
        $this->atualizaAtributos($this);
    }



}