<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    require_once "..//util/Classes.php";

    if(isset($_POST["add"])) {
        $stmt = $pdo->prepare('SELECT cus_id FROM `customers` WHERE `cus_name`=:cus_name');
        $stmt->execute(array(':cus_name'=> $_POST['cus_name']));
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(empty($rows['cus_id'])){
            $stmt = $pdo->prepare('INSERT INTO `customers`(`cus_name`, `mobile_no`) VALUES (:cus_name,:mob_no)');
            $stmt->execute(array(':cus_name'=> $_POST['cus_name'],':mob_no'=> $_POST['cus_mob']));
            $cus_id = $pdo->lastInsertId();
        } else {
            $cus_id = $rows['cus_id'];
        }

        $TodayYear = idate("Y",strtotime($_POST['pur_date']));
        $TodayMonth = idate("m",strtotime($_POST['pur_date']));
        if($TodayMonth<4){
            $startdate=($TodayYear-1).'-04-01';
            $enddate=$TodayYear.'-03-31';
            $FY=($TodayYear-2001).'-'.($TodayYear-2000);
        }
        else{
            $startdate=$TodayYear.'-04-01';
            $enddate=($TodayYear+1).'-03-31';
            $FY=($TodayYear-2000).'-'.($TodayYear-1999);
        }

        $stmt = $pdo->prepare('SELECT bill_no FROM `sales` WHERE sale_date between :startdate and :enddate ORDER BY `bill_no` DESC limit 1');
        $stmt->execute(array(':startdate'=> date("Y-m-d", strtotime($startdate)),':enddate'=> date("Y-m-d", strtotime($enddate))));
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(empty($rows['bill_no'])){
            $bill_no='SEHC-'.$FY.'/1';
        } else {
            $bill_no = $rows['bill_no'];
            $temp=explode("/",rtrim($bill_no," "));
            $bill_no='SEHC-'.$FY.'/'.($temp[1]+1);
        }

        $stmt = $pdo->prepare('INSERT INTO `sales`(`cus_id`, `bill_no`, `sale_date`, `total`, `discount`, `pay_type`, `pay_date`) VALUES 
            (:cus_id,:bill_no,:sale_date,:total,:disc,(SELECT `pay_mode_id` FROM `payment_mode` WHERE `pay_mode`=:pay_mode),:pay_date)');
        $stmt->execute(array(':cus_id'=>$cus_id,':bill_no'=>$bill_no,':sale_date'=> date("Y-m-d", strtotime($_POST['pur_date'])),':total'=> $_POST['billtotal'],':disc'=> $_POST['disc'],':pay_mode'=> $_POST['paymode'],':pay_date'=> date("Y-m-d", strtotime($_POST['pay_date']))));
        $sale_id = $pdo->lastInsertId();

        for ($i=1;$i<50;$i++){
            $temp='prd_id_'.$i;
            if(isset($_POST[$temp])){
                $stmt = $pdo->prepare('INSERT INTO `sales_product`(`sale_id`, `prd_id`, `qnt`, `sell_price`) VALUES (:sale_id,:prd_id,:qnt,:sp)');
                $stmt->execute(array(':sale_id'=>$sale_id,':prd_id'=>$_POST[$temp],':qnt'=> $_POST['Qnt_'.$i],':sp'=> $_POST['sp_'.$i]));
            }
            else{
                break;
            }
        }
        
        $_SESSION['success']="Successfully Saved ! Add Another Product";
        $_SESSION['print']=$sale_id;
        header("Location: ./addnew.php");
        return;
    }

    require_once "..//util/header.php";
?>
        <title>Sales | ADD NEW Sale</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(1,"ADD NEW SALE"); ?>
        <!-- items grid -->
        <div class="w3-container">
            <?php flashMessage(); ?>
        </div>

        <div class="w3-row ">
            <br>
            <div class="w3-card-4 ">
                <header class="w3-container w3-light-grey">
                    <h3>Add New Sale</h3>
                </header>
                <div class="w3-container w3-margin-bottom">
                    <form name='addnew' method="post">
                        <div class="w3-row">
                            <div class="w3-col m5 w3-padding ">
                                <label for="cus_name">Customer Name :</label>
                                <input id="cus_name" class="w3-input" type="text" name="cus_name" size="30" tabindex="1" required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="cus_mob">Customer Mobile :</label>
                                <input id="cus_mob" class="w3-input" type="text" name="cus_mob" size="30" tabindex="2"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="pur_date">Date.:</label>
                                <input class="w3-input" type="date" id="pur_date" name="pur_date" size="30" required value='<?php echo date('Y-m-d');?>' tabindex="3"/>
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
                                    </tbody>
                                    <tfoot>
                                        <tr><th colspan="6"></th></tr>
                                        <tr>
                                            <th colspan="4" class="w3-right-align w3-xlarge"><b>Discount</b></th>
                                            <th><input id="discp" class="w3-input w3-medium" type="text" size="6" value="0"  tabindex="26"/></th>
                                            <th><input id="disc" name="disc" class="w3-input w3-medium" type="text" size="6" value="0" tabindex="27"/></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="w3-right-align w3-xlarge"><b>Round OFF</b></th>
                                            <th><input id="RoundUp" name="RoundUp" class="w3-input w3-medium" type="text" size="6" value="0" tabindex="28"/></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" class="w3-right-align w3-xlarge"><b>Total</b></th>
                                            <th><input id="billtotal" name="billtotal" class="w3-input w3-large" type="text" size="6" value="0" tabindex="29"/></th>
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
                                <input id="paymode" class="w3-input" type="text" name="paymode" size="6" placeholder="Enter Payment Mode" tabindex="30"/>
                            </div>
                            <div class="w3-col m4 w3-padding ">
                                <input id="pay_date" class="w3-input" type="date" name="pay_date" size="30" required value='<?php echo date('Y-m-d');?>' tabindex="31"/>
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
            
            var countitem=0;
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
