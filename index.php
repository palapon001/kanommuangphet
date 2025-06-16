<?php
session_start();
require_once __DIR__ . '/templates/header_footer.php';
require_once __DIR__ . '/src/connect.php';
require_once __DIR__ . '/src/function.php';
$host = $_SERVER['HTTP_HOST'];
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$config = [
  'title' => 'kanommuangphet',
  'description' => 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม',
  'url' => "{$protocol}://{$host}/kanommuangphet/"
];
// กรองค่า page เพื่อความปลอดภัย
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['page']) : '';
if (!empty($page)) {
  $_SESSION['page'] = $page;
}
$currentPage = $page ?: ($_SESSION['page'] ?? '');
$pageFile = "content/{$page}.php";
renderHead($config);
?>

<!-- sweetalert2 -->
<?php if (!empty($_GET['alert'])): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire({
      icon: <?= json_encode($_GET['alert']) ?>,
      title: <?= json_encode($_GET['text'] ?? '') ?>,
      confirmButtonText: 'ตกลง',
      customClass: {
        container: 'my-swal-container'
      }
    }).then(() => {
      // ลบ query string ของ alert/text ออก
      const url = new URL(window.location.href);
      url.searchParams.delete('alert');
      url.searchParams.delete('text');
      window.history.replaceState(null, '', url);
    });
  </script>
  <style>
    .my-swal-container {
      z-index: 1100 !important;
    }
  </style>
<?php endif; ?>
<!-- / sweetalert2 -->
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
        if (!empty($search)) {
          include 'content/search.php';
        } else {
          ($page && file_exists($pageFile)) ? include $pageFile : include 'content/dashboard.php';
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