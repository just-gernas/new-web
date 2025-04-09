<?php
/**************************************/
/* filename: admin/order_show.php     */
/* infor: order detail page           */
/**************************************/
include "../config.inc.php";
include "header.inc.php";

$order_id = intval($_GET['order_id']);

// Get order
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_array($result);

if (!$order) {
    ExitMessage("Order not found.");
}

$session_id = $order['session_id'];

// Get cart info
$sql = "SELECT s.*, s.number * p.price AS amount,
               p.product_id, p.product_name, p.price, p.photo
        FROM products p
        JOIN carts s ON s.product_id = p.product_id
        WHERE session_id = ?
        ORDER BY p.product_name DESC";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$numrows = mysqli_num_rows($result);
?>

<!-- Order Detail Table -->
<table width="100%" class="main" cellspacing="1">
  <caption>Order Detail</caption>
  <tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Amount</th>
  </tr>
  <?php
  if ($numrows > 0) {
      $total_price = 0;
      while ($data = mysqli_fetch_array($result)) {
          $id     = $data['product_id'];
          $name   = $data['product_name'];
          $price  = $data['price'];
          $number = $data['number'];
          $amount = $data['amount'];
          $photo  = $data['photo'] ?: 'default.gif';
          $total_price += $amount;
  ?>
  <tr align="center">
    <td><a href="../show.php?product_id=<?php echo $id ?>" target="_blank"><b><?php echo htmlspecialchars($name) ?></b></a></td>
    <td><?php echo MoneyFormat($price) ?> $</td>
    <td><?php echo $number ?></td>
    <td><?php echo MoneyFormat($amount) ?> $</td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="3" align="right"><strong>Total Price</strong></td>
    <td><strong><?php echo MoneyFormat($total_price) ?> $</strong></td>
  </tr>
  <?php
  } else {
      echo '<tr><td align="center" colspan="4">No product</td></tr>';
  }
  ?>
</table>

<p><hr></p>

<!-- Customer Info -->
<table border="0" class="main" cellspacing="1" width="60%">
  <caption>Client Info</caption>
  <tr>
    <th align="right">User Name</th>
    <td><?php echo htmlspecialchars($order["user_name"]) ?></td>
  </tr>
  <tr>
    <th align="right">Email</th>
    <td><?php echo htmlspecialchars($order["email"]) ?></td>
  </tr>
  <tr>
    <th align="right">Address</th>
    <td><?php echo htmlspecialchars($order["address"]) ?></td>
  </tr>
  <tr>
    <th align="right">Postcode</th>
    <td><?php echo htmlspecialchars($order["postcode"]) ?></td>
  </tr>
  <tr>
    <th align="right">Tel No</th>
    <td><?php echo htmlspecialchars($order["tel_no"]) ?></td>
  </tr>
  <tr>
    <th align="right">Notes</th>
    <td><?php echo nl2br(htmlspecialchars($order["content"])) ?></td>
  </tr>
</table>
</body>
</html>
