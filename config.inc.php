<?php
/**********************/
/* System parameter setting */
/**********************/
// Database config
define('DB_USER',       "root");
define('DB_PASSWORD',   "");
define('DB_HOST',       "localhost");
define('DB_NAME',       "shop");

// Administration
define('ADMIN_USER',    "admin");
define('ADMIN_PW',      "admin");

// Records per page
define('EACH_PAGE',     5);

// File upload path
define('UPLOAD_PATH', dirname(__FILE__) . "/uploads/");

/**********************/
/* Public functions */
/**********************/

// Show error message
function ExitMessage($message, $url = '')
{
    echo '<p class="message">' . $message . '<br>';
    if ($url) {
        echo '<a href="' . $url . '">back</a>';
    } else {
        echo '<a href="#" onClick="window.history.go(-1);">back</a>';
    }
    echo '</p>';
    exit;
}

// Admin: show error box
function ShowErrorBox($error)
{
    if (!is_array($error)) {
        $error = array($error);
    }
    $error_msg = '<ul>';
    foreach ($error as $err) {
        $error_msg .= "<li>$err</li>";
    }
    $error_msg .= '</ul>';
    echo '<div class="error">' . $error_msg . '</div>';
}

// Show <option> list for categories
function OptionCategories($selected_id = 0)
{
    global $db;
    $sql = "SELECT * FROM categories ORDER BY category_name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $category_id = $row["category_id"];
        $category_name = htmlspecialchars($row["category_name"]);

        if ($selected_id == $category_id) {
            echo '<option value="' . $category_id . '" selected>' . $category_name . '</option>';
        } else {
            echo '<option value="' . $category_id . '">' . $category_name . '</option>';
        }
    }
}

// HTML escaping
function Html2Text($html)
{
    return htmlspecialchars(stripslashes($html));
}

// Format price
function MoneyFormat($price)
{
    return number_format($price, 2, '.', ',');
}

/********************/
/* Initial program  */
/********************/

@session_start();

// Connect to database
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);

if (!$db) {
    die('<b>Failed to connect to database:</b> ' . mysqli_connect_error());
}

// Select database
mysqli_select_db($db, DB_NAME);
?>
