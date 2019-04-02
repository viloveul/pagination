<?php

namespace Viloveul\Pagination;

use Viloveul\Pagination\Contracts\ResultSet as IResultSet;

class ResultSet implements IResultSet
{
    /**
     * @var array
     */
    protected $results = [];

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @param int   $total
     * @param array $results
     */
    public function __construct(int $total, array $results)
    {
        $this->total = $total;
        $this->results = $results;
    }

    /**
     * @return mixed
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return mixed
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}
