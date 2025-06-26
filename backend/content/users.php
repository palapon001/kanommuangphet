<?php
$users = $model['users'];

$cols = [
    'id' => 'ลำดับ',
    'name' => 'ชื่อ',
    'phone' => 'โทรศัพท์',
    'role' => 'สถานะ',
    'avatar_url' => 'รูปโปรไฟล์',
    // 'email' => 'อีเมล', 
    // 'password' => 'รหัสผ่าน',
    // 'login_type' => 'ประเภทการเข้าสู่ระบบ',  
    // 'line_user_id' => 'Line User ID', 
    // 'created_at' => 'วันที่สร้าง',
];

if ($_GET['debug'] == 'dev') {
    echo '<pre> Table users';
    print_r($setColumns);
    print_r($users);
    echo '</pre>';
}

?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> User </h4>
    <hr class="my-5" />
    <div class="rounded bg-white  table-responsive" style="padding: 10px 15px;">
        <?= renderTable($users, $cols, 'user_process.php'); ?>
    </div>
</div>