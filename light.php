<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Syrup-->
<!-- * Date: 16/4/25-->
<!-- * Time: 下午4:29-->
<!-- */-->

<!DOCTYPE html>
<?php
 session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>StockAssistant Search</title>
    <link rel="stylesheet" href="stylesheets/search.css" type="text/css" />
    <?php include_once 'head.php' ?>
    <script type="text/javascript" src='https://www.google.com/jsapi?autoload={"modules":[{"name":"visualization","version":"1","packages":["annotationchart"]}]}'></script>
    <script type="text/javascript" src="js/charts_search.js"></script>
</head>
<body>
<?php include_once("header.php"); ?>
<div id="container">
    companies :id + name who have the average stock price lesser than the lowest of Google in the latest one year
</div>
<?php include_once('footer.php'); ?>
</body>
</html>

