<?php
// กำหนดค่าเริ่มต้นสำหรับการแสดงผล
require_once __DIR__ . '/src/header_footer.php';
$config['title'] = 'kanommuangphet';
$config['description'] = 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม';
renderHead($config, 'auth');
?>

<!-- Content -->
<button type="button" class="btn btn-<?= $_GET['debug'] == 'dev' ? 'light' : 'secondary'; ?>  position-absolute" style="left: 20px; top: 20px; z-index: 10; font-family: 'Kanit';"
  onclick="window.history.back();">
  กลับไปก่อนหน้า
</button>
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <? if ($_GET['debug'] == 'dev') : ?>
        <style>
          body {
            background: #252525 !important;
          }

          .authentication-wrapper.authentication-basic .authentication-inner:before,
          .authentication-wrapper.authentication-basic .authentication-inner:after {
            background: none !important;
          }
        </style>
      <? endif; ?>
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">
                <img src="https://placehold.co/100x40?text=Logo" alt="logo login">
              </span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">เข้าสู่ระบบ</h4>
          <p class="mb-4">กรุณาเข้าสู่ระบบเพื่อเริ่มต้นการผจญภัย</p>
          <form id="formAuthentication" class="mb-3" action="<?= $config['url'] ?>process/login_process.php"
            method="post">
            <div class="mb-3">
              <label for="email" class="form-label">อีเมล</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="กรุณากรอกอีเมล" required />
            </div>
            <div class="mb-3 form-password-toggle">
              <label for="password" class="form-label">รหัสผ่าน</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control"
                  placeholder="กรุณากรอกรหัสผ่าน" aria-describedby="password" required />
                <input type="hidden" name="debug" value="<?= $_GET['debug'] ?? '' ?>">
                <span class="input-group-text cursor-pointer"><i class='bx bx-hide'></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">เข้าสู่ระบบ</button>
            </div>
          </form>
          <div class="text-center">
            <span class="text-muted d-inline-block">ยังไม่มีบัญชี?</span>
            <a href="register.php">
              <span class="fw-bold">ลงทะเบียน</span>
            </a>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>
</div>
<!-- / Content -->

<?php renderFooter($config); ?>