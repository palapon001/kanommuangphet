<?php

$host = $_SERVER['HTTP_HOST'];
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$config = [
    'title' => 'kanommuangphet',
    'description' => 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม',
    'url' => ($host === 'localhost' ? "{$protocol}://{$host}/kanommuangphet/" : "{$protocol}://{$host}/"),
    'cacheVersion' => 0.0002
];

function getPDOConnection()
{
    // ตรวจจาก SERVER_NAME หรือ environment
    if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '192.168.1.147') {
        // โหมด offline (บนเครื่อง local)
        $host = 'localhost';
        $dbname = 'kanommuangphet';
        $username = 'root';
        $password = 'root';
    } else {
        // โหมด online (บน server จริง)
        $host = 'localhost'; // ส่วนมาก shared hosting ใช้ localhost
        $dbname = 'makeallc_kanommuangohet';
        $username = 'makeallc_kanommuangphet';
        $password = 'muangphet';
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e) {
        die("Database Connection failed: " . $e->getMessage());
    }
}

/**
 * ฟังก์ชันทดสอบการเชื่อมต่อ
 */
function testConnection()
{
    try {
        $pdo = getPDOConnection();
        echo "<p style='color:green;'>✅ Database connected successfully!</p>";

        // ทดสอบ query เบื้องต้น
        $stmt = $pdo->query("SELECT NOW() as now_time");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Server Time: " . $row['now_time'] . "</p>";

    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
    }
}

if ($_GET['debug'] == 'dev') { 
    testConnection();
}
