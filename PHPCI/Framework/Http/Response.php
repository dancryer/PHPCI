<?php

namespace PHPCI\Framework\Http;

class Response
{
    protected $data = [];

    public function __construct(Response $createFrom = null)
    {
        if (!is_null($createFrom)) {
            $this->data = $createFrom->getData();
        }
    }

    public function hasLayout()
    {
        return !isset($this->data['layout']) ? true : $this->data['layout'];
    }

    public function disableLayout()
    {
        $this->data['layout'] = false;
    }

    public function enableLayout()
    {
        $this->data['layout'] = true;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setResponseCode($code)
    {
        $code = (int)$code;

        if ($code == 301 || $code == 302) {
            $this->disableLayout();
            $this->setContent(null);
        }

        $this->data['code'] = $code;
    }

    public function setHeader($key, $val)
    {
        $this->data['headers'][$key] = $val;
    }

    public function clearHeaders()
    {
        $this->data['headers'] = [];
    }

    public function setContent($content)
    {
        $this->data['body'] = $content;
    }

    public function getContent()
    {
        return $this->data['body'];
    }

    public function flush()
    {
        $this->sendResponseCode();

        if (isset($this->data['headers'])) {
            foreach ($this->data['headers'] as $header => $val) {
                header($header . ': ' . $val, true);
            }
        }

        return $this->flushBody();
    }

    protected function sendResponseCode()
    {
        if (!isset($this->data['code'])) {
            $this->data['code'] = 200;
        }

        switch ($this->data['code']) {
            // 300 class
            case 301:
                $text = 'Moved Permanently';
                break;
            case 302:
                $text = 'Moved Temporarily';
                break;

            // 400 class errors
            case 400:
                $text = 'Bad Request';
                break;
            case 401:
                $text = 'Not Authorized';
                break;
            case 403:
                $text = 'Forbidden';
                break;
            case 404:
                $text = 'Not Found';
                break;

            // 500 class errors
            case 500:
                $text = 'Internal Server Error';
                break;

            // OK
            case 200:
            default:
                $text = 'OK';
                break;
        }

        header('HTTP/1.1 ' . $this->data['code'] . ' ' . $text, true, $this->data['code']);
    }

    protected function flushBody()
    {
        if (empty($this->data['body'])) {
            return '';
        }

        return $this->data['body'];
    }

    public function __toString()
    {
        return $this->flush();
    }
}