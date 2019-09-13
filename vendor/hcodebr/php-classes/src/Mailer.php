<?php

namespace Hcode;
use Rain\Tpl;

class Mailer{

const USERNAME = "jknmdo55@gmail.com";
const PASSWORD = "";
const NAME_FROM = "store";

private $mail;

    public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
    {
        $config = array(
            "tpl_dir"       => "tpl/",
            "cache_dir"     => "cache/",
            "debug"         => false, // set to false to improve the speed
        );
        Tpl::configure( $config );
        // Add PathReplace plugin (necessary to load the CSS with path replace)
        //Tpl::registerPlugin( new Tpl\Plugin\PathReplace() );
         
        // create the Tpl object
        $tpl = new Tpl;
        // assign a variable

        foreach($data as $key => $value){
            $tpl->assign($key,$value);

        }
        
        // draw the template
        $html = $tpl->draw( $tplName, true );
         
        //Import PHPMailer classes into the global namespace
    
         
        //Create a new PHPMailer instance
        $this->$mail = new \PHPMailer;
         
        //Tell PHPMailer to use SMTP
        $this->$mail->isSMTP();
         
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->$mail->SMTPDebug = 2;
         
        //Set the hostname of the mail server
        $this->$mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
         
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->$mail->Port = 587;
         
        //Set the encryption system to use - ssl (deprecated) or tls
        $this->$mail->SMTPSecure = 'tls';
         
        //Whether to use SMTP authentication
        $this->$mail->SMTPAuth = true;
         
        //Username to use for SMTP authentication - use full email address for gmail
        $this->$mail->Username = Mailer::USERNAME;
         
        //Password to use for SMTP authentication
        $this->$mail->Password = Mailer::PASSWORD;
         
        //Set who the message is to be sent from
        $this->$mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);
         
        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');
         
        //Set who the message is to be sent to
        $this->$mail->addAddress('jknmdo@gmail.com', 'Suporte Email');
         
        //Set the subject line
        $this->$mail->Subject = $subject;
         
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $this->$mail->msgHTML($html);
         
        //Replace the plain text body with one created manually
        $this->$mail->AltBody = 'This is a plain-text message body';
         
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
         
        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent 2Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
        }
         
     
        
        public function send()
        {
            return $this->mail->send();
        }
    }



?>