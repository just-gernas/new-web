<?php
/*****************************************/
/*    filename: admin/category_edit.php  */
/*    comment: products category management page */
/*****************************************/
include "../config.inc.php";
include "header.inc.php";

$action = $_POST['action'] ?? '';
$category_name = trim($_POST['category_name'] ?? '');
$category_id = $_POST['category_id'] ?? '';

// escape input
$category_name = mysqli_real_escape_string($db, $category_name);
$category_id = intval($category_id);

// add category
if ($action === 'addcat') {
    if (empty($category_name)) {
        ExitMessage("Please input category name.");
    }

    $sql = "SELECT * FROM categories WHERE category_name='$category_name'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        ExitMessage("Category name exists, please choose another name.");
    } else {
        $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";
        mysqli_query($db, $sql);
        ExitMessage("New category added.", "category.php");
    }
}

// rename category
elseif ($action === 'rencat') {
    if (empty($category_id)) {
        ExitMessage("Please choose the category to modify.");
    } elseif (empty($category_name)) {
        ExitMessage("Please input new category name.");
    }

    $sql = "SELECT * FROM categories WHERE category_name='$category_name' AND category_id<>'$category_id'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        ExitMessage("Category name already exists, please choose another name.");
    } else {
        $sql = "UPDATE categories SET category_name='$category_name' WHERE category_id='$category_id'";
        mysqli_query($db, $sql);
        ExitMessage("Category name modified.", "category.php");
    }
}

// delete category
elseif ($action === 'delcat') {
    if (empty($category_id)) {
        ExitMessage("Please choose the category to modify.");
    }

    $sql = "SELECT * FROM products WHERE category_id='$category_id'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        ExitMessage("There are products in this category, cannot delete.");
    } else {
        $sql = "DELETE FROM categories WHERE category_id='$category_id'";
        mysqli_query($db, $sql);
        ExitMessage("Category deleted.", "category.php");
    }
} else {
    ExitMessage("System parameters error.");
}

?>
