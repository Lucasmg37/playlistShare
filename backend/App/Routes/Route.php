<?php

namespace App\Routes;

use Bootstrap\Router;
use Exception;

Class Route
{

    /**
     * Route constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->router->autenticated = false;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {

        //Rotas personalizadas
        $this->router->setNewRoute("GET", "Index", "render", false);
        $this->router->setNewRoute("GET", "Index", "execute", false);

        $this->router->setNewRoute("GET", "Usuario", "getConfig");
        $this->router->setNewRoute("POST", "Usuario", "saveConfig");
        $this->router->setNewRoute("POST", "Usuario", "activate");
        $this->router->setNewRoute("POST", "Usuario", "recoveryPassword");
        $this->router->setNewRoute("POST", "Usuario", "validateRecovery");
        $this->router->setNewRoute("POST", "Usuario", "resendEmailActivate");

        $this->router->setNewRoute("GET", "Playlist", "getMusics");
        $this->router->setNewRoute("GET", "Playlist", "getSpotify");
        $this->router->setNewRoute("GET", "Playlist", "myPlaylists");
        $this->router->setNewRoute("GET", "Playlist", "getTopPlaylists");
        $this->router->setNewRoute("GET", "Playlist", "getByName");
        $this->router->setNewRoute("POST", "Playlist", "makeCopy");
        $this->router->setNewRoute("POST", "Playlist", "newMusic");
        $this->router->setNewRoute("POST", "Playlist", "getPlaylistSpotify");
        $this->router->setNewRoute("DELETE", "Playlist", "removeMusic");

        $this->router->setNewRoute("GET", "Music", "getTopTracksUser");

        $this->router->setNewRoute("GET", "Player", "getCurrent");
        $this->router->setNewRoute("PUT", "Player", "playTrack");
        $this->router->setNewRoute("PUT", "Player", "playPlaylist");

        $this->router->setNewRoute("POST", "Login", "bySpotify");
        $this->router->setNewRoute("POST", "Signup", "bySpotify");

    }

    /**
     * Classe responsável por executar as definições criadas nas rotas
     * @var Router
     */
    public $router;

    /**
     * @param $verboHttp
     * @param $controller
     * @param $action
     * @param null $autenticated
     */
    public function setNewRoute($verboHttp, $controller, $action, $autenticated = null)
    {
        $this->router->setNewRoute($verboHttp, $controller, $action, $autenticated);
    }

    /**
     * @param $controller
     * @param $action
     * @param null $newCntroller
     * @param null $newActionl
     * @param null $autenticated
     * @throws Exception
     */
    public function changeRoute($controller, $action, $newCntroller = null, $newActionl = null, $autenticated = null)
    {
        $this->router->changeRoute($controller, $action, $newCntroller, $newActionl, $autenticated);
    }

    /**
     * @param $controller
     * @param $action
     * @throws Exception
     */
    public function noAutenticate($controller, $action)
    {
        $this->changeRoute($controller, $action, null, null, false);
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

}
