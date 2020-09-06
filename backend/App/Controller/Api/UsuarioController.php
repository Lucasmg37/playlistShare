<?php


namespace App\Controller\Api;


use App\Business\CodeUser;
use App\Business\Usuario;
use App\Controller\Controller;
use App\Model\Entity\Config;
use App\Model\Entity\Usuarios;
use App\Model\MailService;
use Exception;

class UsuarioController extends Controller
{

    /**
     * @param $id
     * @return array|void
     * @throws Exception
     */
    public function getAction($id)
    {
        $usuario = Usuario::getUserLogged();
        return \App\Transformer\Usuario::getDataPublic($usuario);
    }

    /**
     * @return Config
     * @throws Exception
     */
    public function getConfigAction()
    {

        $config = new Config();
        $config->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $config->findAndMount();
        return $config;
    }

    /**
     * @return Config
     * @throws Exception
     */
    public function saveConfigAction()
    {

        $bl_sincronizaclone = $this->request->getParameter("bl_sincronizaclone");
        $bl_buscamudancasspotify = $this->request->getParameter("bl_buscamudancasspotify");
        $bl_atualizaspotify = $this->request->getParameter("bl_atualizaspotify");
        $bl_deleteplaylistspotify = $this->request->getParameter("bl_deleteplaylistspotify");

        $config = new Config();
        $config->setIdUsuario(Usuario::getUserLogged()->getIdUsuario());
        $config->findAndMount();

        $config->setBlAtualizaspotify($bl_atualizaspotify);
        $config->setBlDeleteplaylistspotify($bl_deleteplaylistspotify);
        $config->setBlBuscamudancasspotify($bl_buscamudancasspotify);
        $config->setBlSincronizaclone($bl_sincronizaclone);
        $config->save();

        return $config;

    }

    /**
     * @return array
     * @throws Exception
     */
    public function activateAction()
    {
        $id_usuario = $this->request->getParameter("id_usuario", true, "O identificador do usuário deve ser informado!");
        $st_code = $this->request->getParameter("st_code", true, "O código de validação deve ser informado!");

        if (!CodeUser::verificarCode($id_usuario, $st_code, CodeUser::CODE_USER_TIPO_ACTIVATE)) {
            throw new Exception("Código informado inválido!");
        }

        $usuarioEntity = new Usuarios();
        $usuarioEntity->findOne($id_usuario);
        $usuarioEntity->setBlAtivo(1);
        $usuarioEntity->save();

        return \App\Transformer\Usuario::getDataPublic($usuarioEntity);
    }

    /**
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception | Exception
     */
    public function recoveryPasswordAction()
    {
        $st_login = $this->request->getParameter("st_email", true, "O email do usuário deve ser informado!");

        $usuarioEntity = new Usuarios();
        $usuarioEntity->setStLogin($st_login);
        $usuarioEntity->findAndMount();

        if (!$usuarioEntity->getIdUsuario()) {
            throw new Exception("Usuário não encontrado!");
        }

        if (!$usuarioEntity->getBlAtivo()) {
            throw new Exception("Usuários inativos não podem recuperar senha!");
        }

        $codeUser = CodeUser::generateCodeUser($usuarioEntity->getIdUsuario(), CodeUser::CODE_USER_TIPO_RECUPERATION);

        $mail = new MailService();
        $mail->sendEmailRecupercaodeSenha($usuarioEntity->getStLogin(), $usuarioEntity->getStNome(), $codeUser->getStCode());

        return true;

    }

    /**
     * @return array
     * @throws Exception
     */
    public function validateRecoveryAction()
    {
        $st_login = $this->request->getParameter("st_email", true, "O email do usuário deve ser informado!");
        $st_code = $this->request->getParameter("st_code", true, "O código de validação deve ser informado!");
        $st_senha = $this->request->getParameter("st_senha", true, "A nova senha deve ser informado!");

        $usuarioEntity = new Usuarios();
        $usuarioEntity->setStLogin($st_login);
        $usuarioEntity->findAndMount();

        if (!CodeUser::verificarCode($usuarioEntity->getIdUsuario(), $st_code, CodeUser::CODE_USER_TIPO_RECUPERATION)) {
            throw new Exception("Código de verificação inválido!");
        }

        $usuarioEntity->setStSenha($st_senha);
        $usuarioEntity->save();

        return \App\Transformer\Usuario::getDataPublic($usuarioEntity);

    }

    /**
     * @param $id_usuario
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception | Exception
     */
    public function resendEmailActivateAction($id_usuario)
    {
        $usuarioEntity = new Usuarios();
        $usuarioEntity->findOne($id_usuario);

        //Envia código de ativação no email
        $codeUserEntity = CodeUser::generateCodeUser($usuarioEntity->getIdUsuario(), CodeUser::CODE_USER_TIPO_ACTIVATE);

        $mail = new MailService();
        $mail->sendEmailActivate($usuarioEntity->getStLogin(), $usuarioEntity->getStNome(), $codeUserEntity->getStCode());

        return true;

    }

}
