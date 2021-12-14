<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    require_once "..//util/header.php";
?>
        <title>Add New Purchase</title>
        <style>
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
  }
  </style>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(0,"Add New Purchase") ?>
        <!-- items grid -->

        <br>
        <div class="w3-row w3-margin">
            <div class="w3-card-4">
                <div class="w3-container">
                    <form name='addnew' method="get">
                    <div class="w3-row">
                        <div class="w3-col m5 w3-padding ">
                            <label for="s_name">Supplier name:</label>
                            <input id="s_name" class="w3-input" type="text" name="s_name" size="30" tabindex="1" required/>
                        </div><div class="w3-col m5 w3-padding ">
                            <label for="p_date">Date.:</label>
                            <input class="w3-input" type="date" name="p_date" size="30" required tabindex="2"/>
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
                    <div class="w3-row">
                        <div class="w3-card-4">
                            <div class="w3-container">
                                <div class="w3-col m2 w3-padding ">
                                    <h3>add items</h3>
                                </div>
                                <div class="w3-col m4 w3-padding ">
                                    <input id="prd_name" class="w3-input" type="text" name="" size="6" placeholder="enter product name" tabindex="8"/>
                                </div>
                                <div class="w3-col m3 w3-padding ">
                                    <input id="prd_qnt" class="w3-input" type="text" name="" size="6" placeholder="enter product quantity" tabindex="9"/>
                                </div>
                                <div class="w3-col m2 w3-padding ">
                                    <button id="addproduct" class="w3-button w3-dark-grey" name="addproduct" value="1" >Add</button>
                                </div>
                            </div>
                        </div>
                        </div><br>
                        <div class="w3-row">
                            <div class="w3-card-4">
                                <div id="additemdiv" class="w3-container">
                                </div>
                            </div>
                        </div><br>
                        <div class="w3-row">
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
                        </div><br>                    
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

            $('#s_name').autocomplete({
                    source: "../util/supplierget.php",
                    minLength: 1,
                });
      
            $('#paymode').autocomplete({
                source: "../util/paymodeget.php",
                minLength: 1,
            });
      
            $('#prd_name').autocomplete({
                source: "../util/productget.php",
                minLength: 1,
            });

            $('#p_bill_no').change(function(){
                var billno = $('#p_bill_no').val();
                $.getJSON("../util/billget.php?term="+billno, function( data ) {
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
            var tabindex = 9;
            $("#addproduct").click(function(){
                event.preventDefault();
                var itemname =$("#prd_name").val();
                var itemqnt =$("#prd_qnt").val();
                if (itemname=="" & itemqnt==""){

                } else{
                countitem++;
                tabindex++;
                $("#additemdiv").append('<div class="w3-col m3 w3-padding"><div class="w3-card-4">' +
                    '<input id="prd'+countitem+'" class="w3-input" type="text" name="prdname'+countitem+'" size="6" tabindex="'+tabindex+'" placeholder="enter product name" value="'+itemname+'" />'+
                    '<input id="Qnt'+countitem+'" class="w3-input" type="text" name="qnt'+countitem+'" tabindex="'+(parseInt(tabindex)+1) +'" size="6" placeholder="enter Quantity" value="'+itemqnt+'" />'+
                '</div></div>');
                tabindex++;
                $("#prd_name").focus();
                }
            });
        });
        </script>

    </body>
</html>
