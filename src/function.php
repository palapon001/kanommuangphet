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
                <tr>
                    <?php foreach ($cols as $key => $label): ?>
                        <td>
                            <?php if ($key === 'avatar_url'): ?>
                                <?php if (!empty($row[$key])): ?>
                                    <img src="<?= htmlspecialchars($row[$key]) ?>" width="40" height="40" class="rounded-circle"
                                        alt="avatar">
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
                            <a href="<?= $url ?>?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                            <a href="<?= $url ?>?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger">ลบ</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            var table = new DataTable('#dynamicTable', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/th.json',
                },
            });
        });
    </script>
    <?php
}

