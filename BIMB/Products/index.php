<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    require_once "..//util/Classes.php";

    if (isset($_GET['fy'])) {
        $_SESSION['fy']=$_GET['fy'];
        DateOptionADD($_GET['fy'],0,'./index.php?fy='); //params 0-today or '2018'
        $_GET["FY"]=$FY;
    }
    else{
        DateOptionADD(0,0,'./index.php?fy=');
        $_GET["FY"]=$FY;
    }

    updatefydata($pdo);

    $prdarr=[];  
    $stmt = $pdo->prepare('SELECT `products`.`prd_id`, `prd_name` FROM `products` join `products_supplier` on `products`.`prd_id`= `products_supplier`.`prd_id` ORDER BY `products`.`prd_id` ASC');
    $stmt->execute(array());
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]=array("prd_id"=>$r['prd_id'],"prd_name"=>$r["prd_name"],"supp_name"=>"");
    }

    $stmt = $pdo->prepare('SELECT `products`.`prd_id`, `suppliers`.`supp_id`,`suppliers`.`supp_name` FROM `products` left join `products_supplier` on `products`.`prd_id`= `products_supplier`.`prd_id` left join `suppliers` on `suppliers`.`supp_id`=`products_supplier`.`supp_id` ORDER BY `products`.`prd_id` ASC');
    $stmt->execute(array());
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]["supp_name"]=$prdarr[$r["prd_id"]]["supp_name"].'<a href="../supplier/about.php?supp_id='.$r["supp_id"].'">'.$r["supp_name"].'</a>,';
    }

    //var_dump($prdarr);
    // SELECT `purchase`.`date`, `prd_id`, SUM(`qnt`) as sm FROM `purchase_products` join purchase on purchase.p_id=purchase_products.p_id WHERE purchase.date BETWEEN :startdate and :enddate GROUP By purchase_products.prd_id
    $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `purchase_product` join purchase on purchase.pur_id=purchase_product.pur_id WHERE purchase.pur_date BETWEEN :startdate and :enddate GROUP By purchase_product.prd_id');
    $stmt->execute(array(':startdate'=>date($startdate), ':enddate'=>date($enddate)));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]["bought"]=$r["sm"];
    }
    $month_variable=[4,5,6,7,8,9,10,11,12,1,2,3];
    $month_string_buy=["Apr_b","May_b","Jun_b","Jul_b","Aug_b","Sep_b","Oct_b","Nov_b","Dec_b","Jan_b","Feb_b","Mar_b"];
    $month_string_sold=["Apr_s","May_s","Jun_s","Jul_s","Aug_s","Sep_s","Oct_s","Nov_s","Dec_s","Jan_s","Feb_s","Mar_s"];
    for ($i=0;$i<12;$i++) {
        $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `purchase_product` join purchase on purchase.pur_id=purchase_product.pur_id WHERE Month(`purchase`.`pur_date`)=:mont and purchase.pur_date BETWEEN :startdate and :enddate GROUP By purchase_product.prd_id');
        $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
        $row = $stmt->fetchall();
        foreach ($row as $r) {
            $prdarr[$r["prd_id"]][$month_string_buy[$i]]=$r["sm"];
        }

        $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `sales_product` join sales on `sales`.`sale_id`=`sales_product`.`sale_id` WHERE Month(`sales`.`sale_date`)=:mont and `sales`.`sale_date` BETWEEN :startdate and :enddate GROUP By `sales_product`.`prd_id` order by `prd_id`');
        $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
        $row = $stmt->fetchall();
        foreach ($row as $r) {
            $prdarr[$r["prd_id"]][$month_string_sold[$i]]=$r["sm"];
        }

    }
    //var_dump($prdarr);
    
    $stmt = $pdo->prepare('SELECT `prd_id`, `qnt` FROM `pre_bal_product` WHERE `fy_id`=(SELECT `fy_id` from `financialyear` where YEAR(`financialyear`.`start_date`)=YEAR(:startdate))');
    $stmt->execute(array(':startdate'=>date($startdate)));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]["prebal"]=$r["qnt"];
    }

    
    $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `sales_product` join sales on sales.sale_id=sales_product.sale_id WHERE sales.sale_date BETWEEN :startdate and :enddate GROUP By sales_product.prd_id order by prd_id');
    $stmt->execute(array(':startdate'=>date($startdate), ':enddate'=>date($enddate)));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]["sold"]=$r["sm"];
    }
    //var_dump($prdarr);
    foreach ($prdarr as $r) {
        if (!isset($prdarr[$r["prd_id"]]['prebal'])) {$prdarr[$r["prd_id"]]['prebal']=0;}
        if (!isset($prdarr[$r["prd_id"]]['bought'])) {$prdarr[$r["prd_id"]]['bought']=0;}
        if (!isset($prdarr[$r["prd_id"]]['sold'])) {$prdarr[$r["prd_id"]]['sold']=0;}
        $prdarr[$r["prd_id"]]["balrem"]=$prdarr[$r["prd_id"]]["prebal"]+$prdarr[$r["prd_id"]]["bought"]-$prdarr[$r["prd_id"]]["sold"];
    }
    
    require_once "..//util/header.php";
?>
        <title>Products</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(3,"Products") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">Add New Product</a>
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
        
        <div class="w3-container">
            <table id="table" class="display w3-table-all w3-small sk-table" >
                <thead>
                    <tr>
                        <th rowspan="2" >Product ID</th>
                        <th rowspan="2" >Product Name</th>
                        <th rowspan="2" >Supplier Name</th>
                        <th rowspan="2" >Previous Balance</th>
                        <th rowspan="2" >Total Purchased</th>
                        <th rowspan="2" >Total Sold</th>
                        <th rowspan="2" >Balance remaining</th>
                        <?php
                        $months=['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December','January', 'February', 'March'];
                        foreach($months as $month){
                            echo('<th class="w3-center" colspan="2" >'.$month.'</th>');
                        }
                        ?>
                        <!--th class="w3-center" colspan="2" >May</th>
                        <th class="w3-center" colspan="2" >June</th>
                        <th class="w3-center" colspan="2" >July</th>
                        <th class="w3-center" colspan="2" >August</th>
                        <th class="w3-center" colspan="2" >September</th>
                        <th class="w3-center" colspan="2" >Octomber</th>
                        <th class="w3-center" colspan="2" >Nov</th>
                        <th class="w3-center" colspan="2" >Dec</th>
                        <th class="w3-center" colspan="2" >Jan</th>
                        <th class="w3-center" colspan="2" >Feb</th>
                        <th class="w3-center" colspan="2" >Mar</th-->
                    </tr>
                    <tr>
                        <?php
                        for ($i=0;$i<12;$i++) {
                        echo('<th>Buy</th>
                        <th>Sell</th>');
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($prdarr as $r){
                    echo('<tr>
                        <td>'.$r['prd_id'].'</td>
                        <td><a href="./about.php?prd_id='.$r['prd_id'].'">'.$r['prd_name'].'</a></td>
                        <td>'.$r['supp_name'].'</td>
                        <td>');if (isset($r['prebal'])) {echo($r['prebal']);} echo('</td>
                        <td>');if (isset($r['bought'])) {echo($r['bought']);} echo('</td>
                        <td>');if (isset($r['sold'])) {echo($r['sold']);} echo('</td>
                        <td class="w3-yellow">');if (isset($r['balrem'])) {echo($r['balrem']);} echo('</td>');
                        $month_variable=[4,5,6,7,8,9,10,11,12,1,2,3];
                        $month_string_buy=["Apr_b","May_b","Jun_b","Jul_b","Aug_b","Sep_b","Oct_b","Nov_b","Dec_b","Jan_b","Feb_b","Mar_b"];
                        $month_string_sold=["Apr_s","May_s","Jun_s","Jul_s","Aug_s","Sep_s","Oct_s","Nov_s","Dec_s","Jan_s","Feb_s","Mar_s"];
                        for ($i=0;$i<12;$i++) {
                        echo('<td class="w3-pale-green">');if (isset($r[$month_string_buy[$i]])) {echo($r[$month_string_buy[$i]]);} echo('</td>
                        <td class="w3-pale-red">');if (isset($r[$month_string_sold[$i]])) {echo($r[$month_string_sold[$i]]);} echo('</td>');
                        }
                        //<td>');if (isset($r['May_b'])) {echo($r['May_b']);} echo('</td>
                        //<td>');if (isset($r['May_s'])) {echo($r['May_s']);} echo('</td>
                        //<td>');if (isset($r['Jun_b'])) {echo($r['Jun_b']);} echo('</td>
                        //<td>');if (isset($r['Jun_s'])) {echo($r['Jun_s']);} echo('</td>
                        //<td>');if (isset($r['Jul_b'])) {echo($r['Jul_b']);} echo('</td>
                        //<td>');if (isset($r['Jul_s'])) {echo($r['Jul_s']);} echo('</td>
                        //<td>');if (isset($r['Aug_b'])) {echo($r['Aug_b']);} echo('</td>
                        //<td>');if (isset($r['Aug_s'])) {echo($r['Aug_s']);} echo('</td>
                        //<td>');if (isset($r['Sep_b'])) {echo($r['Sep_b']);} echo('</td>
                        //<td>');if (isset($r['Sep_s'])) {echo($r['Sep_s']);} echo('</td>
                        //<td>');if (isset($r['Oct_b'])) {echo($r['Oct_b']);} echo('</td>
                        //<td>');if (isset($r['Oct_s'])) {echo($r['Oct_s']);} echo('</td>
                        //<td>');if (isset($r['Nov_b'])) {echo($r['Nov_b']);} echo('</td>
                        //<td>');if (isset($r['Nov_s'])) {echo($r['Nov_s']);} echo('</td>
                        //<td>');if (isset($r['Dec_b'])) {echo($r['Dec_b']);} echo('</td>
                        //<td>');if (isset($r['Dec_s'])) {echo($r['Dec_s']);} echo('</td>
                        //<td>');if (isset($r['Jan_b'])) {echo($r['Jan_b']);} echo('</td>
                        //<td>');if (isset($r['Jan_s'])) {echo($r['Jan_s']);} echo('</td>
                        //<td>');if (isset($r['Feb_b'])) {echo($r['Feb_b']);} echo('</td>
                        //<td>');if (isset($r['Feb_s'])) {echo($r['Feb_s']);} echo('</td>
                        //<td>');if (isset($r['Mar_b'])) {echo($r['Mar_b']);} echo('</td>
                        //<td>');if (isset($r['Mar_s'])) {echo($r['Mar_s']);} echo('</td>
                    //</tr>');
                    }
                ?>
                </tbody>
            </table>
        </div>

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
        <script src="../util/DataTables/datatables.min.js"></script>
        <script>
        $(document).ready(function () {
            window.console && console.log('Document ready called');

            $('#table').DataTable( {
                "scrollY": 380,
                "scrollX": true,
                dom: 'Bfrtip',
                lengthMenu: [[ 10, 25, 50, -1 ],[ '10 rows', '25 rows', '50 rows', 'Show all' ]],
                buttons: {buttons: ['pageLength', 'copy', 'excel', 'pdf', 'print' ]},
                fixedColumns:   {leftColumns: 2,},
                columnDefs: [ {
                    targets: [ 1 ],
                    orderData: [ 2, 1 ]
                }, {
                    targets: [ 2 ],
                    orderData: [ 2, 1 ]
                },
                ],
            });
        });
        </script>
    </body>
</html>
