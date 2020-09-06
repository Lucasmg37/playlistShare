<?php


namespace App\Model;

use PHPMailer\PHPMailer\Exception;

class MailService
{

    public $sendMail;

    const ASSUNTO_EMAIL_ACTIVATE = "Falta pouco! Ative a sua conta!";
    const ASSUNTO_EMAIL_RECOVERY= "Esqueceu a sua Senha? Vamos te ajudar.";

    public function __construct()
    {
        $this->sendMail = new SendMail();
    }

    /**
     * @param $st_email
     * @param $st_nome
     * @param $st_code
     * @throws Exception
     */
    public function sendEmailActivate($st_email, $st_nome, $st_code)
    {
        $render = new Render();
        $render->setCaminho("Email");
        $html = $render->renderBeta(["nome" => $st_nome, "code" => $st_code]);
        $this->sendMail->sendEmailSystem($st_email, self::ASSUNTO_EMAIL_ACTIVATE, $html);
    }

    /**
     * @param $st_email
     * @param $st_nome
     * @param $st_code
     * @throws Exception
     */
    public function sendEmailRecupercaodeSenha($st_email, $st_nome, $st_code)
    {
        $render = new Render();
        $render->setCaminho("Recovery");
        $html = $render->renderBeta(["nome" => $st_nome, "code" => $st_code]);
        $this->sendMail->sendEmailSystem($st_email, self::ASSUNTO_EMAIL_RECOVERY, $html);
    }

}