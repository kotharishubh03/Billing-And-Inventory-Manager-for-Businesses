<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    $_SESSION['my_supp_id']=1;

    if (isset($_GET['fy'])) {
        $_SESSION['fy']=$_GET['fy'];
        DateOptionADD($_GET['fy'],0,'./index.php?fy='); //params 0-today or '2018'
        $_GET["FY"]=$FY;
    }
    else{
        DateOptionADD(0,0,'./index.php?fy=');
        $_GET["FY"]=$FY;
    }
    $stmt = $pdo->prepare('SELECT `products`.`prd_id`, `prd_name`,`sell_price`, `qnt`, `s_p_desc` FROM `products` join `supplier_products` on `products`.`prd_id`= `supplier_products`.`prd_id` Where `supplier_products`.`supp_id`=:supp_id ORDER BY `products`.`prd_id` ASC');
    $stmt->execute(array('supp_id'=>$_SESSION['my_supp_id']));
    $myprd = $stmt->fetchall();

    require_once "..//util/header.php";
?>
        <title>My Products</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(4,"My Products") ?>
        <!-- items grid -->

        <div class="w3-container w3-margin">
            <table id="table" class="display w3-table-all w3-small sk-table" >
                <thead>
                    <tr>
                        <th >Product ID</th>
                        <th >Product Name</th>
                        <th >Sell Price</th>
                        <th >Qnt Balance</th>
                        <th >description</th>
                        <th >Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($myprd as $prd){
                            echo('<tr>
                                <td>'.$prd['prd_id'].'</td>
                                <td><a href="./about.php?prd_id='.$prd['prd_id'].'">'.$prd['prd_name'].'</a></td>
                                <td>'.$prd['sell_price'].'</td>
                                <td>'.$prd['qnt'].'</td>
                                <td>'.$prd['s_p_desc'].'</td>
                                <td></td>
                            </tr>');
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
                dom: 'Bfrtip',
                lengthMenu: [[ 10, 25, 50, -1 ],[ '10 rows', '25 rows', '50 rows', 'Show all' ]],
                buttons: {buttons: ['pageLength', 'copy', 'excel', 'pdf', 'print' ]},
            });
        });
        </script>
    </body>
</html>
