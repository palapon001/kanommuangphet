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

    if ($stmt->execute($data)) {
        return $pdo->lastInsertId(); // คืนค่า ID ของ row ที่เพิ่ง insert
    } else {
        return false; // insert ไม่สำเร็จ
    }
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

function renderTable($data, $cols = null, $url = '')
{

    global $config, $model;
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
                <tr data-id="<?= $row['id'] ?>"
                    <?php foreach ($cols as $key => $label)
                        echo "data-$key='" . htmlspecialchars($row[$key]) . "' "; ?>>
                    <?php foreach ($cols as $key => $label): ?>
                        <td>
                            <?php if ($key === 'avatar_url' || $key === 'profile_image' ||  $key === 'products_image' ||  $key === 'ingredients_image'): ?>
                                <?php if (!empty($row[$key])): ?>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#avatarModal" onclick="setModalImage('<?= htmlspecialchars($row[$key]) . '?v=' . $config['cacheVersion'] ?>')">
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
                            <?php if ($url == 'shop_process.php') { ?>
                                <a href="<?= $config['url'] ?>/index.php?shops=<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-info btn-edit">
                                    ตัวอย่างหน้าเว็บ
                                </a>
                            <?php } ?>
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
                    <div class="modal-dialog">
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

                                            case 'unit': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <select name="<?= $key ?>" class="form-select">
                                                        <option value="g" <?= ($row[$key] ?? '') === 'g' ? 'selected' : '' ?>>กรัม (g)</option>
                                                        <option value="kg" <?= ($row[$key] ?? '') === 'kg' ? 'selected' : '' ?>>กิโลกรัม (kg)</option>
                                                        <option value="ml" <?= ($row[$key] ?? '') === 'ml' ? 'selected' : '' ?>>มิลลิลิตร (ml)</option>
                                                        <option value="l" <?= ($row[$key] ?? '') === 'l' ? 'selected' : '' ?>>ลิตร (L)</option>
                                                        <option value="tsp" <?= ($row[$key] ?? '') === 'tsp' ? 'selected' : '' ?>>ช้อนชา (tsp)</option>
                                                        <option value="tbsp" <?= ($row[$key] ?? '') === 'tbsp' ? 'selected' : '' ?>>ช้อนโต๊ะ (tbsp)</option>
                                                        <option value="cup" <?= ($row[$key] ?? '') === 'cup' ? 'selected' : '' ?>>ถ้วย (cup)</option>
                                                        <option value="piece" <?= ($row[$key] ?? '') === 'piece' ? 'selected' : '' ?>>ชิ้น (piece)</option>
                                                        <option value="egg" <?= ($row[$key] ?? '') === 'egg' ? 'selected' : '' ?>>ฟอง</option>
                                                        <option value="leaf" <?= ($row[$key] ?? '') === 'leaf' ? 'selected' : '' ?>>ใบ</option>
                                                        <option value="flower" <?= ($row[$key] ?? '') === 'flower' ? 'selected' : '' ?>>ดอก</option>
                                                        <option value="clove" <?= ($row[$key] ?? '') === 'clove' ? 'selected' : '' ?>>กลีบ</option>
                                                        <option value="head" <?= ($row[$key] ?? '') === 'head' ? 'selected' : '' ?>>หัว</option>
                                                        <option value="cube" <?= ($row[$key] ?? '') === 'cube' ? 'selected' : '' ?>>ก้อน</option>
                                                        <option value="seed" <?= ($row[$key] ?? '') === 'seed' ? 'selected' : '' ?>>เม็ด</option>
                                                        <option value="rhizome" <?= ($row[$key] ?? '') === 'rhizome' ? 'selected' : '' ?>>แง่ง</option>
                                                        <option value="pod" <?= ($row[$key] ?? '') === 'pod' ? 'selected' : '' ?>>ฝัก</option>
                                                        <option value="string" <?= ($row[$key] ?? '') === 'string' ? 'selected' : '' ?>>เส้น</option>
                                                        <option value="stem" <?= ($row[$key] ?? '') === 'stem' ? 'selected' : '' ?>>ลำ</option>
                                                        <option value="slice" <?= ($row[$key] ?? '') === 'slice' ? 'selected' : '' ?>>ชิ้น/แผ่น</option>
                                                        <option value="fruit" <?= ($row[$key] ?? '') === 'fruit' ? 'selected' : '' ?>>ลูก/ผล</option>
                                                    </select>
                                                </div>
                                            <?php break;

                                            case 'owner_id': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= htmlspecialchars($label) ?>
                                                    </label>
                                                    <select name="<?= $key ?>" class="form-select">
                                                        <option value="<?= ($row[$key]) == '0' ? '0' : $row[$key] ?>" <?= ($row[$key]) == '0' ? 'disabled selected' : '' ?>>รายชื่อผู้ใช้</option>
                                                        <?php foreach ($model['users'] as $value) { ?>
                                                            <option value="<?= $value['id'] ?>" <?= ($row[$key]) == $value['id'] ? 'selected' : '' ?>><?= $value['id'] . ' : ' . $value['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php break;
                                            case 'bank_key': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= htmlspecialchars($label) ?>
                                                    </label>
                                                    <select name="<?= $key ?>" class="form-select">
                                                        <option value="<?= ($row[$key]) == '0' ? '0' : $row[$key] ?>" <?= ($row[$key]) == '0' ? 'disabled selected' : '' ?>>รายชื่อบัญชีธนาคาร</option>
                                                        <?php foreach ($model['banks'] as $k =>  $value) { ?>
                                                            <option value="<?= $k  ?>" <?= ($row[$key]) == $k ? 'selected' : '' ?>>
                                                                <?= $k . ' : ' . $value['fullname'] ?>
                                                            </option>
                                                        <?php } ?>
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

                                            case 'shop_id': ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= htmlspecialchars($label) ?>
                                                    </label>
                                                    <select name="<?= $key ?>" class="form-select">
                                                        <?php foreach ($model['shops'] as $k => $value) { ?>
                                                            <option value="<?= $value['id'] ?>" <?= ($row[$key]) == $value['id'] ? 'selected' : '' ?>>
                                                                <?= $value['id'] . ' : ' . $value['name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php break;

                                            case 'id':
                                            case 'created_at':
                                            ?>
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control"
                                                        readonly>
                                                </div>
                                            <?php break;

                                            case 'avatar_url':
                                                $uploadDir = '../uploads/' . $row['role'] . '/' . $row['id'] . '_' . $row['name'] . '/';
                                                $imgData = ['id' => $row['id'], 'name' => $key, 'currentImage' => $row[$key] ?? '', 'uploadPath' => $uploadDir];
                                                ?> 
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control"
                                                        readonly>
                                                </div>
                                                <?php break;

                                            case 'profile_image':
                                                $uploadDir = '../uploads/shops/' . $row['id'] . '_' . $row['name'] . '/';
                                                $imgData = ['id' => $row['id'], 'name' => $key, 'currentImage' => $row[$key] ?? '', 'uploadPath' => $uploadDir];
                                                 ?> 
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control"
                                                        readonly>
                                                </div>
                                                <?php
                                                break;
                                            case 'products_image':
                                                $uploadDir = '../uploads/products/' . $row['id'] . '_' . $row['shop_id'] . '_' . $row['name'] . '/';
                                                $imgData = ['id' => $row['id'], 'name' => $key, 'currentImage' => $row[$key] ?? '', 'uploadPath' => $uploadDir];
                                                ?> 
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control"
                                                        readonly>
                                                </div>
                                                <?php
                                                break;

                                            case 'ingredients_image':
                                                $uploadDir = '../uploads/ingredients/' . $row['id'] . '_' . $row['shop_id'] . '_' . $row['name'] . '/';
                                                $imgData = ['id' => $row['id'], 'name' => $key, 'currentImage' => $row[$key] ?? '', 'uploadPath' => $uploadDir];
                                                 ?> 
                                                <div class="mb-3">
                                                    <label class="form-label"><?= ($key) ?></label>
                                                    <input type="text" name="<?= $key ?>" value="<?= ($row[$key]) ?>" class="form-control"
                                                        readonly>
                                                </div>
                                                <?php
                                                break;

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
                                renderImageUpload(
                                    $path.'?act=upload',
                                    $imgData['id'],
                                    $imgData['name'],
                                    $imgData['currentImage'],
                                    $imgData['uploadPath']
                                );
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
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= $path ?>?act=insert" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dataModalLabel">เพิ่มข้อมูลใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($cols as $key => $label): ?>
                            <?php switch ($key):
                                case 'id':  
                                case 'created_at': ?>
                                    <?php break; ?> <!-- ไม่ต้องแสดง ID -->
                                <?php
                                case 'role': ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= htmlspecialchars($label) ?></label>
                                        <select name="<?= $key ?>" class="form-select">
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                            <option value="vendor">Vendor</option>
                                        </select>
                                    </div>
                                    <?php break; ?>
                                <?php
                                case 'owner_id': ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= htmlspecialchars($label) ?>
                                        </label>
                                        <select name="<?= $key ?>" class="form-select">
                                            <?php foreach ($model['users'] as $value) { ?>
                                                <option value="<?= $value['id'] ?>"><?= $value['id'] . ' : ' . $value['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php break; ?>
                                <?php
                                case 'bank_key': ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= htmlspecialchars($label) ?>
                                        </label>
                                        <select name="<?= $key ?>" class="form-select">
                                            <?php foreach ($model['banks'] as $k => $value) { ?>
                                                <option value="<?= $k ?>"><?= $k . ' : ' . $value['fullname'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php break; ?>
                                <?php
                                case 'shop_id': ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= htmlspecialchars($label) ?>
                                        </label>
                                        <select name="<?= $key ?>" class="form-select">
                                            <?php foreach ($model['shops'] as $k => $value) { ?>
                                                <option value="<?= $value['id'] ?>"><?= $value['id'] . ' : ' . $value['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php break;
                                
                                case 'unit': ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= ($key) ?></label>
                                        <select name="<?= $key ?>" class="form-select">
                                            <option value="g" <?= ($row[$key] ?? '') === 'g' ? 'selected' : '' ?>>กรัม (g)</option>
                                            <option value="kg" <?= ($row[$key] ?? '') === 'kg' ? 'selected' : '' ?>>กิโลกรัม (kg)</option>
                                            <option value="ml" <?= ($row[$key] ?? '') === 'ml' ? 'selected' : '' ?>>มิลลิลิตร (ml)</option>
                                            <option value="l" <?= ($row[$key] ?? '') === 'l' ? 'selected' : '' ?>>ลิตร (L)</option>
                                            <option value="tsp" <?= ($row[$key] ?? '') === 'tsp' ? 'selected' : '' ?>>ช้อนชา (tsp)</option>
                                            <option value="tbsp" <?= ($row[$key] ?? '') === 'tbsp' ? 'selected' : '' ?>>ช้อนโต๊ะ (tbsp)</option>
                                            <option value="cup" <?= ($row[$key] ?? '') === 'cup' ? 'selected' : '' ?>>ถ้วย (cup)</option>
                                            <option value="piece" <?= ($row[$key] ?? '') === 'piece' ? 'selected' : '' ?>>ชิ้น (piece)</option>
                                            <option value="egg" <?= ($row[$key] ?? '') === 'egg' ? 'selected' : '' ?>>ฟอง</option>
                                            <option value="leaf" <?= ($row[$key] ?? '') === 'leaf' ? 'selected' : '' ?>>ใบ</option>
                                            <option value="flower" <?= ($row[$key] ?? '') === 'flower' ? 'selected' : '' ?>>ดอก</option>
                                            <option value="clove" <?= ($row[$key] ?? '') === 'clove' ? 'selected' : '' ?>>กลีบ</option>
                                            <option value="head" <?= ($row[$key] ?? '') === 'head' ? 'selected' : '' ?>>หัว</option>
                                            <option value="cube" <?= ($row[$key] ?? '') === 'cube' ? 'selected' : '' ?>>ก้อน</option>
                                            <option value="seed" <?= ($row[$key] ?? '') === 'seed' ? 'selected' : '' ?>>เม็ด</option>
                                            <option value="rhizome" <?= ($row[$key] ?? '') === 'rhizome' ? 'selected' : '' ?>>แง่ง</option>
                                            <option value="pod" <?= ($row[$key] ?? '') === 'pod' ? 'selected' : '' ?>>ฝัก</option>
                                            <option value="string" <?= ($row[$key] ?? '') === 'string' ? 'selected' : '' ?>>เส้น</option>
                                            <option value="stem" <?= ($row[$key] ?? '') === 'stem' ? 'selected' : '' ?>>ลำ</option>
                                            <option value="slice" <?= ($row[$key] ?? '') === 'slice' ? 'selected' : '' ?>>ชิ้น/แผ่น</option>
                                            <option value="fruit" <?= ($row[$key] ?? '') === 'fruit' ? 'selected' : '' ?>>ลูก/ผล</option>
                                        </select>
                                    </div>
                                <?php break;
                                case 'avatar_url':
                                case 'profile_image':
                                case 'products_image':
                                case 'ingredients_image':
                                ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= htmlspecialchars($label) ?></label>
                                        <input type="file" name="<?= $key ?>" class="form-control" accept="image/*">
                                    </div>
                                    <?php break; ?>
                                <?php
                                default: ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= htmlspecialchars($label) ?></label>
                                        <input type="text" name="<?= $key ?>" class="form-control">
                                    </div>
                            <?php endswitch; ?>
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
    $inputId = 'upload_' . $id; // ให้ unique id
?>

    <!-- Label สำหรับคลิกเพื่อเลือกไฟล์ -->
    <label for="<?= $inputId ?>" class="btn btn-primary me-2 mb-2">
        <span class="d-none d-sm-block">อัพโหลดรูปภาพใหม่</span>
    </label>

    <!-- Form ต้องอยู่นอก label -->
    <form class="imgForm" action="<?= $url ?>" method="post" enctype="multipart/form-data">
        <!-- hidden fields -->
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <input type="hidden" name="field_name" value="<?= htmlspecialchars($name) ?>">
        <input type="hidden" name="upload_path" value="<?= htmlspecialchars($uploadPath) ?>">

        <!-- input file ซ่อน แต่เชื่อมกับ label ด้วย id -->
        <input type="file" name="upload" id="<?= $inputId ?>" class="form-control" accept="image/*" style="display:none" onchange="this.form.submit()">

        <!-- สามารถมีปุ่ม submit เพิ่มถ้าไม่อยาก auto submit -->
        <!-- <input type="submit" name="save" value="Upload" class="btn btn-primary mt-2"> -->
    </form>

    <!-- Preview ถ้ามีรูป -->
    <?php if (!empty($currentImage) && file_exists($uploadPath . '/' . $currentImage)): ?>
        <div class="mt-2">
            <img src="<?= htmlspecialchars($uploadPath . '/' . $currentImage) ?>" alt="Preview"
                style="max-height: 100px; border:1px solid #ccc; padding:3px;">
        </div>
    <?php endif; ?>
<?php
} 


function uploadFileAndUpdate($table, $id, $field_name, $data, $uploadBasePath = '../uploads/')
{
    if (!empty($_FILES[$field_name]['name'])) {
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['name']);
        $uploadPath = rtrim($uploadBasePath, '/') . '/' . $id . '_' . $safeName . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileTmp  = $_FILES[$field_name]['tmp_name'];
        $fileName = basename($_FILES[$field_name]['name']);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed)) {
            redirectWithAlert('error', "ไฟล์ $field_name ต้องเป็นรูปภาพเท่านั้น", $table);
        }

        $newFileName = $id . '_' . $safeName . '.' . $fileExt;
        $fullPath = $uploadPath . $newFileName;

        if (move_uploaded_file($fileTmp, $fullPath)) {
            dbUpdate($table, [$field_name => $fullPath], 'id = :id', ['id' => $id]);
        } else {
            redirectWithAlert('error', "อัปโหลดไฟล์ $field_name ไม่สำเร็จ", $table);
        }
    }
}
 
function getAvatarUrl($avatar_url, $base_url) {
    if (empty($avatar_url)) {
        return $base_url . '/uploads/default.png'; // default avatar
    }

    // ถ้าเป็น LINE URL
    if (str_starts_with($avatar_url, 'https://profile.line-scdn.net/')) {
        return $avatar_url;
    }

    // ถ้าเป็น local upload
    return $base_url . '/' . ltrim($avatar_url, '/');
}
