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
use b8\Http\Request;
use b8\Http\Response;
use b8\View;

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

    public function init()
    {
        // Extended by actual controllers.
    }

    public function __construct(Config $config, Request $request, Response $response)
    {
        parent::__construct($config, $request, $response);

        $class = explode('\\', get_class($this));
        $this->className = substr(array_pop($class), 0, -10);
        $this->setControllerView();
    }

    protected function setControllerView()
    {
        if (View::exists($this->className)) {
            $this->controllerView = new View($this->className);
        } else {
            $this->controllerView = new View\Template('{@content}');
        }
    }

    protected function setView($action)
    {
        if (View::exists($this->className . '/' . $action)) {
            $this->view = new View($this->className . '/' . $action);
        }
    }

    public function handleAction($action, $actionParams)
    {
        $this->setView($action);
        $response = parent::handleAction($action, $actionParams);

        if (is_string($response)) {
            $this->controllerView->content = $response;
        } elseif (isset($this->view)) {
            $this->controllerView->content = $this->view->render();
        }

        $this->response->setContent($this->controllerView->render());

        return $this->response;
    }
}
