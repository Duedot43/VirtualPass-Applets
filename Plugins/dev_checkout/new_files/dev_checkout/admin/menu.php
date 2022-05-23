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
?>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Portal</title>



<input class="reg" type="button" value="View devices in library card format" onclick="location='/dev_checkout/admin/menu.php?mode=view'" />