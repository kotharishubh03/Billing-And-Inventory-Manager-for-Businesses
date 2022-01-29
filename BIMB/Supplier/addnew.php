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

    if(isset($_GET["add"])) {
        if ($_GET["add"]==1) {
            $stmt = $pdo->prepare('INSERT INTO `suppliers`(`supp_name`, `gstno`) VALUES (:supp_name,:gstno)');
            $stmt->execute(array( ':supp_name' => $_GET['supp_name'], ':gstno' => $_GET['gstno']));
            $supp_id = $pdo->lastInsertId();
            if ($_GET['pre_bal']==0) {}
            else {
                $stmt = $pdo->prepare('INSERT INTO `supp_pre_bal`(`supp_id`, `fy_id`, `amt`) VALUES (:supp_id,(SELECT `fy_id` FROM `financialyear` WHERE YEAR(`start_date`)=YEAR(:startdate) limit 1),:pre_bal)');
                $stmt->execute(array( ':supp_id' => $supp_id, ':startdate'=>date($startdate), ':pre_bal' => $_GET['pre_bal']));
            }
            header("Location: ./index.php");
            return;
        }
    }
    require_once "..//util/header.php";
?>
        <title>Suppliers</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(2,"Suppliers") ?>
        <!-- items grid -->
        <div class="w3-col w3-padding">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>ADD NEW SUPPLIER</h3>
                </header>
                <div class="w3-container">
                    <form name='addnew' method="get">
                        <div class="w3-col m4 w3-padding">
                            <label for="s_name">Supplier name:</label>
                            <input class="w3-input" type="text" name="supp_name" size="30" required/>
                        </div>
                        <div class="w3-col m4 w3-padding">
                            <label for="s_gst_no">GST NO.:</label>
                            <input class="w3-input" type="text" name="gstno" size="30" required/>
                        </div>
                        <div class="w3-col m4 w3-padding">
                            <label for="pre_bal">Previous Balance:</label>
                            <input class="w3-input" type="text" name="pre_bal" size="30" required/>
                        </div><br>
                        <button class="w3-button w3-block w3-dark-grey" type="submit" name="add" value="1" >ADD NEW </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
