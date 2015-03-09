<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use b8\Config;
use b8\Exception\HttpException\ForbiddenException;
use b8\Http\Request;
use b8\Http\Response;
use b8\View;

/**
 * PHPCI Base Controller
 * @package PHPCI
 */
class Controller extends \b8\Controller
{
    /**
    * @var \b8\View
    */
    protected $controllerView;

    /**
     * @var \b8\View
     */
    protected $view;

    /**
     * @var \b8\View
     */
    public $layout;

    /**
     * Initialise the controller.
     */
    public function init()
    {
        // Extended by actual controllers.
    }

    /**
     * @param Config $config
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Config $config, Request $request, Response $response)
    {
        parent::__construct($config, $request, $response);

        $class = explode('\\', get_class($this));
        $this->className = substr(array_pop($class), 0, -10);
        $this->setControllerView();
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
     * Handle the incoming request.
     * @param $action
     * @param $actionParams
     * @return \b8\b8\Http\Response|Response
     */
    public function handleAction($action, $actionParams)
    {
        $this->setView($action);
        $response = parent::handleAction($action, $actionParams);

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
        return $_SESSION['phpci_user']->getIsAdmin();
    }
}
