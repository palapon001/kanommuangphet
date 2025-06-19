<?php
$users = dbSelect('users');

$cols = [
    'id' => 'ลำดับ',
    'name' => 'ชื่อ',
    'role' => 'สถานะ',
    'avatar_url' => 'รูปโปรไฟล์',
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
    <?= renderTable($cols, $users, 'user_process.php'); ?>
</div>


