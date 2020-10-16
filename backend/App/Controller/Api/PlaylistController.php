<?php


namespace App\Controller\Api;


use App\Business\Musics;
use App\Business\Playlist;
use App\Business\Track;
use App\Business\Usuario;
use App\Constants\System\BdAction;
use App\Controller\Controller;
use App\Integrations\Spotify;
use App\Model\Banco;
use App\Model\Entity\Acesso;
use App\Model\Entity\Like;
use App\Model\Entity\Musicplaylist;
use App\Model\Entity\Playlist as EntityPlaylist;
use App\Model\Entity\VwAcessoplaylist;
use App\Model\Entity\VwPlaylist;
use App\Model\Model;
use App\Transformer\PlaylistSpotify;
use App\Transformer\TrackSpotify;
use App\Transformer\UsuarioSpotify;
use Exception;

class PlaylistController extends Controller
{

    /**
     * @param $id_playlist
     * @return mixed
     * @throws Exception
     */
    public function getAction($id_playlist)
    {
        $playlist = new Playlist();
        if (empty($id_playlist)) {
            return $playlist->getAllPlaylistsPublicas();
        }

        $acesso = new Acesso();
        $acesso->setIdPlaylist($id_playlist);
        $acesso->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $acesso->setDtAcesso(Model::nowTime());
        $acesso->save();

        //Buscar Informações do spotify
        $playlistEntity = new \App\Model\Entity\Playlist();
        $playlistEntity->findOne($id_playlist);

        if ($playlistEntity->getBlSincronizado() && $playlistEntity->getBlSincronizar()) {
            $config = Usuario::getConfigUser();
            if ($config->getBlBuscamudancasspotify() && Playlist::ownerPlaylistUser($playlistEntity->getIdPlaylist())) {

                //Verifica se playlist não foi apagada
                if (!$playlist->verificaExistenciaPlaylistSpotify($playlistEntity->getStIdspotify())) {
                    $playlistEntity->setBlSincronizado("0");
                    $playlistEntity->setBlSincronizar("0");
                    $playlistEntity->setStIdspotify(null);
                    $playlistEntity->save();
                } else {
                    $playlist->updatePlaylistFromSpotify($playlistEntity);
                }
            }
        }

        return $playlist->getPlaylist($id_playlist);
    }

    /**
     * @throws Exception
     */
    public function newMusicAction()
    {
        $id_spotify = $this->request->getParameter("id_spotify", true, "O identificador da música não foi informado!");
        $id_playlist = $this->request->getParameter("id_playlist", true, "O identificador da playlist não foi informado!");

        Playlist::ownerPlaylistUser($id_playlist, null, "Usuário sem direitos para realizar esta ação!");

        $music = new Musics();

        $musicPlaylist = new Musicplaylist(["id_playlist" => $id_playlist, "id_spotify" => $id_spotify, "bl_ativo" => 1]);
        if (!$musicPlaylist->isExists()) {
            $musicPlaylist = $music->createNewMusic($id_playlist, $id_spotify);

            $track = new Track();
            $trackEntity = $track->createTrackIfnotExists($id_spotify);

            //Enviar música Para Spotify se a configuração estiver habilitada
            $playlistEntity = new \App\Model\Entity\Playlist();
            $playlistEntity->findOne($id_playlist);

            $config = Usuario::getConfigUser();
            if ($playlistEntity->getBlSincronizar() && $playlistEntity->getBlSincronizado() && $config->getBlAtualizaspotify()) {
                $spotify = new Spotify(true);
                $spotify->addMusicsIntoPlaylist($trackEntity->getStUrispotify(), $playlistEntity->getStIdspotify());
            }

            //Se não tem capa, defini a capa da música
            if (!$playlistEntity->getStCapa()) {
                $playlistEntity->setStCapa($trackEntity->getStUrlimagem());
                $playlistEntity->save();
            }

            return $musicPlaylist;
        }

        return "Música já vinculada!";
    }

    /**
     * @param $id_playlist
     * @return mixed
     * @throws Exception
     */
    public function getMusicsAction($id_playlist)
    {
        $playlist = new Playlist();

        //Buscar novas músicas no spotify
        $playlistEntity = new \App\Model\Entity\Playlist();
        $playlistEntity->findOne($id_playlist);

        if ($playlistEntity->getBlSincronizado() && $playlistEntity->getBlSincronizar()) {
            $config = Usuario::getConfigUser();
            if ($config->getBlBuscamudancasspotify() && Playlist::ownerPlaylistUser($playlistEntity->getIdPlaylist())) {
                $differences = $playlist->getDiferencesBetween($playlistEntity->getIdPlaylist());

                if (sizeof($differences["differences_spotify"]) > 0) {
                    $music = new Musics();

                    foreach ($differences["differences_spotify"] as $item) {
                        $musicPlaylist = new Musicplaylist(["id_playlist" => $id_playlist, "id_spotify" => $item["id_spotify"], "bl_ativo" => 1]);

                        if (!$musicPlaylist->isExists()) {
                            $music->createNewMusic($id_playlist, $item["id_spotify"]);
                            $track = new Track();
                            $track->createTrackIfnotExists($item["id_spotify"]);
                        }
                    }
                }

                if (sizeof($differences["differences_list"]) > 0) {

                    foreach ($differences["differences_list"] as $item) {
                        $musicPlaylist = new Musicplaylist(["id_playlist" => $id_playlist, "id_spotify" => $item["id_spotify"], "bl_ativo" => 1]);

                        if ($musicPlaylist->isExists()) {
                            $musicPlaylist->mount(["id_playlist" => $id_playlist, "id_spotify" => $item["id_spotify"], "bl_ativo" => 1]);
                            $musicPlaylist->findAndMount();
                            $musicPlaylist->setBlAtivo("0");
                            $musicPlaylist->save();
                        }
                    }
                }
            }
        }

        $musics = new Musics();
        return $musics->getAllbyPlaylist($id_playlist);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getTopPlaylistsAction()
    {
        $vwacessoplaylist = new VwAcessoplaylist();
        $vwacessoplaylist->setBlPrivada("0");
        return $vwacessoplaylist->find();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getByNameAction()
    {
        $search = $this->request->getParameter("search", true, "É necessário informar o termo da pesquisa.");

        $vw_playlist = new VwPlaylist();
        $vw_playlist->setBlPrivada("0");
        $vw_playlist->setBlAtivo(1);
        $vw_playlist->setStNome($search);

        return $vw_playlist->findCustom("*", [
            "st_nome" => BdAction::WHERE_LIKE,
            "bl_ativo" => BdAction::WHERE_EQUAL,
            "bl_privada" => BdAction::WHERE_EQUAL
        ]);
    }

    /**
     * @return \App\Model\Entity\Playlist|void
     * @throws Exception
     */
    public function postAction()
    {

        $id_playlist = $this->request->getParameter("id_playlist");

        if ($id_playlist) {
            return $this->putAction();
        }

        $st_nome = $this->request->getParameter("st_nome");
        $st_descricao = $this->request->getParameter("st_descricao");
        $id_usuario = Usuario::getUserLogged()->getIdUsuario();

        $bl_privada = $this->request->getParameter("bl_privada");
        $bl_publicedit = $this->request->getParameter("bl_publicedit");
        $bl_sincronizar = $this->request->getParameter("bl_sincronizar");

        $playlist = new Playlist();
        $playlistEntity = $playlist->createPlaylist($st_nome, $st_descricao, $id_usuario, $bl_privada, $bl_publicedit, $bl_sincronizar);

        if ($playlistEntity->getBlSincronizar() && !$playlistEntity->getBlSincronizado()) {
            $spotify = new Spotify(true);
            $playlistSpotify = $spotify->createPlaylist($playlistEntity);
            $playlistEntity->setStIdspotify($playlistSpotify["id"]);
            $playlistEntity->setBlSincronizado(1);
            $playlistEntity->save();
        }

        return $playlistEntity;
    }

    /**
     * @param $id_playlistmusic
     * @return Musicplaylist
     * @throws Exception
     */
    public function removeMusicAction($id_playlistmusic)
    {
        $musicPlaylist = new Musicplaylist();
        $musicPlaylist->findOne($id_playlistmusic);

        Playlist::ownerPlaylistUser($musicPlaylist->getIdPlaylist(), null, "Usuário sem direitos para realizar esta ação!");

        $musicPlaylist->setBlAtivo(0);
        $musicPlaylist->save();

        $playlistEntity = new \App\Model\Entity\Playlist();
        $playlistEntity->findOne($musicPlaylist->getIdPlaylist());

        $config = Usuario::getConfigUser();
        if ($playlistEntity->getBlSincronizar() && $playlistEntity->getBlSincronizado() && $config->getBlAtualizaspotify()) {

            $track = new Track();
            $track = $track->getTrackByIdSpotify($musicPlaylist->getIdSpotify());

            $spotify = new Spotify(true);
            $spotify->removeMusicsIntoPlaylist($track->getStUrispotify(), $playlistEntity->getStIdspotify());
        }

        return $musicPlaylist;
    }

    /**
     * @return \App\Model\Entity\Playlist|void
     * @throws Exception
     */
    public function putAction()
    {
        $data = $this->request->getAllParameters();
        $playlist = new Playlist();
        $playlistEntity = $playlist->updatePlaylist($data);

        if ($playlistEntity->getBlSincronizar() && $playlistEntity->getBlSincronizado()) {
            $spotify = new Spotify(true);
            $spotify->updatePlaylist($playlistEntity);
        } else if ($playlistEntity->getBlSincronizar() && !$playlistEntity->getBlSincronizado()) {
            $spotify = new Spotify(true);
            $playlistSpotify = $spotify->createPlaylist($playlistEntity);
            $playlistEntity->setStIdspotify($playlistSpotify["id"]);
            $playlistEntity->setBlSincronizado(1);
            $playlistEntity->save();

            $musics = new Musics();
            $musicas = $musics->getAllbyPlaylist($playlistEntity->getIdPlaylist());

            $send = [];
            foreach ($musicas as $musica) {
                $send[] = $musica["st_urispotify"];
            }
            $spotify->addMusicsIntoPlaylist($send, $playlistEntity->getStIdspotify());
        }

        return $this->getAction($playlistEntity->id_playlist);
    }

    /**
     * @param $id
     * @return bool|void
     * @throws Exception
     */
    public function deleteAction($id)
    {
        Playlist::ownerPlaylistUser($id, null, "Usuário sem permissões para realizar esta ação!");
        $playlist = new \App\Model\Entity\Playlist();
        $playlist->delete($id);
        return true;
    }

    /**
     * @throws Exception
     */
    public function getSpotifyAction()
    {
        $spotify = new Spotify(true);
        $playlists = $spotify->getUserPlaylist();
        $integracao = $spotify->integracao;

        $userPlaylist = [];
        $likedPlaylists = [];

        foreach ($playlists as &$playlistretorno) {
            $object = PlaylistSpotify::toObject($playlistretorno);
            $playlist = new \App\Model\Entity\Playlist([
                "st_idspotify" => $object->id,
                "bl_sincronizado" => 1,
                "bl_ativo" => 1,
            ]);

            $playlistretorno["bl_sincronizado"] = $playlist->isExists();

            if ($playlistretorno["bl_sincronizado"]) {
                $playlistretorno["playlist"] = $playlist;
            }

            if ($object->owner->id === $integracao->getStId()) {
                $userPlaylist[] = $playlistretorno;
            } else {
                $likedPlaylists[] = $playlistretorno;
            }
        }

        return [
            "user_playlists" => $userPlaylist,
            "liked_playlists" => $likedPlaylists
        ];
    }

    /**
     * @param $id_spotify
     * @return \App\Model\Entity\Playlist
     * @throws Exception
     */
    public function getPlaylistSpotifyAction($id_spotify)
    {
        $spotify = new Spotify(true);
        $playlistObject = PlaylistSpotify::toObject($spotify->getPlaylist($id_spotify));

        $id_usuario = Usuario::getUserLogged()->getIdUsuario();

        $ownerPlaylist = $playlistObject->owner->id;
        $integracao = $spotify->getIntegracao($id_usuario)->getStId();

        $playlistEntity = new \App\Model\Entity\Playlist([
            "bl_ativo" => 1,
            "st_idspotify" => $id_spotify
        ]);

        if ($playlistEntity->isExists()) {
            throw new Exception("Playlist já existe no Group List");
        }

        $playlist = new Playlist();
        $playlistEntity = $playlist->createPlaylist(
            $playlistObject->name,
            $playlistObject->description,
            $id_usuario,
            !$playlistObject->public,
            $playlistObject->collaborative,
            true
        );

        $playlistEntity->setStIdspotify($id_spotify);
        $playlistEntity->save();

        if ($integracao !== $ownerPlaylist) {
            $usuarioSpotify = UsuarioSpotify::toObject($spotify->getUserData($ownerPlaylist));
            $playlistEntity->setStIdownerspotify($usuarioSpotify->id);
            $playlistEntity->setStNameownerspotify($usuarioSpotify->display_name);
            $playlistEntity->setDtUpdatespotify(Model::nowTime());
            $playlistEntity->save();

            $like = new Like(['user_id' => $id_usuario, 'playlist_id' => $playlistEntity->getIdPlaylist()]);
            $like->insert();
        }

        $tracks = $spotify->getPlaylistTracks($id_spotify);

        foreach ($tracks as $track) {
            $object = TrackSpotify::toObject($track);
            $music = new Musics();
            $music->createNewMusic($playlistEntity->getIdPlaylist(), $object->id_spotify);

            $track = new Track();
            $track->createTrackIfnotExists($object->id_spotify);
        }

        $playlistEntity->setBlSincronizado(1);
        $playlistEntity->setStCapa($playlistObject->image);
        $playlistEntity->save();

        return $playlistEntity;
    }

    public function getMoreAccessAction()
    {
        return Model::executeSource(Banco::getConection(), "select pla.*, count(ac.id_playlist) as number_access from tb_acesso as ac join vw_playlist as pla on pla.id_playlist = ac.id_playlist where ac.id_usuario = 24 and dt_acesso > '2020-09-24 20:25:46' group by ac.id_playlist order by number_access desc limit 10");
    }

    public function getTracksPlaylistSpotifyAction($id_spotify)
    {
        $spotify = new Spotify(true);
        $tracks = $spotify->getPlaylistTracks($id_spotify);
        return $tracks;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function myPlaylistsAction()
    {
        $playlist = new \App\Model\Entity\VwPlaylist();
        $playlist->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $playlist->setHasOwnerPlatform(1);
        $playlist->setBlAtivo(1);
        $playlists = $playlist->find();
        return $playlists;
    }

    /**
     * @param $id_playlist
     * @return \App\Model\Entity\Playlist
     * @throws Exception
     */
    public function makeCopyAction($id_playlist)
    {
        if (empty($id_playlist)) {
            throw new Exception("Identificador da playlist inválido!");
        }

        $config = Usuario::getConfigUser();
        $spotify = new Spotify(true);

        $playlist = new \App\Model\Entity\Playlist();
        $playlist->findOne($id_playlist);
        $playlist->setIdPlaylist(null);
        $playlist->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $playlist->setDtCreate(Model::nowTime());
        $playlist->setDtUpdate(Model::nowTime());
        $playlist->setBlSincronizado(0);
        $playlist->setStIdspotify("");
        $playlist->setBlSincronizar((int)$config->getBlSincronizaclone());
        $playlist->insert();

        if ($config->getBlSincronizaclone()) {
            $playlistSpotify = PlaylistSpotify::toObject($spotify->createPlaylist($playlist));

            $playlist->setStIdspotify($playlistSpotify->id);
            $playlist->setBlSincronizado(1);
            $playlist->save();
        }

        $musics = new Musicplaylist();
        $musics->setIdPlaylist($id_playlist);
        $musics->setBlAtivo(1);
        $musicsArray = $musics->find();

        //Músicas a serem enviadas para a playlist
        $uris = [];
        $track = new \App\Model\Entity\Track();

        foreach ($musicsArray as $item) {
            $musicPlaylist = new Musicplaylist($item);
            $musicPlaylist->setIdMusicplaylist(null);
            $musicPlaylist->setIdPlaylist($playlist->getIdPlaylist());
            $musicPlaylist->setDtCreated(Model::nowTime());
            $musicPlaylist->setDtUpdated(Model::nowTime());
            $musicPlaylist->insert();

            if ($config->getBlSincronizaclone() && $playlist->getBlSincronizado()) {
                $track->clearObject();
                $track->setIdSpotify($musicPlaylist->getIdSpotify());
                $track->findAndMount();
                $uris[] = $track->getStUrispotify();
            }
        }

        if ($config->getBlSincronizaclone() && $playlist->getBlSincronizado()) {
            $spotify->addMusicsIntoPlaylist($uris, $playlist->getStIdspotify());
        }

        return $playlist;
    }


    public function sincAction($id_playlist)
    {

        $playlist = new Playlist();

        //Buscar novas músicas no spotify
        $playlistEntity = new \App\Model\Entity\Playlist();
        $playlistEntity->findOne($id_playlist);

        $playlist->updatePlaylistFromSpotify($playlistEntity);

        if ($playlistEntity->getBlSincronizado() && $playlistEntity->getBlSincronizar()) {
            $config = Usuario::getConfigUser();
            if ($config->getBlBuscamudancasspotify() && Playlist::ownerPlaylistUser($playlistEntity->getIdPlaylist())) {
                $differences = $playlist->getDiferencesBetween($playlistEntity->getIdPlaylist());

                if (sizeof($differences["differences_spotify"]) > 0) {
                    $music = new Musics();

                    foreach ($differences["differences_spotify"] as $item) {
                        $musicPlaylist = new Musicplaylist(["id_playlist" => $id_playlist, "id_spotify" => $item["id_spotify"], "bl_ativo" => 1]);

                        if (!$musicPlaylist->isExists()) {
                            $music->createNewMusic($id_playlist, $item["id_spotify"]);
                            $track = new Track();
                            $track->createTrackIfnotExists($item["id_spotify"]);
                        }
                    }
                }

                if (sizeof($differences["differences_list"]) > 0) {

                    foreach ($differences["differences_list"] as $item) {
                        $musicPlaylist = new Musicplaylist(["id_playlist" => $id_playlist, "id_spotify" => $item["id_spotify"], "bl_ativo" => 1]);

                        if ($musicPlaylist->isExists()) {
                            $musicPlaylist->mount(["id_playlist" => $id_playlist, "id_spotify" => $item["id_spotify"], "bl_ativo" => 1]);
                            $musicPlaylist->findAndMount();
                            $musicPlaylist->setBlAtivo("0");
                            $musicPlaylist->save();
                        }
                    }
                }
            }
        }
    }
}
