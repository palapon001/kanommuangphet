<?php
$ingredients = $model['ingredients'];
if ($_GET['debug'] == 'dev') {
    echo '<pre> Table ingredients';
    print_r($ingredients);
    echo '</pre>';
}

$cols = [
    'id'             => 'ID', 
    'shop_id'        => 'รหัสร้านค้า',   
    // 'name'           => 'ชื่อสินค้า',   
    // // 'description'    => 'รายละเอียด',   
    // // 'price'          => 'ราคา',   
    'products_image' => 'รูปสินค้า',   
    // 'created_at'     => 'วันที่สร้าง'
];

?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> วัตถุดิบ</h4>
    <hr class="my-5" />
    <?= renderTable($ingredients,'','ingredients_process.php'); ?>
</div>