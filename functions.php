<?php
// inc/functions.php
require_once __DIR__.'/config.php';

function is_logged_in(){
    return isset($_SESSION['user_id']);
}
function current_user(){
    if(!is_logged_in()) return null;
    global $mysqli;
    $id = (int)$_SESSION['user_id'];
    $res = $mysqli->query("SELECT id,username,full_name,email,role FROM users WHERE id=$id LIMIT 1");
    return $res->fetch_assoc();
}
function require_role($role){
    $u = current_user();
    if(!$u || $u['role']!==$role){
        header("Location: login.php");
        exit;
    }
}
