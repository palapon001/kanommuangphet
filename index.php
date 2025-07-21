<?php
// กำหนดค่าเริ่มต้นสำหรับการแสดงผล
require_once __DIR__ . '/src/header_footer.php';
$host = $_SERVER['HTTP_HOST'];
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$config = [
    'title' => 'kanommuangphet',
    'description' => 'ระบบเปรียบเทียบราคาวัตถุดิบและร้านขนม',
    'url' => "{$protocol}://{$host}/kanommuangphet/"
];
renderHead($config, 'auth');
$lang = $_GET['lang'] ?? 'th';
?>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">
        <!-- Layout container -->
        <div class="layout-page">

            <!-- Navbar -->
            <nav class="navbar navbar-expand-xl navbar-light bg-light border-bottom py-2">
                <div class="container-xxl">
                    <!-- Toggle for mobile -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Navbar content -->
                    <div class="collapse navbar-collapse" id="mainNavbar">

                        <!-- Right Items -->
                        <ul class="navbar-nav ms-auto d-flex align-items-center gap-3">

                            <!-- Social Icons -->
                            <li class="nav-item d-flex gap-2">
                                <a href="https://www.youtube.com/channel/UCVk1W9MgEPe0x0Ckvs6R3vw" target="_blank"
                                    class="text-danger fs-5">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://www.facebook.com/taladsimummuang/" target="_blank"
                                    class="text-primary fs-5">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="http://line.me/ti/p/~@smm_market" target="_blank" class="text-success fs-5">
                                    <i class="fab fa-line"></i>
                                </a>
                            </li>

                            <!-- Divider -->
                            <li class="nav-item d-none d-xl-block text-muted">|</li>

                            <!-- Auth Links -->
                            <li class="nav-item">
                                <a href="/register" class="nav-link">
                                    <i class="fa-solid fa-user-plus me-1"></i>สมัครสมาชิก
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/login" class="nav-link">
                                    <i class="fa-solid fa-right-to-bracket me-1"></i>เข้าสู่ระบบ
                                </a>
                            </li>

                            <!-- Language -->
                            <li class="nav-item d-flex gap-1 align-items-center">
                                <a href="?lang=en"
                                    class="nav-link px-1 <?= $lang == 'en' ? 'fw-bold text-success' : '' ?> ">EN</a>
                                <span class="text-muted"></span>
                                <a href="?lang=th"
                                    class="nav-link px-1 <?= $lang == 'th' ? 'fw-bold text-success' : '' ?>  ">TH</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- / Navbar -->

            <?php
            $centerMenus = [
                ['label' => 'เช็คราคาสินค้า', 'url' => '/check-price', 'img' => 'https://placehold.co/100x100?text=img'],
                ['label' => 'ข้อมูลร้านค้า', 'url' => '/stores', 'img' => 'https://placehold.co/100x100?text=img'],
                ['label' => 'ข้อมูลองค์กร', 'url' => '/company', 'img' => 'https://placehold.co/100x100?text=img'],
                ['label' => 'ข่าวสารและกิจกรรม', 'url' => '/news', 'img' => 'https://placehold.co/100x100?text=img'],
                ['label' => 'ติดต่อ', 'url' => '/contact', 'img' => 'https://placehold.co/100x100?text=img'],
            ];

            $rightMenus = [
                ['label' => 'ซื้อสินค้าออนไลน์', 'url' => '/shop', 'class' => 'btn btn-outline-success'],
            ];

            $moreMenus = [
                ['label' => 'คำถามที่พบบ่อย', 'url' => '/faq'],
                ['label' => 'นโยบาย', 'url' => '/policy'],
                ['label' => 'ศูนย์ช่วยเหลือ', 'url' => '/support'],
            ];
            ?>

            <nav class="navbar navbar-expand-lg bg-light py-4 shadow-sm">
                <div class="container d-flex align-items-center justify-content-between flex-wrap">

                    <!-- โลโก้ ซ้ายสุด -->
                    <a class="navbar-brand me-4" href="/">
                        <img src="https://placehold.co/150x150?text=LOGO" alt="โลโก้" height="80">
                    </a>

                    <!-- เมนูกลาง + หัวข้อ -->
                    <div class="d-flex flex-column align-items-center mx-auto">
                        <div class="d-flex gap-4 flex-wrap justify-content-center">
                            <?php foreach ($centerMenus as $menu): ?>
                                <div class="text-center">
                                    <a href="<?= $menu['url'] ?>" class="nav-link p-0">
                                        <img src="<?= $menu['img'] ?>" alt="<?= $menu['label'] ?>" class="mb-1 rounded"
                                            width="80" height="80">
                                        <div class="small"><?= $menu['label'] ?></div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- เมนูขวาสุด -->
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <?php foreach ($rightMenus as $menu): ?>
                            <a href="<?= $menu['url'] ?>" class="<?= $menu['class'] ?>"><?= $menu['label'] ?></a>
                        <?php endforeach; ?>

                        <!-- Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                เมนูเพิ่มเติม
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php foreach ($moreMenus as $menu): ?>
                                    <li><a class="dropdown-item" href="<?= $menu['url'] ?>"><?= $menu['label'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                </div>
            </nav>

            <hr class="my-4">

            <div class="container my-5">
                <h2 class="text-center mb-4">หมวดหมู่สินค้า</h2>
                <div class="row g-4">

                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="/category/vegetables" class="text-decoration-none">
                            <div class="card text-center border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <i class="fa-solid fa-leaf fa-2x text-success mb-3"></i>
                                    <h6 class="text-dark">ผักสด</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="/category/fruits" class="text-decoration-none">
                            <div class="card text-center border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <i class="fa-solid fa-apple-whole fa-2x text-danger mb-3"></i>
                                    <h6 class="text-dark">ผลไม้</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="/category/meat" class="text-decoration-none">
                            <div class="card text-center border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <i class="fa-solid fa-drumstick-bite fa-2x text-warning mb-3"></i>
                                    <h6 class="text-dark">เนื้อสัตว์</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="/category/dried" class="text-decoration-none">
                            <div class="card text-center border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <i class="fa-solid fa-seedling fa-2x text-secondary mb-3"></i>
                                    <h6 class="text-dark">ของแห้ง</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>

            <hr class="my-4">

            <div class="container my-5">

                <!-- หัวข้อ + ปุ่ม -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">เช็คราคาสินค้า</h2>
                    <a href="/products" class="btn btn-outline-primary">
                        ดูสินค้าทั้งหมด
                    </a>
                </div>

                <!-- Grid สินค้า -->
                <div class="row g-4">

                    <!-- สินค้า 1 -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="https://placehold.co/600x400?text=Hello+World" class="card-img-top"
                                alt="ชื่อสินค้า 1">
                            <div class="card-body">
                                <h5 class="card-title">ผักกาดขาว</h5>
                                <p class="card-text text-muted">฿25 / กก.</p>
                                <a href="/product/1" class="btn btn-sm btn-success">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>

                    <!-- สินค้า 2 -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="https://placehold.co/600x400?text=Hello+World" class="card-img-top"
                                alt="ชื่อสินค้า 2">
                            <div class="card-body">
                                <h5 class="card-title">แตงกวา</h5>
                                <p class="card-text text-muted">฿20 / กก.</p>
                                <a href="/product/2" class="btn btn-sm btn-success">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>

                    <!-- สินค้า 3 -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="https://placehold.co/600x400?text=Hello+World" class="card-img-top"
                                alt="ชื่อสินค้า 3">
                            <div class="card-body">
                                <h5 class="card-title">แครอท</h5>
                                <p class="card-text text-muted">฿30 / กก.</p>
                                <a href="/product/3" class="btn btn-sm btn-success">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>

                    <!-- เพิ่มสินค้าอื่นๆ -->
                    <!-- คัดลอก col-... เพิ่มได้ตามจำนวนสินค้า -->

                </div>
            </div>

            <hr class="my-4">

            <!-- Section: สมัครสมาชิก -->
            <section class="py-5 text-white"
                style="background-color: #e60012; background-image: url('https://placehold.co/1700x225?text=Background'); background-size: cover;">
                <div class="container text-center">
                    <h4 class="fw-bold">สมัครสมาชิก ฟรี!</h4>
                    <p class="mb-4">ค้นหาข้อมูลและแนวโน้มราคาสินค้าทางการเกษตรย้อนหลังได้ง่ายๆ</p>
                    <a href="/register" class="btn btn-dark btn-lg shadow px-4 py-2">สมัครสมาชิก คลิกที่นี่</a>
                </div>
            </section>

            <!-- Section: จุดเด่นของตลาด -->
            <section class="py-5 bg-white text-center">
                <div class="container">
                    <h3 class="mb-5 fw-bold">ตลาดสี่มุมเมือง</h3>
                    <p class="mb-4 text-muted">ศูนย์กระจายสินค้าทางการเกษตรที่ใหญ่ที่สุดในประเทศไทย</p>

                    <div class="row g-4">

                        <!-- ข้อดี 1 -->
                        <div class="col-6 col-md-3">
                            <div class="text-danger mb-3">
                                <img src="https://placehold.co/100x100?text=Icon1" alt="icon"
                                    class="img-fluid rounded-circle bg-white p-2 shadow">
                            </div>
                            <p class="mb-0 fw-bold">ลูกค้าหมุนเวียนต่อเนื่อง</p>
                            <p class="text-muted">มากกว่า <strong>30,000</strong> คนต่อวัน</p>
                        </div>

                        <!-- ข้อดี 2 -->
                        <div class="col-6 col-md-3">
                            <div class="text-danger mb-3">
                                <img src="https://placehold.co/100x100?text=Icon2" alt="icon"
                                    class="img-fluid rounded-circle bg-white p-2 shadow">
                            </div>
                            <p class="mb-0 fw-bold">พื้นที่จอดรถสะดวก</p>
                            <p class="text-muted">มากกว่า <strong>4,000</strong> คัน</p>
                        </div>

                        <!-- ข้อดี 3 -->
                        <div class="col-6 col-md-3">
                            <div class="text-danger mb-3">
                                <img src="https://placehold.co/100x100?text=Icon3" alt="icon"
                                    class="img-fluid rounded-circle bg-white p-2 shadow">
                            </div>
                            <p class="mb-0 fw-bold">แรงงานขนถ่ายสินค้า</p>
                            <p class="text-muted">มากกว่า <strong>5,000</strong> คน</p>
                        </div>

                        <!-- ข้อดี 4 -->
                        <div class="col-6 col-md-3">
                            <div class="text-danger mb-3">
                                <img src="https://placehold.co/100x100?text=24h" alt="icon"
                                    class="img-fluid rounded-circle bg-white p-2 shadow">
                            </div>
                            <p class="mb-0 fw-bold">ทำการค้าได้ต่อเนื่อง</p>
                            <p class="text-muted">ตลอด <strong>24 ชั่วโมง</strong></p>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-dark text-light py-5">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-3 col-md-6 mb-4">
                            <h5 class="mb-4 border-start border-4 border-primary ps-3 fw-semibold">เกี่ยวกับบริษัท</h5>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-light text-decoration-none d-block mb-2">แนะนำตลาด</a></li>
                                <li><a href="#" class="text-light text-decoration-none d-block mb-2">บริการของเรา</a>
                                </li>
                                <li><a href="#"
                                        class="text-light text-decoration-none d-block mb-2">กิจกรรมเพื่อสังคม</a></li>
                                <li><a href="#"
                                        class="text-light text-decoration-none d-block mb-2">คณะกรรมการบริษัท</a></li>
                                <li><a href="#"
                                        class="text-light text-decoration-none d-block mb-2">โรงเรียนพัฒนาวิทยา</a></li>
                                <li><a href="#" class="text-light text-decoration-none d-block mb-2">คำถามที่พบบ่อย</a>
                                </li>
                                <li><a href="#"
                                        class="text-light text-decoration-none d-block mb-2">ร่วมธุรกิจกับเรา</a></li>
                                <li><a href="#" class="text-light text-decoration-none d-block mb-2">สมัครงาน</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <h5 class="mb-4 border-start border-4 border-primary ps-3 fw-semibold">ข่าวสาร</h5>
                            <ul class="list-unstyled">
                                <li><a href="#"
                                        class="text-light text-decoration-none d-block mb-2">สื่อประชาสัมพันธ์</a></li>
                                <li><a href="#" class="text-light text-decoration-none d-block mb-2">วิดีโอ</a></li>
                            </ul>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <h5 class="mb-4 border-start border-4 border-primary ps-3 fw-semibold">ติดตามเราได้ที่</h5>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-3">
                                    <a href="#" class="text-light me-3 fs-5"><i class="fab fa-facebook-f"></i></a>
                                    <span>ตลาดสี่มุมเมือง</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <a href="#" class="text-light me-3 fs-5"><i class="fab fa-instagram"></i></a>
                                    <span>@smm_market</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <a href="#" class="text-light me-3 fs-5"><i class="fab fa-tiktok"></i></a>
                                    <span>@smm_market</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <a href="#" class="text-light me-3 fs-5"><i class="fab fa-youtube"></i></a>
                                    <span>Simummuang Market</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <h5 class="mb-4 border-start border-4 border-primary ps-3 fw-semibold">ติดต่อเรา</h5>
                            <p class="mb-1" style="line-height: 1.7;">
                                <strong>โทรศัพท์ :</strong> 02-995-0610-3 (24 ชม.)<br>
                                <strong>ที่อยู่ :</strong> 355/115-116 ม.15 ถ.พหลโยธิน ต.คูคต อ.ลำลูกกา จ.ปทุมธานี
                                12130<br>
                                <strong>เวลาทำการ :</strong> 8:00 - 17:00 น.
                            </p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 text-center text-secondary small mt-4">
                            © 2024 Simummuang Market. All Rights Reserved.
                        </div>
                    </div>
                </div>
            </footer>

            <a href="#"
                class="btn btn-primary rounded-circle position-fixed d-flex justify-content-center align-items-center"
                style="width:60px; height:60px; bottom:25px; right:25px; z-index:1050; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                <i class="fas fa-comments fs-4"></i>
            </a>
            <!-- / Footer -->


        </div>
        <!-- / Layout page -->
    </div>
</div>
<!-- / Layout wrapper -->


<?php renderFooter($config); ?>