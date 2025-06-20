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



function renderTable($cols, $data, $url)
{
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
            <?php foreach ($data as $row): ?>
                <tr data-id="<?= $row['id'] ?>" <?php foreach ($cols as $key => $label) echo "data-$key='" . htmlspecialchars($row[$key]) . "' "; ?>>
                    <?php foreach ($cols as $key => $label): ?>
                        <td>
                            <?php if ($key === 'avatar_url'): ?>
                                <?php if (!empty($row[$key])): ?>
                                    <img src="<?= htmlspecialchars($row[$key]) ?>" width="40" height="40" class="rounded-circle" alt="avatar">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= htmlspecialchars($row[$key] ?? '-') ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <div class="btn-group" style="display:flex;gap:5px;">
                            <button class="btn btn-sm btn-warning btn-edit" data-bs-toggle="modal" data-bs-target="#dataModal">แก้ไข</button>
                            <a href="<?= $url ?>?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger">ลบ</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Form สำหรับ Insert / Edit -->
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="<?= $url ?>">
                <input type="hidden" name="id" id="form-id">
                <input type="hidden" name="act" id="form-act" value="insert">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">เพิ่ม / แก้ไขข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php foreach ($cols as $key => $label): ?>
                        <?php if ($key === 'id') continue; ?>
                        <div class="mb-3">
                            <label class="form-label"><?= htmlspecialchars($label) ?></label>
                            <input type="text" name="<?= $key ?>" id="form-<?= $key ?>" class="form-control">
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

    <!-- Script DataTable + Modal -->
    <script>
        $(document).ready(function () {
            new DataTable('#dynamicTable', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/th.json',
                }
            });

            $('.btn-edit').on('click', function () {
                const row = $(this).closest('tr');
                const id = row.data('id');
                $('#form-id').val(id);
                $('#form-act').val('update');
                <?php foreach ($cols as $key => $label): ?>
                    <?php if ($key === 'id') continue; ?>
                    $('#form-<?= $key ?>').val(row.data('<?= $key ?>'));
                <?php endforeach; ?>
            });
        });

        function openForm(act) {
            $('#form-id').val('');
            $('#form-act').val(act);
            <?php foreach ($cols as $key => $label): ?>
                <?php if ($key === 'id') continue; ?>
                $('#form-<?= $key ?>').val('');
            <?php endforeach; ?>
        }
    </script>
 
    <?php
}
