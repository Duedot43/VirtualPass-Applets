<?php
function check_phid($pid){
    if (is_numeric($pid)){
    }
    else{
        echo("Invalid! not numeric");
      
      exit();
    }
  }
if (!isset($_COOKIE['gen'])){
    exec("rm ../../administrator/cookie/*");
    header("Location: /gen_checkout/admin/index.html");
    exit();
}
else{
    if (!file_exists("../../administrator/cookie/" . $_COOKIE['gen'])){
        header("Location: /gen_checkout/admin/index.html");
        exit();
    }
}
check_phid($_COOKIE['gen']);
$ini = parse_ini_file('../../../config/config.ini');
if ($ini['overide_automatic_domain_name'] == "1"){
  $domain = $ini['domain_name'];
}
if ($ini['overide_automatic_domain_name'] != "1"){
  $domain = $_SERVER['SERVER_NAME'];
}
if (isset($_GET['mode'])){
    if ($_GET['mode'] == "view"){
        echo '<link href="/style.css" rel="stylesheet" type="text/css" />';
        $dev_json = json_decode(file_get_contents("../../../gen_config/gen_index.json"), true);
        foreach ($dev_json['computers'] as $computer_arr){
            echo '<button class="reg" onclick="location=\'/gen_checkout/admin/menu.php?mode=' . $computer_arr['computer'] . '\'">' . $computer_arr['computer'] . '</button><br>';
        }
    }
    if (is_numeric($_GET['mode'])){
        echo '<link href="/style.css" rel="stylesheet" type="text/css" />';
        $computer_json = json_decode(file_get_contents("../../../gen_config/gen_index.json"), true)['rooms'][$_GET['mode']];
        foreach ($computer_json['user'] as $who_checkout){
            $user_checkout = parse_ini_file("../../registered_phid/" . $who_checkout['user']);
            echo $user_checkout['first_name'] . " " . $user_checkout['last_name'] . " " . $user_checkout['student_id'] . "<br>" . $who_checkout['date'] . "<br><br>";
        }
        echo '<button class="reg" onclick="location=\'/gen_checkout/admin/menu.php?mode=clear&room=' . $_GET['mode'] . '\'" >Clear entries</button>';
        exit();
    }
    if ($_GET['mode']){
        $dev_json = json_decode(file_get_contents("../../../gen_config/gen_index.json"), true);
        $dev_json['rooms'][$_GET['room']]['user'] = array();
        file_put_contents("../../../gen_config/gen_index.json", json_encode($dev_json));
        echo "Computer user list cleared";
    }
}
?>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Portal</title>



<input class="reg" type="button" value="View devices in library card format" onclick="location='/gen_checkout/admin/menu.php?mode=view'" />
<input class="reg" type="button" value="Make the QR code for checking out computers" onclick="location='/gen_checkout/admin/menu.php?mode=qr'" />