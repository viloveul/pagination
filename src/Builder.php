<?php

namespace Viloveul\Pagination;

use Closure;
use Viloveul\Pagination\Contracts\Builder as IBuilder;
use Viloveul\Pagination\Contracts\Parameter as IParameter;
use Viloveul\Pagination\Parameter;

class Builder implements IBuilder
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var mixed
     */
    protected $parameter;

    /**
     * @param $name
     * @param array   $params
     */
    public function __construct(IParameter $parameter = null)
    {
        $this->parameter = $parameter;
    }

    public function getCount(): int
    {
        return abs($this->count);
    }

    /**
     * @return mixed
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getLinks(): array
    {
        $parameter = $this->getParameter();
        $current = $parameter->getCurrentPage();
        $size = $parameter->getPageSize();

        return [
            'self' => $this->buildUrl($current),
            'prev_page' => $current > 1 ? $this->buildUrl($current - 1) : null,
            'next_page' => ($current * $size) < $this->getCount() ? $this->buildUrl($current + 1) : null,
            'first_page' => $this->buildUrl(1),
            'last_page' => $this->buildUrl(ceil($this->getCount() / $size)),
        ];
    }

    public function getMeta(): array
    {
        $parameter = $this->getParameter();
        $count = $this->getCount();
        $conditions = $parameter->getConditions();
        $size = $parameter->getPageSize();
        $page = $parameter->getCurrentPage();
        $orderBy = $parameter->getOrderBy();
        $sortOrder = $parameter->getSortOrder();

        return compact('count', 'conditions', 'size', 'page', 'orderBy', 'sortOrder');
    }

    public function getParameter(): IParameter
    {
        return is_null($this->parameter) ? new Parameter('search') : $this->parameter;
    }

    public function getResults(): array
    {
        return [
            'items' => $this->getItems(),
            'meta' => array_merge($this->getMeta(), [
                'links' => $this->getLinks(),
            ]),
        ];
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
