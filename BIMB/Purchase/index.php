<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php"; 
?>
    
<?php
    require_once "..//util/header.php";
?>
        <title>Purchase</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(0,"Purchase") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">Add New Purchase</a>
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

        <div class="w3-row w3-margin">
            <?php
                $grand=[0,0,0,0,0,0,0,0,0];
                $quaterly=[0,0,0,0,0,0,0,0,0];
                $month_variable=[4,5,6,7,8,9,10,11,12,1,2,3];
                $month_string=["April","May","June","July","August","September","October","November","December","January","February","March"];
                for ($i=0;$i<12;$i++) {
                    $monthly=[0,0,0,0,0,0,0,0,0];
                    $stmt = $pdo->prepare('SELECT `purchase`.`supp_id`,`suppliers`.`supp_name`, `purchase`.`pur_date` as pdate, `purchase`.`pur_id`, `purchase`.`bill_no`, `purchase`.`total`, `purchase`.`tax5`, `purchase`.`gst5`, `purchase`.`tax12`, 
                    `purchase`.`gst12`, `purchase`.`tax18`,`purchase`.`gst18`, `purchase`.`tax28`, `purchase`.`gst28` , `payment_mode`.`pay_mode` , `purchase`.`pay_date` FROM `purchase` join `suppliers` on `purchase`.`supp_id` = `suppliers`.`supp_id` 
                    left JOIN payment_mode on payment_mode.pay_mode_id=purchase.pay_mode_id WHERE Month(`purchase`.`pur_date`)=:mont and `purchase`.`pur_date` BETWEEN :startdate and :enddate ORDER BY `purchase`.`pur_date` ASC');
                    $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
                    $row = $stmt->fetchall();
                    if ( $row == false ) {
                    } else {
                        echo('<h3>'.$month_string[$i].' ('.$_GET["FY"].')'.'</h3>');
                        echo('<div class="w3-responsive"><table class="w3-table-all w3-small w3-centered sk-table">');
                        echo('<tr><th>Supplier Name</th><th>Date</th><th>Bill No.</th><th>Total</th><th>TAXABLE</th><th>sgst-2.5%</th><th>cgst-2.5%</th><th>TAXABLE</th><th>sgst-6%</th><th>cgst-6%</th><th>TAXABLE</th><th>sgst-9%</th><th>cgst-9%</th><th>TAXABLE</th><th>sgst-14%</th><th>cgst-14%</th><th>Payment Mode</th><th>date</th></tr>');
                        foreach($row as $r) {
                            if ($r["pay_date"]==NULL){
                                $billpay_date='';
                            } else {
                                $billpay_date=date("d-m-Y", strtotime($r["pay_date"]));
                            }
                            echo('<tr><td><a href="../supplier/about.php?supp_id='.$r["supp_id"].'">'.$r["supp_name"].'</a></td><td>'.date("d-m-Y", strtotime($r["pdate"])).'</td><td><b><a href="../purchase/about.php?pur_id='.$r["pur_id"].'">'.$r["bill_no"].'</a></b></td><td><b>'.$r["total"].'</b></td><td>'.$r["tax5"].'</td><td>'.$r["gst5"].'</td><td>'.$r["gst5"].'</td>
                            <td>'.$r["tax12"].'</td><td>'.$r["gst12"].'</td><td>'.$r["gst12"].'</td><td>'.$r["tax18"].'</td><td>'.$r["gst18"].'</td><td>'.$r["gst18"].'</td><td>'.$r["tax28"].'</td><td>'.$r["gst28"].'</td><td>'.$r["gst28"].'</td><td>'.$r["pay_mode"].'</td><td>'.$billpay_date.'</td></tr>');
                            
                            $monthly[0]=$r["total"]+$monthly[0];
                            $monthly[1]=$r["tax5"]+$monthly[1];
                            $monthly[2]=$r["gst5"]+$monthly[2];
                            $monthly[3]=$r["tax12"]+$monthly[3];
                            $monthly[4]=$r["gst12"]+$monthly[4];
                            $monthly[5]=$r["tax18"]+$monthly[5];
                            $monthly[6]=$r["gst18"]+$monthly[6];
                            $monthly[7]=$r["tax28"]+$monthly[7];
                            $monthly[8]=$r["gst28"]+$monthly[8];
                        }

                        $quaterly[0]=$monthly[0]+$quaterly[0];
                        $quaterly[1]=$monthly[1]+$quaterly[1];
                        $quaterly[2]=$monthly[2]+$quaterly[2];
                        $quaterly[3]=$monthly[3]+$quaterly[3];
                        $quaterly[4]=$monthly[4]+$quaterly[4];
                        $quaterly[5]=$monthly[5]+$quaterly[5];
                        $quaterly[6]=$monthly[6]+$quaterly[6];
                        $quaterly[7]=$monthly[7]+$quaterly[7];
                        $quaterly[8]=$monthly[8]+$quaterly[8];

                        echo('<tr class="w3-yellow"><th colspan="3">TOTAL</th><th>'.$monthly[0].'</th><th>'.$monthly[1].'</th><th>'.$monthly[2].'</th><th>'.$monthly[2].'</th><th>'.$monthly[3].'</th><th>'.$monthly[4].'</th>
                        <th>'.$monthly[4].'</th><th>'.$monthly[5].'</th><th>'.$monthly[6].'</th><th>'.$monthly[6].'</th><th>'.$monthly[7].'</th><th>'.$monthly[8].'</th><th>'.$monthly[8].'</th><th></th><th></th></tr>');
                        echo('</table></div>');
                        if ($month_variable[$i]%3==0){
                            echo('<br><div class="w3-responsive"><table class="w3-table-all w3-small w3-centered sk-table">');
                            echo('<tr><th></th><th>Total</th><th>TAXABLE</th><th>sgst-2.5%</th><th>cgst-2.5%</th><th>TAXABLE</th><th>sgst-6%</th><th>cgst-6%</th><th>TAXABLE</th><th>sgst-9%</th><th>cgst-9%</th><th>TAXABLE</th><th>sgst-14%</th><th>cgst-14%</th></tr>');
                            echo('<tr class="w3-yellow"><th>QTR-'.((int) ($i/3)+1).' TOTAL</th><th>'.$quaterly[0].'</th><th>'.$quaterly[1].'</th><th>'.$quaterly[2].'</th><th>'.$quaterly[2].'</th><th>'.$quaterly[3].'</th><th>'.$quaterly[4].'</th>
                            <th>'.$quaterly[4].'</th><th>'.$quaterly[5].'</th><th>'.$quaterly[6].'</th><th>'.$quaterly[6].'</th><th>'.$quaterly[7].'</th><th>'.$quaterly[8].'</th><th>'.$quaterly[8].'</th></tr>');
                            echo('</table></div>');

                            $grand[0]=$quaterly[0]+$grand[0];
                            $grand[1]=$quaterly[1]+$grand[1];
                            $grand[2]=$quaterly[2]+$grand[2];
                            $grand[3]=$quaterly[3]+$grand[3];
                            $grand[4]=$quaterly[4]+$grand[4];
                            $grand[5]=$quaterly[5]+$grand[5];
                            $grand[6]=$quaterly[6]+$grand[6];
                            $grand[7]=$quaterly[7]+$grand[7];
                            $grand[8]=$quaterly[8]+$grand[8];

                            $quaterly=[0,0,0,0,0,0,0,0,0];
                        }
                    }
                }
                if ($grand[0]!=0){
                echo('<br><div class="w3-responsive"><table class="w3-table-all w3-small w3-centered sk-table">');
                echo('<tr><th></th><th>Total</th><th>TAXABLE</th><th>sgst-2.5%</th><th>cgst-2.5%</th><th>TAXABLE</th><th>sgst-6%</th><th>cgst-6%</th><th>TAXABLE</th><th>sgst-9%</th><th>cgst-9%</th><th>TAXABLE</th><th>sgst-14%</th><th>cgst-14%</th></tr>');
                echo('<tr class="w3-yellow"><th>GRAND TOTAL</th><th>'.$grand[0].'</th><th>'.$grand[1].'</th><th>'.$grand[2].'</th><th>'.$grand[2].'</th><th>'.$grand[3].'</th><th>'.$grand[4].'</th>
                <th>'.$grand[4].'</th><th>'.$grand[5].'</th><th>'.$grand[6].'</th><th>'.$grand[6].'</th><th>'.$grand[7].'</th><th>'.$grand[8].'</th><th>'.$grand[8].'</th></tr>');
                echo('</table></div>');
                }
                else{
                    echo('<div class="w3-panel w3-blue">
                    <h3>No Record\'s Found!</h3>
                    <p>You Dont have any record For this Financial Year.</p>
                  </div>');
                }
            ?>
        </div>
        <br>
        <?php require_once "../util/footer.php"; ?>
    </body>
</html>
