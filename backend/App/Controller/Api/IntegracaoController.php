<?php


namespace App\Controller\Api;


use App\Business\Usuario;
use App\Controller\Controller;
use App\Integrations\Spotify;
use App\Model\Entity\Spotifyintegracao;
use App\Transformer\UsuarioSpotify;
use Exception;

class IntegracaoController extends Controller
{

    /**
     * @param $id
     * @return bool|void
     * @throws Exception
     */
    public function deleteAction($id)
    {
        $integracao = new Spotifyintegracao();
        $integracao->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $integracao->findAndMount();
        $integracao->delete();

        return true;
    }

    /**
     * @return Spotifyintegracao|void
     * @throws Exception
     */
    public function postAction()
    {
        $st_code = $this->request->getParameter("code", true, "Código para integração não enviado!");

        $spotify = new Spotify();
        $spotify->setUserCode($st_code);
        $usuario = UsuarioSpotify::toObject($spotify->getUserData());

        $integracao = new Spotifyintegracao();
        $integracao->setStEmail($usuario->email);

        if ($integracao->isExists()) {
            throw new Exception("Conta já integrada!");
        }
        $integracao->clearObject();
        $integracao->setStEmail($usuario->email);
        $integracao->setStCode($st_code);
        $integracao->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $integracao->setStRefreshtoken($spotify->getRefresherToken());
        $integracao->setStAccesstoken($spotify->getAccessToken());
        $integracao->setStId($usuario->id);
        $integracao->setStDisplayname($usuario->display_name);
        $integracao->setStUri($usuario->uri);
        $integracao->setBlPremium($usuario->premium);
        $integracao->insert();

        return $integracao;
    }

}