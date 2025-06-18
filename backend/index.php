<?php
session_start();

$baseDir = '../../';
$parentDir = '../';

// กำหนดค่าเริ่มต้นสำหรับการแสดงผล
require_once __DIR__ . $baseDir . 'templates/header_footer.php';
require_once __DIR__ . $baseDir . 'src/connect.php';
require_once __DIR__ . $baseDir . 'src/function.php';

// กรองค่า page เพื่อความปลอดภัย
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['page']) : '';
if (!empty($page)) {
  $_SESSION['page'] = $page;
}
$currentPage = $page ?: ($_SESSION['page'] ?? '');
$pageFile = $parentDir . "content/{$page}.php";
$config['title'] = 'Kanom Muang Phet (Backend)';
$config['description'] = 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม (Backend)';
renderHead($config);
?>


<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <?php include $parentDir . 'templates/aside.php'; ?>
    <!-- Layout container -->
    <div class="layout-page">
      <!-- Navbar -->
      <?php include $parentDir . 'templates/navbar.php'; ?>
      <!-- / Navbar -->
      <!-- Content wrapper -->
      <div class="content-wrapper">
        <!-- Content -->
        <?php
        if (!empty($search)) {
          include $parentDir . 'content/search.php';
        } else {
          ($page && file_exists($pageFile)) ? include $pageFile : include $parentDir . 'content/dashboard.php';
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