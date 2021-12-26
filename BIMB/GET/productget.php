<?php
require_once "..//util/pdo.php";

$stmt = $pdo->prepare('SELECT prd_name FROM products WHERE prd_name LIKE :prefix');
$stmt->execute(array( ':prefix' => "%".$_REQUEST['term']."%"));
$retval = array();

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['prd_name'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
