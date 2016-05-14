<?php
include("core/adodb/adodb.inc.php");
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db = NewADOConnection('mysql');
$db->Connect(_HOST, _USER, _PASS, _DB) or die("Error al conectarse a la base de datos");
?>