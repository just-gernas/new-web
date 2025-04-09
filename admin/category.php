<?php
/************************************/
/* filename: admin/category.php     */
/* comment: category management     */
/************************************/
include "../config.inc.php";
include "header.inc.php";

// Lấy danh sách category cho dropdown
function GetCategoryOptions($selected_id = 0) {
    global $db;
    $options = '';
    $sql = "SELECT * FROM categories ORDER BY category_name";
    $result = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_array($result)) {
        $category_id = $row["category_id"];
        $category_name = htmlspecialchars($row["category_name"]);
        $selected = ($selected_id == $category_id) ? "selected" : "";
        $options .= "<option value=\"$category_id\" $selected>$category_name</option>\n";
    }
    return $options;
}
?>

<!-- create category -->
<form action="category_edit.php" method="post">
  <table width="100%" class="main" cellspacing="1">
    <caption>create category</caption>
    <input type="hidden" name="action" value="addcat">
    <tr>
      <td width="20%"><input type="submit" value="create category" name="submit1"></td>
      <td width="80%"><input size="30" name="category_name" placeholder="Enter new category name"></td>
    </tr>
  </table>
</form>

<!-- modify category -->
<form action="category_edit.php" method="post">
  <table width="100%" class="main" cellspacing="1">
    <caption>modify category</caption>
    <input type="hidden" name="action" value="rencat">
    <tr>
      <td width="20%"><input type="submit" value="modify category" name="submit2"></td>
      <td width="80%">
        <select name="category_id">
          <option value="0">-=choose category=-</option>
          <?= GetCategoryOptions() ?>
        </select>
        &nbsp;New name:
        <input name="category_name" size="20">
      </td>
    </tr>
  </table>
</form>

<!-- delete category -->
<form action="category_edit.php" method="post">
  <table width="100%" class="main" cellspacing="1">
    <caption>delete category</caption>
    <input type="hidden" name="action" value="delcat">
    <tr>
      <td width="20%"><input type="submit" value="delete category" name="submit3"></td>
      <td width="80%">
        <select name="category_id">
          <option value="0">-=choose category=-</option>
          <?= GetCategoryOptions() ?>
        </select>
      </td>
    </tr>
  </table>
</form>

</body>
</html>
