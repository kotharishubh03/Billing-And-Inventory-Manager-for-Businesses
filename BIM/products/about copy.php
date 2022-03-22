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

    if (isset($_GET['prd_id'])) {
        $prdarr=[];  
        $stmt = $pdo->prepare('SELECT `prd_name`, `mrp`, `selling_price` FROM `products` WHERE `prd_id`=:prd_id');
        $stmt->execute(array(':prd_id'=>$_GET['prd_id']));
        $prd_details = $stmt->fetch();

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
        }
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
        <title>About Products</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(3,"About Products") ?>
        <!-- items grid -->
        <div class="w3-row-padding">
            <select class="w3-col s5 w3-select w3-xlarge w3-border w3-margin" name="option" onchange="location = this.value;">
                <?php
                    if (isset($_GET['fy'])) {
                        $_SESSION['fy']=$_GET['fy'];
                        DateOptionADD($_GET['fy'],1,'./about.php?prd_id='.$_GET['prd_id'].'&fy='); //params 0-today or 2018
                        $_GET["FY"]=$FY;
                    }
                    else{
                        DateOptionADD(0,1,'./about.php?prd_id='.$_GET['prd_id'].'&fy=');
                        $_GET["FY"]=$FY;
                    } 
                ?>
            </select>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>Product Info</h3>
                </header>
                <div class="w3-container">
                <form name='addnew' method="post">
                        <div class="w3-row">
                            <div class="w3-col m5 w3-padding ">
                                <label for="prd_name">Product Name :</label>
                                <input id="prd_name" class="w3-input" type="text" name="prd_name" size="30" tabindex="1" value='<?= $prd_details['prd_name'] ?>' required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="s_name">Supplier name:</label>
                                <input id="s_name" class="w3-input" type="text" name="s_name" size="30" tabindex="2" value='<?= $prd_supp ?>' required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="cost_price">Cost Price:</label>
                                <input id="cost_price" class="w3-input" type="text" name="cost_price" value='<?= $supp_details[0]['cost_price'] ?>' tabindex="3"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="mrp">MRP:</label>
                                <input class="w3-input" type="text" name="mrp" size="30"  value='<?= $prd_details['mrp'] ?>' tabindex="4"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="sell_price">Selling Price:</label>
                                <input id="sell_price" class="w3-input" type="text" name="sell_price" size="30"  value='<?= $prd_details['selling_price'] ?>' tabindex="5"/>
                            </div>
                        </div><br>                 
                        <button class="w3-button w3-block w3-dark-grey" type="submit" name="update"  value='<?= $_GET['prd_id'] ?>' >UPDATE</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>Purchases & Sales</h3>
                </header>
                <div class="w3-container w3-margin">
                    <table class="display w3-table-all w3-small sk-table">
                        <thead>
                            <tr>
                                <th class="w3-center">Month</th>
                                <th class="w3-center">Purchase</th>
                                <th class="w3-center">Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $months=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            for ($i=0;$i<12;$i++) {
                                echo('<tr>');
                                echo('<td class="w3-center">'.$months[$i].'</td>');
                                echo('<td class="w3-center">'.$purchase[$i].'</td>');
                                echo('<td class="w3-center">'.$sale[$i].'</td>');
                                echo('</tr>');
                            }
                            echo('<tr><td class="w3-center w3-yellow">Total</td>
                                      <td class="w3-center w3-yellow">'.array_sum($purchase).'</td>
                                      <td class="w3-center w3-yellow">'.array_sum($sale).'</td>
                                </tr>');
                            ?>
                        </tbody>
                    </table><br>
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
            
            function split( val ) {
                return val.split( /,\s*/ );
            }
            
            function extractLast( term ) {
                return split( term ).pop();
            }
 
            $( "#s_name" )
            // don't navigate away from the field on tab when selecting an item
            .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    $.getJSON( "../GET/supplierget.php", {
                        term: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = extractLast( this.value );
                    if ( term.length < 2 ) {
                        return false;
                    }
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
