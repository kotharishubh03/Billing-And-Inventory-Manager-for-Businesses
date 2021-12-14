<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    require_once "..//util/header.php";
?>
        <title>Purchase</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(0,"Purchase") ?>
        <!-- items grid -->

        <div class="w3-row-padding">
            <a href="./addnew.php" class="w3-col s5 w3-button w3-large w3-black w3-margin">Add New Purchase</a>
            <select class="w3-col s5 w3-select w3-border w3-margin" name="option" onchange="location = this.value;">
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
        
        
        <div>

        </div>


        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
