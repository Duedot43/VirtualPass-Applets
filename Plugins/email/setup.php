<?php
function config_set($config_file, $section, $key, $value) {
    $config_data = parse_ini_file($config_file, true);
    $config_data[$section][$key] = $value;
    $new_content = '';
    foreach ($config_data as $section => $section_content) {
        $section_content = array_map(function($value, $key) {
            return "$key=$value";
        }, array_values($section_content), array_keys($section_content));
        $section_content = implode("\n", $section_content);
        $new_content .= "[$section]\n$section_content\n";
    }
    file_put_contents($config_file, $new_content);
}
if (!isset($_GET['step'])){
    header("Location: /administrator/plugin_manager/tmp/setup.php?plugin=" . $_GET['plugin'] . "&step=0");
    exit();
}
if ($_GET['step'] == 0){
    if (!isset($_POST['email']) and !isset($_POST['passwd']) and !isset($_POST['t_email'])){
        echo '
        <title>Setup emails</title>
        <head>
            <link href="/style.css" rel="stylesheet" type="text/css" />
        </head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <tr>
            <form method="post">
                <td>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr>
                            <td colspan="3"><strong>Please input your specified details to test if the email function works.
                                    <hr />
                                </strong></td>
                        </tr>
                        <tr>
                            <td class="text" width="78">SMTP email address
                                <td width="6">:</td>
                                <td width="294"><input class="box" autocomplete="off" name="email" id="email"
                                    required></td>
                            </td>
                        </tr>
                        <tr>
                            <td>SMTP password</td>
                            <td>:</td>
                            <td><input class="box" type="password" name="passwd" autocomplete="off" type="text" id="passwd"></td>
                        </tr>
                        <tr>
                            <td>Test reciver email</td>
                            <td>:</td>
                            <td><input class="box" name="t_email" autocomplete="off"  id="t_email"></td>
                        </tr>
                        <tr>
                            <td>SMTP username</td>
                            <td>:</td>
                            <td><input class="box" name="username" autocomplete="off"  id="username"></td>
                        </tr>
                        <tr>
                            <td>SMTP Server</td>
                            <td>:</td>
                            <td><input class="box" name="server" autocomplete="off" placeholder="smtp.gmail.com" id="server"></td>
                        </tr>
                        <tr>
                            <td>SMTP Server port</td>
                            <td>:</td>
                            <td><input class="box" name="server_port" autocomplete="off" placeholder="143" id="server_port"></td>
                        </tr>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input class="reg" type="submit" name="Submit" value="Register"></td>
        </tr>
        </table>
        </td>
        </form>
        </tr>
        </table>
        ';
    } else{
        mkdir("./mailer");
        $exception_file = fopen("../tmp/mailer/Exception.php", "w");
        $oauth_file = fopen("../tmp/mailer/OAuth.php", "w");
        $mailer_file = fopen("../tmp/mailer/PHPMailer.php", "w");
        $stmp_file = fopen("../tmp/mailer/SMTP.php", "w");
        $email_file = fopen("../tmp/email.php", "w");
        fwrite($exception_file, file_get_contents("https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php"));
        fwrite($oauth_file, file_get_contents("https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/OAuth.php"));
        fwrite($mailer_file, file_get_contents("https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php"));
        fwrite($stmp_file, file_get_contents("https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php"));
        fwrite($email_file, file_get_contents("https://raw.githubusercontent.com/Duedot43/VirtualPass-Applets/master/Plugins/email/email.php"));
        fclose($exception_file);
        fclose($oauth_file);
        fclose($mailer_file);
        fclose($stmp_file);
        fclose($email_file);
        include "../tmp/email.php";
        send($_POST['email'], $_POST['passwd'], $_POST['t_email'], $_POST['username'], "https://raw.githubusercontent.com/Duedot43/VirtualPass/master/src/administrator/index.html", $_POST['server'], $_POST['server_port']);
        copy("../../../../config/config.ini", "./config.ini");
        config_set("./config.ini", "email_function", "em_enable", "1");
        $mail_json = array(
            "email_email" => $_POST['email'],
            "email_passwd"=>$_POST['passwd'],
            "email_username"=>$_POST['username'],
            "server"=>$_POST['server'],
            "server_port"=>$_POST['server_port']
        );
        $main_json_file = fopen("../tmp/mail.json", "w");
        fwrite($main_json_file, json_encode($mail_json));
        fclose($main_json_file);
        header("Location: /administrator/plugin_manager/tmp/setup.php?plugin=" . $_GET['plugin'] . "&step=1");
    }
}
if ($_GET['step'] == "1"){
    echo '<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Did you recive the email?</title>
<tr>
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr>
<td colspan="80"><strong>Did you recive the email?</strong></td>
</tr>
<tr>
<td width="0"></td>
<td width="0"></td>
<td width="294"><input class="reg" type="button" id="return" value="Yes" onclick="location=\'/administrator/plugin_manager/tmp/setup.php?plugin=' . $_GET['plugin'] . '&step=2\'"</td>
<td width="78"></td>
<td width="80"></td>
<td width="294"><input class="reg" type="button" value="No" onclick="location=\'/administrator/plugin_manager/tmp/setup.php?plugin=' . $_GET['plugin'] . '&step=0\'"/></td>
<td width="0"></td>
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
</table>';
}
if ($_GET['step'] == "2"){
    mkdir("../../../../src/usr_pre_fls/mailer");
    copy("./mailer/Exception.php", "../../../../src/usr_pre_fls/mailer/Exception.php");
    copy("./mailer/OAuth.php", "../../../../src/usr_pre_fls/mailer/OAuth.php");
    copy("./mailer/PHPMailer.php", "../../../../src/usr_pre_fls/mailer/PHPMailer.php");
    copy("./mailer/SMTP.php", "../../../../src/usr_pre_fls/mailer/SMTP.php");
    copy("./mail.json", "../../../../config/mail.json");
    fwrite(fopen("../../../../config/config.ini", "w"), file_get_contents("./config.ini"));
    unlink("./mailer/Exception.php");
    unlink("./mailer/OAuth.php");
    unlink("./mailer/PHPMailer.php");
    unlink("./mailer/SMTP.php");
    unlink("./email.php");
    unlink("./mail.json");
    unlink("./config.ini");
    rmdir("./mailer");
    header("Location: /administrator/plugin_manager/use_plugin.php?plugin=" . $_GET['plugin'] . "&setup=1");
}