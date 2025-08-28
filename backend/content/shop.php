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
    'owner_id' => 'รหัสเจ้าของ',
    'address' => 'ที่อยู่',
    'email' => 'อีเมล',
    'phone' => 'เบอร์โทร',
    'profile_image' => 'รูปโปรไฟล์',
    'description' => 'รายละเอียด',
    'bank_account_name' => 'ชื่อบัญชีธนาคาร',
    'bank_account_number' => 'เลขที่บัญชี',
    'bank_key' => 'รหัสธนาคาร',
    'created_at' => 'วันที่สร้าง'
];
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> ร้านค้า</h4>
    <hr class="my-5" />
   <div class="rounded bg-white  table-responsive" style="padding: 10px 15px;">
        <?= renderTable( $shops ,$cols, 'shop_process.php'); ?>
    </div>
</div>