<?php

namespace AT\votacionBundle\Services;

class MailService
{
    protected $mailer;
    protected $templating; 
    
    function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }
    
    public function sendMail($email, $subject, $data)
    {
        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('diego@altactic.com', 'Votaciones')
            ->setTo($email)
            ->setBody($this->templating->render('::mail.html.twig', $data), 'text/html');
        
        $this->mailer->send($mail);
    }
}
?>
