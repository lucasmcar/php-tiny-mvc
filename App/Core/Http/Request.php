<?php

namespace App\Core\Http;

class Request
{
    private $query;
    private $request;
    private $cookies;
    private $files;
    private $server;

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public function query($key = null, $default = null)
    {
        return $this->getValue($this->query, $key, $default);
    }

    public function post($key = null, $default = null)
    {
        return $this->getValue($this->request, $key, $default);
    }

    public function cookie($key = null, $default = null)
    {
        return $this->getValue($this->cookies, $key, $default);
    }

    public function file($key = null)
    {
        return $key === null ? $this->files : ($this->files[$key] ?? null);
    }

    public function server($key = null, $default = null)
    {
        return $this->getValue($this->server, $key, $default);
    }

    private function getValue(array $source, $key, $default)
    {
        if ($key === null) {
            return $source;
        }

        return $source[$key] ?? $default;
    }

    public function method()
    {
        return $this->server('REQUEST_METHOD', 'GET');
    }

    public function isPost()
    {
        return $this->method() === 'POST';
    }

    public function isGet()
    {
        return $this->method() === 'GET';
    }
}