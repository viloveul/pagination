<?php

/**
 * composer install
 * dump file dump.sql
 */

require_once __DIR__ . '/vendor/autoload.php';

$data = [];
$meta = [];
$links = [];

if (!isset($_GET['size'])) {
	$_GET['size'] = 2; // default 10
}

try {
	$db = new PDO('mysql:host=127.0.0.1;port=3306;dbname=viloveul_contoh', 'dev', 'something');

	$parameter = new Viloveul\Pagination\Parameter('search', $_GET);
	$parameter->setBaseUrl('/example.php');
	$pagination = new Viloveul\Pagination\Builder($parameter);
	$pagination->with(function ($conditions, $size, $page, $order, $sort) use ($db) {
		$where = ['1=1'];
		$param = [];
		foreach ($conditions as $key => $value) {
			if (in_array($key, ['judul', 'isi'])) {
				$where[] = 'judul LIKE :' . $key;
				$param[':' . $key] = "%{$value}%";
			}
		}
		$offset = ($page * $size) - $size;

		$result = $db->prepare('SELECT * FROM tbl_content WHERE ' . implode(' AND ', $where) . " ORDER BY {$order} {$sort} LIMIT {$size} OFFSET {$offset}");
		$result->execute($param);

		$count = $db->prepare('SELECT COUNT(*) AS total FROM tbl_content WHERE ' . implode(' AND ', $where));
		$count->execute($param);

		return new Viloveul\Pagination\ResultSet($count->fetchColumn(), $result->fetchAll(PDO::FETCH_OBJ));
	});

	$meta = $pagination->getMeta();
	$data = $pagination->getData();
	$links = $pagination->getLinks();

} catch (PDOException $e) {
	throw $e;
}

?>
<form method="GET" action="<?= $_SERVER['PHP_SELF']; ?>">
	<input type="text" value="<?= isset($_GET['search_judul']) ? $_GET['search_judul'] : ''; ?>" name="search_judul" placeholder="Judul">
	<input type="text" value="<?= isset($_GET['search_isi']) ? $_GET['search_isi'] : ''; ?>" name="search_isi" placeholder="Isi">
	<button type="submit">Cari</button>
</form>
<table border="1">
	<thead>
		<tr>
			<th>ID</th>
			<th>Judul</th>
			<th>Isi</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data as $row): ?>
			<tr>
				<td><?= $row->id; ?></td>
				<td><?= $row->judul; ?></td>
				<td><?= $row->isi; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<br />
<?php for ($i = 1; $i <= ceil($meta['total'] / $meta['size']); $i++): ?>
	<a href="/example.php?<?= http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?= $i; ?></a>
<?php endfor; ?>
<br />
<?php $links['prev'] and printf('<a href="%s">%s</a>', $links['prev'], 'Prev'); ?>
<?php $links['next'] and printf('<a href="%s">%s</a>', $links['next'], 'Next'); ?>
