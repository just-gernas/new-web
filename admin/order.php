<?php
/*********************************/
/* filename: admin/order.php     */
/* infor: order list             */
/*********************************/
include "../config.inc.php";
include "header.inc.php";

$each_page = EACH_PAGE;
$offset = intval($_GET['offset'] ?? 0);

// total number of orders
$sql = "SELECT COUNT(*) FROM orders";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_row($result);
$total = $row[0];

// pagination bounds
if ($offset < 0) $offset = 0;
if ($offset > $total) $offset = max(0, $total - $each_page);

// order list query
$offset = intval($offset);
$each_page = intval($each_page);

$sql = "SELECT total_price, order_id, user_name, order_time 
        FROM orders 
        ORDER BY order_id DESC 
        LIMIT $offset, $each_page";

$result = mysqli_query($db, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($db));
}
$numrows = mysqli_num_rows($result);

?>
<br>
<table width="100%" class="main" cellspacing="1">
  <caption>Order Admin</caption>
  <tr>
    <th>Order ID</th>
    <th>User</th>
    <th>Total Price</th>
    <th>Date</th>
    <th width="20%">Detail</th>
  </tr>
  <?php if ($numrows > 0): ?>
    <?php while($data = mysqli_fetch_assoc($result)): ?>
    <tr align="center">
      <td>M<?php echo $data['order_id'] ?></td>
      <td><?php echo htmlspecialchars($data['user_name']) ?></td>
      <td><?php echo MoneyFormat($data['total_price']) ?> $</td>
      <td><?php echo date("Y-m-d H:i", strtotime($data['order_time'])) ?></td>
      <td>
        <input name="update" type="button" value="Detail"
               onclick="location.href='order_show.php?order_id=<?php echo $data['order_id'] ?>'" />
      </td>
    </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td align="center" colspan="5">No orders found</td></tr>
  <?php endif; ?>
</table>

<p>Total: <font color="red"><b><?php echo $total ?></b></font> records &nbsp;<b>
<?php
// pagination controls
$last_offset = $offset - $each_page;
$next_offset = $offset + $each_page;

if ($last_offset < 0) {
    echo "Previous";
} else {
    echo "<a href=\"?offset=$last_offset\">Previous</a>";
}

echo " &nbsp; ";

if ($next_offset >= $total) {
    echo "Next";
} else {
    echo "<a href=\"?offset=$next_offset\">Next</a>";
}
?>
</b></p>
</body>
</html>
