<?php
function check_phid($pid){
    if (is_numeric($pid)){
    }
    else{
        echo("Invalid! not numeric");
      
      exit();
    }
  }
if (!isset($_COOKIE['admin'])){
    exec("rm cookie/*");
    header("Location: /administrator/index.html");
    exit();
}
else{
    if (!file_exists("cookie/" . $_COOKIE['admin'])){
        header("Location:index.html");
        exit();
    }
}
check_phid($_COOKIE['admin']);
$ini = parse_ini_file('../../config/config.ini');
$sendemail = $ini['em_enable'];
//exec($sendemail);
if ($sendemail == "1"){
    $enable_email = "Disable Emails";
}
if ($sendemail == "0"){
    $enable_email = "Enable Emails";
}
if ($ini['updates'] == 1){
    //This is an option for updates the update server is in the repo vp-update not really a good feture so im just gonna leave it off by default
$remote_release = file_get_contents("https://85c5-8-48-134-44.ngrok.io/latest");
$remote_merge_info = "https://85c5-8-48-134-44.ngrok.io/release_folder/merge-info";
$remote_merge_info_ini = parse_ini_file($remote_merge_info);
$merge = parse_ini_file("../../merge-info");
if ($remote_release != $merge['release']){
    echo('<input class="reg" type="button" value="Update from ' . $merge['release'] . ' to ' . $remote_release . '" onclick="location=\'update.php\'" />');
}
}
$ver_json = json_decode(file_get_contents("../../version-info"), true);
echo ($ver_json['current_version']);
?>
<head>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Portal</title>



<input class="reg" type="button" value="View logs" onclick="location='inoutlog.php'" />
<input class="reg" type="button" value="Remove all users" onclick="location='rmallusr.php'" />
<input class="reg" type="button" value="Remove all rooms" onclick="location='rmallrom.php'" />
<input class="reg" type="button" value="Remove all logs" onclick="location='rmalllog.php'" />
<input class="reg" type="button" value="Clean server" onclick="location='clean.php'" />
<input class="reg" type="button" value="View all user info" onclick="location='student.php'" />
<input class="reg" type="button" value="<?php echo $enable_email?>" onclick="location='email.php'" />
<input class="reg" type="button" value="Make a room QR Code" onclick="location='/mk_room/index.php'" />
<input class="reg" type="button" value="Check memory usage" onclick="location='mem_usage.php'" />
<input class="reg" type="button" value="Plugin manager" onclick="location='plugin_manager.php'" />
