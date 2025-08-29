<?php
session_start();
require_once __DIR__ . '/../src/connect.php';

$pdo = getPDOConnection();

// ดึงค่าจาก POST
$name       = trim($_POST['name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$phone      = trim($_POST['phone'] ?? '');
$user_type  = $_POST['user_type'] ?? 'user';
$login_type = $_POST['login_type'] ?? 'normal';
$avatar_url = $_POST['avatar_url'] ?? '';

// ตรวจสอบค่าว่าง
if (!$name || !$email || (!$password && $login_type !== 'line') || !$user_type) {
    header("Location: " . $config['url'] . "register.php?alert=error&text=กรุณากรอกข้อมูลให้ครบถ้วน");
    exit;
}

// แฮชรหัสผ่าน (ถ้าไม่ใช่ LINE login)
$hashed_password = ($login_type === 'line' && !$password) ? null : password_hash($password, PASSWORD_DEFAULT);

try {
    // ตรวจสอบอีเมลซ้ำ
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        header("Location: " . $config['url'] . "register.php?alert=error&text=อีเมลนี้มีอยู่ในระบบแล้ว");
        exit;
    }

    // บันทึกผู้ใช้ใหม่
    $sql = "INSERT INTO users (name, email, password, phone, role, login_type, avatar_url, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $hashed_password, $phone, $user_type, $login_type, $avatar_url]);

    // ถ้าเป็น LINE login -> login อัตโนมัติ
    if ($login_type === 'line') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: " . $config['url'] . "index.php?alert=success&text=สมัครสมาชิกและเข้าสู่ระบบ LINE สำเร็จ");
            exit;
        }
    } else {
        header("Location: " . $config['url'] . "login.php?alert=success&text=สมัครสมาชิกสำเร็จ");
        exit;
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    header("Location: " . $config['url'] . "register.php?alert=error&text=เกิดข้อผิดพลาดในการสมัคร");
    exit;
}
