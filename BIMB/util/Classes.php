<?php
require_once "..//util/pdo.php";

class Dbclass{
    private $host = "localhost";
    private $user = "root";
    private $pwd = "";
    private $dbname = "billmanager";
    private $port = "3306";

    public function connect() {
        $dsn = 'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbname;
        $pdo = new PDO($dsn, $this->user, $this->pwd);
        // See the "errors" folder for details...
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

class payment_mode{
    function __construct($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM `payment_mode`');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $this->ret=[];
        foreach($rows as $row){
            $this->ret[$row[0]]=$row[1];
        }
    }
    function ret(){
        return $this->ret;
    }
}


class bill {
    public $billid = "";
    public $pdo="";

    function __construct($pdo,$billid) {
        $this->billid = $billid;
        $this->pdo = $pdo;
        $this->getinfo();
    }

    function getinfo(){
        $stmt = $this->pdo->prepare('SELECT s.supp_name, p.`pur_date`, p.`bill_no`, p.`total`, p.`pay_date`, pm.pay_mode ,`tax5`, p.`gst5`, p.`tax12`, p.`gst12`, p.`tax18`, p.`gst18`, p.`tax28`, p.`gst28`FROM `purchase` as p join suppliers as s on p.supp_id=s.supp_id join payment_mode as pm on p.pay_mode_id=pm.pay_mode_id Where pur_id=:pur_id LIMIT 1');
        $stmt->execute(array( ':pur_id' => $this->billid));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($row);
        $this->supp_name=$row['supp_name'];
        $this->pur_date=$row['pur_date'];
        $this->bill_no=$row['bill_no'];
        $this->total=$row['total'];
        $this->pay_date=$row['pay_date'];
        $this->pay_mode_id=$row['pay_mode'];
        $this->tax5=$row['tax5'];
        $this->gst5=$row['gst5'];
        $this->tax12=$row['tax12'];
        $this->gst12=$row['gst12'];
        $this->tax18=$row['tax18'];
        $this->gst18=$row['gst18'];
        $this->tax28=$row['tax28'];
        $this->gst28=$row['gst28'];
        $this->arrayinfo=$row;
    }

    function getdetails(){
        return $this->arrayinfo;
    }
}

$DbClassObj= new Dbclass();
$pdo=$DbClassObj->connect();

//$a=new payment_mode($pdo);
$atz=[];
$stmt = $pdo->prepare("SELECT `pur_id` FROM `purchase` WHERE pur_date BETWEEN '2020-04-01' and '2021-03-31'");
$stmt->execute(array());
$rows = $stmt->fetchall();

foreach($rows as $row){
    $b1= new bill($pdo,$row['pur_id']);
    array_push($atz,$b1);
}

foreach($atz as $a){
    echo($a->supp_name.' '.$a->pur_date.' '.$a->bill_no.'<br>');
}
?>