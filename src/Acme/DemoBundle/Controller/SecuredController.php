<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @extra:Route("/demo/secured")
 */
class SecuredController extends Controller
{
    /**
     * @extra:Route("/login", name="_demo_login")
     * @extra:Template()
     */
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @extra:Route("/login_check", name="_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @extra:Route("/logout", name="_demo_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @extra:Routes({
     *   @extra:Route("/hello", defaults={"name"="World"}),
     *   @extra:Route("/hello/{name}", name="_demo_secured_hello")
     * })
     * @extra:Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @extra:Route("/hello/admin/{name}", name="_demo_secured_hello_admin")
     * @extra:Secure(roles="ROLE_ADMIN")
     * @extra:Template()
     */
    public function helloadminAction($name)
    {
        return array('name' => $name);
    }
}
