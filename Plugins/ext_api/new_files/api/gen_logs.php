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
function fail(){
    header('WWW-Authenticate: Basic realm="api"');
    header('HTTP/1.0 401 Unauthorized');
}
function err(){
    header('HTTP/1.0 406 Not Acceptable');
}
if (!isset($_GET['user'])){
    err();
    $output = array("success" => 0, "reason" => "no_user", "help_url" => "");
    echo json_encode($output);
    exit();
}
if (!file_exists("../../mass.json")){
    err();
    $output = array("success"=>0, "reason"=>"no_mass", "help_url"=>"https://github.com/Duedot43/VirtualPass/wiki/Edit#no-mass");
    echo json_encode($output);
    exit();
}
$main_json = json_decode(file_get_contents("../../mass.json"), true);
if (!in_array($_GET['user'], $main_json['user'], true)){
    err();
    $output = array("success"=>0, "reason"=>"no_user", "help_url"=>"https://github.com/Duedot43/VirtualPass/wiki/Edit#no-user");
    echo json_encode($output);
    exit();
}
$config = parse_ini_file("../../config/config.ini");
if (isset($_SERVER['PHP_AUTH_USER']) and $_SERVER['PHP_AUTH_USER'] == $config['api_uname']){
    if (isset($_SERVER['PHP_AUTH_PW']) and $_SERVER['PHP_AUTH_PW'] == $config['api_passwd']){
        $cookie_name = $_GET['user'];
        $ini = parse_ini_file("../registered_phid/" . $_GET[$cookie_name]);
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
        if ($ini['hour_gon'] != ""){
            if ($ini['hour_arv'] != ""){
                
                $ini = parse_ini_file("../registered_phid/" . $_GET[$cookie_name]);
                $cookid = $ini['first_name'] . " " . $ini['last_name'] . " " . $ini['student_id'] . " " . $ini['student_email'];
                $dayofmonth_gon = $ini['dayofmonth_gon'];
                $dayofmonth_arv = $ini['dayofmonth_arv'];
                $hour_gon = $ini['hour_gon'];
                $hour_arv = $ini['hour_arv'];
                $minute_gon = $ini['minute_gon'];
                $minute_arv = $ini['minute_arv'];
                $usrinfo = $ini['first_name'] . " " . $ini['last_name'] . " " . $ini['student_id'] . " " . $ini['student_email'];
                //get all the time
                $days_gone = $dayofmonth_arv-$dayofmonth_gon;
                $hours_gone = $hour_arv-$hour_gon;
                $minutes_gone = $minute_arv-$minute_gon;
                //^^^^ see how long they were gone ^^^^
                $a = getdate();
                $current_date = date("Y") . "-" . date("n") . "-" . date("d");
                $current_hour = $a['hours'];
                $rid31 = fopen("../registerd_qrids/" . $qrid, "r");
                $rid12 = fread($rid31, "30");
                $rid1 = str_replace(PHP_EOL, '', $rid12);
                fclose($rid31);
                //get the room ID
                session_start();
                $cookieodd = $_GET[$cookie_name];
                //check if current date exiests if it does dont make a DIR and dont add it to the html file
                $ini = parse_ini_file("../registered_phid/" . $_GET[$cookie_name]);
                if (!is_dir("../human_info/" . $cookieodd . "/" . $current_date)){
                
                mkdir("../human_info/" . $_GET[$cookie_name] . "/" . $current_date);
                //make a button for the current day for the admin log
                $main_html = '<link href="/style.css" rel="stylesheet" type="text/css" /><input class="reg" type="button" value="' . $current_date . '" onclick="location=\'/human_info/' . $_GET[$cookie_name] . '/' . $current_date . '/index.html\'" /></td>';
                $student = file_put_contents('../human_info/' . $_GET[$cookie_name] . '/index.html', $main_html.PHP_EOL , FILE_APPEND | LOCK_EX);
                }
                
                
                
                //check if the room exiests if it does do not add it to the HTML file and do not make the DIR
                if (!is_dir("../human_info/" . $_GET[$cookie_name] . "/" . $current_date . "/" . $rid1)){
                //if so make the dir and add it to the HTML file
                mkdir("../human_info/" . $_GET[$cookie_name] . "/" . $current_date . "/" . $rid1);
                $current_date_html = '<link href="/style.css" rel="stylesheet" type="text/css" /><input class="reg" type="button" value="' . $rid1 . '" onclick="location=\'/human_info/' . $_GET[$cookie_name] . '/' . $current_date . '/' . $rid1 . '/index.html\'" /></td>';
                $student = file_put_contents('../human_info/' . $_GET[$cookie_name] . '/' . $current_date . '/index.html', $current_date_html.PHP_EOL , FILE_APPEND | LOCK_EX);
                }
                //i always need to add the hour to the html file this is assuming people dont go to the restroom every .2 seconds 
                //OH MY GOD WHY DO PEOPLE DO TO THE BATHROOM EVERY .5 SECONDS AHHHH
                //And im too lazy to add another thing above and convert the backend lol NOOOOOO
                if (!is_file("../human_info/" . $_GET[$cookie_name] . "/" . $current_date . "/" . $rid1 . "/" . $current_hour . ".html")){
                $guide_to_info_page = '<link href="/style.css" rel="stylesheet" type="text/css" /><input class="reg" type="button" value="Hour: ' . $current_hour . '" onclick="location=\'/human_info/' . $_GET[$cookie_name] . '/' . $current_date . '/' . $rid1 . '/' . $current_hour . '.html\'" /></td>';
                $student = file_put_contents('../human_info/' . $_GET[$cookie_name] . '/' . $current_date . '/' . $rid1 . '/index.html', $guide_to_info_page.PHP_EOL , FILE_APPEND | LOCK_EX);
            }
                $info_page = '<link href="/style.css" rel="stylesheet" type="text/css" />' . $usrinfo . ' was out for ' . $days_gone . ' days ' . $hours_gone . ' hours and ' . $minutes_gone . ' minutes.<br>Student left classroom ' . $rid1 . ' at ' . $hour_gon . ':' . $minute_gon . ' and arrived at ' . $hour_arv . ':' . $minute_arv . '<br>';
                $student = file_put_contents("../human_info/" . $_GET[$cookie_name] . "/" . $current_date . "/" . $rid1 . "/" . $current_hour . ".html", $info_page.PHP_EOL , FILE_APPEND | LOCK_EX);
                //destroy all the variables for good mesure
                config_set('../registered_phid/' . $_GET[$cookie_name], "srvinfo", "dayofmonth_gon", "");
                config_set('../registered_phid/' . $_GET[$cookie_name], "srvinfo", "hour_gon", "");
                config_set('../registered_phid/' . $_GET[$cookie_name], "srvinfo", "minute_gon", "");
                config_set('../registered_phid/' . $_GET[$cookie_name], "srvinfo", "dayofmonth_arv", "");
                config_set('../registered_phid/' . $_GET[$cookie_name], "srvinfo", "hour_arv", "");
                config_set('../registered_phid/' . $_GET[$cookie_name], "srvinfo", "minute_arv", "");
                session_destroy();
            }
        }

    } else{
        fail();
        $output = array("success"=>0, "reason"=>"auth_fail", "help_url"=>"");
        echo json_encode($output);
        exit();
    }
} else{
    fail();
    $output = array("success"=>0, "reason"=>"auth_fail", "help_url"=>"");
    echo json_encode($output);
    exit();
}
