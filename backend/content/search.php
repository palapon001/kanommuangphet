<?php
$search = $_GET['q'] ?? '';
$params = [':q1' => "%$search%"];

// mapping ตาราง → ชื่อคอลัมน์ที่ต้องค้นหา
$searchMap = [
    'users'     => 'name',
    'products'   => 'name', 
    'shops'      => 'name',
    'ingredients' => 'name',
    // เพิ่มตามต้องการ
];

$resultSearch = [];

foreach ($searchMap as $table => $column) {
    $where = "($column LIKE :q1)";
    $resultDB = dbSelect($table, $where, $params, 20); // ปรับ limit ตามต้องการ
    if (!empty($resultDB)) {
        // แนบชื่อตารางเป็น source ด้วย
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