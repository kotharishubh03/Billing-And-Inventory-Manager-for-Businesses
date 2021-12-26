<?php
require_once "..//util/pdo.php";

$stmt = $pdo->prepare('SELECT `bill_no` FROM `purchase` WHERE `bill_no` LIKE :prefix');
$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
$retval = array();

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['bill_no'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
