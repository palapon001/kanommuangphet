<?php
// กำหนดค่าเริ่มต้นสำหรับการแสดงผล
require_once __DIR__ . '/src/header_footer.php';
require_once __DIR__ . '/src/connect.php';
require_once __DIR__ . '/src/function.php';
$lang = $_GET['lang'] ?? 'th';
$config = [
    'title' => $modelIndex[$lang]['navbar_brand_text'],
    'description' => $modelIndex[$lang]['description']
];
renderHead($config, 'auth');
include 'src/model.php';

$shop = ltrim($_GET['shop'], '0');
if (isset($shop)) {
    // ดึงข้อมูลจากฐานข้อมูล
    $model = [
        'shops' => dbSelect(
            'shops',
            'id LIKE :q1',
            [':q1' => "%$shop%"],
        ),
        // 'products' => dbSelect('products'),
        // 'ingredients' => dbSelect('ingredients'),
    ];

    // $modelIndex[$lang]['navbar_brand_text']

} else {
    // ดึงข้อมูลจากฐานข้อมูล
    $model = [
        'shops' => dbSelect('shops'),
        'products' => dbSelect('products'),
        'ingredients' => dbSelect('ingredients'),
    ];
}




if ($_GET['debug'] == 'dev' || $_SESSION['user_role'] == 'admin') { ?>
    <div class="mb-3">
        <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#debugInfo"
            aria-expanded="false" aria-controls="debugInfo">
            แสดงข้อมูล Debug
        </button>
        <div class="collapse mt-2" id="debugInfo">
            <div class="card card-body bg-dark text-success"
                style="max-height: 400px; overflow: auto; font-family: monospace;">
                <pre style="margin:0;"><?php print_r($_SESSION);
                print_r($model); ?></pre>
            </div>
        </div>
    </div>
<?php } ?>

<?php
if ($_GET['debug'] == 'unset') {
    session_unset();
}

if (isset($model['products'])) {
    $productsHit = [];
    $productsSale = [];
    foreach ($model['products'] as $product) {
        $productsHit[] = [
            "title" => $product['name'],
            "price" => "ลดเหลือ " . number_format($product['price'], 0) . " บาท",
            "img" => $product['image'] ?: "images/products/default.jpg", // หากไม่มีภาพ ให้ใช้ default
        ];
        $productsSale[] = [
            "title" => $product['name'],
            "price" => "ลดเหลือ " . number_format($product['price'], 0) . " บาท",
            "img" => $product['image'] ?: "images/products/default.jpg", // หากไม่มีภาพ ให้ใช้ default
        ];
    }
}

// แบ่งกลุ่มสินค้า 5 ชิ้นต่อ slide
$productChunksHit = chunkArray($productsHit, 5);
$productChunksSale = chunkArray($productsSale, 5);
?>


<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top shadow-sm">
    <div class="container d-flex flex-column">

        <!-- Top Row: Logo + Search + Login + Language -->
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <a class="navbar-brand fw-bold fs-4" href="#"><?= $modelIndex[$lang]['navbar_brand_text'] ?></a>

            <form class="d-flex flex-grow-1 mx-3" role="search" style="max-width: 400px;">
                <input class="form-control me-2" type="search"
                    placeholder="<?= $modelIndex[$lang]['search_placeholder_text'] ?>" aria-label="Search">
                <button class="btn btn-outline-primary" type="submit"><?= $modelIndex[$lang]['search_text'] ?></button>
            </form>

            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_name'])) { ?>
                    <a href="#" id="loginButton"
                        class="btn btn-outline-primary me-3"><?= htmlspecialchars($_SESSION['user_name']); ?></a>
                    <button type="button" id="logoutButton"
                        class="btn btn-outline-danger me-3"><?= $modelIndex[$lang]['logout_button'] ?? 'Logout' ?></button>
                    <script>
                        document.getElementById('logoutButton').addEventListener('click', function (e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'ออกจากระบบ?',
                                text: "คุณต้องการออกจากระบบหรือไม่",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ตกลง',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'logout.php';
                                }
                            });
                        });
                    </script>
                <?php } else { ?>
                    <a href="login.php" id="loginButton"
                        class="btn btn-outline-secondary me-3"><?= $modelIndex[$lang]['login_button'] ?></a>
                <?php } ?>
                <div class="btn-group">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?= strtoupper($lang) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?lang=th">TH</a></li>
                        <li><a class="dropdown-item" href="?lang=en">EN</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Menu Items -->
        <div class="w-100">
            <ul class="navbar-nav d-flex flex-row flex-wrap justify-content-start">
                <div class="w-100">
                    <ul class="navbar-nav d-flex flex-row flex-wrap justify-content-start">
                        <?php foreach ($menuBottom[$lang] as $item => $submenus): ?>
                            <?php if (!empty($submenus)): ?>
                                <li class="nav-item dropdown mx-2">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdown<?= md5($item) ?>" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= $item ?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdown<?= md5($item) ?>">
                                        <?php foreach ($submenus as $submenu): ?>
                                            <li><a class="dropdown-item" href="#"><?= $submenu ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item mx-2">
                                    <a class="nav-link" href="#"><?= $item ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </ul>
        </div>
    </div>
</nav>

<!-- SlideShow -->
<section id="slideShow">
    <div id="carouselExampleIndicators" class="carousel slide container mt-4" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner rounded shadow">
            <div class="carousel-item active">
                <img src="./assets/img/banner/b1.jpg" class="d-block w-100" alt="Slide 1"
                    style="object-fit:cover; height:450px;">
            </div>
            <div class="carousel-item">
                <img src="./assets/img/banner/b2.jpg" class="d-block w-100" alt="Slide 2"
                    style="object-fit:cover; height:450px;">
            </div>
            <div class="carousel-item">
                <img src="./assets/img/banner/b3.jpg" class="d-block w-100" alt="Slide 3"
                    style="object-fit:cover; height:450px;">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">ก่อนหน้า</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">ถัดไป</span>
        </button>
    </div>
</section>

<section id="popularProductSale" class="container mt-5">
    <h3 class="mb-4"><?= $modelIndex[$lang]['product_hit_text'] ?></h3>
    <?php renderCarousel('productCarouselHit', $productChunksHit); ?>

    <h3 class="mb-4 mt-5"><?= $modelIndex[$lang]['product_sale_text'] ?></h3>
    <?php renderCarousel('productCarouselSale', $productChunksSale); ?>
</section>

<style>
    .carousel-control-prev.custom,
    .carousel-control-next.custom {
        background: #685f5f;
        width: 4%;
        height: 25%;
        margin: auto;
        border-radius: 20px;
    }
</style>

<section id="blog" class="container my-5">
    <h2 class="mb-4 text-center"><?= $modelIndex[$lang]['blog_text'] ?></h2>

    <div class="row gy-4">
        <?php foreach ($blogs as $blog): ?>
            <div class="col-md-6 d-flex">
                <img src="<?= htmlspecialchars($blog['img']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>"
                    class="img-fluid rounded me-3" style="width: 40%;">
                <div>
                    <h4><?= htmlspecialchars($blog['title']) ?></h4>
                    <p><?= htmlspecialchars($blog['desc']) ?></p>
                    <a href="<?= htmlspecialchars($blog['link']) ?>" class="btn btn-primary btn-sm">อ่านต่อ</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="ads" class="container my-5">
    <?php foreach ($ads as $ad): ?>
        <a href="<?= htmlspecialchars($ad['link']) ?>" target="_blank" rel="noopener" class="d-block mb-4">
            <img src="<?= htmlspecialchars($ad['img']) ?>" alt="โฆษณา" class="img-fluid rounded" width="100%">
        </a>
    <?php endforeach; ?>
</section>

<footer id="footer">
    <div class="dropdown-divider"></div>
    <div class="pt-5">
        <div class="container">
            <div class="row">

                <?php foreach ($footerSections[$lang] as $section): ?>
                    <div class="col-sm-12 col-md-6 col-lg-3 footer-list mb-5">
                        <h3 class="footer-subtitle"><span
                                style="font-size: 18px;"><?= htmlspecialchars($section['title']) ?></span></h3>

                        <?php if (isset($section['content'])): ?>
                            <p class="small"><?= htmlspecialchars($section['content']['text']) ?></p>
                            <?php if (isset($section['content']['contacts'])): ?>
                                <p class="small">
                                    <?php foreach ($section['content']['contacts'] as $key => $val): ?>
                                        <?= htmlspecialchars($key) ?> : <?= nl2br(htmlspecialchars($val)) ?><br>
                                    <?php endforeach; ?>
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (isset($section['links'])): ?>
                            <ul class="small">
                                <?php foreach ($section['links'] as $link): ?>
                                    <li>
                                        <a href="<?= htmlspecialchars($link['url']) ?>" <?= isset($link['target']) ? 'target="' . htmlspecialchars($link['target']) . '" rel="noopener noreferrer"' : '' ?>>
                                            <?= htmlspecialchars($link['text']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (isset($section['images'])): ?>
                            <div class="d-flex">
                                <?php foreach ($section['images'] as $img): ?>
                                    <img src="<?= htmlspecialchars($img['src']) ?>" alt="<?= htmlspecialchars($img['alt']) ?>"
                                        class="mr-1">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($section['socials'])): ?>
                            <div class="d-flex gap-3">
                                <?php foreach ($section['socials'] as $social): ?>
                                    <a href="<?= htmlspecialchars($social['href']) ?>" target="_blank" rel="noopener noreferrer"
                                        title="<?= htmlspecialchars($social['title']) ?>" class="text-decoration-none fs-4">
                                        <i class="<?= htmlspecialchars($social['icon']) ?>"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <div class="copyright py-3 text-center bg-light">
        <div class="container">
            <div class="row">
                <div class="col">
                    <?= $modelIndex[$lang]['copyright'] ?>
                </div>
            </div>
        </div>
    </div>
</footer>


<?php renderFooter($config); ?>