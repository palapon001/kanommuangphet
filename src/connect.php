<?php 

$host = $_SERVER['HTTP_HOST'];
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$config = [
  'title' => 'kanommuangphet',
  'description' => 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม',
  'url' => "{$protocol}://{$host}/kanommuangphet/"
];

function getPDOConnection()
{
    // ตรวจจาก SERVER_NAME หรือ environment
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
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



