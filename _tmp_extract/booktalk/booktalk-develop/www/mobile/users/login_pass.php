<?php
require_once '../../wps-config.php';

$user_id = $_SESSION['join']['user_id'];

$user_level = wps_get_user_meta( $user_id, 'wps_user_level' );

$users = wps_get_user_by( 'ID', $user_id );

$_SESSION['login']['userid'] = $user_id;
$_SESSION['login']['user_name'] = $users['user_name'];
$_SESSION['login']['display_name'] = $users['display_name'];
$_SESSION['login']['user_level'] = $user_level;

unset($_SESSION['join']);

header( 'Location: ' . MOBILE_URL );

?>