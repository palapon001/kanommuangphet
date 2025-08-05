<?php
require_once __DIR__ . '/src/header_footer.php';
$config = [
  'title' => 'kanommuangphet - Register',
  'description' => 'สมัครสมาชิกใหม่',
];
renderHead($config, 'auth');
?>

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="index.php" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">
                <img src="https://placehold.co/100x40?text=Logo" alt="logo register">
              </span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">ลงทะเบียน</h4>
          <p class="mb-4">กรุณากรอกข้อมูลเพื่อสร้างบัญชีใหม่</p>
          <form id="formRegister" class="mb-3" action="<?= $config['url'] ?>process/register_process.php" method="post">
            <div class="mb-3">
              <label for="name" class="form-label">ชื่อผู้ใช้</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="ชื่อผู้ใช้" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">อีเมล</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="กรุณากรอกอีเมล" required />
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">เบอร์โทร</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxx" />
            </div>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">รหัสผ่าน</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control" placeholder="กรอกรหัสผ่าน" required />
                <span class="input-group-text cursor-pointer"><i class='bx bx-hide'></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">สมัครสมาชิก</button>
            </div>
          </form>
          <div class="text-center">
            <span class="text-muted">มีบัญชีอยู่แล้ว?</span>
            <a href="login.php">
              <span class="fw-bold">เข้าสู่ระบบ</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php renderFooter($config); ?>
