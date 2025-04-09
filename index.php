<?php
/*******************************/
/*      file name: index.php   */
/*      info: homepage         */
/*******************************/
include "config.inc.php";     // config chứa kết nối DB ($db)
include "header.inc.php";     // header
?>

<!-- Link đến file CSS -->
<link rel="stylesheet" href="CSS/index.css">

<h2>Homepage recommended</h2>
<?php
$sql = "SELECT product_name, product_id, price, photo FROM products
        ORDER BY is_commend DESC, post_datetime DESC LIMIT 5";
$result = mysqli_query($db, $sql);
?>
<!-- recommended begin -->
<table class="product-table">
  <tr class="table-header">
  <?php
  while ($data = mysqli_fetch_array($result)) {
      $product_id = $data['product_id'];
      $product_name = htmlspecialchars($data['product_name']);
      $photo = ($data['photo']) ? $data['photo'] : 'default.gif';
      $price = MoneyFormat($data['price']);
  ?>
  <td class="product-item">
    <a href="show.php?product_id=<?php echo $product_id ?>">
      <img src="uploads/<?php echo $photo ?>" alt="<?php echo $product_name ?>" class="product-img"><br>
      <?php echo $product_name ?>
    </a><br><span class="product-price">$<?php echo $price ?></span>
  </td>
  <?php } ?>
  </tr>
</table>
<!-- recommended end -->

<h2>Products list</h2>
<?php
$sql = "SELECT * FROM categories ORDER BY category_name";
$result = mysqli_query($db, $sql);

while ($row = mysqli_fetch_array($result)) {
?>
<table class="product-table">
  <tr class="table-header">
    <th colspan="3" class="category-title">
    <?php
    echo "<a href=\"list.php?catid={$row['category_id']}\">";
    echo htmlspecialchars($row['category_name']);
    echo "</a>";
    ?>
    </th>
  </tr>
<?php
  $sql2 = "SELECT product_name, product_id, price, is_commend FROM products
           WHERE category_id = '{$row['category_id']}'
           ORDER BY is_commend DESC, post_datetime DESC LIMIT 5";
  $result2 = mysqli_query($db, $sql2);

  while ($row2 = mysqli_fetch_array($result2)) {
?>
  <tr class="product-row">
    <td class="product-id"><?php echo $row2['product_id'] ?></td>
    <td class="product-info">
    <?php if ($row2['is_commend']) { ?>
      <img src="img/star.gif" class="star-icon">
    <?php } ?>
    <a href="show.php?product_id=<?php echo $row2['product_id'] ?>">
      <?php echo htmlspecialchars($row2['product_name']) ?>
    </a>
    <a href="docart.php?product_id=<?php echo $row2['product_id'] ?>&action=addcart&number=1">
      <img src="img/add.gif" class="add-icon">
    </a>
    </td>
    <td class="product-price-right"><?php echo MoneyFormat($row2['price']) ?> $</td>
  </tr>
<?php
  }
?>
</table>
<br>
<?php
}
include "footer.inc.php";
?>
