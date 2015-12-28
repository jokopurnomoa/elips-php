<?php
/**
 * Email Library
 *
 * Require PHPMailer To Work
 *
 */

class Email {

    private static $phpMailer;

    /**
     * Initialize Library
     */
    public static function init(){
        require MAIN_PATH . 'vendor/PHPMailer/PHPMailerAutoload.php';
        self::$phpMailer = new PHPMailer();
        self::$phpMailer->isMail();
    }

    /**
     * Set SMTP Mail
     *
     * @param $status
     * @return bool
     */
    public static function smtp($status){
        if($status === true){
            self::$phpMailer->SMTPAuth = true;
            return self::$phpMailer->isSMTP();
        }
        return false;
    }

    /**
     * Set Hostname
     *
     * @param $host
     */
    public static function host($host){
        self::$phpMailer->Host = $host;
    }

    /**
     * Set Username
     *
     * @param $username
     */
    public static function username($username){
        self::$phpMailer->Username = $username;
    }

    /**
     * Set Password
     *
     * @param $password
     */
    public static function password($password){
        self::$phpMailer->Password = $password;
    }

    /**
     * Set SMTP Secure
     *
     * @param $smtp_secure ('ssl' OR 'tls' OR other)
     */
    public static function SMTPSecure($smtp_secure){
        self::$phpMailer->SMTPSecure = $smtp_secure;
    }

    /**
     * Set Port
     *
     * @param $port (465 OR 587 OR other)
     */
    public static function port($port){
        self::$phpMailer->Port = $port;
    }

    /**
     * Set Sender
     *
     * @param $email
     * @param $name
     * @return mixed
     */
    public static function from($email, $name){
        return self::$phpMailer->setFrom($email, $name);
    }

    /**
     * Set Receiver
     *
     * @param $email
     * @param string $name
     * @return mixed
     */
    public static function to($email, $name = ''){
        return self::$phpMailer->addAddress($email, $name);
    }

    /**
     * Set Reply To
     *
     * @param $email
     * @param $name
     * @return mixed
     */
    public static function replyTo($email, $name){
        return self::$phpMailer->addReplyTo($email, $name);
    }

    /**
     * Set CC
     *
     * @param $email
     * @return mixed
     */
    public static function cc($email){
        return self::$phpMailer->addCC($email);
    }

    /**
     * Set BCC
     *
     * @param $email
     * @return mixed
     */
    public static function bcc($email){
        return self::$phpMailer->addBCC($email);
    }

    /**
     * Add File Attachment
     *
     * @param $file
     * @return mixed
     */
    public static function attachment($file){
        return self::$phpMailer->addAttachment($file);
    }

    /**
     * Set HTML view
     *
     * @param $option
     * @return bool
     */
    public static function html($option){
        if($option === true){
            return self::$phpMailer->isHTML();
        }
        return false;
    }

    /**
     * Set Subject
     *
     * @param $subject
     */
    public static function subject($subject){
         self::$phpMailer->Subject = $subject;
    }

    /**
     * Set Message
     *
     * @param $body
     */
    public static function message($body){
        self::$phpMailer->Body = $body;
    }

    /**
     * Set AltBody
     *
     * @param $alt_body
     */
    public static function altBody($alt_body){
        self::$phpMailer->AltBody = $alt_body;
    }

    /**
     * Send Email
     *
     * @return mixed
     */
    public static function send(){
        return self::$phpMailer->send();
    }

    /**
     * Get Error Info
     *
     * @return mixed
     */
    public static function getErrorInfo(){
        return self::$phpMailer->ErrorInfo;
    }
}
