<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= $config['url'] . $config['role'] ?>" class="app-brand-link">
            <img src="https://placehold.co/100x37?text=Logo" alt="">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item home">
            <a href="<?= $config['url'] ?>" target="_blank" class="menu-link"
                onclick="return confirm('หน้าหลัก fontend');">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">หน้าบ้าน</div>
            </a>
        </li>

        <!-- Dashboard -->
        <li class="menu-item <?= ($page == '' || $page == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= $config['url'] . $config['role'] ?>"
                class="menu-link <?= ($page == '' || $page == 'dashboard') ? 'active' : '' ?>">
                 <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Dashboard">แดชบอร์ด</div>
            </a>
        </li>

        <!-- Management -->
        <?php
        $menuItems = [
            'users' => 'จัดการผู้ใช้',
            'shops' => 'จัดการร้านค้า',
            'products' => 'จัดการขนม',
            'ingredients' => 'จัดการวัตถุดิบ',
            'orders' => 'ดูรายการคำสั่งซื้อ', 
            'stocks' => 'จัดการสต็อก',
            'promotions' => 'จัดการโปรโมชัน',
            'reviews' => 'จัดการรีวิว', 
        ];
        ?>

        <li class="menu-item <?= in_array($page, array_keys($menuItems)) ? 'active open' : '' ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Management">จัดการข้อมูล</div>
            </a>

            <ul class="menu-sub">
                <?php foreach ($menuItems as $key => $label): ?>
                    <li class="menu-item <?= $page == $key ? 'active' : '' ?>">
                        <a href="?page=<?= $key ?>" class="menu-link">
                            <div data-i18n="<?= ucfirst($key) ?>"><?= $label ?></div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
         

</aside>
<!-- / Menu -->