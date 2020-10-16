<?php


namespace App\Controller\Api;

use App\Business\PlaylistFileGenerator;
use App\Business\Track;
use App\Constants\PlaylistFileGenerator as ConstantsPlaylistFileGenerator;
use App\Constants\System\BdAction;
use App\Controller\Controller;
use App\Integrations\InternalRequests;
use App\Model\Entity\Musicplaylist;
use App\Model\Entity\Playlist;
use App\Model\Entity\Track as EntityTrack;
use App\Model\Entity\VwTrackplaylist;
use App\Model\File;
use App\Model\Model;
use App\Model\Render;
use App\Model\Request;
use App\Model\Response;
use Bootstrap\Config;

class FileController extends Controller
{

    /**
     * @param $caminhoArquivo
     */
    public function getAction($caminhoArquivo)
    {

        $caminhoArquivo = "../Files/" . $caminhoArquivo;

        $mime = mime_content_type($caminhoArquivo);
        $tamanho = filesize($caminhoArquivo);

        if (!$mime) {
            Response::failResponse("Arquivo não encontrado!");
        }

        header("Content-Type: " . $mime);
        header("Content-Length: " . $tamanho);

        echo file_get_contents($caminhoArquivo);
        exit;
    }

    public function getPlaylistAction($id_playlist)
    {

        set_time_limit(3600);

        $playlist = new Playlist();
        $playlist->findOne($id_playlist);

        $playlistFileGenerateEntity = PlaylistFileGenerator::changeStatus($id_playlist, ConstantsPlaylistFileGenerator::GENERATING);

        if (!$playlistFileGenerateEntity) {
            return ["status" => ConstantsPlaylistFileGenerator::GENERATING];
        }

        $tracks = new Musicplaylist();
        $tracksList =  $tracks->findCustom("*", ["id_playlist" => BdAction::WHERE_EQUAL], false, ["dt_created" => "DESC"], 1,  ["id_playlist" => $id_playlist]);

        $tracks->mount($tracksList[0]);

        if (is_dir("../Files/Playlist/$id_playlist/")) {
            $datePath = scandir("../Files/Playlist/$id_playlist");

            if (sizeof($datePath) === 2) {
                $datePath = false;
            } else {
                $atualZip = $datePath[2];
                $datePath[2] = str_replace(".zip", "", $datePath[2]);
                $datePath = explode(" ", $datePath[2]);
                $datePath = $datePath[0] . " " . str_replace("-", ":", $datePath[1]);
            }
        }

        if ($datePath && strtotime($datePath) > strtotime($tracks->getDtCreated())) {
            PlaylistFileGenerator::changeStatus($id_playlist, ConstantsPlaylistFileGenerator::FINISH);
            return ["status" => ConstantsPlaylistFileGenerator::FINISH];
        }

        $internalRequest = new InternalRequests("File/generateZipFilePlaylist/" . $id_playlist);
        $internalRequest->execute();

        if ($atualZip) {
            unlink("../Files/Playlist/$id_playlist/$atualZip");
        }

        return ["status" => ConstantsPlaylistFileGenerator::GENERATING];

        // header('Content-Type: application/zip');
        // header('Content-disposition: attachment; filename=' . $playlist->getStNome() . ".zip");
        // header('Content-Length: ' . filesize($zipFile));
        // readfile($zipFile);
    }

    public function generateZipFilePlaylistAction(int $id_playlist)
    {
        $request = new Request();

        $st_key_internal_requests = $request->getParameter("st_key_internal_requests");

        $config = new Config();

        if ($config->getConfig("st_key_internal_requests") !== $st_key_internal_requests) {
            Response::failResponse("Unauthorized.");
        }

        $musicTrack = new VwTrackplaylist(["id_playlist" => $id_playlist, "yt_conversion" => 1]);
        $tracks = $musicTrack->find();

        $files  = [];

        foreach ($tracks as $track) {
            $trackObj = new EntityTrack($track);
            if ($trackObj->getYtId()) {
                $files[] = ["file" => "../Files/" . $trackObj->getYtId() . ".mp3", "name" =>  $trackObj->getStNome() . ".mp3"];
            }
        }

        $pathSave = "../Files/Playlist/$id_playlist/";
        $fileName = str_replace(":", "-", Model::nowTime()) . ".zip";
        File::createPath("/Playlist/$id_playlist/");
        File::createZipFile($files, $pathSave, $fileName);
        PlaylistFileGenerator::changeStatus($id_playlist, ConstantsPlaylistFileGenerator::FINISH);

        Response::succesResponse();
    }
}
