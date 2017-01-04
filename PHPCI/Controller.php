<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Config;
use PHPCI\Framework\Exception\HttpException\ForbiddenException;
use PHPCI\Framework\Http\Request;
use PHPCI\Framework\Http\Response;
use PHPCI\Framework\View;

/**
 * PHPCI Base Controller
 * @package PHPCI
 */
class Controller extends Framework\Controller
{
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
     * @return \PHPCI\Framework\Http\Response|Response
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
