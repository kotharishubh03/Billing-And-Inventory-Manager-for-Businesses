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

        <!--div class="w3-row w3-responsive w3-margin">
            <?php
                $months=['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December','January', 'February', 'March'];
                echo('<div class="w3-bar w3-black sk-border-except-bottom">');
                for ($i=0;$i<12;$i++){
                    echo('<button class="w3-bar-item w3-button tablink" onclick="openCity(event,\''.$months[$i].'\')">'.$months[$i].'</button>');
                }
                echo('</div>');
            
            for ($i=0;$i<12;$i++){
                echo('<div id="'.$months[$i].'" class="w3-container sk-border-except-top city" style="display:none">
                    <h2>'.$months[$i].'</h2>
                    
                </div>');
            }
            ?>
        </div-->

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
        <script>
            function openCity(evt, cityName) {
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