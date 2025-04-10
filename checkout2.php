<?php
/*******************************/
/*    filename: checkout2.php  */
/*    info: place order        */
/*******************************/
include "config.inc.php";    // config
include "header.inc.php";    // header
$session_id = session_id();
$order_id = time(); // id sử dụng timestamp 

// xử lí thông tin người dùng nhập
$user_name = trim($_POST['user_name']);
$email     = trim($_POST['email']);
$postcode  = trim($_POST['postcode']);
$tel_no    = trim($_POST['tel_no']);
$content   = trim($_POST['content']);
$address1  = trim($_POST['address1']);
$address2  = trim($_POST['address2']);
$address   = $address1 . ' ' . $address2;
?>

<!-- Link đến file CSS -->
<link rel="stylesheet" href="CSS/checkout2.css">

<h2>Order Information</h2>
<h3>Order ID: <font color="red">M<?php echo $order_id; ?></font></h3>

<table class="order-table">
  <tr class="order-header">
    <th>Product Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Amount</th>
  </tr>

<!-- Hiển thị table danh sách sp -->
<?php
$stmt = mysqli_prepare($db, "
    SELECT s.*, s.number * p.price AS amount, 
           p.product_id, p.product_name, p.price, p.photo 
    FROM products p
    JOIN carts s ON s.product_id = p.product_id
    WHERE s.session_id = ?
    ORDER BY p.product_name DESC
");
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total_price = 0;

if (mysqli_num_rows($result) > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        $id     = $data['product_id'];
        $name   = htmlspecialchars($data['product_name']);
        $price  = $data['price'];
        $number = $data['number'];
        $amount = $data['amount'];
        $total_price += $amount;
?>
    <tr class="order-row">
        <td><a href="show.php?product_id=<?php echo $id; ?>"><b><?php echo $name; ?></b></a></td>
        <td><?php echo MoneyFormat($price); ?> $</td>
        <td><?php echo $number; ?></td>
        <td><?php echo MoneyFormat($amount); ?> $</td>
    </tr>
<?php
    }
?>
    <!-- Hiển thị tổng tiền -->
    <tr class="order-footer">
        <td colspan="3" align="right"><strong>Total Price</strong></td>
        <td><strong><?php echo MoneyFormat($total_price); ?> $</strong></td>
    </tr>
<?php
} else {
    echo '<tr><td colspan="4" align="center" class="order-empty">No products in cart</td></tr>';
}
?>
</table>

<?php
// Lưu thông tin vào database
if ($total_price > 0) {
    $stmt = mysqli_prepare($db, "
        INSERT INTO orders 
            (order_id, session_id, total_price, user_name, email, address, postcode, tel_no, content)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "ssdssssss", $order_id, $session_id, $total_price, 
                           $user_name, $email, $address, $postcode, $tel_no, $content);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<p><strong>✅ Order placed successfully!</strong></p>";
    } else {
        echo "<p><strong>❌ Failed to save the order.</strong></p>";
    }
}
//làm mới giỏ hàng
session_regenerate_id(true);
$new_sessionid = session_id();

include "footer.inc.php";
?>
