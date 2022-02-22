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
    
    require_once "..//util/header.php";
?>
        <title>Customers</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(6,"Customers") ?>
        <!-- items grid -->

        

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
