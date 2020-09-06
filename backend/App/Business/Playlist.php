<?php


namespace App\Business;


use App\Integrations\Spotify;
use App\Model\Entity\VwPlaylist;
use App\Model\Model;
use App\Model\Validate;
use App\Transformer\PlaylistSpotify;
use Exception;

class Playlist
{

    /**
     * @return mixed
     * @throws Exception
     */
    public function getAllPlaylistsPublicas()
    {
        $vwplaylists = new VwPlaylist();

        $vwplaylists->setBlAtivo(1);
        $vwplaylists->setBlPrivada(0);
        return $vwplaylists->find();
    }

    /**
     * @param $id_playlist
     * @return VwPlaylist
     * @throws Exception
     */
    public function getPlaylist($id_playlist)
    {

        $id_usuario = Usuario::getUserLogged()->getIdUsuario();

        $playlist = new VwPlaylist();
        $playlist->setIdPlaylist($id_playlist);
        $playlist->mount($playlist->getFirst($playlist->find($id_playlist)));

        if ($playlist->getBlPrivada() && (int)$playlist->getIdUsuario() !== (int)$id_usuario) {
            throw new Exception("Você não tem permissões para visualizar esta playlist!");
        }

        return $playlist;
    }

    /**
     * @param $st_nome
     * @param $st_descricao
     * @param $id_usuario
     * @param $bl_privada
     * @param $bl_publicedit
     * @param $bl_sincronizar
     * @return \App\Model\Entity\Playlist
     * @throws Exception
     */
    public function createPlaylist($st_nome, $st_descricao, $id_usuario, $bl_privada, $bl_publicedit, $bl_sincronizar)
    {
        $playlist = new \App\Model\Entity\Playlist();
        $playlist->setBlAtivo(1);
        $playlist->setStDescricao($st_descricao);
        $playlist->setIdUsuario($id_usuario);
        $playlist->setBlPublicedit($bl_publicedit);
        $playlist->setBlSincronizar($bl_sincronizar ? "1" : "0");
        $playlist->setBlPrivada($bl_privada);
        $playlist->setDtCreate(Model::nowTime());
        $playlist->setDtUpdate(Model::nowTime());
        $playlist->setStNome($st_nome);

        $playlist->validate(Validate::PLAYLIST, array(), null, false);
        $playlist->save();
        return $playlist;

    }

    /**
     * @param $dataPlaylist
     * @return \App\Model\Entity\Playlist
     * @throws Exception
     */
    public function updatePlaylist($dataPlaylist)
    {
        if (!isset($dataPlaylist["id_playlist"])) {
            throw new Exception("É necessário informar o identificador da Playlist.");
        }

        self::ownerPlaylistUser($dataPlaylist["id_playlist"], null, "Usuário sem direitos para realizar esta ação!");

        $playlist = new \App\Model\Entity\Playlist();
        $playlist->validate(Validate::PLAYLIST, ["UPDATE"], $dataPlaylist, true);

        $playlist->clearObject();
        $playlist->findOne($dataPlaylist["id_playlist"]);
        $playlist->mount($dataPlaylist);

        $playlist->setBlAtivo(1);
        $playlist->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $playlist->setDtUpdate(Model::nowTime());

        $playlist->save();
        return $playlist;

    }

    /**
     * @param $id_playlist
     * @param null $id_usuario
     * @param null $messageException
     * @return bool
     * @throws Exception
     */
    public static function ownerPlaylistUser($id_playlist, $id_usuario = null, $messageException = null)
    {
        if (!$id_usuario) {
            $id_usuario = Usuario::getUserLogged()->getIdUsuario();
        }

        $playlist = new \App\Model\Entity\Playlist();
        $playlist->findOne($id_playlist);

        if ((int)$playlist->getIdUsuario() !== (int)$id_usuario) {
            if (!empty($messageException)) {
                throw new Exception($messageException);
            }
            return false;
        }

        return true;

    }

    /**
     * @param $st_idspotify
     * @return bool
     * @throws Exception
     */
    public function verificaExistenciaPlaylistSpotify($st_idspotify)
    {
        $spotify = new Spotify(true);
        $playists = $spotify->getUserPlaylist();

        foreach ($playists as $playist) {
            if ($playist["id"] === $st_idspotify) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \App\Model\Entity\Playlist $playlistEntity
     * @return mixed
     * @throws Exception
     */
    public function updatePlaylistFromSpotify($playlistEntity)
    {
        $spotify = new Spotify(true);
        $playlist = PlaylistSpotify::toObject($spotify->getPlaylist($playlistEntity->getStIdspotify()));

        $playlistEntity->setStNome($playlist->name);
        $playlistEntity->setStCapa($playlist->image);
        $playlistEntity->setStDescricao($playlist->description);
        $playlistEntity->setBlPrivada((int)!$playlist->public);
        $playlistEntity->setBlPublicedit((int)$playlist->collaborative);
        $playlistEntity->save();

        return $playlistEntity;

    }

    /**
     * @param $id_playlist
     * @return mixed
     * @throws Exception
     */
    public function getDiferencesBetween($id_playlist)
    {

        $add = [];
        $rem = [];

        $musics = new Musics();
        $musicas = $musics->getAllbyPlaylist($id_playlist);

        $playlistEntity = new \App\Model\Entity\Playlist();
        $playlistEntity->findOne($id_playlist);

        $spotify = new Spotify();
        $integracao = $spotify->getIntegracao(24);

        $spotify->setRefresherToken($integracao->getStRefreshtoken());
        $spotify->getAcessTokenUserByRefresherToken();
        $musicasSpotify = $spotify->getPlaylistTracks($playlistEntity->getStIdspotify());

        $origem = $musicas;
        $destino = $musicasSpotify;

        foreach ($origem as $musicaOrigem) {
            $possui = false;

            foreach ($destino as $musicaDestino) {
                if ($musicaOrigem["id_spotify"] === $musicaDestino["id_spotify"]) {
                    $possui = true;
                    break;
                }

            }

            //A música existe na origem, mas não no destino
            if (!$possui) {
                $add[] = [
                    "id_spotify" => $musicaOrigem["id_spotify"],
                    "st_urispotify" => $musicaOrigem["st_urispotify"],
                ];
            }

        }

        foreach ($destino as $musicaDestino) {
            $possui = false;

            foreach ($origem as $musicaOrigem) {
                if ($musicaOrigem["id_spotify"] === $musicaDestino["id_spotify"]) {
                    $possui = true;
                    break;
                }

            }

            //A música existe no destino, mas não na origem
            if (!$possui) {

                $rem[] = [
                    "id_spotify" => $musicaDestino["id_spotify"],
                    "st_urispotify" => $musicaDestino["st_urispotify"],
                ];

            }

        }

        $retorno["differences_list"] = $add;
        $retorno["differences_spotify"] = $rem;

        return $retorno;
    }

}
