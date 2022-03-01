<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";

    if(isset($_POST['shop_info'])){
    
        $stmt = $pdo->prepare('SELECT `s_key` FROM `shop_info`');
        $stmt->execute(array());
        $s_key = $stmt->fetchall();
        foreach($s_key as $key){
            $stmt = $pdo->prepare('UPDATE `shop_info` SET `value`=:val WHERE `s_key`=:s_key');
            $stmt->execute(array(':val'=>$_POST[$key['s_key']],':s_key'=>$key['s_key']));
        }
        $_SESSION['success']="Successfully Saved ";
        header("Location: ./index.php");
        return;
    }
    $stmt = $pdo->prepare('SELECT * FROM `shop_info`');
    $stmt->execute(array());
    $temp = $stmt->fetchall();
    $shopinfo=array();
    foreach($temp as $tem){
        $shopinfo[$tem['s_key']]=$tem['value'];
    }

    require_once "..//util/header.php";
?>
        <title>Settings</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php mainbody(7,"Settings"); 
        flashMessage();?>
        <!-- items grid -->

        <div class="w3-row w3-margin">
            <div class="w3-card-4">
                <div class="w3-container">
                    <form name='shop_info' method="post">
                        <div class="w3-row">
                            <h3>SHOP INFO</h3>
                            <div class="w3-col m5 w3-padding ">
                                <label for="shop_name">Shop name:</label>
                                <input id="shop_name" class="w3-input" type="text" name="shop_name" size="30" placeholder="Type Shop Name" value="<?=$shopinfo['shop_name']?>" tabindex="1" required/>
                            </div><div class="w3-col m5 w3-padding ">
                                <label for="shop_gstin">GSTIN:</label>
                                <input class="w3-input" type="text" name="shop_gstin" size="30" placeholder="Type Shop GSTIN" value="<?=$shopinfo['shop_gstin']?>" required tabindex="3"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="shop_email">Email:</label>
                                <input class="w3-input" type="email" name="shop_email" size="30" placeholder="Type Shop Email" value="<?=$shopinfo['shop_email']?>" required tabindex="4"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="shop_telephone">Telephone No.:</label>
                                <input class="w3-input" type="text" name="shop_telephone" placeholder="Type Shop Telephone No." value="<?=$shopinfo['shop_telephone']?>" required tabindex="5"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="shop_address">Shop Address:</label>
                                <textarea class="w3-input" type="text" name="shop_address" placeholder="Type Shop Address" required  tabindex="6"><?=$shopinfo['shop_address']?></textarea>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="shop_bill_terms">Shop Bill Terms:</label>
                                <textarea class="w3-input" type="text" name="shop_bill_terms" placeholder="Type Shop Terms" required tabindex="7"><?=$shopinfo['shop_bill_terms']?></textarea>
                            </div>
                            <button class="w3-button w3-block w3-dark-grey" type="submit" name="shop_info" value="1" >Save </button>
                        </div>
                    </form>
                    <!--hr>
                    <form name='new_user' method="post">
                        <h3>ADD NEW USER</h3>
                        <div class=w3-row>
                            <div class="w3-col m5 w3-padding ">
                                <label for="usr_name">User Name:</label>
                                <input class="w3-input" type="text" name="usr_name" placeholder="Type Shop Address" required tabindex="8"></input>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="password">Password:</label>
                                <input class="w3-input" type="text" name="password" placeholder="Type Shop Terms" required tabindex="9"></input>
                            </div>
                            <button class="w3-button w3-block w3-dark-grey" type="submit" name="usr_add" value="1" >Add new </button>
                        </div>
                    </form-->
                </div>
            </div>
        </div>

        <!-- END items grid -->
        <?php
            require_once "..//util/footer.php";
        ?>
    </body>
</html>
