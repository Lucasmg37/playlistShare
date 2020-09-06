<?php

namespace App\Transformer;

use App\Integrations\Spotify;
use App\Model\Entity\Usuarios;
use Exception;

class Usuario
{

    /**
     * @param array|Usuarios $parametros
     * @return array
     * @throws Exception
     */
    public static function getDataPublic($parametros)
    {
        $spotify = new Spotify();

        if (is_array($parametros)) {
            unset($parametros["st_senha"]);
            unset($parametros["st_login"]);
            unset($parametros["id_tipousuario"]);

            $parametros["bl_integracao"] = 0;
            $parametros["bl_premium"] = 0;

            if (!empty($parametros["id_usuario"])) {
                $id_usuario = $parametros["id_usuario"];
                $integracao = $spotify->getIntegracao($id_usuario);

                if ($integracao) {
                    $parametros["bl_integracao"] = 1;
                    $parametros["bl_premium"] = $integracao->getBlPremium() ? 1 : 0;
                }

            }

            return $parametros;
        }

        unset($parametros->st_senha);
        unset($parametros->st_login);
        unset($parametros->id_tipousuario);

        $parametros->bl_integracao = 0;
        $parametros->bl_premium = 0;

        if (!empty($parametros->id_usuario)) {
            $id_usuario = $parametros->id_usuario;
            $integracao = $spotify->getIntegracao($id_usuario);

            if ($integracao) {
                $parametros->bl_integracao = 1;
                $parametros->bl_premium = $integracao->getBlPremium() ? 1 : 0;
            }
        }


        return $parametros;

    }

}
