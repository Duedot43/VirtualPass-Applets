<?php
if (isset($_POST['id']) and isset($_GET['room'])){
    $mass_json = json_decode(file_get_contents("../../mass.json"), true);
    foreach ($mass_json['user'] as $user_id){
        $user_ini = parse_ini_file("../registered_phid/" . $user_id);
        if ($user_ini['student_id'] == $_POST['id']){
            $user = $user_ini;
            break;
        }
    }
    echo "Welcome " . $user['first_name'] . ". You have been regustered into this area.
    <script>
    setTimeout(() => { link('/gen_checkout/index.php?room=" . $_GET['room'] . "') }, 4000);
    </script>";
    $gen_index = json_decode(file_get_contents("../../gen_config/gen_index.json"), true);
    $gen_index['rooms'][$_GET['room']] = $something;
}

?>
<title>Login</title>
<head>
    <link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<tr>
    <form method="post" name="form" action="/gen_checkout/index.php">
        <td>
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
                <tr>
                    <td colspan="3"><strong>Please enter your student ID
                            <hr />
                        </strong></td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>:</td>
                    <td><input class="box" name="id" type="number" id="id" autocomplete="off" required></td>
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