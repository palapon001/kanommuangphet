<?php
require_once __DIR__ . '/../src/connect.php';
require_once __DIR__ . '/../src/function.php';

if ($_POST['debug'] == 'dev') {
    $act = $_GET['act'] ?? '';
    echo '<pre>';
    echo 'act = ' . $act;
    print_r($_POST);
    echo '</pre>';
}

$act = $_GET['act'] ?? '';
if (!in_array($act, ['insert', 'update', 'delete'])) {
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง');
}

$data = [
    'name' => trim($_POST['name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'password' => trim($_POST['password'] ?? ''),
    'login_type' => trim($_POST['login_type'] ?? ''),
    'line_user_id' => trim($_POST['line_user_id'] ?? ''),
    'avatar_url' => trim($_POST['avatar_url'] ?? ''),
    'role' => trim($_POST['role'] ?? ''),
];

switch ($act) {
    case 'insert':
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
        ];

        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อ');
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            redirectWithAlert('warning', 'อีเมลไม่ถูกต้อง');
        }

        if ($data['password'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกรหัสผ่าน');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');

        if (dbInsert('users', $data)) {
            redirectWithAlert('success', 'เพิ่มข้อมูลสำเร็จ');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล');
        }
        break;

    case 'update':
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง');
        }

        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อ');
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            redirectWithAlert('warning', 'อีเมลไม่ถูกต้อง');
        }

        if ($data['password'] !== '') {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        if (dbUpdate('users', $data, 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'แก้ไขข้อมูลสำเร็จ');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล');
        }
        break;

    case 'delete':
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง');
        }
        if (dbDelete('users', 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'ลบข้อมูลสำเร็จ');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }
        break;
}
