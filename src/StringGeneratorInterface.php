<?php

declare(strict_types=1);


namespace Myro\NameStream;


interface StringGeneratorInterface
{
    public function generate(): string;

    public function g(): string;
}
