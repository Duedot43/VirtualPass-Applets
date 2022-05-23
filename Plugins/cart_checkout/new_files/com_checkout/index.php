<?php
if(!isset($_GET['cart'])){
    echo("Your cart variable is not set!");
    exit();
}
$cart = $_GET['cart'];
$index_json = json_decode(file_get_contents("../../com_config/com_index.json"), true);
if(!isset($index_json['carts'][$cart])){
    echo "Your cart does not exiest! Please contact an administrator to resolve this";
    exit();
}
if (isset($_POST['room'])){
    $room_sub = $_POST['room'];
    $mass_json = json_decode(file_get_contents("../../mass.json"), true);
    foreach ($mass_json['room'] as $room_id){
        $real_room = file_get_contents("../registerd_qrids/" . $room_id);
        if ($room_sub == $real_room){
            $sub_room_id = $room_id;
            break;
        }
    }
}
?>
<title>Login</title>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<tr>
    <form method="post" name="form" action="/com_checkout/index.php?cart=<?php echo $cart;?>">
        <td>
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
                <tr>
                    <td colspan="3"><strong>Login
                            <hr />
                        </strong></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>:</td>
                    <td><input class="box" name="room" type="number" id="room" autocomplete="off" required></td>
                </tr>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input class="reg" type="submit" name="Submit" value="Move!"></td>
</tr>
</table>
</td>
</form>
</tr>
</table>