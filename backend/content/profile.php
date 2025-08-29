<?php
$profile = $model['profile'][0];
// สร้าง array ของฟิลด์ที่ต้องการแสดงใน form
$fields = [
    ['label' => 'ID', 'name' => 'id', 'type' => 'hidden'],
    ['label' => 'Avatar', 'name' => 'avatar_url', 'type' => 'hidden'],
    ['label' => 'Full Name', 'name' => 'name', 'type' => 'text'],
    ['label' => 'Email', 'name' => 'email', 'type' => 'email'],
    ['label' => 'Phone', 'name' => 'phone', 'type' => 'text'],
    ['label' => 'Password', 'name' => 'password', 'type' => 'password'],
];
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">จัดการโปรไฟล์/</span> โปรไฟล์</h4>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> โปรไฟล์</a>
                </li>
            </ul>
            <div class="card mb-4">
                <h5 class="card-header">รายละเอียดโปรไฟล์</h5>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="<?= htmlspecialchars($config['url'] . '/' . $profile['avatar_url']) ?>"
                            alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar"
                            onerror="this.onerror=null;this.src='<?= $config['url'] ?>/assets/img/avatars/1.png';" />
                        <div class="button-wrapper">
                            <?php
                            $uploadDir = '../uploads/' . $profile['role'] . '/' . $profile['id'] . '_' . $profile['name'] . '/';
                            $imgData = ['id' => $profile['id'], 'name' => 'avatar_url', 'currentImage' => '', 'uploadPath' => $uploadDir];
                            renderImageUpload(
                                $config['url'] . '/process/user_process.php?act=upload&to=profile',
                                $imgData['id'],
                                $imgData['name'],
                                $imgData['currentImage'],
                                $imgData['uploadPath']
                            );
                            ?>
                            <form id="deleteImageForm" method="POST"
                                action="<?= $config['url'] ?>/process/user_process.php?act=update&to=profile"
                                style="display: none;">
                                <input type="hidden" name="id" value="<?= $profile['id'] ?>" />
                                <input type="hidden" name="name" value="<?= $profile['name'] ?>" />
                                <input type="hidden" name="avatar_url" value="" />
                                <!-- ตั้งค่าเป็นว่างเพื่อให้ถือว่าลบ -->
                            </form>

                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4"
                                onclick="document.getElementById('deleteImageForm').submit();">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">ลบภาพ</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <form id="formAccountSettings" method="POST"
                        action="<?= $config['url'] ?>/process/user_process.php?act=update&to=profile">
                        <div class="row">
                            <?php foreach ($fields as $field):
                                ?>
                                <div class="mb-3 col-md-6"
                                    style="display: <?= $field['type'] == 'hidden' ? 'none' : 'block' ?>">
                                    <label for="<?= $field['name'] ?>" class="form-label"><?= $field['label'] ?></label>
                                    <input type="<?= $field['type'] ?>" class="form-control" id="<?= $field['name'] ?>"
                                        name="<?= $field['name'] ?>"
                                        value="<?= htmlspecialchars($profile[$field['name']]) ?>" />
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
                                Cancel
                            </button>

                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>

            <!-- <div class="card">
                <h5 class="card-header">Delete Account</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation"
                                id="accountActivation" />
                            <label class="form-check-label" for="accountActivation">I confirm my account
                                deactivation</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                    </form>
                </div>
            </div> -->
        </div>
    </div>
</div>