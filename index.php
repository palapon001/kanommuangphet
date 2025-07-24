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
$modelIndex = [
    'th' => [
        'navbar_brand_text' => 'ขนมเมืองเพชร',
        'search_placeholder_text' => 'ค้นหาสินค้า...',
        'search_text' => 'ค้นหา',
        'login_button' => 'เข้าสู่ระบบ'
    ],
    'en' => [
        'navbar_brand_text' => 'Kanom Muang Phet',
        'search_placeholder_text' => 'Search products...',
        'search_text' => 'Search',
        'login_button' => 'Login'
    ]
];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top shadow-sm">
    <div class="container d-flex flex-column">

        <!-- Top Row: Logo + Search + Login + Language -->
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <a class="navbar-brand fw-bold fs-4" href="#"><?= $modelIndex[$lang]['navbar_brand_text'] ?></a>

            <form class="d-flex flex-grow-1 mx-3" role="search" style="max-width: 400px;">
                <input class="form-control me-2" type="search" placeholder="<?= $modelIndex[$lang]['search_placeholder_text'] ?>" aria-label="Search">
                <button class="btn btn-outline-primary" type="submit"><?= $modelIndex[$lang]['search_text'] ?></button>
            </form>

            <div class="d-flex align-items-center">
                <a href="#" id="loginButton" class="btn btn-outline-secondary me-3"><?= $modelIndex[$lang]['login_button'] ?></a>

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
             <?php
$menuBottom = [
    'th' => [
        "ขนมไทยโบราณ" => ["ฝอยทอง", "ทองหยิบ", "ขนมชั้น", "ขนมตาล"],
        "ขนมไทยหวาน" => ["ข้าวเหนียวมูน", "กล้วยบวชชี", "บัวลอย"],
        "ขนมไทยทอด" => ["ทองม้วน", "ช่อม่วง", "ขนมไข่ปลา"],
        "ขนมไทยนึ่ง" => ["ขนมถ้วย", "ขนมฟักทอง", "ขนมต้ม"],
        "ขนมไทยอบ" => ["ขนมหม้อแกง", "ขนมเปียกปูน"],
        "ขนมไทยแบบใหม่" => ["ขนมปังสังขยา", "ขนมเค้กไทย"],
        "เครื่องดื่มไทย" => ["น้ำกระเจี๊ยบ", "น้ำเก๊กฮวย"],
        "วัตถุดิบขนมไทย" => ["แป้ง", "กะทิ", "น้ำตาลปี๊บ"],
        "โปรโมชั่น" => []
    ],
    'en' => [
        "Ancient Thai Desserts" => ["Foi Thong", "Thong Yip", "Khanom Chan", "Khanom Tan"],
        "Sweet Thai Desserts" => ["Sticky Rice with Coconut Milk", "Banana in Coconut Milk", "Bua Loi"],
        "Fried Thai Desserts" => ["Thong Muan", "Chor Muang", "Khanom Khai Pla"],
        "Steamed Thai Desserts" => ["Khanom Thuai", "Pumpkin Dessert", "Khanom Tom"],
        "Baked Thai Desserts" => ["Khanom Mo Kaeng", "Piek Poon"],
        "Modern Thai Desserts" => ["Custard Bread", "Thai Cake"],
        "Thai Drinks" => ["Roselle Juice", "Chrysanthemum Tea"],
        "Thai Dessert Ingredients" => ["Flour", "Coconut Milk", "Palm Sugar"],
        "Promotions" => []
    ]
];
?>


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
                <img src="https://www.skyweaver.net/images/media/wallpapers/wallpaper1.jpg" class="d-block w-100"
                    alt="Slide 1" style="object-fit:cover; height:450px;">
            </div>
            <div class="carousel-item">
                <img src="https://placehold.co/1200x500?text=hello2" class="d-block w-100" alt="Slide 2"
                    style="object-fit:cover; height:450px;">
            </div>
            <div class="carousel-item">
                <img src="https://placehold.co/1200x500?text=hello3" class="d-block w-100" alt="Slide 3"
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

<?php
// ข้อมูลสินค้า
$productsHit = [
    ["title" => "ชื่อสินค้า 1", "price" => "ลดเหลือ 99 บาท", "img" => "https://placehold.co/1200x500?text=item1"],
    ["title" => "ชื่อสินค้า 2", "price" => "ลดเหลือ 150 บาท", "img" => "https://placehold.co/1200x500?text=item2"],
    ["title" => "ชื่อสินค้า 3", "price" => "ลดเหลือ 120 บาท", "img" => "https://placehold.co/1200x500?text=item3"],
    ["title" => "ชื่อสินค้า 4", "price" => "ลดเหลือ 200 บาท", "img" => "https://placehold.co/1200x500?text=item4"],
    ["title" => "ชื่อสินค้า 5", "price" => "ลดเหลือ 250 บาท", "img" => "https://placehold.co/1200x500?text=item5"],
    ["title" => "ชื่อสินค้า 6", "price" => "ลดเหลือ 300 บาท", "img" => "https://placehold.co/1200x500?text=item6"],
    ["title" => "ชื่อสินค้า 7", "price" => "ลดเหลือ 180 บาท", "img" => "https://placehold.co/1200x500?text=item7"],
    ["title" => "ชื่อสินค้า 8", "price" => "ลดเหลือ 220 บาท", "img" => "https://placehold.co/1200x500?text=item8"],
    ["title" => "ชื่อสินค้า 9", "price" => "ลดเหลือ 140 บาท", "img" => "https://placehold.co/1200x500?text=item9"],
    ["title" => "ชื่อสินค้า 10", "price" => "ลดเหลือ 160 บาท", "img" => "https://placehold.co/1200x500?text=item10"],
];

$productsSale = [
    ["title" => "ชื่อสินค้า A", "price" => "ลดเหลือ 199 บาท", "img" => "https://placehold.co/1200x500?text=itemA"],
    ["title" => "ชื่อสินค้า B", "price" => "ลดเหลือ 250 บาท", "img" => "https://placehold.co/1200x500?text=itemB"],
    ["title" => "ชื่อสินค้า C", "price" => "ลดเหลือ 220 บาท", "img" => "https://placehold.co/1200x500?text=itemC"],
    ["title" => "ชื่อสินค้า D", "price" => "ลดเหลือ 300 บาท", "img" => "https://placehold.co/1200x500?text=itemD"],
    ["title" => "ชื่อสินค้า E", "price" => "ลดเหลือ 350 บาท", "img" => "https://placehold.co/1200x500?text=itemE"],
    ["title" => "ชื่อสินค้า F", "price" => "ลดเหลือ 180 บาท", "img" => "https://placehold.co/1200x500?text=itemF"],
    ["title" => "ชื่อสินค้า G", "price" => "ลดเหลือ 220 บาท", "img" => "https://placehold.co/1200x500?text=itemG"],
    ["title" => "ชื่อสินค้า H", "price" => "ลดเหลือ 260 บาท", "img" => "https://placehold.co/1200x500?text=itemH"],
    ["title" => "ชื่อสินค้า I", "price" => "ลดเหลือ 240 บาท", "img" => "https://placehold.co/1200x500?text=itemI"],
    ["title" => "ชื่อสินค้า J", "price" => "ลดเหลือ 280 บาท", "img" => "https://placehold.co/1200x500?text=itemJ"],
];

// ฟังก์ชันแบ่ง array เป็นกลุ่มละ $size
function chunkArray($array, $size)
{
    $chunks = [];
    for ($i = 0; $i < count($array); $i += $size) {
        $chunks[] = array_slice($array, $i, $size);
    }
    return $chunks;
}

// แบ่งกลุ่มสินค้า 5 ชิ้นต่อ slide
$productChunksHit = chunkArray($productsHit, 5);
$productChunksSale = chunkArray($productsSale, 5);

// ฟังก์ชันแสดง carousel
function renderCarousel($id, $productChunks)
{
    ?>
    <div id="<?= htmlspecialchars($id) ?>" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($productChunks as $index => $chunk): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="d-flex justify-content-start gap-3">
                        <?php foreach ($chunk as $product): ?>
                            <div class="card border-success" style="min-width: 250px;">
                                <img src="<?= htmlspecialchars($product['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['title']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><?= htmlspecialchars($product['title']) ?></h5>
                                    <p class="card-text">ราคาพิเศษ! <?= htmlspecialchars($product['price']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev custom" type="button" data-bs-target="#<?= htmlspecialchars($id) ?>" data-bs-slide="prev" style="left: -2%;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">ก่อนหน้า</span>
        </button>
        <button class="carousel-control-next custom" type="button" data-bs-target="#<?= htmlspecialchars($id) ?>" data-bs-slide="next" style="right: -2%;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">ถัดไป</span>
        </button>
    </div>
    <?php
}
?>

<section id="popularProductSale" class="container mt-5">
    <h3 class="mb-4">สินค้าขายดี</h3>
    <?php renderCarousel('productCarouselHit', $productChunksHit); ?>

    <h3 class="mb-4 mt-5">สินค้าราคาพิเศษ</h3>
    <?php renderCarousel('productCarouselSale', $productChunksSale); ?>
</section>

<style>
    .carousel-control-prev.custom, .carousel-control-next.custom { 
        background: #685f5f;
        width: 4%;
        height: 25%;
        margin: auto;
        border-radius: 20px;
    }
</style>

<?php
$blogs = [
    [
        "title" => "เคล็ดลับการเลือกวัตถุดิบสดใหม่",
        "desc" => "เรียนรู้วิธีเลือกวัตถุดิบสดใหม่สำหรับทำขนมไทยอย่างมือโปร พร้อมเทคนิคเก็บรักษาคุณภาพให้คงทน",
        "img" => "https://placehold.co/400x250?text=Blog+Image+1",
        "link" => "#"
    ],
    [
        "title" => "สูตรขนมไทยยอดนิยมประจำปี 2025",
        "desc" => "รวมสูตรขนมไทยยอดนิยมที่ขายดีในตลาด พร้อมขั้นตอนทำง่ายๆ สำหรับผู้เริ่มต้น",
        "img" => "https://placehold.co/400x250?text=Blog+Image+2",
        "link" => "#"
    ],
    [
        "title" => "วิธีเก็บรักษาวัตถุดิบให้สดได้นานขึ้น",
        "desc" => "เทคนิคและวิธีการเก็บรักษาวัตถุดิบสำหรับร้านขนม เพื่อความสดใหม่และประหยัดต้นทุน",
        "img" => "https://placehold.co/400x250?text=Blog+Image+3",
        "link" => "#"
    ],
    [
        "title" => "เทรนด์ขนมไทยปี 2025",
        "desc" => "ติดตามเทรนด์และไอเดียใหม่ๆ ในวงการขนมไทย ที่กำลังมาแรงและสร้างรายได้ดี",
        "img" => "https://placehold.co/400x250?text=Blog+Image+4",
        "link" => "#"
    ],
];

// โฆษณา (ads) mockup
$ads = [
    [
        "img" => "https://placehold.co/1200x200?text=Ad+Banner+1",
        "link" => "#"
    ],
    [
        "img" => "https://placehold.co/1200x200?text=Ad+Banner+2",
        "link" => "#"
    ],
];
?>

<section id="blog" class="container my-5">
  <h2 class="mb-4 text-center">บทความและข่าวสาร</h2>

  <div class="row gy-4">
    <?php foreach ($blogs as $blog): ?>
      <div class="col-md-6 d-flex">
        <img src="<?= htmlspecialchars($blog['img']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="img-fluid rounded me-3" style="width: 40%;">
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

<?php
$footerSections = [
    [
        "title" => "Kanom Muang Phet",
        "content" => [
            "text" => "เว็บไซต์ขนมไทยแท้ๆ สั่งง่าย แค่ปลายนิ้ว",
            "contacts" => [
                "โทร" => "02 023 9903 บริการ 24 ชั่วโมง",
                "Line Official" => "@kanommuangphet",
                "เวลาทำการ" => "07:00 น. - 16:00 น"
            ]
        ]
    ],
    [
        "title" => "นโยบายเว็บไซต์",
        "links" => [
            ["text" => "เกี่ยวกับเรา", "url" => "#"],
            ["text" => "เงื่อนไขการให้บริการ", "url" => "#", "target" => "_blank"],
            ["text" => "นโยบายความเป็นส่วนตัว", "url" => "#", "target" => "_blank"],
            ["text" => "คำถามที่พบบ่อย", "url" => "#", "target" => "_blank"]
        ]
    ],
    [
        "title" => "ร่วมเป็นส่วนหนึ่งกับเรา",
        "links" => [
            ["text" => "วิธีสมัครสมาชิก", "url" => "#"],
            ["text" => "วิธีการสร้างสินค้า", "url" => "#", "target" => "_blank"],
            ["text" => "วิธีการใช้ระบบ", "url" => "#", "target" => "_blank"]
        ]
    ],
    // [
    //     "title" => "ดาวน์โหลดแอป Kanom Muang Phet",
    //     "images" => [
    //         ["src" => "/images/appstore.png", "alt" => "App Store"],
    //         ["src" => "/images/playstore.png", "alt" => "Google Play"]
    //     ]
    // ],
    [
        "title" => "ติดตามเรา",
        "socials" => [
        ["href" => "#", "icon" => "fab fa-facebook-f", "title" => "Facebook"],
        ["href" => "#", "icon" => "fab fa-instagram", "title" => "Instagram"],
        ["href" => "#", "icon" => "fab fa-youtube", "title" => "YouTube"],
        ["href" => "#", "icon" => "fab fa-tiktok", "title" => "TikTok"],
        ["href" => "#", "icon" => "fab fa-line", "title" => "Line"]
    ]
    ]
];
?>

 

<footer id="footer">
     <div class="dropdown-divider"></div>
    <div class="pt-5">
        <div class="container">
            <div class="row">

                <?php foreach ($footerSections as $section): ?>
                    <div class="col-sm-12 col-md-6 col-lg-3 footer-list mb-5">
                        <h3 class="footer-subtitle"><span style="font-size: 18px;"><?= htmlspecialchars($section['title']) ?></span></h3>

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
                                        <a href="<?= htmlspecialchars($link['url']) ?>"
                                           <?= isset($link['target']) ? 'target="'.htmlspecialchars($link['target']).'" rel="noopener noreferrer"' : '' ?>>
                                            <?= htmlspecialchars($link['text']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (isset($section['images'])): ?>
                            <div class="d-flex">
                                <?php foreach ($section['images'] as $img): ?>
                                    <img src="<?= htmlspecialchars($img['src']) ?>" alt="<?= htmlspecialchars($img['alt']) ?>" class="mr-1">
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
                    © 2025 Kanom Muang Phet - เว็บไซต์เนื้อหาเกี่ยวกับขนมไทยโดยเฉพาะ
                </div>
            </div>
        </div>
    </div>
</footer>


<?php renderFooter($config); ?>