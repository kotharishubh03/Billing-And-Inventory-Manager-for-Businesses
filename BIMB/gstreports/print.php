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

    switch ($_GET['qtr']) {
        case 0:
            $month_variable=[4,5,6,7,8,9,10,11,12,1,2,3];
            $month_string=["April","May","June","July","August","September","October","November","December","January","February","March"];
            $qtr="";
            break;
        case 1:
            $month_variable=[4,5,6];
            $month_string=["April","May","June"];
            $qtr="(QTR-1)";
            break;
        case 2:
            $month_variable=[7,8,9];
            $month_string=["July","August","September"];
            $qtr="(QTR-2)";
            break;
        case 3:
            $month_variable=[10,11,12];
            $month_string=["October","November","December"];
            $qtr="(QTR-3)";
            break;
        case 4:
            $month_variable=[1,2,3];
            $month_string=["January","February","March"];
            $qtr="(QTR-4)";
            break;
    }

    $stmt = $pdo->prepare('SELECT * FROM `shop_info`');
    $stmt->execute(array());
    $temp = $stmt->fetchall();
    $shopinfo=array();
    foreach($temp as $tem){
        $shopinfo[$tem['s_key']]=$tem['value'];
    }

require_once "..//util/header.php";
?>
<title>GST REPORTS</title>
<style>
    @media print{
        @page {size: landscape};
    }
@media print
{
  table { page-break-after:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
  td    { page-break-inside:avoid; page-break-after:auto }
  thead { display:table-header-group }
  tfoot { display:table-footer-group }
}
</style>

</head>

<body class="w3-content w3-margin">

    <!-- items grid -->
    <table class="sk-table w3-table w3-small" >
        <tr>
            <td colspan="19" class="w3-center"><b class="w3-medium"><?=$shopinfo['shop_name']?> </b><span class="w3-medium">(PURCHASE REPORT) (<?=$_GET['fy']?>-<?=$_GET['fy']+1?>) <?=$qtr?></span></td>
        </tr>
        <tr>
            <td colspan="19" class="sk-no-border"></td>
        </tr>
        <?php
            $grand=[0,0,0,0,0,0,0,0,0];
            $quaterly=[0,0,0,0,0,0,0,0,0];
            for ($i=0;$i<count($month_variable);$i++) {
                $monthly=[0,0,0,0,0,0,0,0,0];
                $stmt = $pdo->prepare('SELECT `purchase`.`supp_id`,`suppliers`.`supp_name`,`suppliers`.`gstno`, `purchase`.`pur_date` as pdate, `purchase`.`pur_id`, `purchase`.`bill_no`, `purchase`.`total`, `purchase`.`tax5`, `purchase`.`gst5`, `purchase`.`tax12`, 
                    `purchase`.`gst12`, `purchase`.`tax18`,`purchase`.`gst18`, `purchase`.`tax28`, `purchase`.`gst28` , `payment_mode`.`pay_mode` , `purchase`.`pay_date` FROM `purchase` join `suppliers` on `purchase`.`supp_id` = `suppliers`.`supp_id` 
                    left JOIN payment_mode on payment_mode.pay_mode_id=purchase.pay_mode_id WHERE Month(`purchase`.`pur_date`)=:mont and `purchase`.`pur_date` BETWEEN :startdate and :enddate ORDER BY `purchase`.`pur_date` ASC');
                $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
                    $row = $stmt->fetchall();
                if ( $row == false ) {
                } else {
                    echo('<tr><td colspan="19" class="sk-no-border"><b class="w3-medium">'.$month_string[$i].'<span class="w3-medium"> ('.$_GET['fy'].'-'.($_GET['fy']+1).')</span></b></td></tr>');
                    echo('<tr><th>Supplier Name</th><th>Date</th><th>Gst No.</th><th>Bill No.</th><th>Total</th><th>TAXABLE</th><th>sgst-2.5%</th><th>cgst-2.5%</th><th>TAXABLE</th><th>sgst-6%</th><th>cgst-6%</th><th>TAXABLE</th><th>sgst-9%</th><th>cgst-9%</th><th>TAXABLE</th><th>sgst-14%</th><th>cgst-14%</th><th>Payment Mode</th><th>date</th></tr>');
                    foreach($row as $r) {
                        if ($r["pay_date"]==NULL){
                            $billpay_date='';
                        } else {
                            $billpay_date=date("d-m-Y", strtotime($r["pay_date"]));
                        }
                        echo('<tr><td>'.$r["supp_name"].'</td><td>'.date("d-m-Y", strtotime($r["pdate"])).'</td><td>'.$r["gstno"].'</td><td>'.$r["bill_no"].'</td><td><b>'.$r["total"].'</b></td><td>'.$r["tax5"].'</td><td>'.$r["gst5"].'</td><td>'.$r["gst5"].'</td>
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
                
                    echo('<tr class="w3-yellow"><th colspan="4">TOTAL</th><th>'.$monthly[0].'</th><th>'.$monthly[1].'</th><th>'.$monthly[2].'</th><th>'.$monthly[2].'</th><th>'.$monthly[3].'</th><th>'.$monthly[4].'</th>
                            <th>'.$monthly[4].'</th><th>'.$monthly[5].'</th><th>'.$monthly[6].'</th><th>'.$monthly[6].'</th><th>'.$monthly[7].'</th><th>'.$monthly[8].'</th><th>'.$monthly[8].'</th><th></th><th></th></tr>');
                    echo('<tr><td colspan="19" class="sk-no-border"></td></tr>');

                    $quaterly[0]=$monthly[0]+$quaterly[0];
                    $quaterly[1]=$monthly[1]+$quaterly[1];
                    $quaterly[2]=$monthly[2]+$quaterly[2];
                    $quaterly[3]=$monthly[3]+$quaterly[3];
                    $quaterly[4]=$monthly[4]+$quaterly[4];
                    $quaterly[5]=$monthly[5]+$quaterly[5];
                    $quaterly[6]=$monthly[6]+$quaterly[6];
                    $quaterly[7]=$monthly[7]+$quaterly[7];
                    $quaterly[8]=$monthly[8]+$quaterly[8];

                    if ($month_variable[$i]%3==0){
                        echo('<tr><th colspan="4"></th><th>Total</th><th>TAXABLE</th><th>sgst-2.5%</th><th>cgst-2.5%</th><th>TAXABLE</th><th>sgst-6%</th><th>cgst-6%</th><th>TAXABLE</th><th>sgst-9%</th><th>cgst-9%</th><th>TAXABLE</th><th>sgst-14%</th><th>cgst-14%</th><th colspan="2"></th></tr>');
                        echo('<tr class="w3-yellow"><th colspan="4">QTR-'.((int) ($month_variable[$i]/3)-1).' TOTAL</th><th>'.$quaterly[0].'</th><th>'.$quaterly[1].'</th><th>'.$quaterly[2].'</th><th>'.$quaterly[2].'</th><th>'.$quaterly[3].'</th><th>'.$quaterly[4].'</th>
                            <th>'.$quaterly[4].'</th><th>'.$quaterly[5].'</th><th>'.$quaterly[6].'</th><th>'.$quaterly[6].'</th><th>'.$quaterly[7].'</th><th>'.$quaterly[8].'</th><th>'.$quaterly[8].'</th><th colspan="2"></th></tr>');
                        echo('<tr><td colspan="19" class="sk-no-border"></td></tr>');
                    }

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
            if ($grand[0]!=0){
                if ($_GET['qtr']!=0) {
                    echo('</table>');
                }
                else{
                    echo('<tr><th colspan="4"></th><th>Total</th><th>TAXABLE</th><th>sgst-2.5%</th><th>cgst-2.5%</th><th>TAXABLE</th><th>sgst-6%</th><th>cgst-6%</th><th>TAXABLE</th><th>sgst-9%</th><th>cgst-9%</th><th>TAXABLE</th><th>sgst-14%</th><th>cgst-14%</th><th colspan="2"></th></tr>');
                    echo('<tr class="w3-yellow"><th colspan="4">GRAND TOTAL</th><th>'.$grand[0].'</th><th>'.$grand[1].'</th><th>'.$grand[2].'</th><th>'.$grand[2].'</th><th>'.$grand[3].'</th><th>'.$grand[4].'</th>
                    <th>'.$grand[4].'</th><th>'.$grand[5].'</th><th>'.$grand[6].'</th><th>'.$grand[6].'</th><th>'.$grand[7].'</th><th>'.$grand[8].'</th><th>'.$grand[8].'</th><th colspan="2"></th></tr>');
                    echo('</table>');
                }
            }
            else{
                echo('<div class="w3-panel w3-blue">
                <h3>No Record\'s Found!</h3>
                <p>You Dont have any record For this Financial Year or QTR.</p>
              </div>');
            }
        ?>
        <br>
    <table class="sk-table w3-table w3-small w3-centered">
        <tr>
            <td colspan="3" class="w3-center"><b class="w3-medium"><?=$shopinfo['shop_name']?> </b><span class="w3-medium">(SALES REPORT) (<?=$_GET['fy']?>-<?=$_GET['fy']+1?>) <?=$qtr?></span></td>
        </tr>
        <tr><td colspan="3" class="sk-no-border"></td></tr>
            <?php
                if(isset($_GET['qtr'])){
                    echo('<tr><th class="w3-medium">Month</th><th class="w3-medium">Total Sales</th><th class="w3-medium">GST to Be Paid</th></tr>');
                    $temp=0;
                    for ($i=0;$i<count($month_variable);$i++) {
                        $stmt = $pdo->prepare('SELECT sum(`total`) FROM `sales` WHERE Month(`sale_date`)=:mont and `sale_date` BETWEEN :startdate and :enddate GROUP by Month(`sale_date`)');
                        $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
                        $row = $stmt->fetch();
                        $temp=$temp+$row['sum(`total`)'];
                        echo('<tr><th>'.$month_string[$i].'</th><th>'.$row['sum(`total`)'].'</th><th>'.($row['sum(`total`)']/100).'</th></tr>');
                    }
                    echo('<tr class="w3-yellow"><th>Total</th><th>'.$temp.'</th><th>'.($temp/100).'</th></tr>');
                }
            ?>
    </table>
    <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    <script>
        window.print();
    </script>
</body>

</html>