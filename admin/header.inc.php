<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
  }
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>online shop admin</title>

<style>
<!--
 /* Global Styles */
 body {background-color: #fff; font-size: 15px; margin-left: 20px;} 
 p {text-align: left; } 
 a {color: #336699; padding: 25px; text-decoration: none;} 
 caption {font-size: 14px; font-weight: bold;}

 /* table format */
 TABLE.main {background: #999; border: 0px; font-size: 12px;} 
 TABLE.main TD {background-color: #FEFEFE; padding:2 5 5 2; height:20px;} 
 TABLE.main TH {background-color: #CCCCCC; padding-top: 4px; height: 22px;} 
 DIV.btnInsert {float: left; width:150px; border: #336699 solid 1px; padding: 15 0 15 0; background: #EFEFEF;} 
 DIV.submit {text-align: center; padding-top: 20px; height: 32px;} 
 DIV.error {text-align:left; background: #FFFFCC; border: #FF0000 solid 1px;} 
//-->
</style>
</head>
<body>
<br><center>
<div style="width:96%; text-align:center">
<!-- navigation bar -->
<h3 align=left>
 <a href="category.php">category management</a> 
 <a href="product.php">product management</a> 
 <a href="order.php">order management</a> 
 <a href="../index.php">exit</a>
</h3>