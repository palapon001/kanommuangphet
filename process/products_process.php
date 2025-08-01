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
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง', 'products');
}

// เตรียมข้อมูลจาก POST
$data = [
    'shop_id' => $_POST['shop_id'] ?? null,
    'name' => trim($_POST['name'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'image' => trim($_POST['image'] ?? ''),
    'price' => floatval($_POST['price'] ?? 0),
    'created_at' => date('Y-m-d H:i:s'),
];

switch ($act) {

    case 'insert':
        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อสินค้า', 'products');
        }

        if (dbInsert('products', $data)) {
            redirectWithAlert('success', 'เพิ่มข้อมูลสำเร็จ', 'products');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล', 'products');
        }
        break;

    case 'update':
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'products');
        }

        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อสินค้า', 'products');
        }

        // ไม่ควรอัปเดต created_at สำหรับการแก้ไข
        unset($data['created_at']);

        if (dbUpdate('products', $data, 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'แก้ไขข้อมูลสำเร็จ', 'products');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล', 'products');
        }
        break;

    case 'delete':
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'products');
        }

        if (dbDelete('products', 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'ลบข้อมูลสำเร็จ', 'products');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการลบข้อมูล', 'products');
        }
        break;
}
