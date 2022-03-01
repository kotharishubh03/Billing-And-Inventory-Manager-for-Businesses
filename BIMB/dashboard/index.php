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
                        hello world
                    </div>
                </div>
            </div>
        </div>

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
        <script src="../util/chart-3.7.1/chart.js"></script>
        <script>
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

            const myChart = new Chart(
                document.getElementById('myChart'),
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
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                            },
                        }
                    },
                }
            );
        </script>
    </body>
</html>
