<?php
require_once "..//util/pdo.php";
require_once "..//util/functions.php";


if (isset($_POST["edit"])) {
    $payment_id = $_POST["edit"];

    $stmt = $pdo->prepare('DELETE FROM `supp_pay_purchase` WHERE payment_id=:payment_id');
    $stmt->execute(array(':payment_id'=>$payment_id)); 

    $stmt = $pdo->prepare('DELETE FROM `suppliers_payment` WHERE payment_id=:payment_id');
    $stmt->execute(array(':payment_id'=>$payment_id)); 

    $stmt = $pdo->prepare('INSERT INTO `suppliers_payment`(`payment_id`,`pay_type`, `supp_id`, `amount`, `date`, `chq_no`) VALUES (:payment_id,(SELECT `pay_mode_id` FROM `payment_mode` WHERE `pay_mode`=:paymode Limit 1),(SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:supp_name limit 1),:amt,:pay_date,:chqno)');
    $stmt->execute(array(':payment_id'=>$payment_id,':paymode' => $_POST['paymode'], ':supp_name' => $_POST['supp_name'], ':amt' => $_POST['amt'], ':pay_date' => date("Y-m-d", strtotime($_POST['pay_date'])), ':chqno' => $_POST['chqno']));

    $pay_bill_nos = explode(", ", rtrim($_POST['pay_bill_nos'], ", "));
    foreach ($pay_bill_nos as $r) {
        $stmt = $pdo->prepare('UPDATE `purchase` SET `pay_date`=:pay_date ,`pay_mode_id`=(SELECT `pay_mode_id` FROM `payment_mode` WHERE `pay_mode`=:paymode limit 1) WHERE `pur_id`=(SELECT `pur_id` FROM `purchase` WHERE `bill_no`=:billno and `supp_id`=(SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:supp_name limit 1))');
        $stmt->execute(array(':pay_date' => date("Y-m-d", strtotime($_POST['pay_date'])), ':paymode' => $_POST['paymode'], ':billno' => $r, ':supp_name' => $_POST['supp_name']));

        $stmt = $pdo->prepare('INSERT INTO `supp_pay_purchase` (`pur_id`, `payment_id`) VALUES ((SELECT `pur_id` FROM `purchase` WHERE `bill_no`=:billno and `supp_id`=(SELECT `supp_id` FROM `suppliers` WHERE `supp_name`=:supp_name limit 1)),:payment_id)');
        $stmt->execute(array(':supp_name' => $_POST['supp_name'], ':billno' => $r, ':payment_id' => $payment_id));
    }
    $_SESSION['success'] = "Successfully Saved !";
    header("Location: ./about.php?pay_id=".$payment_id);
    return;
}

$stmt = $pdo->prepare('SELECT `pay_mode`, suppliers_payment.`supp_id`, `supp_name`, `amount`, `date`, `chq_no` FROM `suppliers_payment` join payment_mode on payment_mode.pay_mode_id=suppliers_payment.pay_type join suppliers on suppliers.supp_id=suppliers_payment.supp_id WHERE `payment_id`=:pay_id');
$stmt->execute(array(':pay_id' => $_GET['pay_id']));
$payinfo = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT `pur_id`, `pur_date`, `bill_no`, `total` FROM `purchase` WHERE pur_id in (SELECT `pur_id` FROM `supp_pay_purchase` WHERE `payment_id`=:pay_id)');
$stmt->execute(array(':pay_id' => $_GET['pay_id']));
$bills = "";
$billinfo = $stmt->fetchall(PDO::FETCH_ASSOC);
//print_r($rows);
foreach ($billinfo as $row) {
    $bills = $row['bill_no'] . ', ' . $bills;
}

require_once "..//util/header.php";
?>
<title>About Payments</title>
</head>

<body class="w3-content" style="max-width:1350px">
    <?php mainbody(4, "Payments") ?>
    <!-- items grid -->
    <div class="w3-container">
        <?php flashMessage(); ?>
    </div>
    <div class="w3-container">
        <div class="w3-card-4 w3-margin">
            <header class="w3-container w3-light-grey">
                <h3>ABOUT</h3>
            </header>
            <div class="w3-container">
                <form name='addnew' method="post">
                    <div class="w3-row">
                        <div class="w3-col m5 w3-padding ">
                            <label for="supp_name">Supplier name:</label>
                            <input id="supp_name" class="w3-input" type="text" name="supp_name" size="30" placeholder="Type 'all' for All Suppliers" tabindex="1" value="<?= $payinfo['supp_name'] ?>" required />
                        </div>
                        <div class="w3-col m5 w3-padding ">
                            <label for="pay_date">Date.:</label>
                            <input id="pay_day" class="w3-input" type="date" name="pay_date" required value="<?= $payinfo['date'] ?>" tabindex="3" />
                        </div>
                        <div class="w3-col m5 w3-padding ">
                            <label for="amt">Amount:</label>
                            <input class="w3-input" type="text" name="amt" required value="<?= $payinfo['amount'] ?>" tabindex="4" />
                        </div>
                        <div class="w3-col m5 w3-padding ">
                            <label for="paymode">Mode:</label>
                            <input id="paymode" class="w3-input" type="text" name="paymode" value="<?= $payinfo['pay_mode'] ?>" placeholder="Type 'all' for All Modes" required tabindex="5" />
                        </div>
                        <div class="w3-col m5 w3-padding ">
                            <label for="chqno">Chq no:</label>
                            <input id="chqno" class="w3-input" type="text" name="chqno" value="<?= $payinfo['chq_no'] ?>" required tabindex="6" />
                        </div>
                        <div class="w3-col w3-padding ">
                            <label for="pay_bill_nos">Bill Nos.:</label>
                            <input id="pay_bill_nos" class="w3-input" name="pay_bill_nos" value="<?= $bills ?>" required tabindex="2" />
                        </div>
                    </div><br>
                    <button class="w3-button w3-block w3-dark-grey" type="submit" name="edit" value="<?= $_GET['pay_id'] ?>">EDIT</button>
                </form>
            </div>
        </div>
    </div>
    <div class="w3-container">
        <div class="w3-card-4 w3-margin">
            <header class="w3-container w3-light-grey">
                <h3>Bills</h3>
            </header>
            <div class="w3-container w3-padding-16">
                <table class="w3-table-all w3-small w3-centered sk-table ">
                    <thead>
                        <tr>
                            <th>Bill No.</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($billinfo as $row) {
                            $total = $total + $row['total'];
                            echo ('<tr><td><b><a href="../purchase/about.php?pur_id=' . $row['pur_id'] . '">' . $row['bill_no'] . '</a></b></td>
                                        <td>' . $row['pur_date'] . '</td>
                                        <td>' . $row['total'] . '</td>
                                    </tr>');
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="w3-yellow">
                            <td colspan="2">Total</td>
                            <td><?= $total ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- END items grid -->
    <?php
    require_once "..//util/footer.php";
    ?>
    <script>
        $(document).ready(function() {
            window.console && console.log('Document ready called');
            $('form input').keydown(function(e) {
                if (e.keyCode == 13) {
                    var inputs = $(this).parents("form").eq(0).find(":input");
                    e.preventDefault();
                    return false;
                }
            });

            $('#supp_name').autocomplete({
                source: "../GET/supplierget.php",
                minLength: 1,
            });

            $('#paymode').autocomplete({
                source: "../GET/paymodeget.php",
                minLength: 1,
            });

            $('#paymode').change(function() {
                if ($('#paymode').val() == "Cash") {
                    var x = new Date($('#pay_day').val());
                    var day = x.getDate();
                    var mnt = x.getMonth();
                    mnt = mnt + 1;
                    var yr = x.getFullYear()
                    var z = 'Cash/' + day + '/' + mnt + '/' + yr + '/' + $('#supp_name').val();
                    $('#chqno').val(z);
                }

                if ($('#paymode').val() == "Discount") {
                    var x = new Date($('#pay_day').val());
                    var day = x.getDate();
                    var mnt = x.getMonth();
                    mnt = mnt + 1;
                    var yr = x.getFullYear()
                    var z = 'Disc/' + day + '/' + mnt + '/' + yr + '/' + $('#supp_name').val();
                    $('#chqno').val(z);
                }

            });

            function split(val) {
                return val.split(/,\s*/);
            }

            function extractLast(term) {
                return split(term).pop();
            }
            var billno = [];
            $('#supp_name').change(function() {
                billno = [];
                $.getJSON("../GET/billpayget.php?supp_name=" + $('#supp_name').val(), function(data) {
                    $.each(data, function(key, val) {
                        billno.push(val);
                    });
                });
                if (billno == []) {
                    billno.push('NO Bill Found')
                }
            });

            $("#pay_bill_nos")
                // don't navigate away from the field on tab when selecting an item
                .on("keydown", function(event) {
                    if (event.keyCode === $.ui.keyCode.TAB &&
                        $(this).autocomplete("instance").menu.active) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    minLength: 0,
                    source: function(request, response) {
                        // delegate back to autocomplete, but extract the last term
                        response($.ui.autocomplete.filter(
                            billno, extractLast(request.term)));
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function(event, ui) {
                        var terms = split(this.value);
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push(ui.item.value);
                        // add placeholder to get the comma-and-space at the end
                        terms.push("");
                        this.value = terms.join(", ");
                        return false;
                    }
                });

            $('#supp_name').trigger("change");
        });
    </script>
</body>

</html>