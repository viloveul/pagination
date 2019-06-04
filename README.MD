
# Installation

make sure your php version > 7.0

```bash
composer require viloveul/pagination
```

## HOW

```php
require __DIR__ . '/vendor/autoload.php';

$parameter = new Viloveul\Pagination\Parameter('search', $_GET);

// $paremeter->setConditions([]);
// $parameter->setCurrentPage(1);
// $parameter->setOrderBy('id');
// $parameter->setSortOrder('asc');
// $parameter->setPageSize(20);

$pagination = new Viloveul\Pagination\Builder($parameter);
$pagination->with(function ($conditions, $size, $page, $order, $sort) use ($parameter) {
    $model = new YourUserModelSample();
    foreach ($conditions as $key => $value) {
        $model->where($key, 'LIKE', "%{$value}%");
    }
    // set total results
    $total = $model->count();
    // set result data array
    $result = $model->orderBy($order, $sort)->skip(($page * $size) - $size)->take($size)->get();

    return new Viloveul\Pagination\ResultSet($total, $result->toArray());
});

var_dump($pagination->getMeta());
var_dump($pagination->getData());
```