<?php
$products = $model['products'];
if ($_GET['debug'] == 'dev') {
    echo '<pre> Table products';
    print_r($products);
    echo '</pre>';
}

?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> ขนม </h4>
    <hr class="my-5" />
    <div class="rounded bg-white  table-responsive" style="padding: 10px 15px;">
        <?= renderTable($products, '', 'products_process.php'); ?>
    </div>
</div>