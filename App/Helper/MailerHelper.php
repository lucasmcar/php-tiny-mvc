<?php

namespace App\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class MailerHelper 
{
    private PHPMailer $mail;

    public function __construct(string $host, int $port, string $username, string $password, string $fromEmail, string $fromName) 
    {
        $this->mail = new PHPMailer(true);

        // Configuração do servidor
        $this->mail->isSMTP();
        $this->mail->Host = $host;
        $this->mail->CharSet = "UTF-8";
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $username;
        $this->mail->Password = $password;
        $this->mail->SMTPKeepAlive = true;  
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = $port;
        $this->mail->Mailer = 'smtp';

        // Remetente
        $this->mail->setFrom($fromEmail, $fromName);

        // Configurações padrão
        $this->mail->isHTML(true);
    }

    public function addRecipient(string $email, string $name = ''): void 
    {
        $this->mail->addAddress($email, $name);
    }

    public function setSubject(string $subject): void 
    {
        $this->mail->Subject = $subject;
    }

    public function setBody(string $body): void 
    {
        $this->mail->Body = $body;
    }

    public function addAttachment(string $filePath, string $fileName = ''): void 
    {
        $this->mail->addAttachment($filePath, $fileName);
    }

    public function send(): bool 
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            
            return false;
        }
    }
}