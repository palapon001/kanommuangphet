<?php
session_start();
require_once('linelogin.php');
require_once('connect.php');

$line = new LineLogin();
$pdo = getPDOConnection(); // สร้าง PDO

$code = $_GET['code'] ?? '';
$state = $_GET['state'] ?? '';

if (!$code || !$state) {
    header('Location: ../login.php?alert=error&text=เกิดข้อผิดพลาด');
    exit;
}

$token = $line->token($code, $state);

if (isset($token->error)) {
    header('Location: ../login.php?alert=error&text=เกิดข้อผิดพลาด');
    exit;
}

if (isset($token->id_token)) {
    $profile = $line->profileFormIdToken($token);
    $_SESSION['line'] = $profile;

    try {
        // ตรวจสอบผู้ใช้ในฐานข้อมูล
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$profile->email]);
        $user = $stmt->fetch();

        if ($user) {
            // มีผู้ใช้แล้ว → login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: " . $config['url'] . "index.php?alert=success&text=เข้าสู่ระบบ LINE สำเร็จ");
            exit;
        } else {
            // ยังไม่มีผู้ใช้ → redirect ไป register
            header("Location: " . $config['url'] . "register.php?login_type=line");
            exit;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: " . $config['url'] . "login.php?alert=error&text=เกิดข้อผิดพลาด");
        exit;
    }
}
?>