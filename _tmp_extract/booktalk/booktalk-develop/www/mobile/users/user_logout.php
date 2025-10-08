<?php
require_once '../../wps-config.php';

unset( $_SESSION['login'] );

wps_redirect( MOBILE_URL );

?>