<?php
 
require_once &quotconstants.php"
$table = &quotlink_tbl"
 
function validate_url(string $url)
{   
    $url_list = parse_url($url);
    $host = $url_list['host'];
    $path = $url_list['path'];
    $url = str_ireplace($path , urlencode($path) , $url);
    $url = str_ireplace($host . &quot%2f&quot , $host . &quot/&quot , $url);
    $url = filter_var($url, FILTER_VALIDATE_URL);
    return $url;
}
 
function show_json_message($array_data)
{
    return json_encode($array_data);
}
 
function generate_url_token($url)
{
    $crc32_number = crc32($url);
    $encoded_url = base_convert($crc32_number, 10, 36);
    $encoded_url_list = str_split($encoded_url, 1);
    $encoded_url = &quot"
 
    for ($i = 0; $i < sizeof($encoded_url_list); $i++) {
        $current_index = $encoded_url_list[$i];
        $letter = is_numeric($current_index) ? $current_index : strtoupper($current_index);
        $encoded_url .= $letter;
    }
 
    return $encoded_url;
}
 
function generate_html_message($message = &quot&quot, $type = &quotnotice&quot)
{
    return &quot<div class=\&quotmsg {$type}\&quot><p>{$message}</p></div>"
}
 
function remove_end_slash($url)
{
    $url = strrev($url);
    $url = str_split($url, 1);
 
    if ($url[0] == &quot/&quot) unset($url[0]);
 
    $url = join($url);
    $url = strrev($url);
 
    return $url;
}
 
function update_views($column, $short_url)
{
 
    $mysqli = new mysqli(HOST, USERNAME, PASSWORD, DB);
    $current_time = CURRENT_TIME;
    $row_updated = 0;
 
    $mysqli->set_charset(&quotutf8&quot);
    $stmt = $mysqli->stmt_init();
    $query = &quotUPDATE `{$GLOBALS['table']}` SET `views` = views + 1 , `date_last_view` = ? WHERE {$column} = ?"
 
    $stmt->prepare($query);
    $stmt->bind_param('ss', $current_time, $short_url);
 
    if ($stmt->execute()) {
        $row_updated = $stmt->affected_rows ? $stmt->insert_id : 0;
    }
 
    $stmt->close();
    $mysqli->close();
 
    return $row_updated;
}
 
function row_select($user_query, $column, $select = &quot*&quot)
{
 
    $mysqli = new mysqli(HOST, USERNAME, PASSWORD, DB);
    $row = 0;
 
    $mysqli->set_charset(&quotutf8&quot);
    $stmt = $mysqli->stmt_init();
    $query = &quotSELECT {$select} FROM `{$GLOBALS['table']}` WHERE {$column}=?"
 
    $stmt->prepare($query);
    $stmt->bind_param(&quots&quot, $user_query);
 
    if ($stmt->execute() && $res = $stmt->get_result()) {
        if ($stmt->affected_rows || $res->num_rows) {
            $row = $res->fetch_assoc();
        }
    }
 
    $stmt->close();
    $mysqli->close();
 
    return $row;
}
 
function row_insert($url)
{
    $mysqli = new mysqli(HOST, USERNAME, PASSWORD, DB);
    $views = 0;
    $token_url = generate_url_token($url);
    $inserted_id = 0;
    $current_time = CURRENT_TIME;
 
    $mysqli->set_charset(&quotutf8&quot);
    $stmt = $mysqli->stmt_init();
 
    $query = &quotINSERT INTO `{$GLOBALS['table']}` (link_original , link_short , date_submitted , date_last_view , views) VALUES (? , ? , ? , ? , ?)"
 
    $stmt->prepare($query);
    $stmt->bind_param('ssssi', $url, $token_url, $current_time, $current_time, $views);
 
    if ($stmt->execute()) {
        $inserted_id = $stmt->affected_rows ? $stmt->insert_id : 0;
    }
 
    $stmt->close();
    $mysqli->close();
 
    return $inserted_id;
}
