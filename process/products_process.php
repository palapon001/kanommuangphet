<?php
require_once __DIR__ . '/../src/connect.php';
require_once __DIR__ . '/../src/function.php';

$act = $_POST['act'] ?? '';
echo '<pre>';
echo 'act = ' . $act . '<br>';
print_r($_POST);
echo '</pre>';

$act = $_POST['act'] ?? $_GET['act'] ?? '';

if (!in_array($act, ['insert', 'update', 'delete', 'upload'])) {
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง', 'products');
}

// เตรียมข้อมูลจาก POST
$data = [
    'shop_id' => $_POST['shop_id'] ?? null,
    'name' => trim($_POST['name'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'products_image' => trim($_POST['products_image'] ?? ''),
    'price' => floatval($_POST['price'] ?? 0),
    'created_at' => date('Y-m-d H:i:s'),
];

switch ($act) {

    case 'insert':
        // 1) Insert ร้านค้า ลง DB ก่อน
        $insertId = dbInsert('products', $data);

        if ($insertId) {
            uploadFileAndUpdate('products', $insertId, 'products_image', $data);
            redirectWithAlert('success', 'เพิ่มร้านค้าสำเร็จ', 'products');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มร้านค้า', 'products');
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
    case 'upload':
        $id = (int) ($_POST['id'] ?? 0);
        $field_name = $_POST['field_name'] ?? 'products_image';
        $uploadPath = $_POST['upload_path'] ?? 'uploads/products/';

        // ✅ สร้างโฟลเดอร์ถ้าไม่มี
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (!empty($_FILES['upload']['name'])) {
            if ($_FILES['upload']['error'] !== 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์',
                    'error_code' => $_FILES['upload']['error']
                ]);
                exit;
            }

            $fileTmp = $_FILES['upload']['tmp_name'];
            $fileName = basename($_FILES['upload']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExt, $allowed)) {
                $newFileName = $id . '_products.' . $fileExt;

                // path เต็มของไฟล์
                $fullPath = rtrim($uploadPath, '/') . '/' . $newFileName;

                if (move_uploaded_file($fileTmp, $fullPath)) {
                    if (dbUpdate('products', [$field_name => $fullPath], 'id = :id', ['id' => $id])) {
                        redirectWithAlert('success', 'อัพเดทรูปโปรไฟล์ร้านค้าสำเร็จ', 'products');
                    } else {
                        redirectWithAlert('error', 'เกิดข้อผิดพลาดในการอัพเดทรูปร้านค้า', 'products');
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกไฟล์ได้']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่มีไฟล์ถูกอัปโหลด']);
        }
        break;
}
