<?php


namespace App\Controller\Api;


use App\Business\CodeUser;
use App\Business\Usuario;
use App\Controller\Controller;
use App\Integrations\Spotify;
use App\Model\Entity\Spotifyintegracao;
use App\Model\MailService;
use App\Transformer\UsuarioSpotify;
use App\Util\Helper;
use Exception;

class SignupController extends Controller
{

    /**
     * @return array|void
     * @throws Exception
     */
    public function postAction()
    {
        $st_login = $this->request->getParameter("st_login", true, "Email para login deve ser enviado!");
        $st_senha = $this->request->getParameter("st_senha", true, "Senha para login deve ser enviada!");
        $st_nome = $this->request->getParameter("st_nome", true, "Nome do usuário deve ser enviado!");

        $usuario = new Usuario();
        $usuarioEntity = $usuario->createUsuario($st_login, $st_senha, $st_nome, 0);

        //Envia código de ativação no email
        $codeUserEntity = CodeUser::generateCodeUser($usuarioEntity->getIdUsuario(), CodeUser::CODE_USER_TIPO_ACTIVATE);

        $mail = new MailService();
        $mail->sendEmailActivate($usuarioEntity->getStLogin(), $usuarioEntity->getStNome(), $codeUserEntity->getStCode());

        return \App\Transformer\Usuario::getDataPublic($usuarioEntity->toArray());

    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function bySpotifyAction()
    {

        $this->getModel()->beginTransaction();

        try {
            $st_code = $this->request->getParameter("code", true, "Código para integração não enviado!");

            $spotify = new Spotify();
            $spotify->setUserCode($st_code);
            $usuario = UsuarioSpotify::toObject($spotify->getUserData());

            $usuarioNegocio = new Usuario();
            $usuarioEntity = $usuarioNegocio->createUsuario($usuario->email, Helper::criptografaWithDate($usuario->id), $usuario->display_name, 1);

            $integracao = new Spotifyintegracao();
            $integracao->setStEmail($usuario->email);

            if ($integracao->isExists()) {
                throw new Exception("Conta já integrada!");
            }
            $integracao->clearObject();
            $integracao->setStEmail($usuario->email);
            $integracao->setStCode($st_code);
            $integracao->setIdUsuario($usuarioEntity->getIdUsuario());
            $integracao->setStRefreshtoken($spotify->getRefresherToken());
            $integracao->setStAccesstoken($spotify->getAccessToken());
            $integracao->setStId($usuario->id);
            $integracao->setStDisplayname($usuario->display_name);
            $integracao->setStUri($usuario->uri);
            $integracao->setBlPremium($usuario->premium);
            $integracao->insert();

            $this->getModel()->commit();

            return Usuario::getDataLoginJwt($usuarioEntity);

        } catch (Exception $exception) {
            $this->getModel()->rollBack();
            throw  $exception;
        }


    }

}