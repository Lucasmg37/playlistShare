<?php


namespace App\Transformer;


use stdClass;

class PlaylistSpotify
{

    public $id;
    public $collaborative;
    public $description;
    public $name;
    public $uri;
    public $image;
    public $public;
    public $tracks;
    public $owner;

    /**
     * @param $arrayPlaylist
     * @return PlaylistSpotify
     */
    public static function toObject($arrayPlaylist)
    {
        $playlistSpotify = new PlaylistSpotify();
        $playlistSpotify->id = isset($arrayPlaylist["id"]) ? $arrayPlaylist["id"] : null;
        $playlistSpotify->collaborative = isset($arrayPlaylist["collaborative"]) ? $arrayPlaylist["collaborative"] : null;
        $playlistSpotify->description = isset($arrayPlaylist["description"]) ? $arrayPlaylist["description"] : null;
        $playlistSpotify->name = isset($arrayPlaylist["name"]) ? $arrayPlaylist["name"] : null;
        $playlistSpotify->uri = isset($arrayPlaylist["uri"]) ? $arrayPlaylist["uri"] : null;
        $playlistSpotify->image = isset($arrayPlaylist["image"]) ? $arrayPlaylist["image"] : null;
        $playlistSpotify->public = isset($arrayPlaylist["public"]) ? $arrayPlaylist["public"] : null;
        $playlistSpotify->tracks = isset($arrayPlaylist["tracks"]) ? $arrayPlaylist["tracks"] : null;

        $playlistSpotify->owner = new stdClass();

        $playlistSpotify->owner->display_name = isset($arrayPlaylist["owner"]["display_name"]) ? $arrayPlaylist["owner"]["display_name"] : null;
        $playlistSpotify->owner->id = isset($arrayPlaylist["owner"]["id"]) ? $arrayPlaylist["owner"]["id"] : null;
        $playlistSpotify->owner->type = isset($arrayPlaylist["owner"]["type"]) ? $arrayPlaylist["owner"]["type"] : null;
        $playlistSpotify->owner->uri = isset($arrayPlaylist["owner"]["uri"]) ? $arrayPlaylist["owner"]["uri"] : null;

        return $playlistSpotify;
    }

    /**
     * @param $playlist
     * @return array
     */
    public static function getSimpleDataPlaylist($playlist)
    {
        return [
            "id" => $playlist->id,
            "collaborative" => $playlist->collaborative,
            "description" => $playlist->description,
            "name" => $playlist->name,
            "uri" => $playlist->uri,
            "image" => isset($playlist->images[0]) ? $playlist->images[0]->url : null,
            "public" => $playlist->public,
            "tracks" => $playlist->tracks,
            "owner" => [
                "display_name" => $playlist->owner->display_name,
                "id" => $playlist->owner->id,
                "type" => $playlist->owner->type,
                "uri" => $playlist->owner->uri,
            ]
        ];

    }
}