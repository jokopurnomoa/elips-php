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
    }

    public static function isSMTP(){
        return self::$phpMailer->isSMTP();
    }

    public static function setHost($host){
        self::$phpMailer->Host = $host;
    }

    public static function setSMTPAuth($smtp_auth){
        self::$phpMailer->SMTPAuth = $smtp_auth;
    }

    public static function setUsername($username){
        self::$phpMailer->Username = $username;
    }

    public static function setPassword($password){
        self::$phpMailer->Password = $password;
    }

    public static function setSMTPSecure($smtp_secure){
        self::$phpMailer->SMTPSecure = $smtp_secure;
    }

    public static function setPort($port){
        self::$phpMailer->Port = $port;
    }

    public static function setFrom($email, $name){
        return self::$phpMailer->setFrom($email, $name);
    }

    public static function addAddress($email, $name = ''){
        return self::$phpMailer->addAddress($email, $name);
    }

    public static function addReplyTo($email, $name){
        return self::$phpMailer->addReplyTo($email, $name);
    }

    public static function addCC($email){
        return self::$phpMailer->addCC($email);
    }

    public static function addBCC($email){
        return self::$phpMailer->addBCC($email);
    }

    public static function addAttachment($file){
        return self::$phpMailer->addAttachment($file);
    }

    public static function isHTML(){
        return self::$phpMailer->isHTML();
    }

    public static function setSubject($subject){
         self::$phpMailer->Subject = $subject;
    }

    public static function setBody($body){
        self::$phpMailer->Body = $body;
    }

    public static function setAltBody($alt_body){
        self::$phpMailer->AltBody = $alt_body;
    }

    public static function send(){
        return self::$phpMailer->send();
    }

    public static function getErrorInfo(){
        return self::$phpMailer->ErrorInfo;
    }
}
