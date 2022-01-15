<?php
    require_once "..//util/pdo.php";
    require_once "..//util/Classes.php";
    require_once "..//util/functions.php";

    session_start();

    if(isset($_POST['add'])){
        $stmt = $pdo->prepare('INSERT INTO `purchase`(`supp_id`, `pur_date`, `bill_no`, `total`, `tax5`, `gst5`, `tax12`, `gst12`, `tax18`, `gst18`, `tax28`, `gst28`)
        VALUES ((SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:supp_name), :pur_date, :bill_no, :total, :tax5, :gst5, :tax12, :gst12, :tax18, :gst18, :tax28, :gst28)');
        $stmt->execute(array(':supp_name'=>$_POST['supp_name'], ':pur_date'=>date("Y-m-d", strtotime($_POST['pur_date'])), ':bill_no'=>$_POST['p_bill_no'], ':total'=>$_POST['totaltotal'], 
            ':tax5'=> $_POST['grossa'], ':gst5'=> $_POST['sgsta'], ':tax12'=> $_POST['grossb'], ':gst12'=> $_POST['sgstb'], ':tax18'=> $_POST['grossc'], ':gst18'=> $_POST['sgstc'], ':tax28'=> $_POST['grossd'], ':gst28'=> $_POST['sgstd']));
        $pur_id = $pdo->lastInsertId();
        
        $items=explode(',',$_POST['items']);
        $i=1;
        $temp="";
        foreach ($items as $item) {
            if ($i%2==0){
            $stmt = $pdo->prepare('INSERT INTO `purchase_product`(`pur_id`, `prd_id`, `qnt`) 
                VALUES (:pur_id, :prd_id, :qnt)');
            $stmt->execute(array(':pur_id'=>$pur_id, ':prd_id'=>$temp, ':qnt'=>$item));
            }
            else{
                $temp=$item;
            }
            $i=$i+1;
        }
        $_SESSION['success']="Successfully Saved ! Add Another Purchase";

        header('Location: ./addnew.php');
        exit;
    }

?>
    
<?php
    require_once "..//util/header.php";
?>
        <title>Add New Purchase</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php 
            mainbody(0,"Add New Purchase");
            flashMessage();
        ?>
        <!-- items grid -->

        <div class="w3-row w3-margin">
            <div class="w3-card-4">
                <div class="w3-container">
                    <form name='addnew' method="post">
                    <div class="w3-row">
                        <div class="w3-col m5 w3-padding ">
                            <label for="supp_name">Supplier name:</label>
                            <input id="supp_name" class="w3-input" type="text" name="supp_name" size="30" tabindex="1" required/>
                        </div><div class="w3-col m5 w3-padding ">
                            <label for="pur_date">Date.:</label>
                            <input class="w3-input" type="date" name="pur_date" size="30" required tabindex="2"/>
                        </div>
                        <div class="w3-col m5 w3-padding ">
                            <label for="p_bill_no">Bill No.:</label>
                            <input id="p_bill_no" class="w3-input" type="text" name="p_bill_no" size="30" required tabindex="3"/>
                            <label id="bill_in_db" class="w3-red w3-hide">Already in database</label>
                        </div>
                    </div>
                    <div class="w3-row">
                        <div class="w3-responsive">
                            <table class="w3-table-all w3-small ">
                                <tr><th></th><th>taxable</th><th>Round Off</th><th>Gross</th><th>SGST</th><th>CGST</th><th>Total</th></tr>
                                <tr><td>2.5</td><td><input id="taxa" class="w3-input skcalculation" type="number" step="0.01" name="taxa" step="0.01" tabindex="4"size="6"/></td><td><input id="roundoffa" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="grossa" class="w3-input skcalculation1" type="number" name="grossa" step="0.01" size="6"/></td><td><input id="sgsta" class="w3-input skcalculation2" type="number" name="sgsta" step="0.01" size="6"/></td><td><input id="cgsta" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="totala" class="w3-input" type="number" name="" step="0.01" size="6"/></td></tr>
                                <tr><td>6</td><td><input id="taxb" class="w3-input skcalculation" type="number" name="taxb" step="0.01" step="0.01" tabindex="5"size="6"/></td><td><input id="roundoffb" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="grossb" class="w3-input skcalculation1" type="number" name="grossb" step="0.01" size="6"/></td><td><input id="sgstb" class="w3-input skcalculation2" type="number" name="sgstb" step="0.01" size="6"/></td><td><input id="cgstb" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="totalb"class="w3-input" type="number" name="" step="0.01" size="6"/></td></tr>
                                <tr><td>9</td><td><input id="taxc" class="w3-input skcalculation" type="number" name="taxc" step="0.01" step="0.01" tabindex="6"size="6"/></td><td><input id="roundoffc" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="grossc" class="w3-input skcalculation1" type="number" name="grossc" step="0.01" size="6"/></td><td><input id="sgstc" class="w3-input skcalculation2" type="number" name="sgstc" step="0.01" size="6"/></td><td><input id="cgstc" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="totalc"class="w3-input" type="number" name="" step="0.01" size="6"/></td></tr>
                                <tr><td>14</td><td><input id="taxd" class="w3-input skcalculation" type="number" name="taxd" step="0.01" step="0.01" tabindex="7"size="6"/></td><td><input id="roundoffd"class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="grossd" class="w3-input skcalculation1" type="number" name="grossd" step="0.01" size="6"/></td><td><input id="sgstd" class="w3-input skcalculation2" type="number" name="sgstd" step="0.01" size="6"/></td><td><input id="cgstd" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="totald" class="w3-input" type="number" name="" step="0.01" size="6"/></td></tr>
                                <tr><td>total</td><td><input id="taxtotal" class="w3-input skcalculation" type="number" step="0.01" name="taxtotal" step="0.01" size="6"/></td><td><input id="roundofftotal" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="grosstotal" class="w3-input skcalculation1" type="number" name="grosstotal" step="0.01" size="6"/></td><td><input id="sgsttotal" class="w3-input skcalculation2" type="number" name="sgsttotal" step="0.01" size="6"/></td><td><input id="cgsttotal" class="w3-input" type="number" name="" step="0.01" size="6"/></td><td><input id="totaltotal"class="w3-input" type="number" name="totaltotal" step="0.01" size="6"/></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="w3-row"><br>
                        <div class="w3-card-4">
                            <div class="w3-container">
                                <div class="w3-col m2 w3-padding ">
                                    <h3>add items</h3>
                                </div>
                                <div class="w3-col m4 w3-padding ">
                                    <input id="prd_name" class="w3-input" type="text" name="" size="6" placeholder="enter product name" tabindex="8"/>
                                </div>
                                <div class="w3-col m2 w3-padding ">
                                    <input id="prd_qnt" class="w3-input" type="text" name="" size="6" placeholder="enter quantity" tabindex="9"/>
                                </div>
                                <div class="w3-col m2 w3-padding ">
                                    <input id="prd_id" class="w3-input" type="text" name="" size="6" placeholder="product id" tabindex="10" disabled/>
                                </div>
                                <div class="w3-col m2 w3-padding ">
                                    <button id="addproduct" class="w3-button w3-dark-grey" name="addproduct" value="1" >Add</button>
                                </div>
                            </div>
                        </div>
                        </div><br>
                        <div class="w3-row">
                            <div class="w3-card-4">
                                <table class="w3-table-all w3-small ">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Product Name</th>
                                            <th>Product ID</th>
                                            <th>Product Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody id="additemdiv" >
                                    </tbody>
                                    <tfoot>
                                        <input name="items" id="items" class="w3-hide" value="">
                                    </tfoot>
                                </table>
                            </div>
                        </div><br>
                        <!--div class="w3-row">
                            <div class="w3-card-4">
                                <div id="" class="w3-container">
                                    <div class="w3-col m2 w3-padding ">
                                        <h3>Payment</h3>
                                    </div>
                                    <div class="w3-col m4 w3-padding ">
                                        <input id="paymode" class="w3-input" type="text" name="paymode" size="6" placeholder="enter payment mode" tabindex="100"/>
                                    </div>
                                    <div class="w3-col m3 w3-padding ">
                                        <input id="" class="w3-input" type="date" name="pay_day" size="6" placeholder="enter date" tabindex="101"/>
                                    </div>
                                </div>
                            </div>
                        </div--><br>                    
                        <button class="w3-button w3-block w3-dark-grey" type="submit" name="add" value="1" >ADD NEW </button>
                    </form>
                </div>
            </div>
        </div><br>
        

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>

        <script>
        $(document).ready(function () {
            window.console && console.log('Document ready called');
            
            $('form input').keydown(function (e) {
                if (e.keyCode == 13) {
                    var inputs = $(this).parents("form").eq(0).find(":input");
                    e.preventDefault();
                    return false;
                }
            });

            $('#supp_name').autocomplete({
                    source: "../GET/supplierget.php",
                    minLength: 1,
            });
      
            $('#paymode').autocomplete({
                source: "../GET/paymodeget.php",
                minLength: 1,
            });
      
            $('#prd_name').autocomplete({
                source: "../GET/productget.php",
                minLength: 1,
            });
            
            $('#prd_name').change(function(){
                var itemname =$("#prd_name").val();
                $.getJSON("../GET/prdidget.php?term="+itemname, function( data ) {
                    $.each( data, function( key, val ) {
                        $('#prd_id').val(val);
                    });
                });
            });

            $('#p_bill_no').change(function(){
                var billno = $('#p_bill_no').val();
                $.getJSON("../GET/billget.php?term="+billno, function( data ) {
                    $.each( data, function( key, val ) {
                        if (val===billno){
                            $('#bill_in_db').removeClass('w3-hide')
                        }
                        else{
                            $('#bill_in_db').addClass('w3-hide')
                        }
                    });
                });
            });

            $('.skcalculation').change(function(){
                var arr=[0.025,0.06,0.09,0.14];
                $.each(['#taxa','#taxb','#taxc','#taxd'], function( index, value ) {
                    if (isNaN(parseFloat($(value).val()))){
                        $(value).val(parseFloat(0));
                    }
                });

                $.each(['a','b','c','d'], function( index, value ) {
                    var tax ='#tax'+value;
                    var roundoff ='#roundoff'+value;
                    var gross ='#gross'+value;
                    var sgst='#sgst'+value;
                    var cgst='#cgst'+value;
                    var total='#total'+value;
                    //alert("The text has been changed.");
                    var z=parseFloat($(tax).val()) * arr[index];
                    z=z.toFixed(2);
                    $(sgst).val(z);
                    $(cgst).val($(sgst).val());
                    z=parseFloat($(sgst).val())+parseFloat($(cgst).val())+parseFloat($(tax).val());
                    z=z.toFixed();
                    $(total).val(z);
                    z=parseFloat($(sgst).val())+parseFloat($(cgst).val())+parseFloat($(tax).val())-z;
                    z=z.toFixed(2);
                    $(roundoff).val(z);
                    z=(parseFloat($(tax).val())-parseFloat($(roundoff).val()));
                    z=z.toFixed(2);
                    $(gross).val(z);
                });
                $.each(['#tax','#roundoff','#gross','#sgst','#cgst','#total'], function( index, value ) {
                    var a=value+'a';
                    var b=value+'b';
                    var c=value+'c';
                    var d=value+'d';
                    var total=value+'total';
                    $(total).val(parseFloat($(a).val())+parseFloat($(b).val())+parseFloat($(c).val())+parseFloat($(d).val()));
                });
            });
            
            $('.skcalculation2').change(function(){
                $.each(['a','b','c','d'], function( index, value ) {
                    var sgst='#sgst'+value;
                    var cgst='#cgst'+value;
                    var gross ='#gross'+value;
                    var total='#total'+value;
                    $(cgst).val($(sgst).val());
                    z=parseFloat($(sgst).val())+parseFloat($(cgst).val())+parseFloat($(gross).val());
                    $(total).val(z);
                });
                $.each(['#tax','#roundoff','#gross','#sgst','#cgst','#total'], function( index, value ) {
                    var a=value+'a';
                    var b=value+'b';
                    var c=value+'c';
                    var d=value+'d';
                    var total=value+'total';
                    $(total).val(parseFloat($(a).val())+parseFloat($(b).val())+parseFloat($(c).val())+parseFloat($(d).val()));
                });
            });

            $('.skcalculation1').change(function(){
                var arr=[0.025,0.06,0.09,0.14];
                $.each(['a','b','c','d'], function( index, value ) {
                    var sgst='#sgst'+value;
                    var cgst='#cgst'+value;
                    var gross ='#gross'+value;
                    var total='#total'+value;
                    var z=parseFloat($(gross).val()) * arr[index];
                    z=z.toFixed(2);
                    $(sgst).val(z);
                    $(cgst).val($(sgst).val());
                    z=parseFloat($(sgst).val())+parseFloat($(cgst).val())+parseFloat($(gross).val());
                    $(total).val(z);
                });
                $.each(['#tax','#roundoff','#gross','#sgst','#cgst','#total'], function( index, value ) {
                    var a=value+'a';
                    var b=value+'b';
                    var c=value+'c';
                    var d=value+'d';
                    var total=value+'total';
                    $(total).val(parseFloat($(a).val())+parseFloat($(b).val())+parseFloat($(c).val())+parseFloat($(d).val()));
                });
            });

            var countitem = 0;
            var tabindex = 10;
            var itemarray=[];
            $("#addproduct").click(function(){
                event.preventDefault();
                var itemname =$("#prd_name").val();
                var itemqnt =$("#prd_qnt").val();
                var productid=$("#prd_id").val();
                if (itemname=="" || itemqnt=="" || productid==''){

                } else{
                    countitem++;
                    tabindex++;
                    var temparray=[];
                    temparray[0]=productid;
                    temparray[1]=itemqnt;
                    itemarray[countitem-1]=temparray;
                    console.log(itemarray);
                    $("#items").val(itemarray);
                    $("#additemdiv").append('<tr><td>'+countitem+'</td><td>'+itemname+'</td><td>'+productid+'</td><td><input id="Qnt'+countitem+'" class="w3-input" type="text" tabindex="'+(parseInt(tabindex)+1) +'" size="6" placeholder="enter Quantity" value="'+itemqnt+'" /></tr>');
                    tabindex++;
                    $("#prd_name").focus();
                }
            });
        });
        </script>

    </body>
</html>