<?php
require_once __DIR__ . '/../src/connect.php';
require_once __DIR__ . '/../src/function.php';

$act = $_POST['act'] ?? '';
echo '<pre>';
echo 'act = ' . $act . '<br>';
print_r($_POST);
echo '</pre>';

$act = $_POST['act'] ?? $_GET['act'] ?? '';

// ตรวจสอบการกระทำ
if (!in_array($act, ['insert', 'update', 'delete'])) {
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง', 'ingredients');
}

// เตรียมข้อมูล
$data = [
    'name' => trim($_POST['name'] ?? ''),
    'unit' => trim($_POST['unit'] ?? ''),
    'image' => trim($_POST['image'] ?? ''),
    'price' => floatval($_POST['price'] ?? 0),
    'created_at' => date('Y-m-d H:i:s'),
];

switch ($act) {
    case 'insert':
        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อวัตถุดิบ', 'ingredients');
        }

        if (dbInsert('ingredients', $data)) {
            redirectWithAlert('success', 'เพิ่มวัตถุดิบสำเร็จ', 'ingredients');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล', 'ingredients');
        }
        break;

    case 'update':
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'ingredients');
        }

        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อวัตถุดิบ', 'ingredients');
        }

        unset($data['created_at']); // ไม่อัปเดต created_at

        if (dbUpdate('ingredients', $data, 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'แก้ไขข้อมูลสำเร็จ', 'ingredients');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล', 'ingredients');
        }
        break;

    case 'delete':
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'ingredients');
        }

        if (dbDelete('ingredients', 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'ลบข้อมูลสำเร็จ', 'ingredients');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการลบข้อมูล', 'ingredients');
        }
        break;
}
