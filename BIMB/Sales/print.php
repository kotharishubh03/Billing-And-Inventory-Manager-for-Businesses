<?php
require_once "..//util/pdo.php";
require_once "..//util/functions.php";

$stmt = $pdo->prepare('SELECT `cus_name`, `mobile_no`, `email`, `address`, `gstin` , `bill_no`, `sale_date`, `total`, `discount`, `sales`.`pay_type`, `pay_mode`, `pay_date` FROM `sales` join customers on customers.cus_id=sales.cus_id join payment_mode on payment_mode.pay_mode_id=sales.pay_type WHERE `sale_id`=:sale_id Limit 1');
$stmt->execute(array(':sale_id'=>$_GET['sale_id']));
$billdetails = $stmt->fetch();

$stmt = $pdo->prepare('SELECT `prd_name`, `qnt`, `sell_price`,`qnt`*`sell_price` as amt FROM `sales_product` join `products` on `products`.`prd_id`=`sales_product`.`prd_id` WHERE `sale_id`=:sale_id');
$stmt->execute(array(':sale_id'=>$_GET['sale_id']));
$billitems = $stmt->fetchall();

$stmt = $pdo->prepare('SELECT * FROM `shop_info`');
$stmt->execute(array());
$temp = $stmt->fetchall();
$shopinfo=array();
foreach($temp as $tem){
    $shopinfo[$tem['s_key']]=$tem['value'];
}

require_once "..//util/header.php";
?>
<title>Bill Print</title>
</head>

<body class="w3-content w3-margin">

    <!-- items grid -->

    <table class="sk-table w3-table">
        <tr>
            <td colspan="10" class=" w3-center w3-small"><b>Bill OF SUPPLY<b></td>
        </tr>
        <tr>
            <td colspan="10" class="sk-no-border"></td>
        </tr>
        <tr>
            <td colspan="5" rowspan="2" class="w3-small" style="width:50%">
                <b class="w3-medium"><?=$shopinfo['shop_name']?></b><br>
                <br>
                <?=$shopinfo['shop_address']?><br>
                <br>
                GSTIN:- <b><?=$shopinfo['shop_gstin']?></b><br>
                TELE:- <b><?=$shopinfo['shop_telephone']?></b><br>
                Email:- <b><?=$shopinfo['shop_email']?></b>
            </td>
            <td colspan="5" class="w3-tiny" style="width:50%">
                <b class="w3-small">Mr. / Mrs. <?=$billdetails['cus_name']?></b><br>
                <br>
                Add:-<?=$billdetails['address']?><br>
                <br>
                GSTIN:- <b><?=$billdetails['gstin']?></b><br>
                TELE:- <b><?=$billdetails['mobile_no']?></b><br>
                Email:- <b><?=$billdetails['email']?></b>
            </td>
        </tr>
        <tr class="w3-tiny">
            <td colspan="3">Invoice No.:- <br><b><?=$billdetails['bill_no']?></b></td>
            <td colspan="2">Invoice Date:- <br><b> <?=$billdetails['sale_date']?></b></td>
            </td>
        </tr>
        <tr>
            <td colspan="10" class="sk-no-border"></td>
        </tr>
        <tr class="w3-small ">
            <td colspan="" class="w3-center" >SR No.</td>
            <td colspan="5" class="w3-center">Product</td>
            <td colspan="" class="w3-center">Qty.</td>
            <td colspan="" class="w3-center">Unit</td>
            <td colspan="" class="w3-center">Rate</td>
            <td colspan="" class="w3-center">Amount</td>
        </tr>
        <?php
            $itemcount=0;
            $totalamt=0;
            foreach($billitems as $item){
                $itemcount++;
                echo('<tr class="w3-tiny"><td colspan="" class="w3-center" >'.$itemcount.'</td>');
                echo('<td colspan="5" class="w3-center">'.$item['prd_name'].'</td>');
                echo('<td colspan="" class="w3-center">'.$item['qnt'].'</td>');
                echo('<td colspan="" class="w3-center">pcs</td>');
                echo('<td colspan="" class="w3-center">'.$item['sell_price'].'</td>');
                echo('<td colspan="" class="w3-center">'.$item['amt'].'</td></tr>');
                $totalamt=$totalamt+$item['amt'];
            }
        ?>

        <tr>
            <td colspan="10" class="sk-no-border"></td>
        </tr>
        <tr class="w3-small ">
            <td colspan="7" class="sk-no-border"></td>
            <td colspan="2" class="w3-right-align">Discount</td>
            <td><?=$billdetails['discount']?></td>
        </tr>
        <tr class="w3-small ">
            <td colspan="7" class="sk-no-border"></td>
            <td colspan="2" class="w3-right-align">Round OFF</td>
            <td><?=$totalamt-$billdetails['discount']-$billdetails['total']?></td>
        </tr>
        <tr class="w3-small ">
            <td colspan="7" class="sk-no-border">
                Payment Status : <?php 
                    if($billdetails['pay_type']==7) {echo($billdetails['pay_mode']);} 
                    else {echo('Paid by '.$billdetails['pay_mode'].' on '.$billdetails['pay_date'] );}
                ?>
            </td>
            <td colspan="2" class="w3-right-align">Total</td>
            <td><?=$billdetails['total']?></td>
        </tr>
        <tr>
            <td colspan="10" class="sk-no-border"></td>
        </tr>
        <tr>
            <td colspan="6" class="w3-small sk-no-border" style="width:60%">
                <div class="w3-center"><b>TERMS AND CONDITION </b></div><br>
                <?=$shopinfo['shop_bill_terms']?>

            </td>
            <td colspan="4" class=" w3-display-container sk-no-border w3-small" style="width:40%">
                <div class="w3-display-topright">
                    <b>For <?=$shopinfo['shop_name']?></b><br><br>
                </div>
                <div class="w3-display-bottomright">Authorised Signatory</div>
            </td>
        </tr>
    </table>

    <!-- END items grid -->
<script>
    window.print();
</script>
</body>

</html>