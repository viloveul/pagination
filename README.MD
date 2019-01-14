
# Installation

make sure your php version > 7.0

```bash
composer require viloveul/pagination
```

## HOW

```php
require __DIR__ . '/vendor/autoload.php';

$parameter = new Viloveul\Pagination\Parameter('search', $_GET);
$parameter->setBaseUrl('/api/v1/user/index');

// $paremeter->setConditions([]);
// $parameter->setCurrentPage(1);
// $parameter->setOrderBy('id');
// $parameter->setSortOrder('asc');
// $parameter->setPageSize(20);

$pagination = new Viloveul\Pagination\Builder($parameter);
$pagination->prepare(function () use ($parameter) {
    $model = new YourUserModelSample();
    foreach ($parameter->getConditions() as $key => $value) {
        $model->where($key, 'LIKE', "%{$value}%");
    }
    // set total results
    $this->total = $model->count();
    // set result data array
    $this->data = $model->orderBy($parameter->getOrderBy(), $parameter->getSortOrder())
        ->skip(($parameter->getCurrentPage() * $parameter->getPageSize()) - $parameter->getPageSize())
        ->take($parameter->getPageSize())
        ->get()
        ->toArray();
});

var_dump($pagination->getResults());
```
