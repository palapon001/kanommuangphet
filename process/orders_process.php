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
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง', 'orders');
}

// เตรียมข้อมูลจาก POST ที่เกี่ยวข้องกับตาราง orders จริง ๆ
$data = [
    'user_id' => (int) ($_POST['user_id'] ?? 0),
    'shop_id' => (int) ($_POST['shop_id'] ?? 0),
    'total' => floatval($_POST['total'] ?? 0),
    'status' => trim($_POST['status'] ?? 'pending'),
    'created_at' => $_POST['order_date'] ?? date('Y-m-d H:i:s'),
];

switch ($act) {
    case 'insert':
        if ($data['user_id'] <= 0 || $data['shop_id'] <= 0) {
            redirectWithAlert('warning', 'กรุณาระบุผู้ใช้และร้านค้าให้ถูกต้อง', 'orders');
        }

        if (dbInsert('orders', $data)) {
            redirectWithAlert('success', 'เพิ่มคำสั่งซื้อสำเร็จ', 'orders');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล', 'orders');
        }
        break;

    case 'update':
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'orders');
        }

        unset($data['created_at']); // ไม่ควรอัปเดต created_at

        if (dbUpdate('orders', $data, 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'แก้ไขข้อมูลสำเร็จ', 'orders');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล', 'orders');
        }
        break;

    case 'delete':
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'orders');
        }

        if (dbDelete('orders', 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'ลบข้อมูลสำเร็จ', 'orders');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการลบข้อมูล', 'orders');
        }
        break;
}
