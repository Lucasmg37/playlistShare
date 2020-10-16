<?php


namespace App\Integrations;


use App\Business\Usuario;
use App\Model\Entity\Playlist;
use App\Model\Entity\Usuarios;
use App\Model\Entity\Spotifyintegracao;
use App\Model\WebService;
use App\Transformer\PlayerSpotify;
use App\Transformer\PlaylistSpotify;
use App\Transformer\TrackSpotify;
use App\Transformer\UsuarioSpotify;
use App\Util\Helper;
use Bootstrap\Config;
use Exception;

class Spotify
{

    private $webservice;
    private $client_id;
    private $client_secret;

    private $access_token;
    private $user_code;
    private $refresher_token;

    /** @var Spotifyintegracao */
    public $integracao;

    /** @var Usuarios */
    private $usuario;

    const END_POINT_TOKEN = "https://accounts.spotify.com/api/token";

    const END_POINT_SOURCE = "https://api.spotify.com/v1/search";
    const END_POINT_USER = "https://api.spotify.com/v1/users";
    const END_POINT_TRACKS = "https://api.spotify.com/v1/tracks";
    const END_POINT_PLAYLIST = "https://api.spotify.com/v1/playlists";

    const END_POINT_ME = "https://api.spotify.com/v1/me";
    const END_POINT_USER_INFO = "https://api.spotify.com/v1/users";

    const END_POINT_ME_TOP_TRACKS = "https://api.spotify.com/v1/me/top/tracks";
    const END_POINT_ME_PLAYER_PLAY = "https://api.spotify.com/v1/me/player/play";
    const END_POINT_ME_PLAYER = "https://api.spotify.com/v1/me/player";

    const END_POINT_PLAYLIST_USER = "playlists";
    const END_POINT_PLAYLIST_TRACKS = "tracks";

    /**
     * @param $endpoint
     * @param null $id
     * @return bool|string
     */
    public function getEndPoint($endpoint, $id = null)
    {
        switch ($endpoint) {
            case self::END_POINT_PLAYLIST_USER:
                return self::END_POINT_USER . "/$id/" . self::END_POINT_PLAYLIST_USER;
            case self::END_POINT_PLAYLIST_TRACKS:
                return self::END_POINT_PLAYLIST . "/$id/" . self::END_POINT_PLAYLIST_TRACKS;
        }

        return false;
    }

    /**
     * Spotify constructor.
     * @param bool $isUser
     * @param null $id_usuario
     * @throws Exception
     */
    public function __construct($isUser = false, $id_usuario = null)
    {
        $config = new Config();
        $this->client_id = $config->getCustomConfig("spotify", "client_id");
        $this->client_secret = $config->getCustomConfig("spotify", "client_secret");

        $this->webservice = new WebService();
        $this->webservice->isArray();

        if ($isUser) {

            if (!$id_usuario) {
                $this->usuario = Usuario::getUserLogged();
                $id_usuario = $this->usuario->getIdUsuario();
            }

            $integracao = $this->getIntegracao($id_usuario, true);
            $this->integracao = $integracao;
            $this->refresher_token = $integracao->getStRefreshtoken();

            $this->getAcessTokenUserByRefresherToken();
        } else {
            $this->getTokenAcessApplication();
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCurrentPlayer()
    {
        $this->webservice->setHeader($this->getHeaderApplication());
        $this->webservice->setEndpoint(self::END_POINT_ME_PLAYER);
        $response = $this->isError($this->webservice->get());
        return PlayerSpotify::getSimpleDataPlayer($response);
    }

    /**
     * @param $id_spotify
     * @return bool|mixed|string
     * @throws Exception
     */
    public function playTrack($id_spotify)
    {

        $data["uris"] = ["spotify:track:$id_spotify"];
        $data["offset"]["position"] = 0;
        $data["position_ms"] = 0;

        return $this->play($data);
    }

    /**
     * @param $id_spotify
     * @return mixed
     * @throws Exception
     */
    public function playPlaylist($id_spotify)
    {

        $data["context_uri"] = "spotify:playlist:$id_spotify";
        $data["offset"]["position"] = 0;
        $data["position_ms"] = 0;

        return $this->play($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function play($data)
    {
        if (!$this->integracao->getBlPremium()) {
            throw new Exception("Ação disponível para usuários premium!");
        }

        $this->webservice->setHeader($this->getHeaderPostSpotify());
        $this->webservice->setEndpoint(self::END_POINT_ME_PLAYER_PLAY);
        $response = $this->webservice->put(json_encode($data));

        return $this->isError($response);
    }

    /**
     * @param array|string $st_uritrack
     * @param $st_playlisSpotify
     * @return bool
     * @throws Exception
     */
    public function addMusicsIntoPlaylist($st_uritrack, $st_playlisSpotify)
    {

        if (!is_array($st_uritrack)) {
            $data[] = $st_uritrack;
        } else {
            $data = ["uris" => $st_uritrack];
        }

        $this->webservice->setHeader($this->getHeaderPostSpotify());
        $this->webservice->setEndpoint($this->getEndPoint(self::END_POINT_PLAYLIST_TRACKS, $st_playlisSpotify));
        $retorno = $this->webservice->post(json_encode($data));
        $this->isError($retorno);
        return true;
    }

    /**
     * @param $st_uritrack
     * @param $st_playlisSpotify
     * @return bool
     * @throws Exception
     */
    public function removeMusicsIntoPlaylist($st_uritrack, $st_playlisSpotify)
    {

        if (!is_array(!$st_uritrack)) {
            $data["tracks"][]["uri"] = $st_uritrack;
        } else {
            $data = [];
            foreach ($st_uritrack as $uri) {
                $data["tracks"][]["uri"] = $uri;
            }
        }

        $this->webservice->setHeader($this->getHeaderPostSpotify());
        $this->webservice->setEndpoint($this->getEndPoint(self::END_POINT_PLAYLIST_TRACKS, $st_playlisSpotify));
        $retorno = $this->webservice->delete(json_encode($data));
        $this->isError($retorno);

        return true;
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function getTrack($id)
    {
        $this->webservice->setHeader($this->getHeaderApplication());
        $this->webservice->setEndpoint(self::END_POINT_TRACKS . "/$id");
        $track = $this->isError($this->webservice->get());
        return TrackSpotify::getSimpleDataTrack($track);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getTopTrack()
    {
        $this->webservice->setHeader($this->getHeaderApplication());
        $this->webservice->setEndpoint(self::END_POINT_ME_TOP_TRACKS);
        $tracks = $this->isError($this->webservice->get());

        $retorno = [];
        foreach ($tracks->items as $track) {
            $retorno[] = TrackSpotify::getSimpleDataTrack($track);
        }

        return $retorno;
    }


    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function getPlaylist($id)
    {
        $this->webservice->setHeader($this->getHeaderApplication());
        $this->webservice->setEndpoint(self::END_POINT_PLAYLIST . "/$id");
        $playlist = $this->isError($this->webservice->get());
        return PlaylistSpotify::getSimpleDataPlaylist($playlist);
    }

    /**
     * @param $code
     * @return $this
     * @throws Exception
     */
    public function setUserCode($code)
    {
        $this->user_code = $code;
        $this->getTokenAcessUserByCode();
        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getUserData($id_user = null)
    {
        $this->webservice->setHeader($this->getHeaderApplication());
        $this->webservice->setEndpoint(self::END_POINT_ME);

        if ($id_user) {
            $this->webservice->setEndpoint(self::END_POINT_USER_INFO . "/" . $id_user);
        }

        $user_info = $this->isError($this->webservice->get());

        return UsuarioSpotify::getSimpleDataUser($user_info);
    }

    /**
     * @param $id_playlist
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function getPlaylistTracks($id_playlist, $page = 0)
    {
        $numberForSource = 100;
        $all = false;
        $paramsPesquisa = [];
        $retorno = [];

        if ($page > 0) {
            $paramsPesquisa = [
                "offset" => $numberForSource * $page,
                "limit" => $numberForSource
            ];
        } else {
            $all = true;
        }

        do {

            $this->webservice->setHeader($this->getHeaderApplication());
            $this->webservice->setEndpoint(
                $this->getEndPoint(self::END_POINT_PLAYLIST_TRACKS, $id_playlist) . Helper::arrayToQuery($paramsPesquisa)
            );

            $tracks = $this->isError($this->webservice->get());

            if (!$all || empty($tracks->next)) {
                foreach ($tracks->items as $track) {
                    $track = TrackSpotify::getSimpleDataTrack($track->track);
                    if ($track) {
                        $retorno[] = $track;
                    }
                }
                break;
            }

            $paramsPesquisa = [
                "offset" => $tracks->offset + $tracks->limit,
                "limit" => $numberForSource
            ];

            foreach ($tracks->items as $track) {
                $retorno[] = TrackSpotify::getSimpleDataTrack($track->track);
            }
        } while (true);

        return $retorno;
    }

    /**
     * @param $id_usuario
     * @param bool $lancaErro
     * @return Spotifyintegracao|bool
     * @throws Exception
     */
    public function getIntegracao($id_usuario, $lancaErro = false)
    {
        if (empty($id_usuario)) {
            throw new Exception("Usuário não informado!");
        }

        $integracao = new Spotifyintegracao();
        $integracao->setIdUsuario($id_usuario);
        $integracao->mount($integracao->getFirst($integracao->find()));

        if (!$integracao->getIdSpotifyintegracao()) {
            if ($lancaErro) {
                throw new Exception("Usuário sem integração!");
            }

            return false;
        }

        return $integracao;
    }

    /**
     * @param $params
     * @return array
     * @throws Exception
     */
    public function listarMusicasByName($params)
    {
        $this->webservice->setHeader($this->getHeaderApplication());
        $this->webservice->setEndpoint(self::END_POINT_SOURCE . Helper::arrayToQuery($params));
        $tracks = $this->isError($this->webservice->get());

        $retorno = [];

        foreach ($tracks->tracks->items as $track) {
            $retorno[] = TrackSpotify::getSimpleDataTrack($track);
        }

        return $retorno;
    }

    /**
     * @return array
     */
    private function getHeaderPostSpotify()
    {
        return array(
            "Authorization: Bearer " . $this->access_token,
            'Content-Type: application/json'
        );
    }

    /**
     * @param int $page
     * @return mixed
     * @throws Exception
     */
    public function getUserPlaylist($page = 0)
    {
        $numberForSource = 20;
        $all = false;
        $paramsPesquisa = [];
        $retorno = [];

        if ($page > 0) {
            $paramsPesquisa = [
                "offset" => $numberForSource * $page,
                "limit" => $numberForSource
            ];
        } else {
            $all = true;
        }

        do {
            $this->webservice->setHeader($this->getHeaderApplication());
            $this->webservice->setEndpoint(
                $this->getEndPoint(self::END_POINT_PLAYLIST_USER, $this->integracao->getStId()) . Helper::arrayToQuery($paramsPesquisa)
            );
            $playlists = $this->isError($this->webservice->get());

            if (!$all || empty($playlists->next)) {
                foreach ($playlists->items as $playlist) {
                    $retorno[] = PlaylistSpotify::getSimpleDataPlaylist($playlist);
                }
                break;
            }

            $paramsPesquisa = [
                "offset" => $playlists->offset + $playlists->limit,
                "limit" => $numberForSource
            ];

            foreach ($playlists->items as $playlist) {
                $retorno[] = PlaylistSpotify::getSimpleDataPlaylist($playlist);
            }
        } while (true);

        return $retorno;
    }

    /**
     * @param Playlist $playlist
     * @return mixed
     * @throws Exception
     */
    public function createPlaylist($playlist)
    {
        $data = [
            "name" => $playlist->getStNome(),
            "public" => !$playlist->getBlPrivada(),
            "collaborative" => (bool)$playlist->getBlPublicedit(),
            "description" => $playlist->getStDescricao()
        ];

        $this->webservice->setHeader($this->getHeaderPostSpotify());
        $this->webservice->setEndpoint($this->getEndPoint(self::END_POINT_PLAYLIST_USER, $this->integracao->getStId()));
        $retorno = $this->webservice->post(json_encode($data));
        $this->isError($retorno);

        return PlaylistSpotify::getSimpleDataPlaylist($retorno);
    }

    /**
     * @param Playlist $playlist
     * @return bool
     * @throws Exception
     */
    public function updatePlaylist($playlist)
    {

        $data = [
            "name" => $playlist->getStNome(),
            "public" => !$playlist->getBlPrivada(),
            "collaborative" => (bool)$playlist->getBlPublicedit(),
            "description" => $playlist->getStDescricao()
        ];

        $this->webservice->setHeader($this->getHeaderPostSpotify());
        $this->webservice->setEndpoint(self::END_POINT_PLAYLIST . "/" . $playlist->getStIdspotify());
        $retorno = $this->webservice->put(json_encode($data));
        $this->isError($retorno);

        return true;
    }

    /**
     * @return string
     */
    private function getHeaderApplication()
    {
        return "Authorization: Bearer $this->access_token";
    }

    /**
     * @return $this
     * @throws Exception
     */
    private function getTokenAcessApplication()
    {
        $post_data = [
            "grant_type" => "client_credentials"
        ];

        $this->webservice->setEndpoint(self::END_POINT_TOKEN);
        $this->webservice->setHeader('Authorization: Basic ' . base64_encode($this->client_id . ':' . $this->client_secret));
        $result = $this->isError($this->webservice->post(http_build_query($post_data)));


        if (!isset($result->access_token)) {
            throw new Exception("Não foi possível recuperar o token de acesso");
        }

        $this->access_token = $result->access_token;
        return $this;
    }


    /**
     * Recupera Token de acesso e refresher token do usuário vinculado
     * @return mixed
     * @throws Exception
     */
    public function getTokenAcessUserByCode()
    {
        $post_data = [
            "grant_type" => "authorization_code",
            "code" => $this->user_code,
            "redirect_uri" => "http://localhost:3000/Perfil/"
        ];

        $this->webservice->setEndpoint(self::END_POINT_TOKEN);
        $this->webservice->setHeader('Authorization: Basic ' . base64_encode($this->client_id . ':' . $this->client_secret));

        $response = $this->webservice->post(http_build_query($post_data));
        $response = $this->isError($response);

        if (isset($response->access_token)) {
            $this->access_token = $response->access_token;
        }

        if (isset($response->access_token)) {
            $this->refresher_token = $response->refresh_token;
        }

        return $this;
    }

    /**
     * Recupera Token para realizar ações pela conta do usuário logado
     * @return $this
     * @throws Exception
     */
    public function getAcessTokenUserByRefresherToken()
    {
        $post_data = [
            "grant_type" => "refresh_token",
            "refresh_token" => $this->refresher_token
        ];

        $this->webservice->setEndpoint(self::END_POINT_TOKEN);
        $this->webservice->setHeader('Authorization: Basic ' . base64_encode($this->client_id . ':' . $this->client_secret));
        $result = $this->isError($this->webservice->post(http_build_query($post_data)));

        if (!isset($result->access_token)) {
            throw new Exception("Não foi possível recuperar o token de acesso");
        }

        $this->access_token = $result->access_token;
        return $this;
    }


    /**
     * @param $response
     * @param bool $isArray
     * @return mixed
     * @throws Exception
     */
    private function isError($response, $isArray = true)
    {
        if (!$isArray) {
            $response = json_decode($response, false);
        }

        if (isset($response->error) && isset($response->error->status)) {

            switch ($response->error->status) {
                case 401:
                    throw new Exception("Token não enviado!");
                    break;
                case 400:
                    throw new Exception($response->error->message);
                    break;
            }
        } else if (isset($response->error) && $response->error_description) {
            switch ($response->error) {
                case "invalid_client":
                    throw new Exception("Cliente inválido!");
                    break;
                case "invalid_grant":
                    throw new Exception("Código de autorização expirado!");
                    break;
            }
        } else if (isset($response->error)) {
            throw new Exception("Erro de integração não identificado!");
        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @return string
     */
    public function getRefresherToken()
    {
        return $this->refresher_token;
    }

    /**
     * @param string $refresher_token
     */
    public function setRefresherToken($refresher_token)
    {
        $this->refresher_token = $refresher_token;
    }
}
