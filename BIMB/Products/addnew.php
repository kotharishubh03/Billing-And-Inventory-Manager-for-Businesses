<?php
    require_once "..//util/pdo.php";
    require_once "..//util/functions.php";
    require_once "..//util/Classes.php";

    if(isset($_POST["add"])) {
        if ($_POST["add"]==1) {
            $stmt = $pdo->prepare('INSERT INTO `products`(`prd_name`, `mrp`, `selling_price`) VALUES (:prd_name , :mrp, :sell_price)');
            $stmt->execute(array(':prd_name'=> $_POST['prd_name'], ':mrp'=> $_POST['mrp'], ':sell_price'=> $_POST['sell_price']));
            $p_id = $pdo->lastInsertId();

            $s_name=explode(", ",rtrim($_POST['s_name'],", "));
            foreach ($s_name as $r) {
                $stmt = $pdo->prepare('INSERT INTO `products_supplier`(`prd_id`, `supp_id`, `cost_price`) VALUES (:prd_id, (SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:supp_name), :cost_price)');
                $stmt->execute(array(':prd_id'=> $p_id, ':supp_name'=> $r, ':cost_price'=> $_POST['cost_price']));
            }
            $_SESSION['success']="Successfully Saved ! Add Another Product";
            header("Location: ./addnew.php");
            return;
        }
    } 

    require_once "..//util/header.php";
?>
        <title>Products | ADD NEW PRODUCTS</title>
    </head>
    <body class="w3-content" style="max-width:1350px">
        <?php 
            mainbody(3,"Products | ADD NEW PRODUCTS");
            flashMessage();
        ?>
        <!-- items grid -->

        <div class="w3-row">
            <br>
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey">
                    <h3>ADD New Products</h3>
                </header>
                <div class="w3-container">
                <form name='addnew' method="post">
                        <div class="w3-row">
                            <div class="w3-col m5 w3-padding ">
                                <label for="prd_name">Product Name :</label>
                                <input id="prd_name" class="w3-input" type="text" name="prd_name" size="30" tabindex="1" required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="s_name">Supplier name:</label>
                                <input id="s_name" class="w3-input" type="text" name="s_name" size="30" tabindex="2" required/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="cost_price">Cost Price:</label>
                                <input id="cost_price" class="w3-input" type="text" name="cost_price" tabindex="3"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="mrp">MRP:</label>
                                <input class="w3-input" type="text" name="mrp" size="30"  tabindex="4"/>
                            </div>
                            <div class="w3-col m5 w3-padding ">
                                <label for="sell_price">Selling Price:</label>
                                <input id="sell_price" class="w3-input" type="text" name="sell_price" size="30"  tabindex="5"/>
                            </div>
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
        <script>
        $(document).ready(function () {
            window.console && console.log('Document ready called');
            $('#prd_name').autocomplete({
                source: "../GET/productget.php",
                minLength: 1,
            });
            
            function split( val ) {
                return val.split( /,\s*/ );
            }
            
            function extractLast( term ) {
                return split( term ).pop();
            }
 
            $( "#s_name" )
            // don't navigate away from the field on tab when selecting an item
            .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    $.getJSON( "../GET/supplierget.php", {
                        term: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = extractLast( this.value );
                    if ( term.length < 2 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
        });
        </script>
    </body>
</html>
