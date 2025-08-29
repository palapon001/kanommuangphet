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
                <div>หน้าหลัก</div>
            </a>
        </li>

        <!-- Dashboard -->
        <li class="menu-item <?= ($page == '' || $page == 'dashboard') ? 'active' : '' ?>">
            <a href="?page=dashboard"
                class="menu-link <?= ($page == '' || $page == 'dashboard') ? 'active' : '' ?>">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div>แดชบอร์ดตลาดกลางวัตถุดิบ</div>
            </a>
        </li>

        <?php if ($_SESSION['user_role'] == 'admin' ||  $_SESSION['user_role'] == 'dessert') { ?>

            <li class="menu-item <?= ($page == 'myRecipe') ? 'active' : '' ?>">
                <a href="?page=myRecipe"
                    class="menu-link <?= ($page == 'myRecipe') ? 'active' : '' ?>">
                    <i class="menu-icon tf-icons bx bx-book"></i>
                    <div>ขนมและสูตร</div>
                </a>
            </li>

            <li class="menu-item <?= ($page == 'productionList') ? 'active' : '' ?>">
                <a href="?page=productionList"
                    class="menu-link <?= ($page == 'productionList') ? 'active' : '' ?>">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div>รายการผลิต</div>
                </a>
            </li>

            <li class="menu-item <?= ($page == 'dessertBooking') ? 'active' : '' ?>">
                <a href="?page=dessertBooking"
                    class="menu-link <?= ($page == 'dessertBooking') ? 'active' : '' ?>">
                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                    <div>รายการจองขนม</div>
                </a>
            </li>

            <li class="menu-item <?= ($page == 'shopManagement') ? 'active' : '' ?>">
                <a href="?page=shopManagement"
                    class="menu-link <?= ($page == 'shopManagement') ? 'active' : '' ?>">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div>การจัดการหน้าร้าน</div>
                </a>
            </li>
        <?php } ?>



        <?php if ($_SESSION['user_role'] == 'admin' ||  $_SESSION['user_role'] == 'ingredient') { ?>
            <li class="menu-item <?= ($page == 'myItem') ? 'active' : '' ?>">
                <a href="?page=myItem"
                    class="menu-link <?= ($page == 'myItem') ? 'active' : '' ?>">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>ข้อมูลวัตถุดิบ</div>
                </a>
            </li>

            <li class="menu-item <?= ($page == 'pinItem') ? 'active' : '' ?>">
                <a href="?page=pinItem"
                    class="menu-link <?= ($page == 'pinItem') ? 'active' : '' ?>">
                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                    <div>รายการจองวัตถุดิบ</div>
                </a>
            </li>

        <?php } ?>

        <li class="menu-item <?= ($page == 'profile') ? 'active' : '' ?>">
            <a href="?page=profile"
                class="menu-link <?= ($page == 'profile') ? 'active' : '' ?>">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div>โปรไฟล์</div>
            </a>
        </li>

        <?php if ($_SESSION['user_role'] == 'admin') { ?>

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
        <?php } ?>

</aside>
<!-- / Menu -->