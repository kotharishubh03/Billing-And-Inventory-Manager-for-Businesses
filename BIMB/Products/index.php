<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    require_once "..//util/header.php";
    require_once "..//util/Classes.php";
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
                        DateOptionADD($_GET['fy']); //params 0-today or '2018'
                        $_GET["FY"]=$FY;
                    }
                    else{
                        DateOptionADD(0);
                        $_GET["FY"]=$FY;
                    } 
                ?>
            </select>
        </div>
        
        <div class="w3-container">
            <table id="table" class="display w3-table-all w3-small sk-table" >
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Supplier Name</th>
                        <th>Previous Balance</th>
                        <th>Total Purchased</th>
                        <th>Total Sold</th>
                        <th>Balance remaining</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                    </tr>
                </thead>
                <tbody>

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
                "scrollY": 450,
                "scrollX": true,
                dom: 'Bfrtip',
                lengthMenu: [[ 10, 25, 50, -1 ],[ '10 rows', '25 rows', '50 rows', 'Show all' ]],
                buttons: {buttons: ['pageLength', 'copy', 'excel', 'pdf', 'print' ]},
                fixedColumns:   {leftColumns: 3,},
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
