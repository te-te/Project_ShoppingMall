<?php

require '../bootstrap.php';
require '../ShopApp.php';

$app = new ShopApp(true);  // error 출력 여부 (true-표시, false-미표시)
$app->run();

?>