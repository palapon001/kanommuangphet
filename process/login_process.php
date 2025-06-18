<?php
session_start();
require_once __DIR__ . '/../src/connect.php';

// เชื่อมต่อฐานข้อมูล
$pdo = getPDOConnection();

// รับค่าพารามิเตอร์ debug (จาก GET หรือ POST)
$debug = $_GET['debug'] ?? $_POST['debug'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo ('Please fill in both fields.');
        exit;
    }

    // ดึงข้อมูลผู้ใช้จากอีเมล
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // ตรวจสอบรหัสผ่านหรือข้ามในโหมด debug
    $isPasswordValid = ($debug === 'dev') ? true : password_verify($password, $user['password']);

    if ($user && $isPasswordValid) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if($debug === 'dev') {
            // แสดงข้อมูลผู้ใช้ในโหมด debug
            header("Location: " . $config['url'] . "backend/index.php?alert=success&text=เข้าสู่ระบบสำเร็จ");
            exit;
        }

        header("Location: " . $config['url'] . "index.php?alert=success&text=เข้าสู่ระบบสำเร็จ");
        exit;
    } else {
        header("Location: " . $config['url'] . "login.php?alert=error&text=กรุณาตรวจสอบอีเมลและรหัสผ่านของคุณอีกครั้ง");
        exit;
    }
}
?>
