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

function redirectWithAlert($alert = 'info', $text = '')
{
    $url = '../index.php?page=user&alert=' . urlencode($alert);

    if ($text !== '') {
        $url .= '&text=' . urlencode($text);
    }

    header('Location: ' . $url);
    exit;
}

?>

<?php function renderTable($data, $post = null, $setColumns = null)
{
    if (empty($data)) {
        return '<div class="alert alert-warning text-center" role="alert">No data to display</div>';
    }

    $dataKeys = array_keys($data[0]);
    $columns = empty($setColumns) ? $dataKeys : $setColumns;
    $imageColumns = ['avatar_url'];
    $placeholderUrl = 'https://via.placeholder.com/150';
    $modalIdCounter = 0;

    ob_start();
    ?>
    <div class="table-responsive">

        <!-- ปุ่มเพิ่มข้อมูล -->
        <?php
        $addModalId = "addModal";
        ?>
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#<?= $addModalId ?>">
            เพิ่มข้อมูล
        </button>

        <table class="table table-striped table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <?php
                    foreach ($columns as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($dataKeys as $col):
                            $cell = $row[$col] ?? '';
                            if (in_array($col, $imageColumns)):
                                $modalIdCounter++;
                                $imgModalId = "imgModal{$modalIdCounter}";
                                $imageUrl = (filter_var($cell, FILTER_VALIDATE_URL)) ? $cell : $placeholderUrl;
                                ?>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#<?= $imgModalId ?>">
                                        แสดงรูป
                                    </button>

                                    <div class="modal fade" id="<?= $imgModalId ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">รูปภาพ</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?= htmlspecialchars($imageUrl) ?>" class="img-fluid" alt="Image"
                                                        onerror="this.onerror=null; this.src='https://f.ptcdn.info/971/067/000/q52xwolorlKa6Qff6aB-o.png';">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($cell) ?></td>
                            <?php endif; endforeach; ?>

                        <td>
                            <?php
                            $modalIdCounter++;
                            $editModalId = "editModal{$modalIdCounter}";
                            ?>
                            <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                data-bs-target="#<?= $editModalId ?>">
                                แก้ไข
                            </button>

                            <a href="process/<?= htmlspecialchars($post . '?act=delete&id=' . urlencode($row['id'])) ?>"
                                class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันลบข้อมูลนี้?');">
                                ลบ
                            </a>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="<?= $editModalId ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">แก้ไขข้อมูล ID <?= htmlspecialchars($row['id']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="post" action="process/<?= htmlspecialchars($post . '?act=update') ?>">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                                <?php foreach ($dataKeys as $col):
                                                    if ($col === 'id') {
                                                        continue;
                                                    }

                                                    if ($col === 'created_at') {
                                                        continue;
                                                    }
                                                    ?>
                                                    <div class="mb-3">
                                                        <label class="form-label"><?= htmlspecialchars($col) ?></label>
                                                        <input type="text" class="form-control" name="<?= htmlspecialchars($col) ?>"
                                                            value="<?= htmlspecialchars($row[$col]) ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                                <input type="hidden" name="debug"
                                                    value="<?= ($_GET['debug'] == 'dev' ? 'dev' : '') ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn btn-success">บันทึก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Modal -->
        <div class="modal fade" id="<?= $addModalId ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มข้อมูลใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="post" action="process/<?= htmlspecialchars($post . '?act=insert') ?>">
                        <div class="modal-body">
                            <?php foreach ($columns as $col):
                                if ($col === 'id')
                                    continue; // id ไม่ต้องใส่ใน insert form
                                ?>
                                <div class="mb-3">
                                    <label class="form-label"><?= htmlspecialchars($col) ?></label>
                                    <input type="text" class="form-control" name="<?= htmlspecialchars($col) ?>" value="">
                                </div>
                            <?php endforeach; ?>
                            <input type="hidden" name="debug" value="<?= ($_GET['debug'] == 'dev' ? 'dev' : '') ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">เพิ่มข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <?php
    return ob_get_clean();
}
?>