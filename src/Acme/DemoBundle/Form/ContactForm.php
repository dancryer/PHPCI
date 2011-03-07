<?php

namespace Acme\DemoBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;

class ContactForm extends Form
{
    public $email;

    public $message;

    public function configure()
    {
        $this->add(new TextField('email'));
        $this->add(new TextField('message'));
    }

    public function send(\Swift_Mailer $mailer)
    {
        //$mailer->send();
    }
}
