<?php
$where = "(name LIKE :q1)";
$params = [':q1' => "%$search%"];
$result_search = dbSelect($currentPage, $where, $params, 50);

if ($_GET['debug'] == 'dev') {
    echo '<pre> Table result_search';
    print_r($result_search);
    echo '</pre>';
}

?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ผลการค้นหา ตาราง /</span> <?= $currentPage; ?> </h4>
    <hr class="my-5" />
    <?= renderTable($result_search); ?>
</div>
?>