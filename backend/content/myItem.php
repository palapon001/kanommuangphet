<?php
$sql = "SELECT 
    i.id AS ingredient_id, 
    i.name AS ingredient_name, 
    i.unit AS ingredient_unit, 
    i.price AS ingredient_price, 
    i.ingredients_image, 
    s.id AS shop_id, 
    s.name AS shop_name, 
    u.id AS user_id, 
    u.name AS user_name 
FROM ingredients i 
INNER JOIN shops s ON i.shop_id = s.id 
INNER JOIN users u ON s.owner_id = u.id 
WHERE u.id = :userId
ORDER BY u.id, s.id, i.name;";

$data = dbSelectSQL($sql, ['userId' => $_SESSION['user_id']]);

if ($_GET['debug'] == 'dev') {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

$cols = [
    'ingredient_id'     => 'ID',
    'shop_id'           => 'รหัสร้านค้า',
    'ingredient_name'   => 'ชื่อวัตถุดิบ',
    'ingredient_unit'   => 'หน่วย',
    'ingredient_price'  => 'ราคา',
    'ingredients_image' => 'รูปสินค้า',
];
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> วัตถุดิบ</h4>
    <hr class="my-5" />
    <?= renderTable($data, $cols, 'ingredients_process.php'); ?>
</div>