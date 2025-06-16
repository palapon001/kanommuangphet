<?php
$shops = dbSelect('shops');
if ($_GET['debug'] == 'dev') {
    echo '<pre> Table shops';
    print_r($shops);
    echo '</pre>';
}

?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> ร้านค้า</h4>
    <hr class="my-5" />
    <?= renderTable($shops,''); ?>
</div>