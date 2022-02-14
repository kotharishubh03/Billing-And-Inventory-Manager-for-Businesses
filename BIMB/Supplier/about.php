<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";

    if (isset($_GET['fy'])) {
        DateOptionADD($_GET['fy'],0,'./index.php?fy='); //params 0-today or '2018'
    }
    else{
        DateOptionADD(0,0,'./index.php?fy=');
    }

    if(isset($_GET["supp_id"])) {
        $stmt = $pdo->prepare('SELECT `suppliers`.`supp_id`, `supp_name`, `gstno` FROM `suppliers` WHERE suppliers.supp_id=:supp_id');
        $stmt->execute(array(':supp_id' => $_GET['supp_id']));
        $SuppInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare('SELECT id, supp_pre_bal.amt FROM supp_pre_bal WHERE supp_pre_bal.fy_id=(SELECT `fy_id` FROM `financialyear` WHERE YEAR(`start_date`)=YEAR(:startdate) limit 1) and supp_id=:supp_id');
        $stmt->execute(array(':supp_id' => $_GET['supp_id'], ':startdate'=>date($startdate) ));
        $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row1==false) {$prebal=0;$prebal_pri=0;} 
        else {$prebal=$row1["amt"];
        $prebal_pri=$row1["id"];}
    }

    if(isset($_GET["edit"])) {
        $stmt = $pdo->prepare('UPDATE `suppliers` SET `supp_name`=:supp_name,`gstno`=:gstno WHERE `supp_id`=:supp_id');
        $stmt->execute(array( ':supp_id' => $_GET['edit'], ':supp_name' => $_GET['supp_name'] ,':gstno' => $_GET['gstno']));

        if ( $_GET["pre_bal"]!=0) {
            if ($_GET["prebal_pri"]!=0){
                $stmt = $pdo->prepare('UPDATE `supp_pre_bal` SET `amt`=:pre_bal WHERE `id`=:prebal_pri');
                $stmt->execute(array(':prebal_pri' => $_GET['prebal_pri'],':pre_bal' => $_GET['pre_bal']));
            } else{
                $stmt = $pdo->prepare('INSERT INTO `supp_pre_bal`(`supp_id`, `fy_id`, `amt`) VALUES (:supp_id,(SELECT `fy_id` FROM `financialyear` WHERE `start_date`=:startdate),:prebal)');
                $stmt->execute(array(':supp_id' => $_GET['edit'],':startdate' => date($startdate),':prebal' => $_GET['pre_bal']));
            } 
        }else {
            if ($_GET["prebal_pri"]!=0){    
                $stmt = $pdo->prepare('UPDATE `supp_pre_bal` SET `amt`=:pre_bal WHERE `id`=:prebal_pri');
                $stmt->execute(array(':prebal_pri' => $_GET['prebal_pri'],':pre_bal' => $_GET['pre_bal']));
            } 
        }
        print_r($_GET['FY']);
        $parm=$_GET['edit'].'&fy='.$_GET['fy'];
        unset($_GET['edit']);
        header("Location: about.php?supp_id=$parm");
        return;
    }

    $puramttotal=$prebal;
    $stmt = $pdo->prepare('SELECT `purchase`.`pur_id`, `purchase`.`pur_date` as dt, `purchase`.`bill_no`, `purchase`.`total` FROM `purchase`  where `purchase`.`supp_id`=:supp_id and `purchase`.`pur_date` BETWEEN :startdate and :enddate ORDER by `dt`');
    $stmt->execute(array( ':supp_id' => $_GET['supp_id'],':startdate'=>date($startdate), ':enddate'=>date($enddate)));
    $rowpur = $stmt->fetchall();
    $countpur = $stmt->rowCount();

    $payamttotal=0;
    $stmt = $pdo->prepare('SELECT payment_id,`suppliers_payment`.`date` as dt, pay_mode, chq_no,amount FROM `suppliers_payment` join payment_mode on payment_mode.pay_mode_id=suppliers_payment.pay_type WHERE `supp_id`=:supp_id and `suppliers_payment`.`date` BETWEEN :startdate and :enddate ORDER by `dt`');
    $stmt->execute(array( ':supp_id' => $_GET['supp_id'],':startdate'=>date($startdate), ':enddate'=>date($enddate)));
    $rowpay = $stmt->fetchall();
    $countpay = $stmt->rowCount();

    $stmt = $pdo->prepare('SELECT `pur_id`, `pur_date`, `bill_no`, `total` FROM `purchase` WHERE `supp_id`=:supp_id and pay_date IS NULL and pay_mode_id IS NULL order by pur_date');
    $stmt->execute(array( ':supp_id' => $_GET['supp_id']));
    $billpending = $stmt->fetchall();

    require_once "..//util/header.php";
?>
        <title>Suppliers</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(2,"Suppliers") ?>
        <!-- items grid -->
        <div class="w3-row-padding">
            <a href="./index.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">&larr; Home</a>
            <select class="w3-col s5 w3-select w3-xlarge w3-border w3-margin" name="option" onchange="location = this.value;">
                <?php
                    if (isset($_GET['fy'])) {
                        $_SESSION['fy']=$_GET['fy'];
                        DateOptionADD($_GET['fy'],1,'./about.php?supp_id='.$_GET['supp_id'].'&fy='); //params 0-today or '2018'
                        $_GET["FY"]=$FY;
                    }
                    else{
                        DateOptionADD(0,1,'./about.php?supp_id='.$_GET['supp_id'].'&fy=');
                        $_GET["FY"]=$FY;
                    } 
                ?>
            </select>
        </div>
        <div class="w3-col w3-padding">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3><?php echo($SuppInfo["supp_name"]); echo('('.$SuppInfo["gstno"].')');?></h3>
                </header>
            </div>
        </div>
        <div class="w3-col w3-padding">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>Edit Supplier </h3>
                </header>
                <div class="w3-container">
                    <form name='edit' method="get">
                        <div class="w3-col m4 w3-padding">
                            <input class="w3-input w3-hide" type="text" name="prebal_pri" value="<?=$prebal_pri?>" />
                            <?php 
                            if (isset($_GET['fy'])) {
                            echo('<input class="w3-input w3-hide" type="text" name="fy" value="'.$_GET['fy'].'" />');
                            } ?>
                            <label for="s_name">Supplier name:</label>
                            <input class="w3-input" type="text" name="supp_name" size="30" value="<?=$SuppInfo['supp_name']?>" required/>
                        </div>
                        <div class="w3-col m4 w3-padding">
                            <label for="s_gst_no">GST NO.:</label>
                            <input class="w3-input" type="text" name="gstno" size="30" value="<?=$SuppInfo['gstno']?>" required/>
                        </div>
                        <div class="w3-col m4 w3-padding">
                            <label for="pre_bal">Previous Balance:</label>
                            <input class="w3-input" type="text" name="pre_bal" size="30" value="<?=$prebal?>" required/>
                        </div><br>
                        <button class="w3-button w3-block w3-dark-grey" type="submit" name="edit" value="<?=$SuppInfo['supp_id']?>" >Edit </button>
                    </form>
                </div>
            </div>
        </div><br>
        <div class="w3-col w3-padding">
            <div class="w3-container w3-border">
                <div class="w3-responsive  w3-margin-bottom">
                    <h3>LEDGER</h3>
                    <table class="w3-table-all w3-small sk-table">
                        <thead>
                            <tr>
                                <th colspan="4"><h4>PURCHASE</h4></th>
                                <th></th>
                                <th colspan="4"><h4>PAYMENT</h4></th>
                                <th><h4>Balance</h4></th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Bill No.</th>
                                <th>Amount</th>
                                <th></th>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Chq No. / UTR No.</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                        <tr class="w3-yellow">
                            <td></td><td><b>by bal c/f</b></td>
                            <td></td><td><b><?=$prebal?></b></td>
                            <td></td><td></td><td></td><td></td><td></td>
                        </tr>
                        <?php 
                            if ($countpay>$countpur){
                                $maxline=$countpay;
                            } else{
                                $maxline=$countpur;
                            }

                            for ($i=0;$i<$maxline;$i++){
                                echo('<tr>');
                                if ($i<$countpur){
                                    $r=$rowpur[$i];
                                    $puramttotal=$r["total"]+$puramttotal;
                                    echo('
                                        <td><b>'.date("d-m-Y", strtotime($r["dt"])).'</b></td>
                                        <td><b>By Purchase</b></td>
                                        <td><b><a href="../purchase/about.php?pur_id='.$r["pur_id"].'">'.$r["bill_no"].'</a></b></td>
                                        <td><b>'.$r["total"].'</b></td>
                                        ');
                                } else {
                                    echo('<td></td> <td></td> <td></td> <td></td>');
                                }
                                
                                echo('<td></td>');

                                if ($i<$countpay){
                                    $r=$rowpay[$i];
                                    $payamttotal=$r["amount"]+$payamttotal;
                                    echo('
                                    <td><b>'.date("d-m-Y", strtotime($r["dt"])).'</b></td>
                                    <td><b>'.$r["pay_mode"].'</b></td>
                                    <td><b><a href="../payment/about.php?pay_id='.$r["payment_id"].'">'.$r["chq_no"].'</a></b></td>
                                    <td><b>'.$r["amount"].'</b></td>');
                                } else {
                                    echo('<td></td> <td></td> <td></td> <td></td>');
                                }

                                echo('</tr>');
                            }
                        ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="9"></td>
                            </tr>
                            <tr class="w3-yellow">
                                <td colspan="3"><b>Total</b></td><td><b><?=$puramttotal?></b></td>
                                <td></td>
                                <td colspan="3"><b>Total</b></td><td><b><?=$payamttotal?></b></td>
                                <td><b><?=$puramttotal-$payamttotal?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="w3-col w3-padding">
            <div class="w3-container w3-border">
                <div class="w3-responsive  w3-margin-bottom">
                    <h3>Bill Payment Pending</h3>
                    <table class="w3-table-all w3-small sk-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Bill No</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalrem=0;
                                foreach ($billpending as $r){
                                    $totalrem=$totalrem+$r["total"];
                                    echo('<tr>
                                    <td><b>'.date("d-m-Y", strtotime($r["pur_date"])).'</b></td>
                                    <td><b><a href="../purchase/about.php?pur_id='.$r["pur_id"].'">'.$r["bill_no"].'</a></b></td>
                                    <td><b>'.$r["total"].'</b></td>
                                    </tr>');
                                }
                                echo('<tr class="w3-yellow"><td colspan="2"><b>TOTAL</b></td><td><b>'.$totalrem.'</b></td></tr>');
                            ?>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
