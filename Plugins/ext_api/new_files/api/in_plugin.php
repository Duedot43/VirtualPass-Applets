<?php
/*
MIT License

Copyright (c) 2022 Jack Gendill

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
function fail()
{
    header('WWW-Authenticate: Basic realm="api"');
    header('HTTP/1.0 401 Unauthorized');
}
function err()
{
    header('HTTP/1.0 406 Not Acceptable');
}
if (!isset($_GET['plugin'])) {
    err();
    $output = array("success" => 0, "reason" => "no_plugin", "help_url" => "");
    echo json_encode($output);
    exit();
}
$plugin_json = json_decode(file_get_contents("https://raw.githubusercontent.com/Duedot43/VirtualPass-Applets/master/index.json"), true);
if (!isset($plugin_json['plugins'][$_GET['plugin']])) {
    err();
    $output = array("success" => 0, "reason" => "invalid_plugin", "help_url" => "");
    echo json_encode($output);
    exit();
}
$config = parse_ini_file("../../config/config.ini");
if (isset($_SERVER['PHP_AUTH_USER']) and $_SERVER['PHP_AUTH_USER'] == $config['api_uname']) {
    if (isset($_SERVER['PHP_AUTH_PW']) and $_SERVER['PHP_AUTH_PW'] == $config['api_passwd']) {
        $plugin_id = $_GET['plugin'];
        $plugin_index = json_decode(file_get_contents("https://raw.githubusercontent.com/Duedot43/VirtualPass-Applets/master/index.json"), true);
        $selected_plugin = $plugin_index['plugins'][$plugin_id];
        $installed_json = json_decode(file_get_contents("../usr_pre_fls/plugins.json"), true);
        $version_json = json_decode(file_get_contents("../../version-info"), true);
        if (!isset($installed_json[$plugin_id])) {
            $installed_json[$plugin_id] = 0;
        }
        if ($installed_json[$plugin_id] == 1) {
            for ($x = 0; $x <= $selected_plugin['changed']; $x++) {
                //uninstall the plugin
                if ($selected_plugin['orig_url'][$x] != "None") {
                    $plugin_file = fopen("../.." . $selected_plugin['location'][$x], "w");
                    fwrite($plugin_file, file_get_contents($selected_plugin['orig_url'][$x]));
                    fclose($plugin_file);
                }
            }
            for ($a = 0; $a <= $selected_plugin['to_remove_file']; $a++) {
                if ($selected_plugin['remove_file'][$a] != "None") {
                    unlink("../.." . $selected_plugin['remove_file'][$a]);
                }
            }
            for ($a = 0; $a <= $selected_plugin['to_remove_dir']; $a++) {
                if ($selected_plugin['remove_dir'][$a] != "None") {
                    rmdir("../.." . $selected_plugin['remove_dir'][$a]);
                }
            }
            $installed_json[$plugin_id] = 0;
            $plugin_index_file = fopen("../../usr_pre_fls/plugins.json", "w");
            fwrite($plugin_index_file, json_encode($installed_json));
            fclose($plugin_index_file);
            $output = array("success" => 1, "reason" => "uninstalled", "help_url" => "");
            echo json_encode($output);
            exit();
        } else {
            if (!in_array($version_json['current_version'], $selected_plugin['valid_ver'], true)) {
                $output = array("success" => 0, "reason" => "incorrect_version", "help_url" => "");
                echo json_encode($output);
                exit();
            }
            if ($selected_plugin['headless_setup'] == "Invalid"){
                $output = array("success" => 0, "reason" => "not_installable_api", "help_url" => "");
                echo json_encode($output);
                exit();
            }
            if ($selected_plugin['headless_setup'] != "None") {
                mkdir("./tmp");
                $setup_file = fopen("./tmp/setup.php", "w");
                fwrite($setup_file, file_get_contents($selected_plugin['setup']));
                fclose($setup_file);
                include "tmp/setup.php";
                setup();
            }
            for ($x = 0; $x <= $selected_plugin['changed']; $x++) {
                //install the plugin
                if ($selected_plugin['clone_url'][$x] != "None") {
                    $plugin_file = fopen("../.." . $selected_plugin['location'][$x], "w");
                    fwrite($plugin_file, file_get_contents($selected_plugin['clone_url'][$x]));
                    fclose($plugin_file);
                    $installed_json[$plugin_id] = 1;
                    $plugin_index_file = fopen("../../usr_pre_fls/plugins.json", "w");
                    fwrite($plugin_index_file, json_encode($installed_json));
                    fclose($plugin_index_file);
                    $output = array("success" => 1, "reason" => "installed", "help_url" => "");
                    echo json_encode($output);
                    exit();
                }
            }
        }
        unset($plugin_index);
        unset($selected_plugin);
    } else {
        fail();
        $output = array("success" => 0, "reason" => "auth_fail", "help_url" => "");
        echo json_encode($output);
        exit();
    }
} else {
    fail();
    $output = array("success" => 0, "reason" => "auth_fail", "help_url" => "");
    echo json_encode($output);
    exit();
}
