<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";

    if (isset($_GET['fy'])) {
        $_SESSION['fy']=$_GET['fy'];
        DateOptionADD($_GET['fy'],0,'./about.php?prd_id='.$_GET['prd_id'].'&fy='); //params 0-today or '2018'
        $_GET["FY"]=$FY;
    }
    else{
        DateOptionADD(0,0,'./about.php?prd_id='.$_GET['prd_id'].'&fy=');
        $_GET["FY"]=$FY;
    }

    if (isset($_GET['sale_id'])) {
        $prdarr=[];  
        $stmt = $pdo->prepare('SELECT `cus_name`, `mobile_no`, `email`, `address`, `gstin` , `bill_no`, `sale_date`, `total`, `discount`, `sales`.`pay_type`, `pay_mode`, `pay_date` FROM `sales` join customers on customers.cus_id=sales.cus_id join payment_mode on payment_mode.pay_mode_id=sales.pay_type WHERE `sale_id`=:sale_id Limit 1');
        $stmt->execute(array(':sale_id'=>$_GET['sale_id']));
        $billdetails = $stmt->fetch();

        $stmt = $pdo->prepare('SELECT `prd_name`,`sales_product`.prd_id, `qnt`, `sell_price`,`qnt`*`sell_price` as amt FROM `sales_product` join `products` on `products`.`prd_id`=`sales_product`.`prd_id` WHERE `sale_id`=:sale_id');
        $stmt->execute(array(':sale_id'=>$_GET['sale_id']));
        $billitems = $stmt->fetchall();
        /*
        $stmt = $pdo->prepare('SELECT `products_supplier`.`supp_id`, `supp_name`, `cost_price` FROM `products_supplier` join suppliers on suppliers.supp_id=products_supplier.supp_id WHERE `prd_id`=:prd_id ORDER BY `products_supplier`.`supp_id` DESC');
        $stmt->execute(array(':prd_id'=>$_GET['prd_id']));
        $supp_details = $stmt->fetchAll();
        $prd_supp="";
        foreach ($supp_details as $supp) {
            $prd_supp=$supp['supp_name'].', '.$prd_supp;
        }

        $month_variable=[4,5,6,7,8,9,10,11,12,1,2,3];
        $purchase=[];
        $sale=[];
        $months=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        for ($i=0;$i<12;$i++) {
            $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `purchase_product` join purchase on purchase.pur_id=purchase_product.pur_id WHERE Month(`purchase`.`pur_date`)=:mont and prd_id=:prd_id and purchase.pur_date BETWEEN :startdate and :enddate GROUP By purchase_product.prd_id');
            $stmt->execute(array(':mont'=>$month_variable[$i], ':prd_id'=>$_GET['prd_id'], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
            $row = $stmt->fetch();
                if (isset($row['sm'])){
                    array_push($purchase,$row['sm']);
                }
                else{
                    array_push($purchase,0);
                }
    
            $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `sales_product` join sales on `sales`.`sale_id`=`sales_product`.`sale_id` WHERE Month(`sales`.`sale_date`)=:mont and prd_id=:prd_id and `sales`.`sale_date` BETWEEN :startdate and :enddate GROUP By `sales_product`.`prd_id` order by `prd_id`');
            $stmt->execute(array(':mont'=>$month_variable[$i], ':prd_id'=>$_GET['prd_id'], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
            $row = $stmt->fetch();
                if (isset($row['sm'])){
                    array_push($sale,$row['sm']);    
                }
                else{
                    array_push($sale,0);
                }
        }*/
    }

    if (isset($_POST['update'])) {
        $stmt = $pdo->prepare('UPDATE `products` SET `prd_name`=:prd_name,`mrp`=:mrp,`selling_price`=:sell_price WHERE `prd_id`=:prd_id');
        $stmt->execute(array(':prd_name'=> $_POST['prd_name'], ':mrp'=> $_POST['mrp'], ':sell_price'=> $_POST['sell_price'], ':prd_id'=>$_POST['update']));

        $stmt = $pdo->prepare('DELETE FROM `products_supplier` WHERE prd_id=:prd_id');
        $stmt->execute(array(':prd_id'=>$_POST['update']));

        $s_name=explode(", ",rtrim($_POST['s_name'],", "));
        foreach ($s_name as $r) {
            $stmt = $pdo->prepare('INSERT INTO `products_supplier`(`prd_id`, `supp_id`, `cost_price`) VALUES (:prd_id, (SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:supp_name), :cost_price)');
            $stmt->execute(array(':prd_id'=> $_POST['update'], ':supp_name'=> $r, ':cost_price'=> $_POST['cost_price']));
        }
        header("Location: ./about.php?prd_id=".$_POST['update']);
        return;
    }

    require_once "..//util/header.php";
?>
<title>About Sales</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(1,"About Sales"); ?>
        <!-- items grid -->
        <div class="w3-container">
            <?php flashMessage(); ?>
        </div>

        <div class="w3-row ">
            <br>
            <div class="w3-card-4 ">
                <header class="w3-container w3-light-grey">
                    <h3>About Sales</h3>
                </header>
                <div class="w3-container w3-margin-bottom">
                    <form name='addnew' method="post">
                        <div class="w3-row">
                            <div class="w3-col m5 w3-padding ">
                                <label for="cus_name">Customer Name :</label>
                                <input id="cus_name" class="w3-input" type="text" name="cus_name" size="30" value="<?=$billdetails['cus_name']?>" tabindex="1" required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="cus_mob">Customer Mobile :</label>
                                <input id="cus_mob" class="w3-input" type="text" name="cus_mob" size="30" value="<?=$billdetails['mobile_no']?>" tabindex="2"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="pur_date">Date.:</label>
                                <input class="w3-input" type="date" id="pur_date" name="pur_date" size="30" required value="<?=$billdetails['sale_date']?>" tabindex="3"/>
                            </div>
                        </div><hr>
                        
                        <div class="w3-row">
                            <div class="w3-col m2 w3-padding ">
                                <h3>ADD ITEMS</h3>
                            </div>
                        </div>
                        <div class="w3-row">
                            <div class="w3-col m4 w3-padding ">
                                <input id="prd_name" class="w3-input" type="text" name="" size="6" placeholder="enter product name" tabindex="4"/>
                            </div>
                            <div class="w3-col m2 w3-padding ">
                                <input id="prd_qnt" class="w3-input" type="text" name="" size="6" placeholder="enter quantity" tabindex="5"/>
                            </div>
                            <div class="w3-col m2 w3-padding ">
                                <input id="sell_price" class="w3-input" type="text" name="" size="6" placeholder="Selling Price" tabindex="6"/>
                            </div>
                            <div class="w3-col m2 w3-padding ">
                                <input id="prd_id" class="w3-input" type="text" name="" size="6" placeholder="product id" tabindex="7" disabled/>
                            </div>
                            <div class="w3-col m2 w3-padding ">
                                <button id="addproduct" class="w3-button w3-dark-grey" name="addproduct" value="1" >Add</button>
                            </div>
                        </div><hr>
                        <div class="w3-row">
                            <div class="w3-card-4">
                                <table class="w3-table-all w3-small sk-table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Product Name</th>
                                            <th>Product ID</th>
                                            <th>Product Quantity</th>
                                            <th>Selling Price</th>
                                            <th>Total</th>

                                        </tr>
                                    </thead>
                                    <tbody id="additemdiv" >
                                        <?php
                                        $countitem=1;
                                        foreach($billitems as $item){
                                            echo('<tr>
                                            <td>'.$countitem.'</td>
                                            <td>'.$item['prd_name'].'</td>
                                            <td><input id="prd_id_'.$countitem.'" name="prd_id_'.$countitem.'" class="w3-input" type="text" size="6" value="'.$item['prd_id'].'" /> </td>
                                            <td><input id="Qnt_'.$countitem.'" name="Qnt_'.$countitem.'" class="w3-input QntSpChangeCalc" type="text" size="6" value="'.$item['qnt'].'" /> </td>
                                            <td><input id="sp_'.$countitem.'" name="sp_'.$countitem.'" class="w3-input QntSpChangeCalc" type="text" size="6" value="'.$item['sell_price'].'"/> </td>
                                            <td><input id="subtotal_'.$countitem.'" class="w3-input" type="text" size="6" value="'.$item['amt'].'"/> </td>
                                            </tr>');
                                            $countitem++;
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr><th colspan="6"></th></tr>
                                        <tr>
                                            <th colspan="4" class="w3-right-align w3-xlarge"><b>Discount</b></th>
                                            <th><input id="discp" class="w3-input w3-medium" type="text" size="6" value="0"  tabindex="26"/></th>
                                            <th><input id="disc" name="disc" class="w3-input w3-medium" type="text" size="6" value="<?=$billdetails['discount']?>" tabindex="27"/></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="w3-right-align w3-xlarge"><b>Round OFF</b></th>
                                            <th><input id="RoundUp" name="RoundUp" class="w3-input w3-medium" type="text" size="6" value="0" tabindex="28"/></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="w3-right-align w3-xlarge"><b>Total</b></th>
                                            <th><input id="billtotal" name="billtotal" class="w3-input w3-large" type="text" size="6" value="<?=$billdetails['total']?>" tabindex="29"/></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div><hr>
                        <div class="w3-row">
                            <div class="w3-col m2 w3-padding ">
                                <h3>Payments</h3>
                            </div>
                        </div>
                        <div class="w3-row">
                            <div class="w3-col m4 w3-padding ">
                                <input id="paymode" class="w3-input" type="text" name="paymode" size="6" placeholder="Enter Payment Mode" value="<?=$billdetails['pay_mode']?>" tabindex="30"/>
                            </div>
                            <div class="w3-col m4 w3-padding ">
                                <input id="pay_date" class="w3-input" type="date" name="pay_date" size="30" required value="<?=$billdetails['pay_date']?>" tabindex="31"/>
                            </div>
                        </div><hr>
                        <button class="w3-button w3-left w3-margin-bottom w3-dark-grey" style="width:49%" type="submit" name="add" value="1" >ADD NEW </button>
                        <button class="w3-button w3-right w3-margin-bottom w3-dark-grey" style="width:49%" type="submit" name="add" value="2" > Save and Print</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>

<script>
        $(document).ready(function () {
            window.console && console.log('Document ready called');
            $('#prd_name').autocomplete({
                source: "../GET/productget.php",
                minLength: 1,
            });

            $('#paymode').autocomplete({
                source: "../GET/paymodeget.php",
                minLength: 1,
            });

            $('#cus_name').autocomplete({
                source: "../GET/customerget.php",
                minLength: 1,
            });

            $("#pur_date").change(function(){
                $("#pay_date").val($("#pur_date").val());
            });

            $('#prd_name').change(function(){
                var itemname =$("#prd_name").val();
                $.getJSON("../GET/prdidget.php?term="+itemname, function( data ) {
                    $.each( data, function( key, val ) {
                        $('#prd_id').val(val);
                    });
                });
            });
            
            var countitem=<?=$countitem-1?>;
            var tabindex=7;
            $("#addproduct").click(function(){
                event.preventDefault();
                var itemname =$("#prd_name").val();
                var itemqnt =$("#prd_qnt").val();
                var productid=$("#prd_id").val();
                var sell_price=$("#sell_price").val();
                var billtotal=$('#billtotal').val();
                var disc=$('#disc').val();
                if (itemname=="" || itemqnt=="" || productid==""){

                } else{
                    var temp=parseFloat(itemqnt) * parseFloat(sell_price);
                    temp=temp.toFixed(2);
                    var billtotal=parseFloat(billtotal) + parseFloat(temp) - parseFloat(disc);
                    //console.log(disc);
                    //temp=temp.toFixed(2);
                    countitem++;
                    tabindex++;
                    $("#additemdiv").append('<tr> \
                    <td>'+countitem+'</td> \
                    <td>'+itemname+'</td> \
                    <td><input id="prd_id_'+countitem+'" name="prd_id_'+countitem+'" class="w3-input" type="text" size="6" value="'+productid+'" /> </td> \
                    <td><input id="Qnt_'+countitem+'" name="Qnt_'+countitem+'" class="w3-input QntSpChangeCalc" type="text" size="6" value="'+itemqnt+'" /> </td> \
                    <td><input id="sp_'+countitem+'" name="sp_'+countitem+'" class="w3-input QntSpChangeCalc" type="text" size="6" value="'+sell_price+'"/> </td> \
                    <td><input id="subtotal_'+countitem+'" class="w3-input" type="text" size="6" value="'+temp+'"/> </td> \
                    </tr>');
                    tabindex++;

                    temp=Math.round(billtotal);
                    $("#billtotal").val(temp);
                    $("#RoundUp").val((billtotal-temp).toFixed(2));
                
                    $("#prd_name").focus();
                }
            });

            $("#discp").change(function(){
                var billtotal=0;
                $.each(Array.from({length:countitem},(v,k)=>k+1), function( index, value ) {
                    var subtotal="#subtotal_"+value;
                    billtotal=billtotal+parseFloat($(subtotal).val());
                });
                var discp=$('#discp').val();
                $('#disc').val(billtotal*(discp/100));
                $('#disc').trigger("change");
            });

            $("#disc").change(function(){
                var billtotal=0;
                $.each(Array.from({length:countitem},(v,k)=>k+1), function( index, value ) {
                    var subtotal="#subtotal_"+value;
                    billtotal=billtotal+parseFloat($(subtotal).val());
                });
                var disc=$('#disc').val();
                $('#billtotal').val(parseFloat(billtotal)-parseFloat(disc));
            });

            $('#additemdiv').on('change','.QntSpChangeCalc',function(){
                var billtotal=0;
                $.each(Array.from({length:countitem},(v,k)=>k+1), function( index, value ) {
                    var Qnt="#Qnt_"+value;
                    var sp="#sp_"+value;
                    var subtotal="#subtotal_"+value;
                    var mul=parseFloat($(Qnt).val())*parseFloat($(sp).val());
                    mul=mul.toFixed(2);
                    //console.log(mul);
                    billtotal=billtotal+parseFloat(mul);
                    //console.log(billtotal);
                    $(subtotal).val(mul);
                });
                billtotal=billtotal-$("#disc").val();
                temp=Math.round(billtotal);
                $("#billtotal").val(temp);
                $("#RoundUp").val((billtotal-temp).toFixed(2));
            });
            <?php
                if(isset($_SESSION['print'])) {
                    echo('window.open(\' ./print.php?sale_id='.$_SESSION['print'].' \', \' _blank \');');
                    unset($_SESSION['print']);
                    echo('alert("Allow Popups Right side on URL bar ");');
                }
            ?>

        });
        </script>

    </body>
</html>
