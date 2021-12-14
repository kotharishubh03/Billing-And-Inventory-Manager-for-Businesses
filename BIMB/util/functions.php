<?php
function nav ($st=0){
echo('<nav class="w3-sidebar w3-bar-block w3-black w3-collapse w3-top" style="z-index:3;width:250px;left:0" id="mySidebar">
    <div class="w3-container w3-display-container w3-padding-16">
        <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
            <h3 class="w3-wide"><b>BIMB</b></h3>
    </div>
    <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">');
    if ($st==0){echo('<a href="../purchase/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-sticky-note-o w3-margin-right"></i>Purchase</a>');} 
        else {echo('<a href="../purchase/index.php" class="w3-bar-item w3-button"><i class="fa fa-sticky-note-o w3-margin-right"></i>Purchase</a>');}
    if ($st==1){echo('<a href="../sales/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-id-card w3-margin-right"></i>Sales</a>');} 
        else {echo('<a href="../sales/index.php" class="w3-bar-item w3-button"><i class="fa fa-id-card w3-margin-right"></i>Sales</a>');}
    if ($st==2){echo('<a href="../supplier/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-key w3-margin-right"></i>Suppliers</a>');} 
        else {echo('<a href="../supplier/index.php" class="w3-bar-item w3-button"><i class="fa fa-key w3-margin-right"></i>Suppliers</a>');}
    if ($st==3){echo('<a href="../products/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-address-book w3-margin-right"></i>Products</a>');} 
        else {echo('<a href="../products/index.php" class="w3-bar-item w3-button"><i class="fa fa-address-book w3-margin-right"></i>Products</a>');}
    if ($st==4){echo('<a href="../payment/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-id-card w3-margin-right"></i>Payments</a>');} 
        else {echo('<a href="../payment/index.php" class="w3-bar-item w3-button"><i class="fa fa-id-card w3-margin-right"></i>Payments</a>');}
    if ($st==5){echo('<a href="../gstreports/index.php" class="w3-bar-item w3-button w3-black"><i class="fa fa-id-card w3-margin-right"></i>GST reports</a>');} 
        else {echo('<a href="../gstreports/index.php" class="w3-bar-item w3-button"><i class="fa fa-id-card w3-margin-right"></i>GST reports</a>');}
    echo('<a href="./logout.php" class="w3-bar-item w3-button w3-black"></i>Logout</a>
    </div>
    <a href="#footer"  onclick=confi(); class="w3-bar-item w3-button w3-padding">Security Checkup</a>
    <a href="contact.php" class="w3-bar-item w3-button w3-padding">Contact Us</a>
</nav>');
}


function headerfunc(){
    echo('<header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
    <div class="w3-bar-item w3-padding-24 w3-wide">BIMB</div>
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
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
        unset($_SESSION['success']);
    }
}


function DateOptionADD($adz){
    if ($adz==0){
        $TodayYear = idate("Y");
        $TodayMonth = idate("m");
    }
    else {
        $TodayYear = idate("Y",strtotime('01-04-'.$adz));
        $TodayMonth = idate("m",strtotime('01-04-'.$adz));
    }
    if ($TodayMonth<4){
        $TodayYear=$TodayYear-1;
    }
    $Year=$TodayYear-3;
    for ($i=0;$i<6;$i++){
        if ($Year==$TodayYear){
            echo('<option value="./index.php?fy='.$Year.'" selected>'.$Year.'-'.($Year+1).'</option>');
        }
        else{
            echo('<option value="./index.php?fy='.$Year.'">'.$Year.'-'.($Year+1).'</option>');
        }
        $Year=$Year+1;
    }
}
?>