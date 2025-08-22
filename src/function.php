<?php
function dbSelectSQL($sql, $params = [], $limit = null)
{
    $pdo = getPDOConnection();
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create
function dbInsert($table, $data)
{
    $pdo = getPDOConnection();
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
    return $stmt->execute($data);
}

// Read (WHERE + optional LIMIT)
function dbSelect($table, $where = "", $params = [], $limit = null, $columns = '*')
{
    $pdo = getPDOConnection();
    // ตรวจสอบชื่อ table และ columns ถ้าจำเป็น
    $sql = "SELECT $columns FROM $table";
    if ($where) {
        $sql .= " WHERE $where";
    }
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update
function dbUpdate($table, $data, $where, $params = [])
{
    $pdo = getPDOConnection();
    $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
    $stmt = $pdo->prepare("UPDATE $table SET $set WHERE $where");
    return $stmt->execute(array_merge($data, $params));
}

// Delete
function dbDelete($table, $where, $params = [])
{
    $pdo = getPDOConnection();
    $stmt = $pdo->prepare("DELETE FROM $table WHERE $where");
    return $stmt->execute($params);
}

function redirectWithAlert($alert = 'info', $text = '', $page = 'dashboard')
{
    $url = '../backend/index.php?page=' . urlencode($page) . '&alert=' . urlencode($alert);

    if ($text !== '') {
        $url .= '&text=' . urlencode($text);
    }

    header('Location: ' . $url);
    exit;
}

function renderTable($data, $cols = null, $url = '', $config = [])
{

    $path = '../process/' . $url;

    if (empty($cols) && !empty($data)) {
        $cols = array_combine(array_keys($data[0]), array_keys($data[0]));
    }
    ?>
    <!-- ปุ่มเพิ่มข้อมูล -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dataModal" onclick="openForm('insert')">
            + เพิ่มข้อมูล
        </button>
    </div>

    <!-- ตารางข้อมูล -->
    <table id="dynamicTable" class="display table table-bordered" style="width:100%">
        <thead>
            <tr>
                <?php foreach ($cols as $key => $label): ?>
                    <th><?= htmlspecialchars($label) ?></th>
                <?php endforeach; ?>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $dataKey => $row): ?>
                <tr data-id="<?= $row['id'] ?>" <?php foreach ($cols as $key => $label)
                      echo "data-$key='" . htmlspecialchars($row[$key]) . "' "; ?>>
                    <?php foreach ($cols as $key => $label): ?>
                        <td>
                            <?php if ($key === 'avatar_url'): ?>
                                <?php if (!empty($row[$key])): ?>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#avatarModal" onclick="setModalImage('<?= htmlspecialchars($row[$key]) ?>')">
                                        ดูภาพ
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body text-center">
                                                    <img id="modalAvatar" src="" class="img-fluid rounded" alt="avatar">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= htmlspecialchars($row[$key] ?? '-') ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td id="colEdit">
                        <div class="btn-group" style="display:flex;gap:5px;">
                            <? if ($url == 'shop_process.php') { ?>
                                <a href="<?= $config['url'] ?>/index.php?shop=<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>"
                                    class="btn btn-sm btn-info btn-edit">
                                    ตัวอย่างหน้าเว็บ
                                </a>
                            <? } ?>
                            <button type="button" class="btn btn-sm btn-warning btn-edit" data-bs-toggle="modal"
                                data-bs-target="#modal-<?= $row['id'] ?>">
                                แก้ไข
                            </button>
                            <a href="<?= $path ?>?act=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">ลบ</a>
                        </div>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="modal-<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">แก้ไขข้อมูล <?= $row['name'] ?? $row['id'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <form method="post" action="<?= $path ?>?act=update" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                    <?php
                                    $imgData = [];
                                    foreach (array_keys($row) as $key): ?>
                                        <?php switch ($key):
                                            case 'role': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <select name="<?= $key ?>" class="form-select">
                                                        <option value="admin" <?= ($row[$key] ?? '') === 'admin' ? 'selected' : '' ?>>Admin
                                                        </option>
                                                        <option value="user" <?= ($row[$key] ?? '') === 'user' ? 'selected' : '' ?>>User
                                                        </option>
                                                        <option value="vendor" <?= ($row[$key] ?? '') === 'vendor' ? 'selected' : '' ?>>Vendor
                                                        </option>
                                                    </select>
                                                </div>
                                                <?php break;
                                            case 'login_type': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <select name="<?= $key ?>" class="form-select">
                                                        <option value="normal" <?= ($row[$key] ?? '') === 'normal' ? 'selected' : '' ?>>Normal
                                                        </option>
                                                        <option value="line" <?= ($row[$key] ?? '') === 'line' ? 'selected' : '' ?>>Line
                                                        </option>
                                                    </select>
                                                </div>
                                                <?php break;
                                            case 'avatar_url':
                                                $uploadDir = '../uploads/' . $row['role'] . '/' . $row['id'] . '_' . $row['name'] . '/';
                                                $imgData = ['id' => $row['id'], 'name' => $key, 'currentImage' => $row[$key] ?? '', 'uploadPath' => $uploadDir];
                                                break;
                                            case 'id': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control"
                                                        readonly>
                                                </div>
                                                <?php break;
                                            default: ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control">
                                                </div>
                                        <?php endswitch; ?>
                                    <?php endforeach; ?>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                    </div>
                                </form>
                                <div class="dropdown-divider"></div>
                                <?php
                                renderImageUpload($path, $imgData['id'], $imgData['name'], $imgData['currentImage'], $imgData['uploadPath']);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal เพิ่มข้อมูล -->
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="post" action="<?= $path ?>?act=insert" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dataModalLabel">เพิ่มข้อมูลใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($cols as $key => $label): ?>
                            <?php if ($key === 'id' || $key === 'avatar_url')
                                continue; ?>
                            <div class="mb-3">
                                <label class="form-label"><?= htmlspecialchars($label) ?></label>
                                <?php if ($key === 'role'): ?>
                                    <select name="<?= $key ?>" class="form-select">
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                        <option value="vendor">Vendor</option>
                                    </select>
                                <?php else: ?>
                                    <input type="text" name="<?= $key ?>" class="form-control">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">บันทึก</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

// ฟังก์ชันแบ่ง array เป็นกลุ่มละ $size
function chunkArray($array, $size)
{
    $chunks = [];
    for ($i = 0; $i < count($array); $i += $size) {
        $chunks[] = array_slice($array, $i, $size);
    }
    return $chunks;
}

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
                                <img src="<?= htmlspecialchars($product['img']) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($product['title']) ?>"
                                    onerror="this.onerror=null;this.src='./assets/img/items/placehold.jpg';">
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
        <button class="carousel-control-prev custom" type="button" data-bs-target="#<?= htmlspecialchars($id) ?>"
            data-bs-slide="prev" style="left: -3%;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">ก่อนหน้า</span>
        </button>
        <button class="carousel-control-next custom" type="button" data-bs-target="#<?= htmlspecialchars($id) ?>"
            data-bs-slide="next" style="right: -3%;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">ถัดไป</span>
        </button>
    </div>
    <?php
}
?>

<?
function uploadMultipleImages($inputName, $targetDir = 'uploads/', $baseFilename = 'image')
{
    $uploadedPaths = [];

    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Count total files
    $totalFiles = count($_FILES[$inputName]['name']);

    for ($i = 0; $i < $totalFiles; $i++) {
        if ($_FILES[$inputName]['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$inputName]['tmp_name'][$i];
            $originalName = $_FILES[$inputName]['name'][$i];

            // Get file extension
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Generate unique filename (e.g. profile-0001-1.jpg)
            $filename = $baseFilename . '-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '.' . $extension;
            $destination = rtrim($targetDir, '/') . '/' . $filename;

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedPaths[] = $destination;
            }
        }
    }

    return $uploadedPaths;
}
function renderImageUpload($url, $id, $name, $currentImage = '', $uploadPath = '')
{
    ?>
    <div class="mb-3">
        <label class="form-label"><?= htmlspecialchars($name) ?></label>
        <form class="imgForm" action="<?= $url ?>?act=upload" method="post" enctype="multipart/form-data">
            <!-- hidden field เก็บข้อมูล -->
            <input type="hidden" name="id" value="<?= (int) $id ?>">
            <input type="hidden" name="field_name" value="<?= htmlspecialchars($name) ?>">
            <input type="hidden" name="upload_path" value="<?= htmlspecialchars($uploadPath) ?>">

            <!-- input file -->
            <input type="file" name="upload" class="form-control" accept="image/*">

            <input type="submit" name="save" value="Upload" class="btn btn-primary mt-2">
        </form>

        <!-- Preview ถ้ามีรูป -->
        <?php if (!empty($currentImage) && file_exists($uploadPath . '/' . $currentImage)): ?>
            <div class="mt-2">
                <img src="<?= htmlspecialchars($uploadPath . '/' . $currentImage) ?>" alt="Preview"
                    style="max-height: 100px; border:1px solid #ccc; padding:3px;">
            </div>
        <?php endif; ?>
    </div>
    <?php
}

