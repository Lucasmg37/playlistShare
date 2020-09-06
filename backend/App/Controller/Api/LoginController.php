<?php


namespace App\Controller\Api;


use App\Business\Usuario;
use App\Controller\Controller;
use App\Integrations\Spotify;
use App\Model\Entity\Spotifyintegracao;
use App\Transformer\UsuarioSpotify;
use App\Util\Helper;
use Exception;

class LoginController extends Controller
{
    /**
     * @return mixed|void
     * @throws Exception
     */
    public function postAction()
    {
        $st_login = $this->request->getParameter("st_email", true, "E-mail não informado!");
        $st_senha = $this->request->getParameter("st_senha", true, "Senha não informada!");

        $usuarioNegocio = new Usuario();
        $usuarioEntity = $usuarioNegocio->validaLogin($st_login, $st_senha);

        return Usuario::getDataLoginJwt($usuarioEntity);

    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function bySpotifyAction()
    {
        $code = $this->request->getParameter("code", true, "O código de acesso deve ser enviado!");
        $spotify = new Spotify();
        $spotify->setUserCode($code);
        $usuario = UsuarioSpotify::toObject($spotify->getUserData());

        //Verifica se existe este usuário no banco
        $usuarioNegocio = new Usuario();
        $usuarioEntity = $usuarioNegocio->validaLoginByIntegracao($usuario->id);

        //Se não tenho usuário, irei criar a sua conta
        if (!$usuarioEntity) {
            try {
                $this->getModel()->beginTransaction();
                $usuarioEntity = $usuarioNegocio->createUsuario($usuario->email, Helper::criptografaWithDate($usuario->id), $usuario->display_name, 1);

                $integracao = new Spotifyintegracao();
                $integracao->setStEmail($usuario->email);

                if ($integracao->isExists()) {
                    throw new Exception("Conta inexistente!");
                }
                $integracao->clearObject();
                $integracao->setStEmail($usuario->email);
                $integracao->setStCode($code);
                $integracao->setIdUsuario($usuarioEntity->getIdUsuario());
                $integracao->setStRefreshtoken($spotify->getRefresherToken());
                $integracao->setStAccesstoken($spotify->getAccessToken());
                $integracao->setStId($usuario->id);
                $integracao->setStDisplayname($usuario->display_name);
                $integracao->setStUri($usuario->uri);
                $integracao->setBlPremium($usuario->premium);
                $integracao->insert();

                $this->getModel()->commit();

            } catch (Exception $exception) {
                $this->getModel()->rollBack();
                throw $exception;
            }
        }

        return Usuario::getDataLoginJwt($usuarioEntity);

    }

}