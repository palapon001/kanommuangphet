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
    case 'upload':
        $id = (int) ($_POST['id'] ?? 0);
        $role = preg_replace('/[^a-z0-9_-]/i', '', $_POST['role'] ?? 'user');
        $field_name = $_POST['field_name'] ?? 'avatar_url';
        $uploadPath = $_POST['upload_path'] ?? 'uploads/';

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
                $newFileName = $id . '_' . time() . '.' . $fileExt;

                // path เต็มของไฟล์
                $fullPath = rtrim($uploadPath, '/') . '/' . $newFileName;

                // ✅ แสดง path debug
                echo json_encode(['status' => 'debug', 'path' => $fullPath]);

                if (move_uploaded_file($fileTmp, $fullPath)) {
                    echo json_encode(['status' => 'success', 'file' => $newFileName, 'path' => $fullPath]);
                    redirectWithAlert('success', 'อัพเดทรูปภาพข้อมูลสำเร็จ', 'users');
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกไฟล์ได้', 'path' => $fullPath]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่มีไฟล์ถูกอัปโหลด']);
        }
        break;

}
