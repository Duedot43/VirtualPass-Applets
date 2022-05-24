<?php
if (!isset($_GET['step'])){
    header("Location: /administrator/plugin_manager/tmp/setup.php?plugin=" . $_GET['plugin'] . "&step=0");
    exit();
}
if ($_GET['step'] == "0"){
    if (!isset($_POST['uname']) and !isset($_POST['passwd'])){
        echo '
            <title>Login</title>
            <head>
                <link href="/style.css" rel="stylesheet" type="text/css" />
            </head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Register</title>
            <tr>
                <form method="post" name="form" action="/administrator/plugin_manager/tmp/setup.php?plugin=' . $_GET['plugin'] . '&step=0">
                    <td>
                        <table width="100%" border="0" cellpadding="3" cellspacing="1">
                            <tr>
                                <td colspan="3"><strong>Make a user name and password for general login admin portal
                                        <hr />
                                    </strong></td>
                            </tr>
                            <tr>
                                <td class="text" width="78">User name
                                    <td width="6">:</td>
                                    <td width="294"><input class="box" name="uname" type="text" id="uname" autocomplete="off" required></td>
                                </td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>:</td>
                                <td><input class="box" name="passwd" type="password" id="passwd" autocomplete="off" required></td>
                            </tr>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><input class="reg" type="submit" name="Submit" value="Login"></td>
            </tr>
            </table>
            </td>
            </form>
            </tr>
            </table>
        ';
        exit();
    } else{
        $auth_json = array(
            "uname"=>$_POST['uname'],
            "passwd"=>$_POST['passwd']
        );
        mkdir("../../../../gen_config");
        file_put_contents("../../../../gen_config/auth.json", json_encode($auth_json));
        header("Location: /administrator/plugin_manager/tmp/setup.php?plugin=" . $_GET['plugin'] . "&step=1");
        exit();
    }
}
if ($_GET['step'] = "1"){
    mkdir("../../../../src/gen_checkout");
    mkdir("../../../../src/gen_checkout/admin");
    mkdir("../../../../src/gen_checkout/admin/cookie");
    file_put_contents("../../../../gen_config/gen_index.json", file_get_contents("https://raw.githubusercontent.com/Duedot43/VirtualPass-Applets/master/Plugins/gen_checkout/new_files/gen_config/gen_index.json"));
    header("Location: /administrator/plugin_manager/use_plugin.php?plugin=" . $_GET['plugin'] . "&setup=1");
    exit();
}
?>