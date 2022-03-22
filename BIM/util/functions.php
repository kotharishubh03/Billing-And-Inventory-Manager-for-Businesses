<?php
function nav ($st=0){
echo('<nav class="w3-sidebar w3-bar-block w3-black w3-collapse w3-top" style="z-index:3;width:250px;left:0" id="mySidebar">
    <div class="w3-container w3-display-container w3-padding-16">
        <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
            <h3 class="w3-wide"><b>BIMARKET</b></h3>
    </div>
    <div class="w3-padding-32 w3-large w3-text-grey" style="font-weight:bold">');
    if ($st==0){echo('<a href="../dashboard/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-line-chart w3-margin-right"></i>Dashboard</a>');} 
        else {echo('<a href="../dashboard/index.php" class="w3-bar-item w3-button"><i class="fa fa-line-chart w3-margin-right"></i>Dashboard</a>');}
    if ($st==1){echo('<a href="../purchase/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-shopping-cart w3-margin-right"></i>Purchase</a>');} 
        else {echo('<a href="../purchase/index.php" class="w3-bar-item w3-button"><i class="fa fa-shopping-cart w3-margin-right"></i>Purchase</a>');}
    if ($st==2){echo('<a href="../sales/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-id-card w3-margin-right"></i>Sales</a>');} 
        else {echo('<a href="../sales/index.php" class="w3-bar-item w3-button"><i class="fa fa-id-card w3-margin-right"></i>Sales</a>');}
    if ($st==3){echo('<a href="../supplier/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-truck w3-margin-right"></i>Suppliers</a>');} 
        else {echo('<a href="../supplier/index.php" class="w3-bar-item w3-button"><i class="fa fa-truck w3-margin-right"></i>Suppliers</a>');}
    if ($st==4){echo('<a href="../products/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-address-book w3-margin-right"></i>Products</a>');} 
        else {echo('<a href="../products/index.php" class="w3-bar-item w3-button"><i class="fa fa-address-book w3-margin-right"></i>Products</a>');}
    if ($st==5){echo('<a href="../payment/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-money w3-margin-right"></i>Payments</a>');} 
        else {echo('<a href="../payment/index.php" class="w3-bar-item w3-button"><i class="fa fa-money w3-margin-right"></i>Payments</a>');}
    if ($st==6){echo('<a href="../gstreports/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-pie-chart w3-margin-right"></i>GST reports</a>');} 
        else {echo('<a href="../gstreports/index.php" class="w3-bar-item w3-button"><i class="fa fa-pie-chart w3-margin-right"></i>GST reports</a>');}
    if ($st==7){echo('<a href="../customers/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-user w3-margin-right"></i>Customers</a>');} 
        else {echo('<a href="../customers/index.php" class="w3-bar-item w3-button"><i class="fa fa-user w3-margin-right"></i>Customers</a>');}
    if ($st==8){echo('<a href="../settings/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-gear w3-margin-right"></i>Settings</a>');} 
        else {echo('<a href="../settings/index.php" class="w3-bar-item w3-button "><i class="fa fa-gear w3-margin-right"></i>Settings</a>');}
    if ($st==9){echo('<a href="../contact/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-gear w3-margin-right"></i>Contact Us</a>');} 
        else {echo('<a href="../contact/index.php" class="w3-bar-item w3-button "><i class="fa fa-gear w3-margin-right"></i>Contact Us</a>');}
    if ($st==10){echo('<a href="./logout.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-arrow-left w3-margin-right"></i>Logout</a>');} 
        else {echo('<a href="./logout.php" class="w3-bar-item w3-button"><i class="fa fa-arrow-left w3-margin-right"></i>Logout</a>');}

    echo('

    </div>

</nav>');
}


function headerfunc(){
    echo('<header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
    <div class="w3-bar-item w3-padding-24 w3-wide">BIMARKET</div>
    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-padding-24 w3-right" onclick="w3_open()"><i class="fa fa-bars"></i></a>
</header>');
}


function tophead($st){
echo('<header class="w3-container w3-xlarge w3-black">
<p class="w3-left" style="border: none;display: inline-block;
padding: 8px 16px;vertical-align: middle;
text-decoration: none;color: inherit;background-color: inherit;
text-align: center;">'.$st.'</p>
</header>');
}


function mainbody($n,$name) {
    //<!--Sidebar/menu -->
    nav($n);
    //<!-- Top menu on small screens -->
    headerfunc();
    //<!-- Overlay effect when opening sidebar on small screens -->
    echo('<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>');
    //<!-- !PAGE CONTENT! -->
    echo('<div class="w3-main" style="margin-left:250px;width: inherit;">');
    //<!-- Push down content on small screens -->
    echo('<div class="w3-hide-large" style="margin-top:83px"></div>');
    //<!-- Top header -->
    tophead($name);
}


function flashMessage()
{
    if (isset($_SESSION['error'])) {
        echo('<div class="w3-panel w3-red w3-display-container"><h3>Error!</h3>
        <span onclick="this.parentElement.style.display=\'none\'"
        class="w3-button w3-red w3-large w3-display-topright">x</span>
            <p>' . htmlentities($_SESSION['error']) . '</p>
        </div>');
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo('
        <div class="w3-panel w3-green w3-display-container"><h3>Success!</h3>
        <span onclick="this.parentElement.style.display=\'none\'"
        class="w3-button w3-red w3-large w3-display-topright">x</span>
            <p>' . htmlentities($_SESSION['success']) . '</p>
        </div>');
        unset($_SESSION['success']);
    }
}


function DateOptionADD($adz,$prnt,$goto){
    global $startdate;
    global $enddate;
    global $FY;
    if ($adz==0){
        $TodayYear = idate("Y");
        $TodayMonth = idate("m");
        if($TodayMonth<4){
            $startdate=($TodayYear-1).'-04-01';
            $enddate=$TodayYear.'-03-31';
        }
        else{
            $startdate=$TodayYear.'-04-01';
            $enddate=($TodayYear+1).'-03-31';
        }
    }
    else {
        $TodayYear = idate("Y",strtotime('01-04-'.$adz));
        $TodayMonth = idate("m",strtotime('01-04-'.$adz));
            $startdate=$TodayYear.'-04-01';
            $enddate=($TodayYear+1).'-03-31';
    }
    if ($TodayMonth<4){
        $TodayYear=$TodayYear-1;
    }
    $Year=$TodayYear-2;
    if ($prnt==1){
        for ($i=0;$i<5;$i++){
            if ($Year==$TodayYear){
                echo('<option class="w3-grey" value="'.$goto.$Year.'" selected>'.$Year.'-'.($Year+1).'</option>');
                $FY=$Year.'-'.($Year+1);
            }
            else{
                echo('<option value="'.$goto.$Year.'">'.$Year.'-'.($Year+1).'</option>');
            }
            $Year=$Year+1;
        }
    }

}

////need to be updated 
function updatefydata($adz,$pdo){
    $adz1=$adz-1;
    $prdarr=[];
    $stmt = $pdo->prepare('SELECT `prd_id`, SUM(`qnt`) as sm FROM `purchase_product` join purchase on purchase.pur_id=purchase_product.pur_id WHERE purchase.pur_date BETWEEN :startdate and :enddate GROUP By purchase_product.prd_id');
    $stmt->execute(array(':startdate'=>date($adz1.'-04-01'), ':enddate'=>date($adz.'-03-31')));
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]["bought"]=$r["sm"];
    }

    $stmt = $pdo->prepare('SELECT count(0) as prd_id,COUNT(0) as s');
    $stmt->execute(array());
    $row = $stmt->fetchall();
    foreach ($row as $r) {
        $prdarr[$r["prd_id"]]["sold"]=$r["s"];
    }

}

session_start();
?>