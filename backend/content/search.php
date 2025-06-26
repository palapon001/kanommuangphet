<?php
$search = $_GET['q'] ?? '';
$params = [':q1' => "%$search%"];

$resultSearch = [];

foreach (array_keys($model) as $table) {
    $where = "(name LIKE :q1)";
    $resultDB = dbSelect($table, $where, $params, 20); // ปรับ limit ตามต้องการ

    if (!empty($resultDB)) {
        foreach ($resultDB as &$row) {
            $row['_source'] = $table;
        }
        $resultSearch = array_merge($resultSearch, $resultDB);
    }
}


// Debug
if ($_GET['debug'] == 'dev') {
    echo '<pre>Table resultSearch';
    print_r($resultSearch);
    echo '</pre>';
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">ผลการค้นหา จากหลายตาราง /</span> <?= htmlspecialchars($search); ?>
    </h4>
    <hr class="my-5" />
    <?= renderTable($resultSearch); ?>
</div> 