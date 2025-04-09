<?php
include "../config.inc.php";
include "header.inc.php";

$photo_name = ""; 
$has_error = false;

if (isset($_POST['submit'])) {
    $error_msg = [];

    $category_id = intval($_POST['category_id'] ?? 0);
    $product_name = trim($_POST['product_name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $detail = trim($_POST['detail'] ?? '');
    $is_commend = isset($_POST['is_commend']) ? 1 : 0;

    if ($category_id <= 0) $error_msg[] = "please select category";
    if ($product_name == "") $error_msg[] = "please input product name";
    if ($price == "") {
        $error_msg[] = "please input product price";
    } elseif (!is_numeric($price)) {
        $error_msg[] = "product price must be numbers";
    }
    if ($detail == "") $error_msg[] = "please input details for the product";

    if ($_FILES['photo']['size'] > 0 && $_FILES['photo']['name']) {
        if (!in_array($_FILES['photo']['type'], ['image/gif', 'image/jpeg', 'image/pjpeg'])) {
            $error_msg[] = "product images must be .gif or .jpeg";
        } else {
            list($tmp, $file_ext) = explode("/", $_FILES['photo']['type']);
            $photo_name = mt_rand() . "_" . time() . "." . $file_ext;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_PATH . $photo_name)) {
                $error_msg[] = "fail to save product image";
            }
        }
    }

    $has_error = !empty($error_msg);

    if (!$has_error) {
        $stmt = mysqli_prepare($db,
            "INSERT INTO products (category_id, product_name, price, detail, is_commend, photo, post_datetime)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param($stmt, "isdsss", $category_id, $product_name, $price, $detail, $is_commend, $photo_name);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            ExitMessage("products content added", "product.php?catid=$category_id");
        } else {
            ExitMessage("fail to add products content");
        }
        mysqli_stmt_close($stmt);
    } else {
        ShowErrorBox($error_msg);
    }
}

if (!isset($_POST['category_id'])) {
    $_POST['category_id'] = $_GET['catid'] ?? 0;
}
?>
<form method="post" action="product_add.php" enctype="multipart/form-data">
  <table width="100%" class="main" cellspacing="1">
    <caption>add new product</caption>
    <tr>
      <th>product_category<font color="red">(*)</font></th>
      <td>
        <select name="category_id">
          <option value="0">select product category</option>
          <?php echo OptionCategories($_POST['category_id'] ?? 0) ?>
        </select>
      </td>
    </tr>
    <tr>
      <th>product <font color="red">(*)</font></th>
      <td>
        <input name="product_name" value="<?php echo Html2Text($_POST['product_name'] ?? '') ?>"
               type="text" size="35" maxlength="20">
      </td>
    </tr>
    <tr>
      <th>price<font color="red">(*)</font></th>
      <td>
        <input name="price" value="<?php echo Html2Text($_POST['price'] ?? '') ?>"
               type="text" size="12" maxlength="20"> $
      </td>
    </tr>
    <tr>
      <th>products recommended</th>
      <td>
        <input name="is_commend" type="checkbox" value="1"
               <?php if (!empty($_POST['is_commend'])) echo "checked" ?>>
      </td>
    </tr>
    <tr>
      <th>product image</th>
      <td><input name="photo" type="file" size="50" /></td>
    </tr>
    <tr>
      <th>details<font color="red">(*)</font></th>
      <td>
        <textarea name="detail" rows="10" cols="50"><?php echo Html2Text($_POST['detail'] ?? '') ?></textarea>
      </td>
    </tr>
    <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
  </table>
  <div class="submit">
    <input name="submit" type="submit" id="submit" value=" create new ">
    <input name="return" type="button" value=" back " onClick="location.href='catalog.php'">
  </div>
</form>

</body>
</html>
