<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Framework\Exception\HttpException\ForbiddenException;
use PHPCI\Framework\Http\Request;
use PHPCI\Framework\Http\Response;
use PHPCI\Framework\View;
use PHPCI\Model\User;

/**
 * PHPCI Base Controller
 * @package PHPCI
 */
abstract class Controller
{
    /**
     * @var \PHPCI\Framework\Http\Request
     */
    protected $request;

    /**
     * @var \PHPCI\Framework\Http\Response
     */
    protected $response;

    /**
     * @var \PHPCI\Config
     */
    protected $config;

    /**
     * @var \PHPCI\Framework\View
     */
    protected $controllerView;

    /**
     * @var \PHPCI\Framework\View
     */
    protected $view;

    /**
     * @var \PHPCI\Framework\View
     */
    public $layout;

    /**
     * @var string
     */
    protected $className;

    public function __construct(Config $config, Request $request, Response $response)
    {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;

        $class = explode('\\', get_class($this));
        $this->className = substr(array_pop($class), 0, -10);
        $this->setControllerView();
    }

    public function hasAction($name)
    {
        if (method_exists($this, $name)) {
            return true;
        }

        if (method_exists($this, '__call')) {
            return true;
        }

        return false;
    }

    /**
     * Handles an action on this controller and returns a Response object.
     * @return \PHPCI\Framework\Http\Response
     */
    public function handleAction($action, $actionParams)
    {
        $this->setView($action);
        $response = call_user_func_array([$this, $action], $actionParams);

        if ($response instanceof Response) {
            return $response;
        }

        if (is_string($response)) {
            $this->controllerView->content = $response;
        } elseif (isset($this->view)) {
            $this->controllerView->content = $this->view->render();
        }

        $this->response->setContent($this->controllerView->render());

        return $this->response;
    }

    /**
     * Initialise the controller.
     */
    abstract public function init();

    /**
     * Get a hash of incoming request parameters ($_GET, $_POST)
     *
     * @return array
     */
    public function getParams()
    {
        return $this->request->getParams();
    }

    /**
     * Get a specific incoming request parameter.
     *
     * @param      $key
     * @param mixed $default    Default return value (if key does not exist)
     *
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        return $this->request->getParam($key, $default);
    }

    /**
     * Change the value of an incoming request parameter.
     * @param $key
     * @param $value
     */
    public function setParam($key, $value)
    {
        $this->request->setParam($key, $value);
    }

    /**
     * Remove an incoming request parameter.
     * @param $key
     */
    public function unsetParam($key)
    {
        $this->request->unsetParam($key);
    }


    /**
     * Set the view that this controller should use.
     */
    protected function setControllerView()
    {
        if (View::exists($this->className)) {
            $this->controllerView = new View($this->className);
        } else {
            $this->controllerView = new View\Template('{@content}');
        }
    }

    /**
     * Set the view that this controller action should use.
     * @param $action
     */
    protected function setView($action)
    {
        if (View::exists($this->className . '/' . $action)) {
            $this->view = new View($this->className . '/' . $action);
        }
    }

    /**
     * Require that the currently logged in user is an administrator.
     * @throws ForbiddenException
     */
    protected function requireAdmin()
    {
        if (!$this->currentUserIsAdmin()) {
            throw new ForbiddenException('You do not have permission to do that.');
        }
    }

    /**
     * Check if the currently logged in user is an administrator.
     * @return bool
     */
    protected function currentUserIsAdmin()
    {
        /** @var User $user */
        $user = $_SESSION['phpci_user'];

        return $user->getIsAdmin();
    }
}
