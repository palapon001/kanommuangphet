<?php
session_start();
require_once __DIR__ . '/../src/connect.php'; 

// เชื่อมต่อฐานข้อมูล
$pdo = getPDOConnection();

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
$phone = $_POST['phone'] ?? null;
$role = 'user';
$login_type = 'normal';

// ตรวจสอบว่าอีเมลนี้มีอยู่แล้วหรือยัง
$checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$checkEmail->execute([$email]);
if ($checkEmail->fetchColumn() > 0) {
    // ถ้ามีอีเมลนี้แล้ว
    header("Location: " . $config['url'] . "register.php?alert=error&text=อีเมลนี้มีอยู่ในระบบแล้ว");
    exit;
}
// ถ้าอีเมลไม่ซ้ำ -> สมัครได้
$sql = "INSERT INTO users (name, email, password, phone, role, login_type, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$name, $email, $password, $phone, $role, $login_type])) {
    header("Location: " . $config['url'] . "login.php?alert=success&text=สมัครสมาชิกสำเร็จ");
    exit;
} else {
    header("Location: " . $config['url'] . "register.php?alert=error&text=เกิดข้อผิดพลาดในการสมัคร");
    exit;
}
