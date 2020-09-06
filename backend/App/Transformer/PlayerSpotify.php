<?php


namespace App\Transformer;


class PlayerSpotify
{

    /**
     * @param $response
     * @return array]
     */
    public static function getSimpleDataPlayer($response)
    {
        return [
            "is_playing" => isset($response->is_playing) ? $response->is_playing : null,
            "name" => isset($response->item->name) ? $response->item->name : null,
            "artists" => isset($response->item->artists[0]->name) ? $response->item->artists[0]->name : null,
            "progress_ms" => isset($response->progress_ms) ? $response->progress_ms : null,
            "duration_ms" => isset($response->item->duration_ms) ? $response->item->duration_ms : null,
            "currently_playing_type" => isset($response->currently_playing_type) ? $response->currently_playing_type : null,
            "repeat_state" => isset($response->repeat_state) ? $response->repeat_state : null,
        ];

    }

}