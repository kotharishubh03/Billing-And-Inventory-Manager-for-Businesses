<?php
require_once "..//util/pdo.php";

$stmt = $pdo->prepare('SELECT `bill_no` FROM `purchase` WHERE pay_date IS NULL and supp_id=(SELECT supp_id FROM suppliers WHERE supp_name=:supp_name Limit 1)');
$stmt->execute(array( ':supp_name' => $_GET['supp_name']));
$retval = array();

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['bill_no'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
