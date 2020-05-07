<?php


namespace App\Service;


class Env
{
    public function get(string $name)
    {
        return $_ENV[$name] ?? null;
    }
}
