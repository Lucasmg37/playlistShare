<?php


namespace App\Model;


class Validate
{

    const DEFAULT_MESSAGE = "";

    const GLOBAL_VALIDATE = [];

    const PLAYLIST = [
        "GLOBAL" => [
            "st_nome" => "O nome da playlist deve ser informada!",
            "id_usuario" => "O identificador do usuário deve ser informado!",
        ],
        "UPDATE" => [
            "IGNORE" => ["id_usuario"],
            "id_playlist" => "O identificador da playlist deve ser enviado para realização da operação!"
        ]
    ];

}
