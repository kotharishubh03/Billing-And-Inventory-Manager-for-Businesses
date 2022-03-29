<?php
require_once "..//util/pdo.php";

$stmt = $pdo->prepare('SELECT `prd_id` FROM `products` WHERE `prd_name` like :prefix LIMIT 1');
$stmt->execute(array( ':prefix' => '%'.$_REQUEST['term'].'%'));
$retval = array();

while ( $row = $stmt->fetch() ) {
    $retval[] = $row['prd_id'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
