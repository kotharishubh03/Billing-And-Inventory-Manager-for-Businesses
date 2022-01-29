<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";

    if (isset($_GET['fy'])) {
        $_SESSION['fy']=$_GET['fy'];
        DateOptionADD($_GET['fy'],0,'./index.php?fy='); //params 0-today or '2018'
        $_GET["FY"]=$FY;
    }
    else{
        DateOptionADD(0,0,'./index.php?fy=');
        $_GET["FY"]=$FY;
    }

    
    $payarr=[];
    $stmt = $pdo->prepare('SELECT suppliers_payment.supp_id,sum(suppliers_payment.amount) as "total payed" FROM suppliers_payment WHERE suppliers_payment.date BETWEEN :startdate and :enddate group by suppliers_payment.supp_id');
    $stmt->execute(array(':startdate'=>date($startdate), ':enddate'=>date($enddate)));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $payarr[$r["supp_id"]]=$r["total payed"];
    }
    $prebalarr=[];
    $stmt = $pdo->prepare('SELECT `supp_id`, `amt` FROM `supp_pre_bal` WHERE `fy_id`=(SELECT `fy_id` from `financialyear` where `financialyear`.`start_date`= :startdate)');
    $stmt->execute(array(':startdate'=>date($startdate)));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prebalarr[$r["supp_id"]]=$r["amt"];
    }

    $purchasearr=[];
    $stmt = $pdo->prepare('SELECT `purchase`.`supp_id`, sum(`purchase`.`total`) as "Total Purchased" FROM `purchase` Where `purchase`.`pur_date` BETWEEN :startdate and :enddate GROUP BY `purchase`.`supp_id`');
    $stmt->execute(array(':startdate'=>date($startdate), ':enddate'=>date($enddate)));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $purchasearr[$r["supp_id"]]=$r["Total Purchased"];
    }

    $stmt = $pdo->prepare('SELECT `suppliers`.`supp_id`,`suppliers`.`supp_name`,`suppliers`.`gstno` FROM `suppliers` order by `supp_name`');
    $stmt->execute(array());
    $row = $stmt->fetchall();

    require_once "..//util/header.php";
?>
        <title>Suppliers</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(2,"Suppliers") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">Add New Supplier</a>
            <select class="w3-col s5 w3-select w3-xlarge w3-border w3-margin" name="option" onchange="location = this.value;">
                <?php
                    if (isset($_GET['fy'])) {
                        $_SESSION['fy']=$_GET['fy'];
                        DateOptionADD($_GET['fy'],1,'./index.php?fy='); //params 0-today or '2018'
                        $_GET["FY"]=$FY;
                    }
                    else{
                        DateOptionADD(0,1,'./index.php?fy=');
                        $_GET["FY"]=$FY;
                    } 
                ?>
            </select>
        </div>

        <div class="w3-row w3-padding">
            <div class="w3-responsive">
                <table class="w3-table-all w3-small sk-table">
                <tr>
                    <th>Supplier Name</th>
                    <th>GST No.</th>
                    <th>Previous Balance</th>
                    <th>Total Purchased</th>
                    <th>Total Payed</th>
                    <th>Balance remaining</th>
                </tr>
                <?php
                #var_dump($payarr);
                $grandarray=[0,0,0,0];
                    foreach ($row as $r) {
                        
                        if (array_key_exists($r["supp_id"],$payarr)){$Payed=$payarr[$r["supp_id"]];} 
                        else {$Payed=0;}
                        
                        if (array_key_exists($r["supp_id"],$prebalarr)){$prebal=$prebalarr[$r["supp_id"]];} 
                        else {$prebal=0;}
                        
                        if (array_key_exists($r["supp_id"],$purchasearr)){$Purchased=$purchasearr[$r["supp_id"]];} 
                        else {$Purchased=0;}
                        
                        $bal=$Purchased+$prebal-$Payed;
                        if ($prebal==0 & $Payed==0 & $Purchased==0 & $bal==0) {}
                        else {
                            echo('<tr>
                                <td><b><a href="../supplier/about.php?supp_id='.$r["supp_id"].'">'.$r["supp_name"].'</a></b></td>
                                <td><b>'.$r["gstno"].'</b></td>
                                <td><b>'.$prebal.'</b></td>
                                <td><b>'.$Purchased.'</b></td>
                                <td><b>'.$Payed.'</b></td>
                                <td><b>'.$bal.'</b></td>
                                </tr>');
                                $grandarray[0]=intval($prebal)+$grandarray[0];
                                $grandarray[1]=intval($Purchased)+$grandarray[1];
                                $grandarray[2]=intval($Payed)+$grandarray[2];
                                $grandarray[3]=intval($bal)+$grandarray[3];
                                //var_dump($grandarray);
                        }
                    }
                    //print_r($grandarray);
                    echo('<tr><td colspan="6"></td></tr>
                    <tr class="w3-yellow">
                        <td colspan="2">Total</td>
                        <td><b>'.$grandarray[0].'</b></td>
                        <td><b>'.$grandarray[1].'</b></td>
                        <td><b>'.$grandarray[2].'</b></td>
                        <td><b>'.$grandarray[3].'</b></td>
                    </tr>');
                ?>
                </table>
            </div>
        </div><br>

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
