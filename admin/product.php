<?php
/***********************************/
/*    file name: admin/product.php */
/*    infor: add product list      */
/***********************************/
include "../config.inc.php"; //configure file
include "header.inc.php";   //admin header file

$each_page = EACH_PAGE; //records in every page
$offset = intval($_GET['offset'] ?? 0); //offset
$category_id = intval($_GET['catid'] ?? 0); //product_category
?>
<div class="btnInsert"> 
  <a href="product_add.php?catid=<?php echo $category_id ?>">add new product</a> 
</div>
select product category
<select name="catid" onChange="location='?catid='+this.options[this.selectedIndex].value">
  <option value="0">select product category</option>
  <?php echo optionCategories($category_id); ?>
</select>
<br><br>

<table width="100%" class="main" cellspacing="1">
  <caption>products admin</caption>
  <tr>
    <th>ID</th>
    <th>product_name</th>
    <th>price</th>
    <th>action</th>
  </tr>
  <?php
  // records total
  $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
  $stmt->bind_param("i", $category_id);
  $stmt->execute();
  $stmt->bind_result($total);
  $stmt->fetch();
  $stmt->close();

  if ($offset < 0) $offset = 0;
  elseif ($offset > $total) $offset = $total;

  $stmt = $db->prepare("SELECT product_id, product_name, price FROM products 
                          WHERE category_id = ? ORDER BY post_datetime DESC 
                          LIMIT ?, ?");
  $stmt->bind_param("iii", $category_id, $offset, $each_page);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0):
    while ($data = $result->fetch_assoc()):
      $id = $data['product_id'];
      $name = htmlspecialchars($data['product_name']);
      $price = $data['price'];
  ?>
  <tr align="center">
    <td><?php echo $id; ?></td>
    <td><a href="product_edit.php?product_id=<?php echo $id; ?>"><?php echo $name; ?></a></td>
    <td><?php echo MoneyFormat($price); ?> $</td>
    <td>
      <input name="update" type="button" value="edit"
             onclick="location.href='product_edit.php?product_id=<?php echo $id ?>'">
      &nbsp;
      <input name="delete" type="button" value="delete"
             onClick="if(confirm('are you sure to delete this product?')) 
             location.href='product_del.php?product_id=<?php echo $id ?>'">
    </td>
  </tr>
  <?php
    endwhile;
  else:
  ?>
    <tr>
      <td align="center" colspan="4">no product in this category</td>
    </tr>
  <?php endif; ?>
</table>

<p>total <font color="red"><b><?php echo $total; ?></b></font> records &nbsp;<b>
<?php
  $last_offset = $offset - $each_page;
  if ($last_offset < 0) {
    echo "previous";
  } else {
    echo '<a href="?offset=' . $last_offset . '&catid=' . $category_id . '">previous</a>';
  }

  echo " &nbsp; ";

  $next_offset = $offset + $each_page;
  if ($next_offset >= $total) {
    echo "next";
  } else {
    echo '<a href="?offset=' . $next_offset . '&catid=' . $category_id . '">next</a>';
  }
?>
</b></p>
</body>
</html>
