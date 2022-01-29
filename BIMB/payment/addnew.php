<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";

    if(isset($_POST["add"])) {
        if ($_POST["add"]==1) {
            $stmt = $pdo->prepare('INSERT INTO `suppliers_payment`(`pay_type`, `supp_id`, `amount`, `date`, `chq_no`) VALUES ((SELECT `pay_mode_id` FROM `payment_mode` WHERE `pay_mode`=:paymode Limit 1),(SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:s_name limit 1),:amt,:pay_date,:chqno)');
            $stmt->execute(array(':paymode'=> $_POST['paymode'], ':s_name'=> $_POST['s_name'], ':amt'=> $_POST['amt'],':pay_date'=>date("Y-m-d", strtotime($_POST['pay_date'])),':chqno'=> $_POST['chqno']));
            $payment_id = $pdo->lastInsertId();

            $pay_bill_nos=explode(", ",rtrim($_POST['pay_bill_nos'],", "));
            foreach ($pay_bill_nos as $r) {
                $stmt = $pdo->prepare('UPDATE `purchase` SET `pay_date`=:pay_date ,`pay_mode_id`=(SELECT `pay_mode_id` FROM `payment_mode` WHERE `pay_mode`=:paymode limit 1) WHERE `pur_id`=(SELECT `pur_id` FROM `purchase` WHERE `bill_no`=:billno and `supp_id`=(SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:s_name limit 1))');
                $stmt->execute(array(':pay_date'=>date("Y-m-d", strtotime($_POST['pay_date'])),':paymode'=> $_POST['paymode'],':billno'=> $r,':s_name'=> $_POST['s_name']));
                
                $stmt = $pdo->prepare('INSERT INTO `supp_pay_purchase` (`pur_id`, `payment_id`) VALUES ((SELECT `pur_id` FROM `purchase` WHERE `bill_no`=:billno and `supp_id`=(SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:s_name limit 1)),:payment_id)');
                $stmt->execute(array(':s_name'=> $_POST['s_name'], ':billno'=> $r, ':payment_id'=> $payment_id));
            }
            $_SESSION['success']="Successfully Saved ! Add Another Product";
            header("Location: ./addnew.php");
            return;
        }
    } 

    require_once "..//util/header.php";
?>
        <title>Add New Payments</title>
        <style>
            .ui-autocomplete {
                max-height: 100px;
                overflow-y: auto;
                overflow-x: hidden;
            }
            * html .ui-autocomplete {
                height: 200px;
            }
        </style>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(4,"Payments") ?>
        <!-- items grid -->
        <div class="w3-container">
            <div class="w3-card-4 w3-margin">
                <header class="w3-container w3-light-grey">
                    <h3>ADD NEW Payment</h3>
                </header>
                <div class="w3-container">
                    <form name='addnew' method="post">
                        <div class="w3-row">
                            <div class="w3-col m5 w3-padding ">
                                <label for="s_name">Supplier name:</label>
                                <input id="s_name" class="w3-input" type="text" name="s_name" size="30" placeholder="Type 'all' for All Suppliers" tabindex="1" required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="pay_date">Date.:</label>
                                <input id="pay_day" class="w3-input" type="date" name="pay_date"  required tabindex="3"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="amt">Amount:</label>
                                <input class="w3-input" type="text" name="amt" required tabindex="4"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="paymode">Mode:</label>
                                <input id="paymode" class="w3-input" type="text" name="paymode" placeholder="Type 'all' for All Modes" required tabindex="5"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="chqno">Chq no:</label>
                                <input id="chqno" class="w3-input" type="text" name="chqno" required tabindex="6"/>
                            </div>
                            <div class="w3-col w3-padding ">
                                <label for="pay_bill_nos">Bill Nos.:</label>
                                <input id="pay_bill_nos" class="w3-input" name="pay_bill_nos"  required tabindex="2"/>
                            </div>
                        </div><br>                 
                        <button class="w3-button w3-block w3-dark-grey" type="submit" name="add" value="1" >ADD NEW </button>
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
            $('form input').keydown(function (e) {
                if (e.keyCode == 13) {
                    var inputs = $(this).parents("form").eq(0).find(":input");
                    e.preventDefault();
                    return false;
                }
            });

            $('#s_name').autocomplete({
                    source: "../GET/supplierget.php",
                    minLength: 1,
                });
      
            $('#paymode').autocomplete({
                source: "../GET/paymodeget.php",
                minLength: 1,
            });

            $('#paymode').change(function() {
                if ($('#paymode').val()=="Cash") {
                    var x= new Date($('#pay_day').val());
                    var day=x.getDate();
                    var mnt=x.getMonth();
                    mnt=mnt+1;
                    var yr=x.getFullYear()
                    var z='Cash/'+day+'/'+mnt+'/'+yr+'/'+$('#s_name').val();
                    $('#chqno').val(z);
                } 

                if ($('#paymode').val()=="Discount") {
                    var x= new Date($('#pay_day').val());
                    var day=x.getDate();
                    var mnt=x.getMonth();
                    mnt=mnt+1;
                    var yr=x.getFullYear()
                    var z='Disc/'+day+'/'+mnt+'/'+yr+'/'+$('#s_name').val();
                    $('#chqno').val(z);
                } 

            });
            function split( val ) {
                return val.split( /,\s*/ );
            }
            
            function extractLast( term ) {
                return split( term ).pop();
            }
            var billno=[];
            $('#s_name').change(function() {
                billno=[];
                $.getJSON( "../GET/billpayget.php?supp_name="+$('#s_name').val(), function( data ) {
                    $.each( data, function( key, val ) {
                    billno.push(val);
                    });
                });
                if (billno==[]){
                    billno.push('NO Bill Found')
                }
            });
 
            $( "#pay_bill_nos" )
            // don't navigate away from the field on tab when selecting an item
            .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function( request, response ) {
                // delegate back to autocomplete, but extract the last term
                response( $.ui.autocomplete.filter(
                    billno, extractLast( request.term ) ) );
                },
                focus: function() {
                // prevent value inserted on focus
                return false;
                },
                select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
                }
            });
        });
        </script>
    </body>
</html>
