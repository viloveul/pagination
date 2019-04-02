<?php

namespace Viloveul\Pagination\Contracts;

interface ResultSet
{
    public function getResults(): array;

    public function getTotal(): int;
}
