<?php

namespace Viloveul\Pagination\Contracts;

use Closure;
use Viloveul\Pagination\Contracts\Parameter;

interface Builder
{
    public function getData(): array;

    public function getLinks(): array;

    public function getMeta(): array;

    public function getParameter(): Parameter;

    public function getResults(): array;

    public function getTotal(): int;

    /**
     * @param Closure $handler
     */
    public function prepare(Closure $handler): void;
}
