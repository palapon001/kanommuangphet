<?php
$sql = "SELECT 
  orders.id AS order_id,
  orders.total,
  orders.status,
  orders.created_at AS order_date,
  
  users.id AS user_id,
  users.name AS user_name,
  users.email AS user_email,
  
  shops.id AS shop_id,
  shops.name AS shop_name,
  shops.location AS shop_location
FROM orders
JOIN users ON orders.user_id = users.id
JOIN shops ON orders.shop_id = shops.id
ORDER BY orders.id ASC";

$orders = dbSelectSQL($sql);

if ($_GET['debug'] == 'dev') {
    echo '<pre> Table orders';
    print_r($orders);
    echo '</pre>';
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตาราง /</span> ออเดอร์</h4>
    <hr class="my-5" />
    <?= renderTable($orders,''); ?>
</div>