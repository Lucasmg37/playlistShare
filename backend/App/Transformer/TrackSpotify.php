<?php


namespace App\Transformer;


class TrackSpotify
{
    public $st_urlimagem;
    public $st_album;
    public $st_artista;
    public $st_spotify;
    public $id_spotify;
    public $st_nome;
    public $st_urispotify;
    public $st_preview_url;

    /**
     * @param $arrayTrack
     * @return TrackSpotify
     */
    public static function toObject($arrayTrack)
    {
        $trackTransformer = new TrackSpotify();
        $trackTransformer->st_urlimagem = isset($arrayTrack["st_urlimagem"]) ? $arrayTrack["st_urlimagem"] : null;
        $trackTransformer->st_album = isset($arrayTrack["st_album"]) ? $arrayTrack["st_album"] : null;
        $trackTransformer->st_artista = isset($arrayTrack["st_artista"]) ? $arrayTrack["st_artista"] : null;
        $trackTransformer->st_spotify = isset($arrayTrack["st_spotify"]) ? $arrayTrack["st_spotify"] : null;
        $trackTransformer->id_spotify = isset($arrayTrack["id_spotify"]) ? $arrayTrack["id_spotify"] : null;
        $trackTransformer->st_nome = isset($arrayTrack["st_nome"]) ? $arrayTrack["st_nome"] : null;
        $trackTransformer->st_urispotify = isset($arrayTrack["st_urispotify"]) ? $arrayTrack["st_urispotify"] : null;
        $trackTransformer->st_preview_url = isset($arrayTrack["st_preview_url"]) ? $arrayTrack["st_preview_url"] : null;

        return $trackTransformer;
    }

    /**
     * @param $track
     * @return array
     */
    public static function getSimpleDataTrack($track)
    {
        if ($track) {
            return [
                "st_urlimagem" => $track->album->images[0]->url,
                "st_album" => $track->album->name,
                "st_artista" => $track->artists[0]->name,
                "st_spotify" => $track->external_urls->spotify,
                "id_spotify" => $track->id,
                "st_nome" => $track->name,
                "st_urispotify" => $track->uri,
                "st_preview_url" => $track->preview_url
            ];
        }

        return null;
    }
}