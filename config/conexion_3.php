<?php
include("../../core/adodb/adodb.inc.php");
$db = NewADOConnection('mysql');
$db->Connect(_HOST, _USER, _PASS, _DB);
?>