<?php
/***************************************/
/*    filename: admin/product_del.php  */
/*    info: delete product             */
/***************************************/
include "../config.inc.php";
include "header.inc.php";

$product_id = intval($_GET['product_id']); // sanitize input

// Get product photo
$stmt = mysqli_prepare($db, "SELECT photo FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $photo);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Delete product
$stmt = mysqli_prepare($db, "DELETE FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);

    // Delete image safely
    if (!empty($photo) && file_exists(UPLOAD_PATH . $photo)) {
        unlink(UPLOAD_PATH . $photo);
    }

    ExitMessage("Product deleted successfully", "product.php");
} else {
    mysqli_stmt_close($stmt);
    ExitMessage("Failed to delete product", "product.php");
}
?>
