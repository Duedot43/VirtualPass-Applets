<?php
if(!isset($_GET['dev'])){
    if (!isset($_POST['id'])){
        echo '
            <title>What computer</title>
        <head>
            <link href="/style.css" rel="stylesheet" type="text/css" />
        </head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <tr>
            <form method="post" name="form" action="/dev_checkout/index.php">
                <td>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr>
                            <td colspan="3"><strong>Type the ID of the computer you are trying to checkout
                                    <hr />
                                </strong></td>
                        </tr>
                        <tr>
                            <td>ID</td>
                            <td>:</td>
                            <td><input class="box" name="id" type="number" id="id" autocomplete="off" required></td>
                        </tr>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input class="reg" type="submit" name="Submit" value="Submit"></td>
        </tr>
        </table>
        </td>
        </form>
        </tr>
        </table>
        ';
        exit();
    } else{
        header("Location: /dev_checkout/index.php?dev=" . $_POST['id']);
        exit();
    }
}
$dev = $_GET['dev'];
$index_json = json_decode(file_get_contents("../../dev_config/dev_index.json"), true);
if (!isset($index_json['computers'][$dev])){
    if (!isset($_COOKIE['phid'])){
        //make the user but ill do this later TODO
    }
    if (is_dir("../../com_config") and !isset($_POST['cart'])){
        echo '
            <title>What cart</title>
        <head>
            <link href="/style.css" rel="stylesheet" type="text/css" />
        </head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <tr>
            <form method="post" name="form" action="/dev_checkout/index.php?dev=' . $dev . '">
                <td>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr>
                            <td colspan="3"><strong>Enter the cart ID specified on the side of the cart you are taking this computer from
                                    <hr />
                                </strong></td>
                        </tr>
                        <tr>
                            <td>Room number</td>
                            <td>:</td>
                            <td><input class="box" name="cart" type="number" id="cart" autocomplete="off" required></td>
                        </tr>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input class="reg" type="submit" name="Submit" value="Submit"></td>
        </tr>
        </table>
        </td>
        </form>
        </tr>
        </table>
        ';
        exit();
    }
    if (isset($_POST['cart'])){
        $cart = $_POST['cart'];
        $com_json = json_decode(file_get_contents("../../com_config/com_index.json"), true);
        array_push($com_json['carts'][$cart]['devices'], $cart);
        file_put_contents("../../com_config/com_index.json", json_encode($com_json));
    } else{
        $cart = 0;
    }
    $index_json['computers'][$dev] = array(
        "cart"=>$cart,
        "computer"=>$dev,
        "user"=>array()
    );
    file_put_contents("../../dev_config/dev_index.json", json_encode($index_json));
}
$index_json = json_decode(file_get_contents("../../dev_config/dev_index.json"), true);
array_push($index_json['computers'][$dev]['user'], array("date"=>date('l jS \of F Y h:i:s A'),"user"=>$_COOKIE['phid']));
file_put_contents("../../dev_config/dev_index.json", json_encode($index_json));

?>
<title>Take a computer</title>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>

Computer succesfully checked out.