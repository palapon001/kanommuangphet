<?php
$shops = $model['shops'];
if ($_GET['debug'] == 'dev') {
    echo '<pre> Table shops';
    print_r($shops);
    echo '</pre>';
}


$cols = [
    'id' => 'ID', 
    'name' => 'ชื่อร้าน', 
    'location' => 'ที่อยู่',
    'shop_type' => 'ประเภทร้านค้า',  
    'phone' => 'เบอร์โทร', 
    'created_at' => 'วันที่สร้าง'
];

?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> ร้านค้า</h4>
    <hr class="my-5" />
   <div class="rounded bg-white  table-responsive" style="padding: 10px 15px;">
        <?= renderTable( $shops ,$cols, 'shop_process.php', $config); ?>
    </div>
</div>