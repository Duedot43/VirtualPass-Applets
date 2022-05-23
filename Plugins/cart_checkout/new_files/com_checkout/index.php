<?php
if(!isset($_GET['cart'])){
    echo("Your cart variable is not set!");
    exit();
}
$cart = $_GET['cart'];
$index_json = json_decode(file_get_contents("../../com_config/com_index.josn"), true);
if(!isset($index_json['carts'][$cart])){
    echo "Your cart does not exiest! Please contact an administrator to resolve this";
    exit();
}
if (isset($_GET['activity']) and $_GET['activity'] == "Depart"){
    $index_json['carts'][$cart]['status'] = "Arrived";
    file_put_contents("../../com_config/com_index.josn", json_encode($index_json));
}
if (isset($_GET['activity']) and $_GET['activity'] == "Arrive"){
    //arrive the cart
    $index_json['carts'][$cart]['status'] = "Departed";
    file_put_contents("../../com_config/com_index.josn", json_encode($index_json));
}
$cart_arr = $index_json['carts'][$cart];
if ($cart_arr['status'] == "Departed"){
    $activ = "Arrive";
} else{
    $activ = "Depart";
}
?>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Move a cart</title>
<tr>
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr>
<td colspan="80"><strong>Move a cart!<br></strong></td>
</tr>
<tr>
<td width="0"></td>
<td width="0"></td>
<td width="294"><input class="reg" type="button" id="return" value='<?php echo $activ;?>' onclick="location='/com_checkout/index.php?cart=<?php echo $cart;?>'&activity=<?php echo $activ;?>" /></td>
<td width="78"></td>
<td width="0"></td>
<td width="294"><input class="box" type="number" id="room"  onclick="location='/com_checkout/index.php?cart=<?php echo $cart;?>'&activity=<?php echo $activ;?>" /></td>
<td width="78"></td>
<td width="0"></td>
</tr>
<tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>