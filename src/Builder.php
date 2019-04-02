<?php

namespace Viloveul\Pagination;

use Closure;
use InvalidArgumentException;
use Viloveul\Pagination\Parameter;
use Viloveul\Pagination\Contracts\Builder as IBuilder;
use Viloveul\Pagination\Contracts\Parameter as IParameter;
use Viloveul\Pagination\Contracts\ResultSet as IResultSet;

class Builder implements IBuilder
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var mixed
     */
    protected $parameter;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @param $name
     * @param array   $params
     */
    public function __construct(IParameter $parameter = null)
    {
        $this->parameter = $parameter;
    }

    /**
     * @return mixed
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getLinks(): array
    {
        $parameter = $this->getParameter();
        $current = $parameter->getCurrentPage();
        $size = $parameter->getPageSize();

        return [
            'self' => $this->buildUrl($current),
            'prev' => $current > 1 ? $this->buildUrl($current - 1) : null,
            'next' => ($current * $size) < $this->getTotal() ? $this->buildUrl($current + 1) : null,
            'first' => $this->buildUrl(1),
            'last' => $this->buildUrl(ceil($this->getTotal() / $size)),
        ];
    }

    public function getMeta(): array
    {
        $total = $this->getTotal();
        $parameter = $this->getParameter();
        $conditions = $parameter->getConditions();
        $size = $parameter->getPageSize();
        $page = $parameter->getCurrentPage();
        $order = $parameter->getOrderBy();
        $sort = $parameter->getSortOrder();

        return compact('total', 'conditions', 'size', 'page', 'order', 'sort');
    }

    public function getParameter(): IParameter
    {
        return null === $this->parameter ? new Parameter('search') : $this->parameter;
    }

    public function getTotal(): int
    {
        return abs($this->total);
    }

    /**
     * @param Closure $handler
     */
    public function with(Closure $handler): void
    {
        $parameter = $this->getParameter();
        $conditions = $parameter->getConditions();
        $size = $parameter->getPageSize();
        $page = $parameter->getCurrentPage();
        $order = $parameter->getOrderBy();
        $sort = $parameter->getSortOrder();
        $result = $handler($conditions, $size, $page, $order, $sort);
        if (!($result instanceof IResultSet)) {
            throw new InvalidArgumentException("Argument must return the ResultSet value");
        }
        $this->total = $result->getTotal();
        $this->data = $result->getResults();
    }

    /**
     * @param  $page
     * @return mixed
     */
    protected function buildUrl($page)
    {
        $parameter = $this->getParameter();
        $base = $parameter->getBaseUrl();
        if (!preg_match('/^https?\:\/\//', $base)) {
            $base = '/' . trim($base, '/');
        }
        $selfParams = array_replace_recursive($parameter->all(), [
            'page' => abs($page),
            'size' => $parameter->getPageSize(),
        ]);
        return $base . ($selfParams ? ('?' . http_build_query($selfParams)) : '');
    }
}
