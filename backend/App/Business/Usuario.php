<?php


namespace App\Business;


use App\Integrations\Spotify;
use App\Model\Entity\Config;
use App\Model\Entity\Spotifyintegracao;
use App\Model\Entity\Usuarios;
use App\Model\Response;
use App\Util\Debug;
use App\Util\JWT;
use App\Util\Token;
use Exception;

class Usuario
{

    private $usuario;

    public function __construct()
    {
        $this->usuario = new Usuarios();
    }

    /**
     * @param $st_login
     * @param $st_senha
     * @return Usuarios
     * @throws Exception
     */
    public function validaLogin($st_login, $st_senha)
    {
        $this->usuario->clearObject();
        $this->usuario->setStLogin($st_login);
        $this->usuario->mount($this->usuario->getFirst($this->usuario->find()));

        if (!$this->usuario->getIdUsuario()) {
            throw new Exception("Usuário não encontrado!");
        }

        if (!$this->usuario->getBlAtivo()) {
            Response::failResponse("Usuário sem confirmação de email!", \App\Transformer\Usuario::getDataPublic($this->usuario));
            throw new Exception("Usuário sem confirmação de email!");
        }

        if ($this->usuario->getStSenha() !== $st_senha) {
            throw new Exception("Senha incorreta!");
        }

        return $this->usuario;
    }

    /**
     * @param $id_usuariospotify
     * @return Usuarios|bool
     * @throws Exception
     */
    public function validaLoginByIntegracao($id_usuariospotify)
    {
        $integracao = new Spotifyintegracao();
        $integracao->setStId($id_usuariospotify);
        $integracao->findAndMount();

        if (!$integracao->getIdUsuario()) {
            return false;
        }

        $usuario = new Usuarios();
        $usuario->findOne($integracao->getIdUsuario());
        return $usuario;

    }

    /**
     * @return Usuarios
     * @throws Exception
     */
    public static function getUserLogged()
    {
        $jwt = new JWT();
        $data = $jwt->getDataToken(Token::getTokenByAuthorizationHeader());
        $usuario = new Usuarios();
        $usuario->findOne($data["userid"]);

        return $usuario;
    }

    /**
     * @param null $id_usuario
     * @return Config
     * @throws Exception
     */
    public static function getConfigUser($id_usuario = null)
    {
        if ($id_usuario) {
            $usuario = new Usuarios();
            $usuario->findOne($id_usuario);
        } else {
            $usuario = Usuario::getUserLogged();
        }

        $config = new Config();
        $config->setIdUsuario($usuario->getIdUsuario());
        $config->mount($config->getFirst($config->find()));
        return $config;
    }

    /**
     * @param $st_login
     * @param $st_senha
     * @param $st_nome
     * @param int $bl_ativo
     * @return Usuarios
     * @throws Exception
     */
    public function createUsuario($st_login, $st_senha, $st_nome, $bl_ativo = 1)
    {
        $usuarioEntity = new Usuarios();
        $usuarioEntity->setStLogin($st_login);

        if ($usuarioEntity->isExists()) {
            if ($usuarioEntity->getBlAtivo()) {
                throw new Exception("Email já está sendo utilizado no sistema!");
            }

            throw new Exception("Email já utilizado no sistema! Usuário inativo!");
        }

        $usuarioEntity->clearObject();
        $usuarioEntity->setStLogin($st_login);
        $usuarioEntity->setBlAtivo($bl_ativo);
        $usuarioEntity->setStNome($st_nome);
        $usuarioEntity->setStSenha($st_senha);
        $usuarioEntity->insert();

        return $usuarioEntity;

    }

    /**
     * @param Usuarios $usuarioEntity
     * @return mixed
     * @throws Exception
     */
    public static function getDataLoginJwt($usuarioEntity)
    {

        $jwt = new JWT();
        $jwt->addInfoPayload("userid", $usuarioEntity->getIdUsuario());
        $retorno["st_token"] = $jwt->generateCode([
            "id_usuario" => $usuarioEntity->getIdUsuario(),
            "st_nome" => $usuarioEntity->getStNome(),
            "st_email" => $usuarioEntity->getStLogin()
        ]);

        $retorno["id_usuario"] = $usuarioEntity->getIdUsuario();

        $spotify = new Spotify();
        $integracao = $spotify->getIntegracao($usuarioEntity->getIdUsuario());
        $retorno["bl_integracao"] = $integracao && !empty($integracao->getStId()) ? 1 : 0;

        return $retorno;


    }

}