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
    exec("rm admin/cookie/*");
    header("Location: /com_checkout/admin/index.html");
    exit();
}
else{
    if (!file_exists("admin/cookie/" . $_COOKIE['com'])){
        header("Location: /com_checkout/admin/index.html");
        exit();
    }
}
check_phid($_COOKIE['com']);
$ini = parse_ini_file('../../config/config.ini');
if ($ini['overide_automatic_domain_name'] == "1"){
  $domain = $ini['domain_name'];
}
if ($ini['overide_automatic_domain_name'] != "1"){
  $domain = $_SERVER['SERVER_NAME'];
}
$cart = rand();
$com_index = json_decode(file_get_contents("../../com_config/com_index.json"), true);
$com_index['carts'][$cart] = array(
    "room"=>0
);
//TODO register card but i dont know what info i need...
$url = "https://" . $domain . "/com_checkout/index.php?cart=" . $cart;
file_put_contents("../../com_config/com_index.json", json_encode($com_index));
?>
<script src="/mk_room/qrcode.min.js"></script>

<!-- (B) GENERATE QR CODE HERE -->
<div id="qrcode"></div>
<a href="" id="dbth" download="<?php echo "cart_" . $cart?>" >Download QR code</a>
<!-- (C) CREATE QR CODE ON PAGE LOAD -->
<script>
window.addEventListener("load", () => {
  var qrc = new QRCode(document.getElementById("qrcode"), "<?php echo $url; ?>");
  const div = document.createElement('div');
  new QRCode(div, "<?php echo $url;?>");
  var thing = div.children[0].toDataURL("image/png");
  document.querySelector('#dbth').href = thing;
});
</script>