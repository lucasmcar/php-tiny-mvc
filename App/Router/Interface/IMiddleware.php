<?php

namespace App\Router\Interface;

interface IMiddleware
{
    public function handle($request, $next);
}