<?php

namespace Viloveul\Pagination;

use BadMethodCallException;
use InvalidArgumentException;
use Viloveul\Pagination\Contracts\Parameter as IParameter;

class Parameter implements IParameter
{
    /**
     * @var string
     */
    protected $baseUrl = '/v1';

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var int
     */
    protected $currentPage = 1;

    /**
     * @var mixed
     */
    protected $orderBy = 'id';

    /**
     * @var int
     */
    protected $pageSize = 10;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $searchName = 'search';

    /**
     * @var string
     */
    protected $sortOrder = 'DESC';

    /**
     * @param $searchName
     * @param array         $params
     */
    public function __construct($searchName, array $params = [])
    {
        $this->setSearchName($searchName);

        $this->setConditions($params);

        if (array_key_exists('page', $params)) {
            $this->setCurrentPage($params['page']);
        }
        if (array_key_exists('size', $params)) {
            $this->setPageSize($params['size']);
        }
        if (array_key_exists('order', $params)) {
            $this->setOrderBy($params['order']);
        }
        if (array_key_exists('sort', $params)) {
            $this->setSortOrder($params['sort']);
        }

        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->params;
    }

    /**
     * @param  $default
     * @return mixed
     */
    public function getBaseUrl($default = '/'): string
    {
        return $this->baseUrl ?: $default;
    }

    /**
     * @return mixed
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param  $default
     * @return mixed
     */
    public function getCurrentPage($default = 1): int
    {
        return $this->currentPage ?: abs($default);
    }

    /**
     * @param  $default
     * @return mixed
     */
    public function getOrderBy($default = 'id'): string
    {
        return $this->orderBy ?: $default;
    }

    /**
     * @param  $default
     * @return mixed
     */
    public function getPageSize($default = 10): int
    {
        return $this->pageSize ?: abs($default);
    }

    /**
     * @param  $default
     * @return mixed
     */
    public function getSearchName($default = 'search'): string
    {
        return $this->searchName ?: $default;
    }

    /**
     * @return mixed
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder === static::PARAM_SORT_ASC ? static::PARAM_SORT_ASC : static::PARAM_SORT_DESC;
    }

    /**
     * @param $key
     */
    public function offsetExists($key)
    {
        throw new BadMethodCallException("Method not exists.");
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        $method = ucfirst($key);
        if (method_exists($this, 'get' . $method)) {
            return $this->{'get' . $method}();
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        $method = ucfirst($key);
        if (method_exists($this, 'set' . $method)) {
            $this->{'set' . $method}($value);
        }
    }

    /**
     * @param $key
     */
    public function offsetUnset($key)
    {
        throw new BadMethodCallException("Method not exists.");
    }

    /**
     * @param $baseUrl
     */
    public function setBaseUrl($baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param array  $params
     * @param string $defaultSearchName
     */
    public function setConditions(array $params, string $defaultSearchName = 'search'): void
    {
        $conditions = [];
        $searchName = $this->getSearchName($defaultSearchName);

        if (array_key_exists($searchName, $params) && is_array($params[$searchName])) {
            foreach ($params[$searchName] as $key => $value) {
                $conditions[$key] = $value;
            }
        } else {
            foreach ($params as $key => $value) {
                if (is_scalar($value) && 0 === stripos($key, $searchName . '_') && $key !== ($searchName . '_')) {
                    $conditions[substr($key, strlen($searchName) + 1)] = $value;
                }
            }
        }
        $this->conditions = $conditions;
    }

    /**
     * @param $page
     */
    public function setCurrentPage($page): void
    {
        if ($page !== null) {
            if (is_array($page)) {
                if (!array_key_exists('current', $page)) {
                    throw new InvalidArgumentException("Argument must have index 'current' if array.");
                }
                $this->currentPage = abs($page['current']) ?: 1;
                if (array_key_exists('size', $page)) {
                    $this->setLimit($page['size']);
                }
            } elseif (is_scalar($page)) {
                $this->currentPage = abs($page) ?: 1;
            } else {
                throw new InvalidArgumentException("Argument must type of array or number.");
            }
        }
    }

    /**
     * @param $orderBy
     */
    public function setOrderBy($orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @param $pageSize
     */
    public function setPageSize($pageSize): void
    {
        if ($pageSize !== null) {
            $this->pageSize = abs($pageSize) ?: 10;
        }
    }

    /**
     * @param $searchName
     */
    public function setSearchName($searchName): void
    {
        $this->searchName = $searchName;
    }

    /**
     * @param $sortOrder
     */
    public function setSortOrder($sortOrder): void
    {
        $this->sortOrder = strtoupper($sortOrder) === static::PARAM_SORT_ASC ? 'ASC' : 'DESC';
    }
}
