<?php
$profile = [
    'name' => 'admin',
    'role' => 'admin',
    'imagePath' => 'uploads/profile/default.png'
];
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <form action="index.php" method="get">
                    <input type="text" name="q" class="form-control border-0 shadow-none" placeholder="ค้นหา..."
                        aria-label="Search..." />
                </form>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?= $config['url'] . $profile['imagePath'] ?>" alt="profile"
                            class="w-px-40 h-auto rounded-circle"
                            onerror="this.onerror=null; this.src='https://placehold.co/40?text=Profile';" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="?page=profile">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?= $config['url'] . $profile['imagePath'] ?>" alt="profile"
                                            class="w-px-40 h-auto rounded-circle"
                                            onerror="this.onerror=null; this.src='https://placehold.co/40?text=Profile';" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?= $profile['name'] ?></span>
                                    <small class="text-muted"><?= $profile['role'] ?></small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?page=profile">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">จัดการโปรไฟล์</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <button class="dropdown-item" href="#" onclick="alertConfirm('คุณต้องการออกจากระบบหรือไม่?', '?page=logout')">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">ออกจากระบบ</span>
                        </button>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

