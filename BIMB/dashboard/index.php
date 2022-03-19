<?php

use function PHPSTORM_META\type;

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

    $pur_record="[";
    foreach([4,5,6,7,8,9,10,11,12,1,2,3] as $i) {
        $stmt = $pdo->prepare('SELECT sum(`total`) FROM `purchase` WHERE Month(`pur_date`)=:mont and `pur_date` BETWEEN :startdate and :enddate GROUP by Month(`pur_date`)');
        $stmt->execute(array(':mont'=>$i, ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
        $row = $stmt->fetch();
        if($row['sum(`total`)']){
        $pur_record=$pur_record.$row['sum(`total`)'].",";
        } else{
            $pur_record=$pur_record.'0'.",";
        }
    }
    $pur_record=$pur_record."]";

    $sales_record="[";
    foreach([4,5,6,7,8,9,10,11,12,1,2,3] as $i) {
        $stmt = $pdo->prepare('SELECT sum(`total`) FROM `sales` WHERE Month(`sale_date`)=:mont and `sale_date` BETWEEN :startdate and :enddate GROUP by Month(`sale_date`)');
        $stmt->execute(array(':mont'=>$i, ':startdate'=>date($startdate), ':enddate'=>date($enddate)));
        $row = $stmt->fetch();
        if($row['sum(`total`)']){
        $sales_record=$sales_record.$row['sum(`total`)'].",";
        } else{
            $sales_record=$sales_record.'0'.",";
        }
    }
    $sales_record=$sales_record."]";

    $prev_startdate=date('Y-m-d',strtotime($startdate." -1 year"));
    $prev_enddate=date('Y-m-d',strtotime($enddate." -1 year"));
    $todaymonth = date('m');

    $stmt = $pdo->prepare('SELECT purchase_product.prd_id,prd_name,sum(qnt) FROM `purchase_product` join purchase on purchase_product.pur_id=purchase.pur_id join products on products.prd_id=purchase_product.prd_id where Month(`pur_date`)=:mont and purchase.pur_date BETWEEN :startdate and :enddate group by prd_id  ORDER BY `sum(qnt)`  DESC LIMIT 20 ');
    $stmt->execute(array(':mont'=>$todaymonth, ':startdate'=>date($prev_startdate), ':enddate'=>date($prev_enddate)));
    $purchase_product = $stmt->fetchall();
    $countpur_prd = $stmt->rowCount();

    $stmt = $pdo->prepare('SELECT sales_product.prd_id,prd_name,sum(qnt) FROM `sales_product` join sales on sales_product.sale_id=sales.sale_id join products on products.prd_id=sales_product.prd_id where Month(`sale_date`)=:mont and sales.sale_date BETWEEN :startdate and :enddate group by prd_id ORDER BY `sum(qnt)` DESC LIMIT 20');
    $stmt->execute(array(':mont'=>$todaymonth, ':startdate'=>date($prev_startdate), ':enddate'=>date($prev_enddate)));
    $sales_product = $stmt->fetchall();
    $countsales_prd = $stmt->rowCount();

    require_once "..//util/header.php";
?>
        <title>Dashboard</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(-1,"Dashboard") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
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

        <div class="w3-row">
            <div class="w3-half">
                <div class="w3-card-4 w3-round-large" style="margin: 5px;">
                    <div class="w3-container w3-center">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="w3-half">
                <div class="w3-card-4 w3-round-large" style="margin: 5px;">
                    <div class="w3-container w3-center">
                        <canvas id="myChart1"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="w3-row">
            <div class="w3-responsive w3-card-4 w3-round-large" style="margin: 5px;">
                <table class="w3-table-all w3-small w3-centered sk-table">
                    <thead>
                        <tr>
                            <th colspan="3">Previous Purchase</th>
                            <th class="w3-yellow"></th>
                            <th colspan="3">Previous sales</th>
                        </tr>
                        <tr>
                            <th>No.</th><th>Product Name</th><th>Qnt</th>
                            <th class="w3-yellow"></th>
                            <th>No.</th><th>Product Name</th><th>Qnt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($countpur_prd>$countsales_prd){
                                $maxline=$countpur_prd;
                            } else{
                                $maxline=$countsales_prd;
                            }
                            $purflag=0;
                            if($countpur_prd==0){
                                $purflag=1;
                            }
                            if($countsales_prd==0){
                                $salesflag=1;
                            }
                            if($maxline==0){
                                echo('<tr><td colspan="7" class="w3-yellow">NO DATA AVAILABE</td></tr>');
                            }
                            for ($i=0;$i<$maxline;$i++){
                                echo('<tr>');
                                if ($i<$countpur_prd){
                                    $r=$purchase_product[$i];
                                    echo('<td>'.($i+1).'</td><td>'.$r['prd_name'].'</td><td>'.$r['sum(qnt)'].'</td>');
                                } else {
                                    if($purflag==1){
                                        echo('<td colspan="3" class="w3-yellow">NO DATA AVAILABE</td>');
                                        $purflag=0;
                                    } else {
                                        echo('<td></td><td></td><td></td>');
                                    }
                                }

                                echo('<td class="w3-yellow"></td>');

                                if ($i<$countsales_prd){
                                    $r=$sales_product[$i];
                                    echo('<td>'.($i+1).'</td><td>'.$r['prd_name'].'</td><td>'.$r['sum(qnt)'].'</td>');
                                } else {
                                    if($salesflag==1){
                                        echo('<td colspan="3" class="w3-yellow">NO DATA AVAILABE</td>');
                                        $salesflag=0;
                                    } else {
                                        echo('<td></td><td></td><td></td>');
                                    }
                                }
                                
                                echo('</tr>');
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
        <script src="../util/chart-3.7.1/chart.js"></script>
        <script>
            $( document ).ready(function() {
                const data = {
                    labels: ["April","May","June","July","August","September","October","November","December","January","February","March"],
                    datasets: [{
                        label: 'Purchase',
                        backgroundColor: 'rgb(255, 0, 0)',
                        borderColor: 'rgb(255, 0, 0)',
                        data: <?=$pur_record?>,
                    },
                    {
                        label: 'Sales',
                        backgroundColor: 'rgb(0, 255, 0)',
                        borderColor: 'rgb(0, 255, 0)',
                        data: <?=$sales_record?>,
                    }]
                };

                const myChart = new Chart(document.getElementById('myChart'),
                {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        stacked: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Sales And Purchase Data'
                            }
                        },
                    },
                });
                        
                var todayDate = new Date().toISOString().slice(0, 10);
                var url='http://127.0.0.1:5000/getpred?date='+todayDate+'&NOD=5';
                $.getJSON( url, function( data ) {
                    var lab2 = [];
                    var dat2 =[];
                    $.each( data, function( key, val ) {
                        lab2.push(key);
                        dat2.push(val);
                    });

                    data2 = {
                        labels:lab2,
                        datasets: [{
                            label: 'Sales prediction',
                            backgroundColor: 'rgb(0, 255, 0)',
                            borderColor: 'rgb(0, 255, 0)',
                            data:dat2
                        }]
                    };

                    const myChart1 = new Chart(
                    document.getElementById('myChart1'),
                    {
                        type: 'line',
                        data: data2,
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            stacked: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: '7 Days Prediction'
                                }
                            },
                        },
                    });
                });
            });
        </script>
    </body>
</html>
