<?php


namespace App\Transformer;


class UsuarioSpotify
{

    public $display_name;
    public $email;
    public $id;
    public $type;
    public $uri;
    public $premium;

    /**
     * @param $arrayUser
     * @return UsuarioSpotify
     */
    public static function toObject($arrayUser)
    {
        $usuarioSpotify = new UsuarioSpotify();
        $usuarioSpotify->display_name = isset($arrayUser["display_name"]) ? $arrayUser["display_name"] : null;
        $usuarioSpotify->email = isset($arrayUser["email"]) ? $arrayUser["email"] : null;
        $usuarioSpotify->id = isset($arrayUser["id"]) ? $arrayUser["id"] : null;
        $usuarioSpotify->type = isset($arrayUser["type"]) ? $arrayUser["type"] : null;
        $usuarioSpotify->uri = isset($arrayUser["uri"]) ? $arrayUser["uri"] : null;
        $usuarioSpotify->premium = isset($arrayUser["premium"]) ? $arrayUser["premium"] : null;
        return $usuarioSpotify;
    }

    /**
     * @param $user
     * @return array
     */
    public static function getSimpleDataUser($user)
    {
        return [
            "display_name" => $user->display_name,
            "email" => $user->email,
            "id" => $user->id,
            "type" => $user->type,
            "uri" => $user->uri,
            "premium" => isset($user->product) && $user->product === "premium" ? true : false,
        ];
    }

}