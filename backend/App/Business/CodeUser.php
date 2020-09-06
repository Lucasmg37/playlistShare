<?php


namespace App\Business;


use App\Util\Helper;

use Exception;

class CodeUser
{

    const CODE_USER_TIPO_ACTIVATE = "ACTIVATE";
    const CODE_USER_TIPO_RECUPERATION = "RECUPERATION";

    /**
     * @param $id_usuario
     * @param $tipo
     * @return \App\Model\Entity\Codeuser
     * @throws Exception
     */
    public static function generateCodeUser($id_usuario, $tipo)
    {
        $hash = Helper::criptografaWithDate();
        $hash = str_split($hash, 6)[0];

        $codeUserEntity = new \App\Model\Entity\Codeuser();
        $codeUserEntity->setIdUsuario($id_usuario);
        $codeUserEntity->setStCode(strtoupper($hash));
        $codeUserEntity->setStTipo($tipo);
        $codeUserEntity->insert();

        return $codeUserEntity;

    }

    /**
     * @param $id_usuario
     * @param $st_code
     * @param $st_tipo
     * @return bool
     * @throws Exception
     */
    public static function verificarCode($id_usuario, $st_code, $st_tipo)
    {
        $codeUserEntity = new \App\Model\Entity\Codeuser();
        $codeUserEntity->setIdUsuario($id_usuario);
        $codeUserEntity->setStTipo($st_tipo);
        $codeUserEntity->setStCode(strtoupper($st_code));
        return $codeUserEntity->isExists();
    }
}