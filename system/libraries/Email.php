<?php
/**
 * Email library
 *
 *
 */

class Email {

    private static $phpMailer;

    public static function init(){
        require 'PHPMailer/PHPMailerAutoload.php';
        self::$phpMailer = new PHPMailer();
        self::$phpMailer->isMail();
    }

    public static function smtp($status){
        if($status === true){
            return self::$phpMailer->isSMTP();
        }
        return false;
    }

    public static function host($host){
        self::$phpMailer->Host = $host;
    }

    public static function SMTPAuth($smtp_auth){
        self::$phpMailer->SMTPAuth = $smtp_auth;
    }

    public static function username($username){
        self::$phpMailer->Username = $username;
    }

    public static function password($password){
        self::$phpMailer->Password = $password;
    }

    public static function SMTPSecure($smtp_secure){
        self::$phpMailer->SMTPSecure = $smtp_secure;
    }

    public static function port($port){
        self::$phpMailer->Port = $port;
    }

    public static function from($email, $name){
        return self::$phpMailer->setFrom($email, $name);
    }

    public static function to($email, $name = ''){
        return self::$phpMailer->addAddress($email, $name);
    }

    public static function replyTo($email, $name){
        return self::$phpMailer->addReplyTo($email, $name);
    }

    public static function cc($email){
        return self::$phpMailer->addCC($email);
    }

    public static function bcc($email){
        return self::$phpMailer->addBCC($email);
    }

    public static function attachment($file){
        return self::$phpMailer->addAttachment($file);
    }

    public static function html($option){
        if($option === true){
            return self::$phpMailer->isHTML();
        }
        return false;
    }

    public static function subject($subject){
         self::$phpMailer->Subject = $subject;
    }

    public static function message($body){
        self::$phpMailer->Body = $body;
    }

    public static function altBody($alt_body){
        self::$phpMailer->AltBody = $alt_body;
    }

    public static function send(){
        return self::$phpMailer->send();
    }

    public static function getErrorInfo(){
        return self::$phpMailer->ErrorInfo;
    }
}
