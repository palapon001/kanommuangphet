<?php
require_once __DIR__ . '/src/connect.php';
require_once __DIR__ . '/src/header_footer.php';

$config['title'] = 'kanommuangphet - Register';
$config['description'] = 'สมัครสมาชิกใหม่';
renderHead($config, 'auth');

// เช็ค LINE session
$line = $_SESSION['line'] ?? null;


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

          <!-- แสดงข้อความ Login LINE สำเร็จ -->
          <?php if (!empty($line) && !empty($line->access_token)): ?>
            <div id="lineLoginSuccess" class="alert alert-success" role="alert">
              ✅ Login LINE สำเร็จ
            </div>
          <?php endif; ?>

          <p class="mb-4">กรุณากรอกข้อมูลเพื่อสร้างบัญชีใหม่</p>

          <!-- Debug -->
          <?php if (($_GET['debug'] ?? '') === 'dev'): ?>
            <pre><?php print_r($line); ?></pre>
          <?php endif; ?>

          <form id="formRegister" class="mb-3" action="<?= $config['url'] ?>process/register_process.php" method="post">
            <div class="mb-3">
              <label for="name" class="form-label">ชื่อผู้ใช้</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="ชื่อผู้ใช้" 
                     value="<?= htmlspecialchars($line->name ?? '') ?>" required />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">อีเมล</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="กรุณากรอกอีเมล" 
                     value="<?= htmlspecialchars($line->email ?? '') ?>" 
                     <?= !empty($line->email) ? 'readonly' : 'required' ?>/>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">เบอร์โทร</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="09xxxxxxxx" />
            </div>

            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">รหัสผ่าน</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control" placeholder="กรอกรหัสผ่าน" required />
                <span class="input-group-text cursor-pointer"><i class='bx bx-hide'></i></span>
              </div>
            </div>

            <!-- ประเภทผู้ใช้ -->
            <div class="mb-3">
              <label for="user_type" class="form-label">ประเภทผู้ใช้</label>
              <select class="form-select" id="user_type" name="user_type" required>
                <option value="">-- กรุณาเลือกประเภทผู้ใช้ --</option>
                <option value="ingredient">ผู้ผลิตวัตถุดิบ</option>
                <option value="dessert">ผู้ผลิตขนมหวาน</option>
                <option value="user">ผู้ใช้ทั่วไป</option>
                <?php if (($_GET['debug'] ?? '') === 'dev'): ?>
                  <option value="admin">แอดมิน</option>
                <?php endif; ?>
              </select>
            </div>

             <input type="hidden" name="avatar_url" value="<?= $line->picture ?>">
            <input type="hidden" name="login_type" value="<?= !empty($line->access_token) ? 'line' : 'normal' ?>">

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
