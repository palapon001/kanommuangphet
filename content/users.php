<?php
$users = dbSelect('users');

$setColumns = [
    'id' => 'ลำดับ',
    'name' => 'ชื่อ',
    'password' => 'รหัสผ่าน',
    'email' => 'อีเมล',
    'phone' => 'เบอร์',
    'role' => 'สถานะ',
    'signup_source' => 'สมัครจาก',
    'avatar_url' => 'รูปโปรไฟล์',
    'created_at' => 'สมัครเมื่อ'
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
    <?= renderTable($users, 'user_process.php', $setColumns ); ?>
</div>