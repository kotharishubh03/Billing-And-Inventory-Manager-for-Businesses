<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";

    if (isset($_GET['fy'])) {
        $_SESSION['fy']=$_GET['fy'];
        DateOptionADD($_GET['fy'],0,'./index.php?fy='); //params 0-today or '2018'
        $_GET["FY"]=$FY;
    }
    else{
        DateOptionADD(0,0,'./index.php?fy=');
        $_GET["FY"]=$FY;
    }

    echo('<script src="../util/chart-3.7.1/chart.js"></script>');
    require_once "..//util/header.php";
?>
        <title>Sales</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(1,"Sales") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-xlarge w3-black w3-margin">Add New Sales</a>
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

        <div class="w3-row-padding w3-responsive">

        </div>

            <?php
                $month_variable=[4,5,6,7,8,9,10,11,12,1,2,3];
                $month_string=["April","May","June","July","August","September","October","November","December","January","February","March"];
                echo('<div class="w3-bar w3-black sk-border-except-bottom">');
                for ($i=0;$i<12;$i++){
                    echo('<button class="w3-bar-item w3-button tablink" onclick="openMonth(event,\''.$month_string[$i].'\')">'.$month_string[$i].'</button>');
                }
                echo('</div>');
            
                for ($i=0;$i<12;$i++){
                    $temp=0;
                    $disc=0;
                    $stmt = $pdo->prepare('SELECT `sale_id`, customers.`cus_id`,`cus_name`, `bill_no`, `sale_date`, `total`, `discount`, `pay_type`,`pay_mode`, `pay_date` FROM `sales` join customers on customers.`cus_id`=sales.cus_id join payment_mode on sales.pay_type=payment_mode.pay_mode_id WHERE Month(`sale_date`)=:mont and `sale_date` BETWEEN :startdate and :enddate ORDER BY `sale_date` ASC');
                    $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
                    $row = $stmt->fetchall();
                    echo('<div id="'.$month_string[$i].'" class="w3-container sk-border-except-top city" style="display:none">
                        <h2>'.$month_string[$i].' ('.$_GET["FY"].')'.'</h3>');
                        if ( $row == false ) {
                            echo('<div class="w3-panel w3-blue">
                            <h3>No Record\'s Found!</h3>
                            <p>You Dont have any record For this Financial Year.</p>
                          </div>');
                        } else {
                            $stmt = $pdo->prepare('SELECT `sale_date` as day, sum(`total`) as total FROM `sales` WHERE Month(`sale_date`)=:mont and `sale_date` BETWEEN :startdate and :enddate  GROUP by sale_date ORDER BY `sale_date` ASC');
                            $stmt->execute(array(':mont'=>$month_variable[$i], ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
                            $m = $stmt->fetchall();
                            $label=[];
                            $data=[];
                            foreach($m as $r){
                                array_push($label,$r['day']);
                                array_push($data,$r['total']);
                            }

                            echo('<div class="w3-row"><div class="w3-threequarter">
                                <div class="w3-card-4 w3-round-large" style="margin: 5px;">
                                    <div class="w3-container w3-center">
                                        <canvas id="myChart'.$month_variable[$i].'"></canvas>
                                </div></div></div></div><br>');
                            echo('<script>
                            const myChart'.$month_variable[$i].' = new Chart(document.getElementById(\'myChart'.$month_variable[$i].'\'),
                            {
                                type: \'line\',
                                data: {
                                    labels: ["'.implode("\",\"",$label).'"],
                                    datasets: [{
                                        label: \'Sales\',
                                        backgroundColor: \'rgb(0, 255, 0)\',
                                        borderColor: \'rgb(0, 255, 0)\',
                                        data: ['.implode(",",$data).'],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    interaction: {
                                        mode: \'index\',
                                        intersect: false,
                                    },
                                    stacked: false,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: \'Sales Data\'
                                        }
                                    },
                                },
                            });
                            </script>');

                            echo('<div class="w3-responsive"><table class="w3-table-all w3-small w3-centered sk-table">');
                            echo('<tr> <th>Customer</th> <th>Bill No</th> <th>Sale Date</th> <th>Total</th> <th>Discount</th> <th>Payment Mode</th> <th>Payment Date</th></tr>');
                            foreach($row as $r) {
                                $temp=$temp+$r['total'];
                                $disc=$disc+$r['discount'];
                                echo('<tr> <th><a href="../customers/about.php?cus_id='.$r["cus_id"].'">'.$r['cus_name'].'</a></th> <th><a href="../sales/about.php?sale_id='.$r["sale_id"].'">'.$r['bill_no'].'</a></th> <th>'.date("d-m-Y", strtotime($r['sale_date'])).'</th> <th>'.$r['total'].'</th> <th>'.$r['discount'].'</th> <th>'.$r['pay_mode'].'</th> <th>'.date("d-m-Y", strtotime($r['pay_date'])).'</th></tr>');
                            }
                            echo('<tr> <th colspan="7"></th></tr>');
                            echo('<tr class="w3-yellow"> <th colspan="3">Total</th><th>'.$temp.'</th><th>'.$disc.'</th><th></th><th></th></tr>');
                            echo('</table></div>');
                        }
                    echo('</div>');
            }
            ?>

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
        <script>
            function openMonth(evt, cityName) {
            var i, x, tablinks;
            x = document.getElementsByClassName("city");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < x.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" w3-white", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " w3-white";
            }
        </script>
    </body>
</html>