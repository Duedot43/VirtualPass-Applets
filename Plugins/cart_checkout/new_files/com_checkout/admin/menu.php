<?php
function check_phid($pid){
    if (is_numeric($pid)){
    }
    else{
        echo("Invalid! not numeric");
      
      exit();
    }
  }
if (!isset($_COOKIE['com'])){
    exec("rm ../../administrator/cookie/*");
    header("Location: /com_checkout/admin/index.html");
    exit();
}
else{
    if (!file_exists("../../administrator/cookie/" . $_COOKIE['com'])){
        header("Location: /com_checkout/admin/index.html");
        exit();
    }
}
check_phid($_COOKIE['com']);
if (isset($_GET['page']) and $_GET['page'] == "view_room"){
    $cart_index = json_decode(file_get_contents("../../../com_config/com_index.json"), true);
    echo '<link href="/style.css" rel="stylesheet" type="text/css" />';
    foreach ($cart_index['rooms'] as $room_array){
        $carts = "";
        foreach ($room_array['carts'] as $cart_id){
            $carts += $cart_id . ", ";
        }
        echo 'Room ' . $room_array['real_room'] . " has carts " . $carts . " in it with a total of " . count($room_array['carts']) . " carts<br><br>";
    }
    exit();
}
?>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Portal</title>



<input class="reg" type="button" value="Rebuild room DB" onclick="location='reload.php'" />
<input class="reg" type="button" value="Make cart" onclick="location='/com_checkout/regcart.php'" />
<input class="reg" type="button" value="View rooms with carts" onclick="location='/com_checkout/admin/menu.php?page=view_room'" />