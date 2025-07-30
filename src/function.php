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
                    <td id="colEdit">
                        <div class="btn-group" style="display:flex;gap:5px;">
                            <? if ($url == 'shop_process.php') { ?>
                                <a href="<?= $config['url'] ?>/index.php?shop=<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>" class="btn btn-sm btn-info btn-edit" >
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
                                <form method="post" action="<?= $path ?>?act=update">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                    <?php foreach (array_keys($row) as $key): ?>
                                        <div class="mb-3">
                                            <label class="form-label"><?= htmlspecialchars($key) ?></label>
                                            <input type="text" name="<?= $key ?>" value="<?= htmlspecialchars($row[$key]) ?>"
                                                class="form-control" <?= $key === 'id' ? 'readonly' : '' ?>>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                    </div>
                                </form>
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
                <form method="post" action="<?= $path ?>?act=insert">
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





    <!-- Script DataTable + Modal -->
    <script>
        $(document).ready(function () {
            new DataTable('#dynamicTable', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/th.json',
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        customize: function (doc) {
                            // กำหนดฟอนต์ภาษาไทย
                            doc.defaultStyle = {
                                font: 'THSarabun',
                                fontSize: 16
                            };
                            // จัดระเบียบตาราง PDF
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            doc.styles.tableHeader.alignment = 'center';
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            // กำหนด margin
                            doc.pageMargins = [20, 20, 20, 20];
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'asc']]
            });

            // เพิ่มฟอนต์ภาษาไทยสำหรับ pdfMake
            if (window.pdfMake) {
                if (!pdfMake.fonts) pdfMake.fonts = {};
                pdfMake.fonts['THSarabun'] = {
                    normal: 'https://cdn.jsdelivr.net/npm/font-th-sarabun-new@1.0.0/fonts/THSarabunNew-webfont.ttf',
                    bold: 'https://cdn.jsdelivr.net/npm/font-th-sarabun-new@1.0.0/fonts/THSarabunNew_bold-webfont.ttf',
                    italics: 'https://cdn.jsdelivr.net/npm/font-th-sarabun-new@1.0.0/fonts/THSarabunNew_italic-webfont.ttf',
                    bolditalics: 'https://cdn.jsdelivr.net/npm/font-th-sarabun-new@1.0.0/fonts/THSarabunNew_bolditalic-webfont.ttf'
                };
            }
        });
    </script>

    <?php
}
