<?php

require("config/configuracion.php");
require("config/conexion.php");
// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AIzaSyDg7t3WahID1wyLOtoPBWAh513HLMgSYv0' );
extract($_REQUEST);

if($accion == 1)
{
	//registro al usuario
	$query  = sprintf("select * from usuarios where usuario='%s' and clave=sha1('%s') and activo=1 and eliminado=0",$usuario,$clave);
	$result = $db->GetAll($query);

	if(count($result) > 0)
	{
		$salida = array("mensaje"=>"Buenvenido",
						"datos"=>$result[0],
						"continuar"=>1);
	}
	else
	{
		$salida = array("mensaje"=>"El usuario y la contraseña no son correctos",
						"datos"=>array(),
						"continuar"=>0);
	}
	echo $callback."(".json_encode($salida).")";
}
elseif($accion == 2)
{
	$query   = sprintf("UPDATE usuarios SET registroCel='%s' WHERE idusuario=%s",$idReg,$usuario);
	$result  = $db->Execute($query);
	if($result)
	{
		$salida = array("mensaje"=>"Conectado a la red",
						"datos"=>array(),
						"continuar"=>1);
	}
	else
	{
		$salida = array("mensaje"=>"Falla de GCM",
						"datos"=>array(),
						"continuar"=>0);
	}
	echo $callback."(".json_encode($salida).")";
}
elseif($accion == 3)
{
	$query = sprintf("INSERT INTO chat (`from`,`message`,`sent`) values('%s','%s','%s')",$usuario,$mensaje,date("Y-m-d H:I:s"));
	$result  = $db->Execute($query);
	if($result)
	{
		$dataU  = dataUsuario($usuario);
		$persona = $dataU['nombre']." ".$dataU['apellido'];
		$salida = array("mensaje"=>"Enviado",
						"datos"=>array(),
						"continuar"=>1);
		sendGCM($mensaje,$persona);
	}
	else
	{
		$salida = array("mensaje"=>"No Enviado",
						"datos"=>array(),
						"continuar"=>0);
	}
	echo $callback."(".json_encode($salida).")";
}




function consultaGCMReg()
{
	global $db;
	$query  = sprintf("select * from usuarios where registroCel != '' ");
	$result = $db->GetAll($query);
	if(count($result) > 0)
	{
		foreach($result as $list)
		{
			$salida[]	=	$list['registroCel'];
		}
	}
	else
	{
		$salida = array();
	}
	return $salida;
}


function dataUsuario($usuario)
{
	global $db;
	$query  = sprintf("SELECT * FROM usuarios WHERE idusuario=%s",$usuario);
	$result = $db->GetAll($query);
	return $result[0];
}

function sendGCM($mensaje,$persona)
{
	//$registrationId = array('APA91bHv3GhmC1DrYIvNn3mjd4KK9-4OiqItG07cC5ZSeLERPmr0GKYaYw_eSjEe1ErnvBGW2XTeUankmTvepue-vt_HewMmdociAix4v5rU37IYzA_AOwI');
	$registrationId = consultaGCMReg();

	$msg = array
	(
		'message' 	=> $mensaje,
		'title'		=> $persona,
		'subtitle'	=> '',
		'tickerText'	=> '',
		'vibrate'	=> 1,
		'sound'		=> 1,
		'largeIcon'	=> 'large_icon',
		'smallIcon'	=> 'small_icon'
	);

	//$msg = "please note this..";

	$fields = array
	(
		'registration_ids' 	=> $registrationId,
		'data'			=> $msg
	);
	 
	$headers = array
	(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json',
	        'delay_while_idle: true',
	);
	 
	try{
	    $ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );

	echo $result;

	}
	catch(Exception $e){
	    echo $e;
	    echo "inside catch";
	}
}

//sendGCM();




