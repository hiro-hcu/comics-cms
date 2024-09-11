<?php

class Route
{
    public function __construct(
        public string $path,
        public string $controller,
        public bool $login_require = false,
    ){
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getLoginRequire(): bool
    {
        return $this->login_require;
    }
}