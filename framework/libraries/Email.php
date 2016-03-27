<?php
/**
 * Email Library
 *
 * Require PHPMailer To Work
 *
 */

class Email
{

    private static $host;
    private static $port;
    private static $encryption;
    private static $username;
    private static $password;

    private static $from;
    private static $to;
    private static $subject;
    private static $body;
    private static $altBody;
    private static $replyTo;
    private static $cc;
    private static $bcc;
    private static $attachment;
    private static $html = false;

    private static $sendingMessage;

    /**
     * Initialize Library
     */
    public static function init()
    {
        require MAIN_PATH . 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
    }

    /**
     * Set Hostname
     *
     * @param $host
     */
    public static function host($host)
    {
        self::$host = $host;
    }

    /**
     * Set Username
     *
     * @param $username
     */
    public static function username($username)
    {
        self::$username = $username;
    }

    /**
     * Set Password
     *
     * @param $password
     */
    public static function password($password)
    {
        self::$password = $password;
    }

    /**
     * Set Encryption
     *
     * @param $encryption
     */
    public static function encryption($encryption)
    {
        self::$encryption = $encryption;
    }

    /**
     * Set Port
     *
     * @param $port (465 OR 587 OR other)
     */
    public static function port($port)
    {
        self::$port = $port;
    }

    /**
     * Set Sender
     *
     * @param $email
     * @param $name
     * @return mixed
     */
    public static function from($emailOrFrom, $name = null)
    {
        if ($name != null) {
            self::$from = array($emailOrFrom => $name);
        } elseif (is_array($emailOrFrom)) {
            self::$from = $emailOrFrom;
        }
    }

    /**
     * Set Receiver
     *
     * @param $email
     * @param string $name
     * @return mixed
     */
    public static function to($email, $name = '')
    {
        if (is_array($email)) {
            self::$to = $email;
        } elseif ($name != '') {
            self::$to = array($email => $name);
        } else {
            self::$to = array($email);
        }
    }

    /**
     * Set Reply To
     *
     * @param $email
     * @param $name
     * @return mixed
     */
    public static function replyTo($email, $name)
    {
        if (is_array($email)) {
            self::$replyTo = $email;
        } else {
            self::$replyTo = array($email => $name);
        }
    }

    /**
     * Set CC
     *
     * @param $email
     * @return mixed
     */
    public static function cc($email)
    {
        self::$cc = $email;
    }

    /**
     * Set BCC
     *
     * @param $email
     * @return mixed
     */
    public static function bcc($email)
    {
        self::$bcc = $email;
    }

    /**
     * Add File Attachment
     *
     * @param $file
     * @return mixed
     */
    public static function attachment($file)
    {
        self::$attachment = $file;
    }

    /**
     * Set HTML view
     *
     * @param $option
     * @return bool
     */
    public static function html($option)
    {
        if ($option === true) {
            self::$html = true;
        }
    }

    /**
     * Set Subject
     *
     * @param $subject
     */
    public static function subject($subject)
    {
        self::$subject = $subject;
    }

    /**
     * Set Message
     *
     * @param $body
     */
    public static function message($body)
    {
        self::$body = $body;
    }

    /**
     * Set AltBody
     *
     * @param $alt_body
     */
    public static function altMessage($altBody)
    {
        self::$altBody = $altBody;
    }

    /**
     * Set email configuration
     *
     * @param array $config
     */
    public static function setConfig($config)
    {
        if (is_array($config)) {
            if ($config != null) {
                foreach ($config as $key => $value) {
                    switch ($key) {
                        case 'host'         : self::host($value);break;
                        case 'username'     : self::username($value);break;
                        case 'password'     : self::password($value);break;
                        case 'encryption'   : self::encryption($value);break;
                        case 'port'         : self::port($value);break;

                        case 'from'         : self::from($value);break;
                        case 'to'           : self::to($value);break;
                        case 'cc'           : self::cc($value);break;
                        case 'bcc'          : self::bcc($value);break;

                        case 'html'         : self::html($value);break;
                        case 'subject'      : self::subject($value);break;
                        case 'message'      : self::message($value);break;
                        case 'altMessage'   : self::altMessage($value);break;
                    }
                }
            }
        }
    }

    /**
     * Reset email configuration
     */
    public static function resetConfig()
    {
        self::$host = null;
        self::$port = null;
        self::$encryption = null;
        self::$username = null;
        self::$password = null;

        self::$from = null;
        self::$to = null;
        self::$subject = null;
        self::$body = null;
        self::$altBody = null;
        self::$replyTo = null;
        self::$cc = null;
        self::$bcc = null;
        self::$attachment = null;
        self::$html = false;

        self::$sendingMessage = null;
    }

    /**
     * Send Email
     *
     * @return mixed
     */
    public static function send()
    {
        if (self::$encryption != null) {
            $transport = Swift_SmtpTransport::newInstance(self::$host, self::$port, self::$encryption);
        } else {
            $transport = Swift_SmtpTransport::newInstance(self::$host, self::$port);
        }

        $transport->setUsername(self::$username)->setPassword(self::$password);

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance()
            ->setSubject(self::$subject)
            ->setFrom(self::$from)
            ->setTo(self::$to)
            ->setBody(self::$body);

        if (self::$altBody != null) {
            $message->addPart(self::$altBody);
        }

        if (self::$attachment != null) {
            $message->attach(Swift_Attachment::fromPath(self::$attachment));
        }

        if (self::$cc != null){
            $message->setCc(self::$cc);
        }

        if (self::$bcc != null){
            $message->setBcc(self::$bcc);
        }

        if (self::$html) {
            $message->setContentType('text/html');
        } else {
            $message->setContentType('text/plain');
        }

        $result = false;
        try {
            if ($mailer->send($message)) {
                $result = true;
            }
        } catch (Swift_TransportException $e) {
            self::$sendingMessage = $e->getMessage();
            $mailer->getTransport()->stop();
        }

        return $result;
    }

    /**
     * Get sending message
     *
     * @return mixed
     */
    public static function getSendingMessage()
    {
        return self::$sendingMessage;
    }

}
