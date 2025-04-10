<?php
/**********************************/
/* file name: search.php          */
/* info: category list            */
/**********************************/
include "config.inc.php";       
include "header.inc.php";      

$each_page = EACH_PAGE;  
$offset = intval($_GET['offset'] ?? 0);          
$category_id = intval($_GET['catid'] ?? 0);      
?>

<!-- Link đến file CSS -->
<link rel="stylesheet" href="CSS/search.css">

<h2>Product List</h2>

<!-- chọn danh mục sản phẩm -->
<div class="category-select">
  <label for="catid">Select product category:</label>
  <select name="catid" id="catid" onChange="location='?catid='+this.value">
    <option value="0">Select product category</option>
    <?php OptionCategories($category_id);  ?>
  </select>
</div>

<!-- Bảng hiển thị danh sách sản phẩm -->
<table class="product-table">
  <tr class="product-header">
    <th>Image</th>
    <th class="product-name-col">Product Name</th>
    <th>Price</th>
    <th class="action-col">Action</th>
  </tr>

  <?php
 
  $sql = "SELECT COUNT(*) FROM products WHERE category_id='$category_id'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_row($result);
  $total = $row[0];


  if ($offset < 0) $offset = 0;
  elseif ($offset > $total) $offset = $total;

  // Truy vấn sản phẩm theo danh mục 
  $sql = "SELECT product_id, product_name, photo, price FROM products 
          WHERE category_id='$category_id'
          ORDER BY post_datetime DESC
          LIMIT $offset, $each_page";
  $result = mysqli_query($db, $sql);
  $numrows = mysqli_num_rows($result);

  // Nếu có sản phẩm
  if ($numrows > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
      $id = $data['product_id'];               
      $name = $data['product_name'];             
      $price = MoneyFormat($data['price']);      
      $photo = ($data['photo']) ? $data['photo'] : 'default.gif';  
  ?>
      <tr class="product-row">
        <td><img src="uploads/<?php echo $photo ?>" class="product-img" alt=""></td>
        <td>
          <a href="show.php?product_id=<?php echo $id ?>"><b><?php echo htmlspecialchars($name) ?></b></a>
        </td>
        <td><?php echo $price ?> $</td>
        <td>
        
          <input type="button" value="Buy Now"
            class="buy-btn"
            onclick="location.href='docart.php?action=addcart&product_id=<?php echo $id ?>&number=1'">
        </td>
      </tr>
  <?php
    }
  } else {
  ?>
    <!-- Nếu không có sản phẩm -->
    <tr class="empty-product-row">
      <td colspan="4">No product in this category</td>
    </tr>
  <?php } ?>
</table>

<!-- Phân trang -->
<div class="pagination">
  Total <span class="total-count"><?php echo $total ?></span> records &nbsp;
  <b>
    <?php 
    $last_offset = $offset - $each_page;
    echo $last_offset < 0
      ? "Previous"
      : "<a href=\"?offset=$last_offset&catid=$category_id\">Previous</a>";

    echo " &nbsp; ";

    $next_offset = $offset + $each_page;
    echo $next_offset >= $total
      ? "Next"
      : "<a href=\"?offset=$next_offset&catid=$category_id\">Next</a>";
    ?>
  </b>
</div>

<?php include "footer.inc.php"; // Giao diện phần footer ?>
