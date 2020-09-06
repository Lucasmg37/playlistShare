<?php


namespace App\Controller;


use App\Model\Integration\Integracao;
use App\Model\Integration\Spotify;
use App\Model\Negocio\Track;
use App\Model\Request;
use App\Model\Response;

class RoutineController
{

    public static function sincronizeTracks()
    {
        $request = new Request();
        $accessKey = $request->getParameter('accessKey', true);

        if ($accessKey !== 'bniopdajfhaj4f4sdf564gaxf5a6dsg423das2g3g4asf465') {
            throw new \Exception('Chave inválida!');
        }

        $trackObj = new Track();
        $tracks = $trackObj->getAllTracks();
        $spotify = new Spotify(null, false);
        $retorno = [];

        foreach ($tracks as $track) {

            $trackObj = new Track($track);

            $infoTrack = $spotify->getTrackInfo($trackObj->id_spotify);

            if (empty($infoTrack['id_spotify'])) {
                continue;
            }

            $infoTrack['st_nome'] = str_replace('"', ' - ', $infoTrack['st_nome']);
            $infoTrack['st_album'] = str_replace('"', ' - ', $infoTrack['st_album']);
            $infoTrack['st_artista'] = str_replace('"', ' - ', $infoTrack['st_artista']);

            //Verificar as diferenças
            if (!($trackObj->st_nome == $infoTrack['st_nome']
                && $trackObj->st_preview_url == $infoTrack['st_preview_url']
                && $trackObj->st_urlimagem == $infoTrack['st_urlimagem']
                && $trackObj->st_album == $infoTrack['st_album']
                && $trackObj->st_artista == $infoTrack['st_artista']
                && $trackObj->st_spotify == $infoTrack['st_spotify']
                && $trackObj->st_urispotify == $infoTrack['st_urispotify']
            )) {

                $trackSave = new Track($infoTrack);
                $trackSave->id_track = $trackObj->id_track;
                $trackSave->dt_created = $trackObj->dt_created;

                $retorno[] = $trackSave->getData(($trackSave->save()));

            }

        }

        Response::succesResponse('Tracks Atualizadas.', $retorno);

    }

}