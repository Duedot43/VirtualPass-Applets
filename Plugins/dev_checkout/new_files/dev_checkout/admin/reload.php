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
    header("Location: /dev_checkout/admin/index.html");
    exit();
}
else{
    if (!file_exists("../../administrator/cookie/" . $_COOKIE['com'])){
        header("Location: /dev_checkout/admin/index.html");
        exit();
    }
}
check_phid($_COOKIE['com']);
if (file_exists("../../../mass.json")){
    echo "building room DB please wait...";
    $mass = json_decode(file_get_contents("../../../mass.json"), true);
    $com_index = json_decode(file_get_contents("https://raw.githubusercontent.com/Duedot43/VirtualPass-Applets/master/Plugins/dev_checkout/new_files/dev_config/base_cart.json"), true);
    foreach ($mass['room'] as $room_id){
        $real_room = file_get_contents("../../../src/registerd_qrids/" . $room_id);
        $com_index['rooms'][$room_id] = array(
            "carts"=>array(),
            "cart_id"=>$real_room
        );
    }
    file_put_contents("../../../dev_config/dev_index.json", json_encode($com_index));
}
echo '
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
Room DB reloaded
<input class="reg" type="button" value="Main menu" onclick="location=\'menu.php\'" />';
?>