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
    
    if (isset($_GET['prd_id'])) { 
        $stmt = $pdo->prepare('SELECT products.`prd_id`, `prd_name`, `co_name`, `prd_desc`, `prd_img_link`, `gst%` FROM `products` join company on company.co_id=products.prd_company join `supplier_products` on `products`.`prd_id`= `supplier_products`.`prd_id` WHERE products.prd_id=:prd_id');
        $stmt->execute(array(':prd_id'=>$_GET['prd_id']));
        $prd_details = $stmt->fetch();

        $stmt = $pdo->prepare('SELECT `sell_price`, `qnt`, `s_p_desc` FROM `supplier_products` WHERE prd_id=:prd_id and supp_id=:supp_id');
        $stmt->execute(array(':prd_id'=>$_GET['prd_id'],':supp_id'=>$_SESSION['my_supp_id']));
        $supp_prd_details = $stmt->fetch();
    }

    if (isset($_POST['update'])) { 
        if ($_FILES["prd_img"]["error"] > 0){
            echo "Error: " . $_FILES["prd_img"]["error"] . "<br>";
        } else {
            $temp = explode(".", $_FILES["file"]["name"]);
            $newfilename =  $_GET['prd_id']. '.' . $temp[-1];
            move_uploaded_file($_FILES["prd_img"]["tmp_name"],
            "./prd_img/" . $newfilename);
        }
    }

    require_once "..//util/header.php";
?>
        <title>About Product</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(4,"About Product") ?>
        <!-- items grid -->

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>Product Info</h3>
                </header>
                <div class="w3-container">
                <form name='addnew' method="post" enctype="multipart/form-data">
                        <div class="w3-row">
                            <div class="w3-col m5 w3-padding ">
                                <label for="prd_name">Product Name :</label>
                                <input id="prd_name" class="w3-input" type="text" name="prd_name" size="30" tabindex="1" value='<?= $prd_details['prd_name'] ?>' required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="co_name">Company name:</label>
                                <input id="co_name" class="w3-input" type="text" name="co_name" size="30" tabindex="2" value='<?= $prd_details['co_name'] ?>' required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="prd_desc">Product Description:</label>
                                <input id="prd_desc" class="w3-input" type="text" name="prd_desc" value='<?= $prd_details['prd_desc'] ?>' tabindex="3"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="gst%">GST :</label>
                                <input class="w3-input" type="text" name="gst%" size="30"  value='<?= $prd_details['gst%'] ?>' tabindex="4"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="prd_img">Product Image:</label>
                                <input id="prd_img" class="w3-input" type="file" name="prd_img" size="30"  value='<?= $prd_details['selling_price'] ?>' tabindex="5"/>
                            </div>
                        </div><br>                 
                        <button class="w3-button w3-block w3-dark-grey" type="submit" name="update"  value='<?= $_GET['prd_id'] ?>' >UPDATE</button>
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
