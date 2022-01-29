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

    require_once "..//util/header.php";
?>
        <title>Payments</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(4,"Payments") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">Add New Payment</a>
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
        <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>Payments</h3>
                </header>
                <div class="w3-container"><br>
                <table class="w3-table-all w3-small w3-centered sk-table">
                    <tr><th>Supplier Name</th><th>Date</th><th>Payment mode</th><th>Chq no</th><th>Amount</th></tr>
                    <?php
                        $amt=0;
                        $stmt = $pdo->prepare('SELECT `payment_id`, `pay_mode`, suppliers.`supp_id`, `supp_name`, `amount`, suppliers_payment.`date`, `chq_no` FROM `suppliers_payment` join suppliers on suppliers.supp_id=suppliers_payment.supp_id join payment_mode on payment_mode.pay_mode_id=suppliers_payment.pay_type WHERE `suppliers_payment`.`date` BETWEEN :startdate and :enddate ORDER by `suppliers_payment`.`date`');
                        $stmt->execute(array(':startdate'=>date($startdate), ':enddate'=>date($enddate)));
                        $row=$stmt->fetchall();
                        foreach ($row as $r){
                            echo('<tr>
                            <td><b><a href="../supplier/about.php?supp_id='.$r['supp_id'].'">'.$r['supp_name'].'</a></b></td>
                            <td>'.date("d-m-Y", strtotime($r['date'])).'</td>
                            <td>'.$r['pay_mode'].'</td>
                            <td><b><a href="../payment/about.php?pay_id='.$r['payment_id'].'">'.$r['chq_no'].'</a></b></td>
                            <td>'.$r['amount'].'</td>
                            </tr>');
                            $amt=$amt+$r['amount'];
                        }
                    ?><tr><td colspan="6"></td><tr>
                    <tr class="w3-yellow">
                        <td colspan="4"><b>TOTAL</b></td>
                        <td><b><?php echo($amt);?></b></td>
                    </tr>
                </table>
                </div><br>
            </div>
        <div class="w3-row-padding">

        </div>
        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
