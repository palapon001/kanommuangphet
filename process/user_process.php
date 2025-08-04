<?php
require_once __DIR__ . '/../src/connect.php';
require_once __DIR__ . '/../src/function.php';


$act = $_POST['act'] ?? '';
echo '<pre>';
echo 'act = ' . $act . '<br>';
print_r($_POST);
echo '</pre>';


$act = $_POST['act'] ?? $_GET['act'] ?? '';
if (!in_array($act, ['insert', 'update', 'delete'])) {
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง', 'users');
}

$data = [
    'name' => trim($_POST['name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'password' => trim($_POST['password'] ?? ''),
    'login_type' => trim($_POST['login_type'] ?? 'normal'), // ถ้าไม่มีส่งมา ให้ default เป็น 'normal'
    'line_user_id' => trim($_POST['line_user_id'] ?? ''),
    'avatar_url' => trim($_POST['avatar_url'] ?? ''),
    'role' => trim($_POST['role'] ?? 'user'),         // ค่า default ปลอดภัย
    'created_at' => date('Y-m-d H:i:s'),
];

switch ($act) {
    case 'insert':
        echo 'insert working';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if (dbInsert('users', $data)) {
            redirectWithAlert('success', 'เพิ่มข้อมูลสำเร็จ', 'users');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล', 'users');
        }
        break;

    case 'update':
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'users');
        }

        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อ', 'users');
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            redirectWithAlert('warning', 'อีเมลไม่ถูกต้อง', 'users');
        }

        if ($data['password'] !== '') {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        if (dbUpdate('users', $data, 'id = :id', ['id' => $id])) {
            echo 'update id = ' . $id . '<br>' . 'data = ' . print_r($data, true);
            redirectWithAlert('success', 'แก้ไขข้อมูลสำเร็จ', 'users');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล', 'users');
        }
        break;

    case 'delete':
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'users');
        }
        if (dbDelete('users', 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'ลบข้อมูลสำเร็จ', 'users');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการลบข้อมูล', 'users');
        }
        break;
}
