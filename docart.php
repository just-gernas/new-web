<?php
/**************************************/
/*    file name: docart.php           */
/*    info: update product in cart    */
/**************************************/
require_once 'config.inc.php'; // configure

$session_id = session_id(); // user session_id
$product_id = intval($_GET['product_id']); // product_id
$number = intval($_GET['number']); // number of products
$action = $_GET['action']; // action

// Get product record
$sql = "SELECT number FROM carts WHERE session_id=? AND product_id=?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "si", $session_id, $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_row($result);

if ($row) {
    $old_number = intval($row[0]); // original quantity
    $have_product = true;
} else {
    $old_number = 0;
    $have_product = false;
}

if ($action === 'addcart') {
    $new_number = $old_number + $number;

    if ($new_number > 0) {
        if ($have_product) {
            // Product exists: update
            $sql = "UPDATE carts SET number=? WHERE session_id=? AND product_id=?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "isi", $new_number, $session_id, $product_id);
        } else {
            // Product doesn't exist: insert
            $sql = "INSERT INTO carts (session_id, product_id, number) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $session_id, $product_id, $new_number);
        }
    } else {
        // Quantity <= 0: delete
        $sql = "DELETE FROM carts WHERE session_id=? AND product_id=?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "si", $session_id, $product_id);
    }
    mysqli_stmt_execute($stmt);
} elseif ($action === 'editcart') {
    if ($number > 0) {
        // Update quantity
        $sql = "UPDATE carts SET number=? WHERE session_id=? AND product_id=?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "isi", $number, $session_id, $product_id);
    } else {
        // Delete product from cart
        $sql = "DELETE FROM carts WHERE session_id=? AND product_id=?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "si", $session_id, $product_id);
    }
    mysqli_stmt_execute($stmt);
}

// Redirect to cart page
header("Location: mycart.php");
exit();
?>
