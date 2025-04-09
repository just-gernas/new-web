<?php
/************************************/
/*      file name: show.php         */
/*      info: product details       */
/************************************/
include "config.inc.php";
include "header.inc.php";

$product_id = intval($_REQUEST['product_id']);

$stmt = mysqli_prepare($db, "
  SELECT p.*, c.category_name FROM products p
  JOIN categories c ON c.category_id = p.category_id
  WHERE p.product_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
?>

<!-- Link đến file CSS -->
<link rel="stylesheet" href="CSS/show.css">

<form method="get" action="docart.php" class="product-detail-form">
  <h2>Product Detail</h2>
  <table class="product-detail-table">
    <tr>
      <th>Category</th>
      <td><?php echo htmlspecialchars($data['category_name']); ?></td>
    </tr>
    <tr>
      <th>Product Name</th>
      <td>
        <b><?php echo htmlspecialchars($data['product_name']); ?></b>
        <?php if (!empty($data['is_commend'])) echo '<span class="recommended">Recommended!</span>'; ?>
      </td>
    </tr>
    <tr>
      <th>Product Image</th>
      <td>
        <?php if (!empty($data['photo'])): ?>
          <img src="uploads/<?php echo htmlspecialchars($data['photo']); ?>" class="product-image" alt="">
        <?php else: ?>
          <em>No image available</em>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <th>Price</th>
      <td>
        <span class="product-price"><?php echo MoneyFormat($data['price']); ?></span> $
        <input type="hidden" name="action" value="addcart">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input name="number" value="1" type="text" size="4" maxlength="2" class="quantity-input">
        <input type="submit" value="Submit" class="submit-btn">
        <input type="image" src="img/buyit.gif" alt="Order" class="buy-image">
      </td>
    </tr>
    <tr>
      <th>Description</th>
      <td><?php echo nl2br(htmlspecialchars($data['detail'])); ?></td>
    </tr>
  </table>
</form>

<?php include "footer.inc.php"; ?>
