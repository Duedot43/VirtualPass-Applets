<?php
function check_phid($pid){
    if (is_numeric($pid)){
    }
    else{
        echo("Invalid! not numeric");
      
      exit();
    }
  }
if (!isset($_COOKIE['dev'])){
    exec("rm ../../administrator/cookie/*");
    header("Location: /dev_checkout/admin/index.html");
    exit();
}
else{
    if (!file_exists("../../administrator/cookie/" . $_COOKIE['dev'])){
        header("Location: /dev_checkout/admin/index.html");
        exit();
    }
}
check_phid($_COOKIE['dev']);
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
        $dev_json = json_decode(file_get_contents("../../../dev_config/dev_index.json"), true);
        foreach ($dev_json['computers'] as $computer_arr){
            echo '<button class="reg" onclick="location=\'/dev_checkout/admin/menu.php?mode=' . $computer_arr['computer'] . '\'">' . $computer_arr['computer'] . '</button><br>';
        }
    }
    if (is_numeric($_GET['mode'])){
        echo '<link href="/style.css" rel="stylesheet" type="text/css" />';
        $computer_json = json_decode(file_get_contents("../../../dev_config/dev_index.json"), true)['computers'][$_GET['mode']];
        foreach ($computer_json['user'] as $who_checkout){
            $user_checkout = parse_ini_file("../../registered_phid/" . $who_checkout['user']);
            echo $user_checkout['first_name'] . " " . $user_checkout['last_name'] . " " . $user_checkout['student_id'] . "<br>" . $who_checkout['date'] . "<br><br>";
        }
        exit();
    }
    if ($_GET['mode'] == "qr"){
        echo '
        <script src="/mk_room/qrcode.min.js"></script>

        <!-- (B) GENERATE QR CODE HERE -->
        <div id="qrcode"></div>
        <a href="" id="dbth" download="QR_Code" >Download QR code</a>
        <!-- (C) CREATE QR CODE ON PAGE LOAD -->
        <script>
        window.addEventListener("load", () => {
        var qrc = new QRCode(document.getElementById("qrcode"), "https://' . $domain . '/dev_checkout/");
        const div = document.createElement("div");
        new QRCode(div, "https://' . $domain . '/dev_checkout/");
        var thing = div.children[0].toDataURL("image/png");
        document.querySelector("#dbth").href = thing;
        });
        </script>
        ';
        exit();
    }
}
?>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Portal</title>



<input class="reg" type="button" value="View devices in library card format" onclick="location='/dev_checkout/admin/menu.php?mode=view'" />
<input class="reg" type="button" value="Make the QR code for checking out computers" onclick="location='/dev_checkout/admin/menu.php?mode=qr'" />