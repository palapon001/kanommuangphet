<?php
$sql = "SELECT 
            u.id AS user_id,
            u.name AS user_name,
            s.id AS shop_id,
            s.name AS shop_name,
            i.id AS ingredient_id,
            i.name AS ingredient_name,
            i.unit AS ingredient_unit,
            i.price AS ingredient_price,
            i.ingredients_image
        FROM users u
        INNER JOIN shops s ON s.owner_id = u.id
        LEFT JOIN ingredients i ON i.shop_id = s.id
        WHERE u.id = :userId
        ORDER BY s.id, i.name";

$data = dbSelectSQL($sql, ['userId' => $_SESSION['user_id']]);

if ($_GET['debug'] == 'dev') {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

$cols = [
    'id' => 'ID',
    'shop_id' => 'รหัสร้านค้า',
    // 'name'           => 'ชื่อสินค้า',   
    // // 'description'    => 'รายละเอียด',   
    // // 'price'          => 'ราคา',   
    'products_image' => 'รูปสินค้า',
    // 'created_at'     => 'วันที่สร้าง'
];

?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> วัตถุดิบ</h4>
    <hr class="my-5" />
    <?= renderTable($ingredients, '', 'ingredients_process.php'); ?>
</div>