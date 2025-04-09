<?php
/****************************************/
/*    file name: updatecart.php         */
/*    info: update all products in cart */
/****************************************/
require_once 'config.inc.php'; // config file

$session_id = session_id(); // current user's session ID

// Update all records
if (isset($_POST['update_cart'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'p_') === 0) {
            $product_id = intval(substr($key, 2)); // get product ID from key
            $number = intval($value);              // cast input to integer

            if ($number > 0) {
                $stmt = mysqli_prepare($db, "UPDATE carts SET number=? WHERE session_id=? AND product_id=?");
                mysqli_stmt_bind_param($stmt, "isi", $number, $session_id, $product_id);
                mysqli_stmt_execute($stmt);
            } else {
                $stmt = mysqli_prepare($db, "DELETE FROM carts WHERE session_id=? AND product_id=?");
                mysqli_stmt_bind_param($stmt, "si", $session_id, $product_id);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}

// Clear all records
if (isset($_POST['clear_cart'])) {
    $stmt = mysqli_prepare($db, "DELETE FROM carts WHERE session_id=?");
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
}

// Redirect to cart page
header("Location: mycart.php");
exit();
?>
