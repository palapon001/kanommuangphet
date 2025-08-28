<?php
require_once __DIR__ . '/../src/connect.php';
require_once __DIR__ . '/../src/function.php';

$act = $_POST['act'] ?? $_GET['act'] ?? '';
if (!in_array($act, ['insert', 'update', 'delete', 'upload'])) {
    redirectWithAlert('error', 'การกระทำไม่ถูกต้อง', 'shops');
}

$data = [
    'name' => trim($_POST['name'] ?? ''),
    'owner_id' => (int) ($_POST['owner_id'] ?? 0),
    'address' => trim($_POST['address'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'profile_image' => trim($_POST['profile_image'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'bank_account_name' => trim($_POST['bank_account_name'] ?? ''),
    'bank_account_number' => trim($_POST['bank_account_number'] ?? ''),
    'bank_key' => trim($_POST['bank_key'] ?? ''),
    'created_at' => date('Y-m-d H:i:s'),
];

switch ($act) {
    case 'insert':
        // 1) Insert ร้านค้า ลง DB ก่อน
        $insertId = dbInsert('shops', $data);

        if ($insertId) {
            uploadFileAndUpdate('shops', $insertId, 'profile_image', $data); // อัปโหลด profile_image
            uploadFileAndUpdate('shops', $insertId, 'logo', $data);
            redirectWithAlert('success', 'เพิ่มร้านค้าสำเร็จ', 'shops');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการเพิ่มร้านค้า', 'shops');
        }
        break;

    case 'update':
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'shops');
        }

        if ($data['name'] === '') {
            redirectWithAlert('warning', 'กรุณากรอกชื่อร้าน', 'shops');
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            redirectWithAlert('warning', 'อีเมลไม่ถูกต้อง', 'shops');
        }

        if (dbUpdate('shops', $data, 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'แก้ไขข้อมูลร้านค้าสำเร็จ', 'shops');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล', 'shops');
        }
        break;

    case 'delete':
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirectWithAlert('error', 'ID ไม่ถูกต้อง', 'shops');
        }
        if (dbDelete('shops', 'id = :id', ['id' => $id])) {
            redirectWithAlert('success', 'ลบร้านค้าสำเร็จ', 'shops');
        } else {
            redirectWithAlert('error', 'เกิดข้อผิดพลาดในการลบร้านค้า', 'shops');
        }
        break;

    case 'upload':
        $id = (int) ($_POST['id'] ?? 0);
        $field_name = $_POST['field_name'] ?? 'profile_image';
        $uploadPath = $_POST['upload_path'] ?? 'uploads/shops/';

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
                $newFileName = $id . '_shop.' . $fileExt;

                // path เต็มของไฟล์
                $fullPath = rtrim($uploadPath, '/') . '/' . $newFileName;

                if (move_uploaded_file($fileTmp, $fullPath)) {
                    if (dbUpdate('shops', [$field_name => $fullPath], 'id = :id', ['id' => $id])) {
                        redirectWithAlert('success', 'อัพเดทรูปโปรไฟล์ร้านค้าสำเร็จ', 'shops');
                    } else {
                        redirectWithAlert('error', 'เกิดข้อผิดพลาดในการอัพเดทรูปร้านค้า', 'shops');
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
