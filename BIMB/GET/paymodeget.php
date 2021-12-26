<?php
require_once "..//util/pdo.php";

$stmt = $pdo->prepare('SELECT `type` FROM `pay_type_id` WHERE `type` LIKE :prefix');
$stmt->execute(array( ':prefix' => "%".$_REQUEST['term']."%"));
$retval = array();

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['type'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
