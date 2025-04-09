<?php
/******************************************/
/*  file name: admin/product_edit.php    */
/*  info: edit product                    */
/******************************************/
include "../config.inc.php";    // Config file
include "header.inc.php";       // Admin header

$product_id = intval($_REQUEST['product_id']);
$has_error = false;
$error_msg = [];

if (isset($_POST['submit'])) {
    // Validate input
    if (empty($_POST['category_id']) || $_POST['category_id'] == "0") {
        $error_msg[] = "Please select product category";
    }

    if (empty($_POST['product_name'])) {
        $error_msg[] = "Please input product name";
    }

    if (empty($_POST['price'])) {
        $error_msg[] = "Please input price";
    } elseif (!is_numeric($_POST['price'])) {
        $error_msg[] = "Price must be numeric";
    }

    // Handle image upload
    if ($_FILES['photo']['size'] > 0 && $_FILES['photo']['name']) {
        if (!in_array($_FILES['photo']['type'], ['image/gif', 'image/jpeg', 'image/pjpeg'])) {
            $error_msg[] = "Images must be .gif or .jpeg";
        } else {
            list($tmp, $file_ext) = explode("/", $_FILES['photo']['type']);
            $photo_name = mt_rand() . "_" . time() . "." . $file_ext;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_PATH . $photo_name)) {
                $error_msg[] = "Failed to save image";
            }
        }
    } else {
        $photo_name = $_POST['old_photo'];
    }

    if (empty($_POST['detail'])) {
        $error_msg[] = "Please input product details";
    }

    $has_error = !empty($error_msg);

    if (!$has_error) {
        $sql = "UPDATE products SET
                category_id = ?,
                product_name = ?,
                price = ?,
                detail = ?,
                is_commend = ?,
                photo = ?
                WHERE product_id = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "isdsssi",
            $_POST['category_id'],
            $_POST['product_name'],
            $_POST['price'],
            $_POST['detail'],
            $_POST['is_commend'] ? 1 : 0,
            $photo_name,
            $product_id
        );
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) >= 0) {
            ExitMessage("Product updated successfully", "product.php?catid=" . $_POST['category_id']);
        } else {
            ExitMessage("Failed to update product");
        }
    }
} else {
    // Load product to prefill the form
    $stmt = mysqli_prepare($db, "SELECT * FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $_POST = $data;
    $photo = $data['photo'];
}
?>

<form method="post" action="product_edit.php?product_id=<?php echo $product_id ?>" enctype="multipart/form-data">
  <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
  <table width="100%" class="main" cellspacing="1">
    <caption>Edit Product</caption>
    <tr>
      <th>Product Category<font color="red">(*)</font></th>
      <td>
        <select name="category_id">
          <option value="0">Select product category</option>
          <?php OptionCategories($_POST['category_id']) ?>
        </select>
      </td>
    </tr>
    <tr>
      <th>Product Name<font color="red">(*)</font></th>
      <td><input name="product_name" type="text" size="35" maxlength="20"
                 value="<?php echo Html2Text($_POST['product_name']) ?>"></td>
    </tr>
    <tr>
      <th>Price<font color="red">(*)</font></th>
      <td><input name="price" type="text" size="12" maxlength="20"
                 value="<?php echo Html2Text($_POST['price']) ?>"> $</td>
    </tr>
    <tr>
      <th>Product Recommended</th>
      <td><input name="is_commend" type="checkbox" value="1"
                 <?php if (!empty($_POST['is_commend'])) echo "checked" ?>></td>
    </tr>
    <tr>
      <th>Product Image</th>
      <td>
        <?php if (!empty($photo)) { ?>
            <img src="../uploads/<?php echo $photo ?>" width="100" height="80"><br>
            <input type="hidden" name="old_photo" value="<?php echo $photo ?>">
        <?php } ?>
        <input name="photo" type="file" size="50">
      </td>
    </tr>
    <tr>
      <th>Details<font color="red">(*)</font></th>
      <td><textarea name="detail" rows="10" cols="50"><?php echo Html2Text($_POST['detail']) ?></textarea></td>
    </tr>
  </table>
  <div class="submit">
    <input type="submit" name="submit" value="Edit">
    <input type="button" value="Back" onclick="location.href='product.php'">
  </div>
</form>
</body>
</html>
