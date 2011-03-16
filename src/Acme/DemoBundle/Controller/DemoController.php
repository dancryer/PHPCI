<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Acme\DemoBundle\Form\ContactForm;

class DemoController extends Controller
{
    /**
     * @extra:Route("/", name="_demo")
     * @extra:Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @extra:Route("/hello/{name}", name="_demo_hello")
     * @extra:Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @extra:Route("/contact", name="_demo_contact")
     * @extra:Template()
     */
    public function contactAction()
    {
        $form = ContactForm::create($this->get('form.context'), 'contact');

        $form->bind($this->container->get('request'), $form);
        if ($form->isValid()) {
            $form->send($this->get('mailer'));

            $this->get('session')->setFlash('notice', 'Message sent!');

            return new RedirectResponse($this->generateUrl('_demo'));
        }

        return array('form' => $form);
    }
}
