<?php
session_start();
$baseDir = '../../';
$parentDir = '../';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
  header("Location: ../login.php"); 
  exit;
}

// กำหนดค่าเริ่มต้นสำหรับการแสดงผล
require_once __DIR__ . $baseDir . 'src/header_footer.php';
require_once __DIR__ . $baseDir . 'src/connect.php';
require_once __DIR__ . $baseDir . 'src/function.php';
require_once __DIR__ . $baseDir . 'src/banks.php';
// กรองค่า page เพื่อความปลอดภัย
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['page']) : '';
if (!empty($page)) {
  $_SESSION['page'] = $page;
}
$currentPage = $page ?: ($_SESSION['page'] ?? 'users');
$pageFile = "content/{$page}.php";
$config['title'] = 'Kanom Muang Phet (Backend)';
$config['description'] = 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม (Backend)';
$config['role'] = 'backend'; 

renderHead($config); 
?>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <?php include 'templates/aside.php'; ?>
    <!-- Layout container -->
    <div class="layout-page">
      <!-- Navbar -->
      <?php include 'templates/navbar.php'; ?>
      <!-- / Navbar -->
      <!-- Content wrapper -->
      <div class="content-wrapper">
        <!-- Content -->
        <?php

        $model = [
          'users' => dbSelect('users'),
          'shops' => dbSelect('shops'),
          'products' => dbSelect('products'),
          'ingredients' => dbSelect('ingredients'),
          'banks' => $banks ?? []
        ];

        if (!empty($search)) {
          include 'content/search.php'; // ถ้ามีคำค้นหา ให้แสดงหน้า search
        } else {
          if ($page == 'logout') {
            session_destroy();
            header("Location: ../login.php"); // ออกจากระบบ
            exit;
          }
          ($page && file_exists($pageFile))
            ? include $pageFile               // ถ้ามีหน้าและไฟล์มีจริง ให้ include
            : include 'content/dashboard.php'; // ไม่เช่นนั้นให้กลับไปหน้า dashboard
        }
        ?>
        <!-- / Content -->
        <!-- Footer -->
        <?php include 'templates/footer.php'; ?>
        <!-- / Footer -->
        <div class="content-backdrop fade"></div>
      </div>
      <!-- / Content wrapper -->
    </div>
    <!-- / Layout page -->
  </div>
</div>
<!-- / Layout wrapper -->
<?php renderFooter($config); ?>