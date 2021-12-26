<?php
require_once "..//util/pdo.php";
if ($_REQUEST['term']=="a"){
    $stmt = $pdo->prepare('SELECT supp_name FROM suppliers order by supp_name');
    $stmt->execute(array());
    $retval = array();
}
else{
    $stmt = $pdo->prepare('SELECT supp_name FROM suppliers WHERE supp_name LIKE :prefix order by supp_name');
    $stmt->execute(array( ':prefix' => "%".$_REQUEST['term']."%"));
    $retval = array();
}
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['supp_name'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));
