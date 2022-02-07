<?php
require_once "..//util/pdo.php";
if ($_REQUEST['term']=="all"){
    $stmt = $pdo->prepare('SELECT `cus_name` FROM `customers` ORDER BY `cus_name`');
    $stmt->execute(array());
    $retval = array();
}
else{
    $stmt = $pdo->prepare('SELECT `cus_name` FROM `customers` WHERE cus_name LIKE :prefix ORDER BY `cus_name`');
    $stmt->execute(array( ':prefix' => "%".$_REQUEST['term']."%"));
    $retval = array();
}
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['cus_name'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
