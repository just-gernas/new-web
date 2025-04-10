<?php
/********************************/
/*  file name: list.php         */
/*  info: product category list */
/********************************/
include "config.inc.php";    // configure
include "header.inc.php";    // header

$each_page = EACH_PAGE;
$offset = intval($_GET['offset'] ?? 0);
$category_id = intval($_GET['catid'] ?? 0);
?>

<!-- Link đến file CSS -->
<link rel="stylesheet" href="CSS/list.css">

<h2>Products List</h2>

<!-- Chọn danh mục -->
<label for="catid">Select product category:</label>
<select name="catid" id="catid" onchange="location='?catid='+this.value">
  <option value="0">Select product category</option>
  <?php OptionCategories($category_id); ?>
</select>

<br><br>

<table class="product-list-table">
  <tr class="table-header">
    <th>Image</th>
    <th>Product Name</th>
    <th>Price</th>
    <th>Action</th>
  </tr>
  <?php
  // đếm tổng số sp
  $stmt = mysqli_prepare($db, "SELECT COUNT(*) FROM products WHERE category_id=?");
  mysqli_stmt_bind_param($stmt, "i", $category_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $total);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if ($offset < 0) $offset = 0;
  if ($offset > $total) $offset = $total;

  //lấy danh sách sp
  $stmt = mysqli_prepare($db, "SELECT product_id, product_name, photo, price 
                               FROM products 
                               WHERE category_id=? 
                               ORDER BY post_datetime DESC 
                               LIMIT ?, ?");
  mysqli_stmt_bind_param($stmt, "iii", $category_id, $offset, $each_page);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
      $id = $data['product_id'];
      $name = $data['product_name'];
      $price = MoneyFormat($data['price']);
      $photo = $data['photo'] ? $data['photo'] : 'default.gif';
  ?>

  <!-- hiển thị danh sách sp -->
  <tr class="product-row">
    <td><img src="uploads/<?php echo htmlspecialchars($photo); ?>" class="product-img" alt="Product"></td>
    <td>
      <a href="show.php?product_id=<?php echo $id ?>">
        <b><?php echo htmlspecialchars($name); ?></b>
      </a>
    </td>
    <td><?php echo $price ?> $</td>
    <td>
      <input type="button" value="Buy now"
             onclick="location.href='docart.php?action=addcart&product_id=<?php echo $id ?>&number=1'">
    </td>
  </tr>
  <?php
    }
  } else {
  ?>
  <tr class="product-row">
    <td colspan="4" class="no-product-msg">No product in this category</td>
  </tr>
  <?php } ?>
</table>

<p class="pagination">
  Total: <span class="highlight"><?php echo $total ?></span> records —
  <b>
    <?php
    //phân trang
    $last_offset = $offset - $each_page;
    if ($last_offset < 0) {
      echo "Previous";
    } else {
      echo "<a href=\"?offset=$last_offset&catid=$category_id\">Previous</a>";
    }

    echo " &nbsp; ";

    $next_offset = $offset + $each_page;
    if ($next_offset >= $total) {
      echo "Next";
    } else {
      echo "<a href=\"?offset=$next_offset&catid=$category_id\">Next</a>";
    }
    ?>
  </b>
</p>

<?php include "footer.inc.php"; ?>
