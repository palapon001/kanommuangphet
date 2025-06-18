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
    $host = 'localhost';
    $dbname = 'kanommuangphet';
    $username = 'root'; // เปลี่ยนตามเครื่องของคุณ
    $password = 'root';     // หากมีรหัสผ่านให้ใส่

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // echo "✅";
        return $pdo;
    } catch (PDOException $e) {
        // echo "❌";
        die("Database Connection failed: " . $e->getMessage());
    }
}

