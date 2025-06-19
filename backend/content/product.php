<?php
$products = dbSelect('products');
if ($_GET['debug'] == 'dev') {
    echo '<pre> Table products';
    print_r($products);
    echo '</pre>';
}

?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> สินค้า</h4>
    <hr class="my-5" />
    <?= renderTable($products,'user_process.php'); ?>
</div>