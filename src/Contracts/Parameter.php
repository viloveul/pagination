<?php

namespace Viloveul\Pagination\Contracts;

use ArrayAccess;

interface Parameter extends ArrayAccess
{
    const PARAM_SORT_ASC = 'ASC';

    const PARAM_SORT_DESC = 'DESC';

    public function all(): array;

    /**
     * @param $default
     */
    public function getBaseUrl($default = '/'): string;

    public function getConditions(): array;

    /**
     * @param $default
     */
    public function getCurrentPage($default = 1): int;

    /**
     * @param $default
     */
    public function getOrderBy($default = 'id'): string;

    /**
     * @param $default
     */
    public function getPageSize($default = 10): int;

    /**
     * @param $default
     */
    public function getSearchName($default = 'search'): string;

    public function getSortOrder(): string;

    /**
     * @param $baseUrl
     */
    public function setBaseUrl($baseUrl): void;

    /**
     * @param array  $params
     * @param string $defaultSearchName
     */
    public function setConditions(array $params, string $defaultSearchName = 'search'): void;

    /**
     * @param $page
     */
    public function setCurrentPage($page): void;

    /**
     * @param $orderBy
     */
    public function setOrderBy($orderBy): void;

    /**
     * @param $pageSize
     */
    public function setPageSize($pageSize): void;

    /**
     * @param $searchName
     */
    public function setSearchName($searchName): void;

    /**
     * @param $sortOrder
     */
    public function setSortOrder($sortOrder): void;
}
