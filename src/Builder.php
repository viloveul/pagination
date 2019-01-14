<?php

namespace Viloveul\Pagination;

use Closure;
use Viloveul\Pagination\Contracts\Builder as IBuilder;
use Viloveul\Pagination\Contracts\Parameter as IParameter;
use Viloveul\Pagination\Parameter;

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
        $parameter = $this->getParameter();
        $total = $this->getTotal();
        $conditions = $parameter->getConditions();
        $size = $parameter->getPageSize();
        $page = $parameter->getCurrentPage();
        $orderBy = $parameter->getOrderBy();
        $sortOrder = $parameter->getSortOrder();

        return compact('total', 'conditions', 'size', 'page', 'orderBy', 'sortOrder');
    }

    public function getParameter(): IParameter
    {
        return is_null($this->parameter) ? new Parameter('search') : $this->parameter;
    }

    public function getResults(): array
    {
        return [
            'meta' => $this->getMeta(),
            'data' => $this->getData(),
            'links' => $this->getLinks(),
        ];
    }

    public function getTotal(): int
    {
        return abs($this->total);
    }

    /**
     * @param Closure $handler
     */
    public function prepare(Closure $handler): void
    {
        call_user_func($handler->bindTo($this, $this));
    }

    /**
     * @param  $page
     * @return mixed
     */
    protected function buildUrl($page)
    {
        $parameter = $this->getParameter();
        $base = $parameter->getBaseUrl();
        $selfParams = array_replace_recursive($parameter->all(), [
            'page' => abs($page),
            'size' => $parameter->getPageSize(),
        ]);
        return $base . ($selfParams ? ('?' . http_build_query($selfParams)) : '');
    }
}
