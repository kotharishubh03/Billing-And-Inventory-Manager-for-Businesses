<?php
require_once "..//util/pdo.php";

if ($_REQUEST['term']=='all'){
    $stmt = $pdo->prepare('SELECT `pay_mode` FROM `payment_mode`');
    $stmt->execute(array());
}
else{
$stmt = $pdo->prepare('SELECT `pay_mode` FROM `payment_mode` WHERE `pay_mode` LIKE :prefix');
$stmt->execute(array( ':prefix' => "%".$_REQUEST['term']."%"));
}
$retval = array();

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['pay_mode'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
