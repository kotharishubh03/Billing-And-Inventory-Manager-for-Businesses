<?php
    require_once "..//util/pdo.php";
    require_once "..//util/Classes.php";
    require_once "..//util/functions.php";
?>
    
<?php
    require_once "..//util/header.php";
?>
        <title>Purchase</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(0,"Purchase") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">Add New Purchase</a>
            <select class="w3-col s5 w3-select w3-xlarge w3-border w3-margin" name="option" onchange="location = this.value;">
                <?php 
                    if (isset($_GET['fy'])) {
                        DateOptionADD($_GET['fy']); //params 0-today or '2018'
                    }
                    else{
                        DateOptionADD(0);
                    } 
                ?>
            </select>
        </div>
        
        <div class="w3-row-padding">
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr><th>First name</th><th>Last name</th><th>Position</th><th>Office</th><th>Salary</th><th>Salary</th></tr>
                </thead>
                <tbody>
                    <tr><td>Tiger</td><td>Nixon</td><td>System Architect</td><td>Edinburgh</td><td>15</td><td>15</td></tr>
                    <tr><td>Garrett</td><td>Winters</td><td>Accountant</td><td>Tokyo</td><td></td><td></td></tr>
                    <tr><td>Donna</td><td>Snider</td><td>Customer Support</td><td>New York</td><td>10</td><td>10</td></tr>
                </tbody>
                <tfoot>
                    <tr><th colspan="4" style="text-align:right">Total:</th><th></th><th></th></tr>
                </tfoot>
            </table>
        </div>
        <div class="w3-row-padding">
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr><th>First name</th><th>Last name</th><th>Position</th><th>Office</th><th>Salary</th><th>Salary</th></tr>
                </thead>
                <tbody>
                    <tr><td>fj</td><td>gj</td><td>fgjrchitect</td><td>Exfgjrgh</td><td>17</td><td>15</td></tr>
                    <tr><td>Garrett</td><td>Winters</td><td>Accountant</td><td>Tokyo</td><td></td><td></td></tr>
                    <tr><td>Donna</td><td>Snider</td><td>Customer Support</td><td>New York</td><td>10</td><td>10</td></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right">Total:</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>



        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>

        <script>
            $(document).ready(function() {
                $('table.display').DataTable( {
                    dom: 'Bfrtip',
                    buttons: [
                        'colvis',
                        'excel',
                        'print',
                        'pdf'
                    ],
                    scrollY:'50vh',
                    scrollX: true,
                    scrollCollapse: true,
                    paging:false,
                    ordering: false,
                    searching: false,

                    "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
 
                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over this page
                        pageTotal4 = api
                            .column( 4, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                        pageTotal5 = api
                            .column( 5, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
    
                        // Update footer
                        $( api.column( 4 ).footer() ).html(
                            '$'+pageTotal4
                        );
                        $( api.column( 5 ).footer() ).html(
                            '$'+pageTotal5
                        );
                    }
                } );
            } );
        </script>
    </body>
</html>
