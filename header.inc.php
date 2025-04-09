<?php
// Gọi session nếu chưa gọi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kết nối tới CSDL
require_once("config.inc.php");

// Lấy session ID hiện tại
$session_id = session_id();

// Truy vấn giỏ hàng để lấy số lượng sản phẩm
$sql = "SELECT SUM(number) FROM carts WHERE session_id='$session_id'";
$result = mysqli_query($db, $sql);

@$row = mysqli_fetch_row($result);
$number = intval($row[0]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Shop</title>
    <link rel="stylesheet" href="CSS/header.css">
</head>
<body>
<div class="layout">
    <div class="sidebar">
        <img src="img/logo.gif" alt="Logo" class="logo">
        <p class="cart-info">Cart has <a href="mycart.php"><span class="cart-count"><?php echo $number ?></span></a> products</p>
        <hr>

        <ul class="nav">
            <li><a href="index.php">Homepage</a></li>
            <li><strong>Products list</strong></li>
            <ul>
                <?php
                $sql = "SELECT * FROM categories ORDER BY category_name";
                $result = mysqli_query($db, $sql);
                if ($result === FALSE) {
                    die(mysqli_error($db));
                }

                while ($row = mysqli_fetch_array($result)) {
                    echo '<li><a href="list.php?catid=' . $row['category_id'] . '">';
                    echo htmlspecialchars($row['category_name']);
                    echo '</a></li>';
                }
                ?>
            </ul>
            <li><a href="mycart.php">Cart</a></li>
            <li><a href="checkout.php">Checkout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <!-- begin content here -->
