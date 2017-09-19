<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <link rel="stylesheet" type="text/css" href="/css/login.css">
    <link rel="stylesheet" type="text/css" href="/css/content.css">
    <link rel="stylesheet" type="text/css" href="/css/board.css">
    <link rel="stylesheet" type="text/css" href="/css/board_view.css">
    <link rel="stylesheet" type="text/css" href="/css/board_write.css">
</head>
<body>
<div id="wrap">
    <nav id="nav_topMenu">
        <?php require "../views/nav/topMenu.php"; ?>
    </nav>
    <div id="header">
        <?php
        require "../views/header/titleLogo.php";
        ?>
    </div> <!-- end of header -->

    <nav id="nav_menu">
        <?php
        require "../views/nav/menu.php";
        ?>
    </nav> <!-- end of nav_menu -->

    <div id="content">
        <?=$_content?>
    </div> <!-- end of content -->
</div> <!-- end of wrap -->
</body>
</html>