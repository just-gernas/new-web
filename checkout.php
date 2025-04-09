<?php
/*************************************/
/*    file name: checkout.php        */
/*    info: client info form page    */
/*************************************/
include "config.inc.php";     // config
include "header.inc.php";     // header


$session_id = session_id();   // current user ID

// get cart info
$stmt = mysqli_prepare($db, "
    SELECT s.*, s.number * p.price AS amount, 
           p.product_id, p.product_name, p.price, p.photo 
    FROM products p
    JOIN carts s ON s.product_id = p.product_id
    WHERE s.session_id = ?
    ORDER BY p.product_name DESC
");
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$numrows = mysqli_num_rows($result);

// if no records, go back to cart
if ($numrows === 0) {
    header("Location: mycart.php");
    exit();
}
?>

<!-- Link tới file CSS -->
<link rel="stylesheet" href="CSS/checkout.css">

<script>
function checkit(form) {
    if (form.user_name.value.trim() === '') {
        alert('User name must be provided'); return false;
    } else if (form.email.value.trim() === '') {
        alert('Email must be provided'); return false;
    } else if (form.address1.value.trim() === '') {
        alert('Delivery address must be provided'); return false;
    } else if (form.tel_no.value.trim() === '') {
        alert('Phone number must be provided'); return false;
    }
    return true;
}
</script>

<form action="checkout2.php" method="POST" onsubmit="return checkit(this)">
    <h2>Personal Information</h2>
    <table class="form-table">
        <tr>
            <th>User Name</th>
            <td><input name="user_name" type="text" size="40" maxlength="20"></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input name="email" type="email" size="40" maxlength="40"></td>
        </tr>
        <tr>
            <th>Address to Deliver To</th>
            <td><input type="text" name="address1" size="40"></td>
        </tr>
        <tr>
            <th>Province/City</th>
            <td><input type="text" name="address2" size="40"></td>
        </tr>
        <tr>
            <th>Postcode</th>
            <td><input name="postcode" type="text" maxlength="10"></td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td><input name="tel_no" type="text" maxlength="20"></td>
        </tr>
        <tr>
            <th>Notes</th>
            <td><textarea name="content" cols="40" rows="5"></textarea></td>
        </tr>
    </table>
    <p>Please fill in your information to receive your products.</p>
    <p>
        <input type="submit" value="Place Order">
    </p>
</form>

<h2>Cart</h2>
<table class="cart-table">
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Amount</th>
    </tr>
    <?php
    $total_price = 0;
    while ($data = mysqli_fetch_assoc($result)) {
        $id = $data['product_id'];
        $name = htmlspecialchars($data['product_name']);
        $price = $data['price'];
        $number = $data['number'];
        $amount = $data['amount'];
        $total_price += $amount;
    ?>
    <tr>
        <td><a href="show.php?product_id=<?php echo $id; ?>"><b><?php echo $name; ?></b></a></td>
        <td><?php echo MoneyFormat($price); ?> $</td>
        <td><?php echo $number; ?></td>
        <td><?php echo MoneyFormat($amount); ?> $</td>
    </tr>
    <?php } ?>
    <tr class="total-row">
        <td colspan="3" align="right"><strong>Total Price</strong></td>
        <td><strong><?php echo MoneyFormat($total_price); ?> $</strong></td>
    </tr>
</table>

<?php include "footer.inc.php"; ?>
