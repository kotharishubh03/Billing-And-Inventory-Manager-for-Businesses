<?php
    class Dbclass{
        private $host = "localhost";
        private $user = "root";
        private $pwd = "";
        private $dbname = "sarvodaya";
        private $port = "3306";

        public function connect() {
            $dsn = 'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbname;
            $pdo = new PDO($dsn, $this->user, $this->pwd);
            // See the "errors" folder for details...
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
    }

    class suppliers {
        public $pdo="";
        public $Name="";
        public $GstNo="";
        public $Id = 0;
        public $Date_Added = "";
        public $purchase=[];
        
        function __construct($pdo,$id,$name,$gstno,$date_added) {
            $this->pdo=$pdo;
            $this->Name = $name;
            $this->GstNo=$gstno;
            $this->Id = $id;
            $this->Date_Added = $date_added;
        }

        # Setter's
        function SetName($name){
            $this->Name=$name;
        }
        
        function SetGstNo($gstno){
            $this->GstNo=$gstno;
        }

        // GETER's
        function GetName(){
            return $this->Name;
        }
        
        function GetGstNo(){
            return $this->GstNo;
        }

        function SupplierDBUpdate() {
            $sql = "UPDATE `suppliers` SET `s_name`=:s_name,`s_gst_no`=:gstno,`date_added`=:date_added WHERE `s_id`=:id";
            $stmt =$this->pdo->prepare($sql);
            $stmt->execute(array(':s_name'=>$this->Name,':gstno'=>$this->GstNo,':id'=>$this->Id,':date_added'=>$this->Date_Added));

        }
    }

    class bills {
        public $id = "";
        public $supplier_id = "";
        public $totalammount = "";
        public $Db = "";
        public $Billno ="";
    }
    #$DbClassObj= new Dbclass();
    #pdo=$DbClassObj->connect();
    #$supplier = new suppliers($pdo,1,'Aaijee Enterprises','27AETPC8819E1Z7','2021-01-17');
    #$supplier->SetName('Aaijee Enterprises');
    #$supplier->SupplierDBUpdate();
?>