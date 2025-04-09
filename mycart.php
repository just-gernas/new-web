<?php
/**********************************/
/*  file name: mycart.php         */
/*  info: cart detail             */
/**********************************/
include "config.inc.php";
include "header.inc.php";

$session_id = session_id();
?>

<!-- Link đến file CSS -->
<link rel="stylesheet" href="CSS/mycart.css">

<form action="updatecart.php" method="post" class="cart-form">
  <h2>Cart</h2>
  <table class="cart-table">
    <tr class="cart-header">
      <th>Image</th>
      <th>Product Name</th>
      <th>Price</th>
      <th>Number</th>
      <th>Amount</th>
      <th>Action</th>
    </tr>

<?php
$stmt = mysqli_prepare($db, "
  SELECT s.number, s.number * p.price AS amount,
         p.product_id, p.product_name, p.price, p.photo
  FROM carts s
  JOIN products p ON s.product_id = p.product_id
  WHERE s.session_id = ?
  ORDER BY p.product_name DESC
");

mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
  $total_price = 0;

  while ($data = mysqli_fetch_assoc($result)) {
    $id = $data['product_id'];
    $name = $data['product_name'];
    $price = $data['price'];
    $number = $data['number'];
    $amount = $data['amount'];
    $photo = $data['photo'] ? $data['photo'] : 'default.gif';

    $total_price += $amount;
?>
    <tr class="cart-row">
      <td><img src="uploads/<?php echo htmlspecialchars($photo); ?>" class="cart-img" alt="Product"></td>
      <td>
        <a href="show.php?product_id=<?php echo $id; ?>">
          <b><?php echo htmlspecialchars($name); ?></b>
        </a>
      </td>
      <td><?php echo MoneyFormat($price); ?> $</td>
      <td>
        <input type="text" name="p_<?php echo $id; ?>" value="<?php echo $number; ?>" size="4" maxlength="3" class="input-qty">
      </td>
      <td><?php echo MoneyFormat($amount); ?> $</td>
      <td>
        <input type="button" value="Cancel"
          class="cancel-btn"
          onclick="if(confirm('Sure to cancel this product?')) location.href='docart.php?action=editcart&product_id=<?php echo $id; ?>&number=0'">
      </td>
    </tr>
<?php
  }
?>
    <tr class="cart-total-row">
      <td colspan="4" class="text-right"><strong>Total Price</strong></td>
      <td colspan="2"><strong><?php echo MoneyFormat($total_price); ?> $</strong></td>
    </tr>
<?php
} else {
?>
    <tr class="empty-cart-row">
      <td colspan="6">No product in cart</td>
    </tr>
<?php
}
?>
  </table>

  <div class="cart-actions">
    <input type="submit" name="update_cart" value="Update Cart" class="update-btn">
    <input type="button" name="check_out" value="Go to Checkout" class="checkout-btn" onclick="location.href='checkout.php'">
  </div>
</form>

<?php include "footer.inc.php"; ?>
