<?php
class Funciones
{
	/*
	 * Funcion que realiza consultas para traer el listado de los nodos hijos
	 * @param int $nodo el cual es el nodo al cual le queremos consultar los subnodos
	 * @return array $arreglo_final que es el listado de opciones del menu principal
	 */
	function obtenerListado($id,$condicion='',$limit=50000000,$tabla=_TABLA_PRINCIPAL,$tipo_accesorios=3)
	{
		//variable base de datos @see config/conexion.php
		global $db;
		//valido si viene el condicional vacio
		if(empty($condicion))
		{
			//realizo el query universal con el nodo que me pasen
			$query	=	sprintf("SELECT * FROM %s WHERE id_padre=%s AND visible=1 AND eliminado=0 ORDER BY orden LIMIT %s",$tabla,$id,$limit);
		}
		else
		{
			//realizo el query universal con el nodo que me pasen
			$query	=	sprintf("SELECT * FROM %s WHERE id_padre=%s AND visible=1 AND eliminado=0 %s ORDER BY orden LIMIT %s",$tabla,$id,$condicion,$limit);
		} 
		//echo $query."<br>";
		//ejecuto la consulta
		$result	=	$db->Execute($query) or die(sprintf(_MENSAJE_ERROR,__LINE__,__FILE__));
		//declaro el arreglo con la informacion
		$arreglo_final	=	array();
		//recorro el resultado para ponerlo en un arreglo
		while(!$result->EOF)
		{
			$arreglo	=	array("id"=>($result->fields['id']),
								  "id_padre"=>$result->fields['id_padre'],
								  "antetitulo"=>$result->fields['antetitulo'],
								  "titulo"=>$result->fields['titulo'],
								  "subtitulo"=>$result->fields['subtitulo'],
								  "resumen"=>html_entity_decode(nl2br(utf8_encode($result->fields['resumen']))),
								  "contenido"=>html_entity_decode(nl2br(utf8_encode($result->fields['contenido']))),
								  "especificaciones"=>html_entity_decode(utf8_encode(nl2br($result->fields['especificaciones']))),
								  "marca"=>$result->fields['marca'],
								  "imagen"=>$result->fields['imagen'],
								  "imagen2"=>$result->fields['imagen2'],
								  "imagen_home"=>$result->fields['imagen_home'],
								  "pie_imagen"=>$result->fields['pie_imagen'],
								  "adjunto"=>$result->fields['adjunto'],
			 					  "autor"=>$result->fields['autor'],
								  "link"=>$result->fields['link'],
							      "tipo_link"=>$result->fields['tipo_link'],
						    	  "mail_autor"=>$result->fields['mail_autor'],
								  "tipo_contenido"=>$result->fields['tipo_contenido'],
								  "visible"=>$result->fields['visible'],
								  "eliminado"=>$result->fields['eliminado'],
								  "hijos"=>$this->obtenerListado($result->fields['id'],$condicion,$limit,$tabla,$tipo_accesorios),
								  "eventos"=>$this->obtenerRelUniversal('principal as p,relacion_universal as r',sprintf('r.id_padre=%s AND r.id=p.id AND r.tipo=%s',$result->fields['id'],$tipo_accesorios)),
								  "prom_votos"=>$this->obtenerPromedioVotos($result->fields['id']),
								  "tipo"=>$this->tipoArchivo($result->fields['adjunto']),
								  "promocion"=>$result->fields['promocion'],
								  "precio_normal"=>$result->fields['precio_normal'],
								  "precio_oferta"=>$result->fields['precio_oferta'],
								  "iva"=>$result->fields['iva'],
								  "direccion"=>$result->fields['direccion'],
								  "telefono"=>$result->fields['telefono'],
								  "telefono2"=>$result->fields['telefono2'],
								  "mail"=>$result->fields['mail'],
								  "horarios"=>$result->fields['horarios'],
							      "notas"=>$result->fields['notas'],
								  "mapa"=>$result->fields['mapa'],
								  "horarioinicio"=>$result->fields['horarioinicio'],
								  "horariofin"=>$result->fields['horariofin'],
								  "alto"=>$result->fields['alto'],
								  "visitas"=>$result->fields['visitas'],
								  "fecha"=>$result->fields['fecha'],
								  "puntoscanje"=>$result->fields['puntoscanje']);
			
			array_push($arreglo_final,$arreglo);
			$result->MoveNext();	
		}
		//retorno el resultado
		return $arreglo_final;
	}
	/*
	 * Funcion que me trae el listado de productos relacionados con el usuario
	 * @param $idusuario id del usuario al cual le consultaremos sus productos
	 * @return $listado el cual es el listado de productos relacionados con el usuario
	 */
	function getProductoUsuario($idusuario)
	{
		//variable base de datos @see config/conexion.php
		global $db;
		//realizo el query
		$query	=	sprintf("SELECT * FROM principal as p,rel_usuario_producto as r WHERE p.id = r.id AND r.idusuario=%s",$idusuario);
		//ejecuto el query
		$listado	=	$db->GetAll($query);
		return $listado;
	}
	/*
	 * Funcion que sirve para detectar el tipo de archivo que se usa para las descargas
	 * @param string $archivo es el nombre del archivo para detectar el tipo mime
	 * @return string $icono es la imagen correspondiente al tipo mime
	 */
	function tipoArchivo($archivo)
    {
        $extencion        =     substr($archivo,-3);
        $ruta_iconos    =      'images/mime/';
        $icono            =    '';
        if($extencion != '')
        {
            if($extencion == 'txt')//txt
            {
                $icono    =    'txt.gif';
            }
            elseif($extencion == 'doc')//word
            {
                $icono    =    'doc.gif';
            }         
            elseif($extencion == 'ocx')//word 2007
            {
                $icono    =    'doc.gif';
            }
            elseif($extencion == 'xls')//excel
            {
                $icono    =    'xls.gif';
            }
            elseif($extencion == 'lsx')//excel 2007
            {
                $icono    =    'xls.gif';
            }
            elseif($extencion == 'ppt')//power point
            {
                $icono    =    'ppt.gif';
            }
            elseif($extencion == 'ptx')//power point 2007
            {
                $icono    =    'ppt.gif';
            }
            elseif($extencion == 'pps')//powerpoint ejecutable
            {
                $icono    =    'ppt.gif';
            }
            elseif($extencion == 'html')//pagina web html
            {
                $icono    =    'html.gif';
            }
            elseif($extencion == 'pdf')//archivo pdf
            {
                $icono    =    'pdf.gif';
            }
            elseif($extencion == 'jpg')//imagen jpg
            {
                $icono    =    'jpg.gif';
            }
            elseif($extencion == 'JPG')//imagen jpg
            {
                $icono    =    'jpg.gif';
            }
            elseif($extencion == 'gif')//imagen gif
            {
                $icono    =    'gif.gif';
            }
            elseif($extencion == 'GIF')//imagen gif
            {
                $icono    =    'gif.gif';
            }
            elseif($extencion == 'tif')//imagen tif
            {
                $icono    =    'fic.gif';
            }
            elseif($extencion == 'png')//imagen png
            {
                $icono    =    'png.gif';
            }
            elseif($extencion == 'PNG')//imagen png
            {
                $icono    =    'png.gif';
            }
            elseif($extencion == 'fla')//archivo flash
            {
                $icono    =    'fic.gif';
            }
            elseif($extencion == 'swf')//archivo swf
            {
                $icono    =    'fic.gif';
            }
            elseif($extencion == 'mp3')//archivo swf
            {
                $icono    =    'fic.gif';
            }
            elseif($extencion == 'php')//archivo swf
            {
                $icono    =    'php.gif';
            }
            elseif($extencion == 'ttf')//archivo swf
            {
                $icono    =    'ttf.gif';
            }
            elseif($extencion == '.js')//archivo swf
            {
                $icono    =    'js.gif';
            }
            elseif($extencion == 'rar')//archivo swf
            {
                $icono    =    'rar.gif';
            }
            elseif($extencion == 'zip')//archivo swf
            {
                $icono    =    'zip.gif';
            }
            else
            {
                $icono    =    'fic.gif';
            }
        }
        return $icono;
    }
	
	/*
	 * Funcion que calcula el promedio de votos del contenido
	 * @param int $nodo nodo al cual le consultaremos los votos
	 * @return int $promedio promedio de votos
	 */
	function obtenerPromedioVotos($id)
	{
		$votos		=	$this->infoId($id);
		if($votos[0]['calificacion'] != 0)
		{
			$promedio 	=	round($votos[0]['calificacion'] / $votos[0]['votos']);
		}
		return $promedio;
	}
	/*
	 * Funcion que permite hacer una busqueda recursiva en reversa, es decir buscando siempre el padre inicial
	 * @param int $id en el id del nodo al cual queremos buscarle el padre
	 * @param array $datos es un arreglo que inicialmente se envia vacio y el sistema se encarga de llenarlo
	 * @return $datos retorna el arreglo con la informacion de la recursividad
	 */
	function BusquedaRecursiva($id,$datos)
    {
    	//variable base de datos @see config/conexion.php
            global $db;
            //creo la consulta que me traera el padre de la categoria seleccionada
            $traer_dato=sprintf("SELECT  id ,id_padre,titulo FROM principal WHERE id=%s",$id);
            //ejecuto la consulta
            $resultado=$db->Execute($traer_dato);
            //pregunto si el padre es diferente de 0
            if($resultado->fields['id_padre'] != "0")
            {
                    //si lo es pondre los datos traidos en el arreglo
                    array_push($datos,$resultado->fields);
                    //y de nuevo llamo la funcion
                    return $this->BusquedaRecursiva($resultado->fields['id_padre'],$datos);

            }
            //cuando ya el padre sea igual a 0 es por que ya llego a la pagina de inicio
            else
            {
                    //si lo es pondre los datos traidos en el arreglo
                    array_push($datos,$resultado->fields);
                    //retorno el arreglo
                    return (array_reverse($datos));
            }
    }
	/*
	 * Funcion que realiza una busqueda recursiva partiendo desde un nodo padre y hacia abajo
	 * @param int $idbusca el cual sera el id desde donde buscaremos
	 * @param int $cantidad el cual sera la cantidad de hijos que traera de ese nodo
	 * @param string $orden el cual sera el parametro por el cual se ordenaran estos resltados
	 * @param string $criterio el cual es el criterio de ordenacion de los contenidos 
	 * @param string $campos el cual seran los campos que quiero que traiga
	 */
	function recursivaAbajo($idbusca,$cantidad=20,$orden='id',$criterio='ASC',$campos='*')
	{
		global $db;
		$devolver=array();
		$temp_devolver=array();
		//consulto los datos de la categoria

		$query=sprintf("SELECT %s
			FROM principal
			WHERE id_padre=%s  AND eliminado=0 AND visible=1 order by %s %s limit %s ",$campos,$idbusca,$orden,$criterio,$cantidad);
		$resu=$db->Execute($query); 
		if(!empty($resu))
		{
			foreach ($resu as $res)
			{
				array_push($devolver,$res);
			}
			foreach ($resu as $fila)
			{
				$array_temp	=	$this->recursivaAbajo($fila['id'],$cantidad,$orden,$criterio,$campos);
				if($array_temp)
				{
					foreach ($array_temp as $array_tem)
					{
						array_push($devolver,$array_tem);
					}
				}
			}
			return $devolver;
		}
		return false;
	}
	/*
	 *Funcion que permite capturar variables por el metodo POST o GET
	 * @param string $nombre es el nombre de la variable del cualquiera de los metodos
	 * @param string $defecto	valor por defecto en caso de que la variable venga vacia 
	 * @return string $variable el cual es el valor que contenga la variable capturada	
	 */
	function obtenerVariable($nombre='',$defecto='')
	{
        //asigno una variable que sera la que me retornara
        $variable    =    '';
        //valido si viene la variable que envie por el metodo GET
        if(isset($_GET["$nombre"]))
        {
            //si es asi se la asigno a la variable antes declarada
            $variable    =    $_GET[$nombre];
        }
        //pero si viene por el metodo POST
        elseif(isset($_POST["$nombre"]))
        {
       
            //se la asigno a la variable asignada
            $variable    =    $_POST[$nombre];
        }
        //devuelvo el resultado
        return $variable;
	}
	
	/*
	 * Funcion que realiza consultas para traer la informacion del nodo
	 * @param int $nodo el cual es el nodo al cual le queremos consultar su informacion
	 * @return array $arreglo_final informacion del nodo
	 */
	function infoId($id)
	{
		//variable base de datos @see config/conexion.php
		global $db;
		//realizo el query universal con el nodo que me pasen
		$query	=	sprintf("SELECT * FROM %s WHERE id=%s",_TABLA_PRINCIPAL,$id);
		//echo $query; 
		//ejecuto la consulta
		$result	=	$db->Execute($query) or die(sprintf(_MENSAJE_ERROR,__LINE__,__FILE__).$query);
		//declaro el arreglo con la informacion
		$arreglo_final	=	array();
		//recorro el resultado para ponerlo en un arreglo
		while(!$result->EOF)
		{

			$arreglo	=	array("id"=>($result->fields['id']),
								  "id_padre"=>$result->fields['id_padre'],
								  "antetitulo"=>$result->fields['antetitulo'],
								  "titulo"=>$result->fields['titulo'],
								  "subtitulo"=>$result->fields['subtitulo'],
								  "resumen"=>html_entity_decode(nl2br($result->fields['resumen'])),
								  "contenido"=>html_entity_decode(nl2br($result->fields['contenido'])),
								  "especificaciones"=>html_entity_decode(nl2br($result->fields['especificaciones'])),
								  "marca"=>$result->fields['marca'],
								  "imagen"=>$result->fields['imagen'],
								  "imagen2"=>$result->fields['imagen2'],
								  "imagen3"=>$result->fields['imagen3'],
				              	  "imagen4"=>$result->fields['imagen4'],
								  "imagen5"=>$result->fields['imagen5'],
								  "imagen_home"=>$result->fields['imagen_home'],
								  "pie_imagen"=>$result->fields['pie_imagen'],
								  "adjunto"=>$result->fields['adjunto'],
			 					  "autor"=>$result->fields['autor'],
								  "link"=>$result->fields['link'],
							      "tipo_link"=>$result->fields['tipo_link'],
						    	  "mail_autor"=>$result->fields['mail_autor'],
								  "tipo_contenido"=>$result->fields['tipo_contenido'],
								  "visible"=>$result->fields['visible'],
								  "eliminado"=>$result->fields['eliminado'],
								  "puntaje"=>$result->fields['puntaje'],
								  "cantidad_votos"=>$result->fields['cantidad_votos'],
								  "producto"=>$result->fields['producto'],
								  "codproducto"=>$result->fields['codproducto'],
								  "marca"=>$result->fields['marca'],
								  "partefabricante"=>$result->fields['partefabricante'],
								  "parteproducto"=>$result->fields['parteproducto'],
								  "keywords"=>$result->fields['keywords'],
								  "alto"=>$result->fields['alto'],
								  "ancho"=>$result->fields['ancho'],
								  "largo"=>$result->fields['largo'],
								  "profundidad"=>$result->fields['profundidad'],
								  "puntos"=>$result->fields['puntos'],
								  "canje"=>$result->fields['canje'],
								  "puntoscanje"=>$result->fields['puntoscanje'],
								  "destacado"=>$result->fields['destacado'],
								  "novedad"=>$result->fields['novedad'],
								  "promocion"=>$result->fields['promocion'],
								  "precio_normal"=>$result->fields['precio_normal'],
								  "precio_oferta"=>$result->fields['precio_oferta'],
								  "iva"=>$result->fields['iva'],
								  "direccion"=>$result->fields['direccion'],
								  "telefono"=>$result->fields['telefono'],
								  "telefono2"=>$result->fields['telefono2'],
								  "mail"=>$result->fields['mail'],
								  "horarios"=>$result->fields['horarios'],
							      "notas"=>$result->fields['notas'],
								  "mapa"=>$result->fields['mapa'],
								  "horarioinicio"=>$result->fields['horarioinicio'],
								  "horariofin"=>$result->fields['horariofin'],
								  "visitas"=>$result->fields['visitas'],
								  "fecha"=>$result->fields['fecha'],
								  "votos"=>$result->fields['votos'],
								  "calificacion"=>$result->fields['calificacion']);
			array_push($arreglo_final,$arreglo);
			$result->MoveNext();	
		}
		//retorno el resultado
		return $arreglo_final;
	}
	/*
	 *Funcion que me dira el tipo de nodo el aucl corresponde al nodo visitado
	 * @param int $nodo el cual es el id del nodo visitado
	 * @return int $tipo_nodo el cual es el id del tipo del nodo. 
	 */
	function obtenerTipoNodo($id)
	{
		//variable base de datos @see config/conexion.php
		global $db;
		//query
		$query_tipo_nodo	=	sprintf("SELECT tipo_contenido FROM %s WHERE id=%s",_TABLA_PRINCIPAL,$id);
		//ejecuto el query
		$result_tipo_nodo	=	$db->Execute($query_tipo_nodo);
		//asigno el tipo del nodo
		$tipo_nodo			=	$result_tipo_nodo->fields['tipo_contenido'];
		//retorno el resultado
		return $tipo_nodo;
	}
	/*
	 *Funcion que permite realizar el ingreso de usuarios al sistema
	 * @param string $usuario nombre de usuario a autenticar
	 * @param string $password contraseña del usuario
	 */
	function login($usuario,$password)
	{
		//variable base de datos @see config/conexion.php
		global $db;
		//realizo el queri que me autenticara el usuario
		$query	=	sprintf("SELECT * FROM usuarios WHERE username='%s' AND contrasena = sha1('%s')",$usuario,$password);
		//ejecuto la consulta
		$result	=	$db->Execute($query) or die("No se pudo realizar la consulta del usuario");
		//verifico que me retorne datos la consulta
		if($result->NumRows() > 0)
		{
			return $_SESSION['login']	=	$result->fields;
		}
		else
		{
			return false;
		}
	}
/*
	 *Funcion que permite verificar la cantidad de hijso que tiene un contenido
	 * @param string $tabla el cual es la tabla a la cual le haremos la verificacion
	 * @param int $id el cual es el contenido al cual le verificaremos los hijos
	 * @return $res el cual es la cantidad de hijos
	 */
	function verificaHijos($tabla, $id){
		global $db;
		$sql = "SELECT * FROM $tabla WHERE id_padre=$id AND visible=1 AND eliminado=0";
		$res = $db->Execute($sql);
		return $res->NumRows();
	}
	/*
	 *Funcion que permite de forma automatica eliminar los datos 
	 * @param string $tabla es la tabla a la cual le haremos la eliminacion
	 * @param string $id identificadores a eliminar	  
	 * @return string $mensaje el cual es el estado de lo que se halla realizado
	 */
	function eliminarTabla($tabla, $id){
		global $db;
		$id = explode(",",$id);
		foreach ($id as $valor){
			$hijos = $this->verificaHijos($tabla, $valor);
			if($hijos<=0){
				$sql = "DELETE FROM $tabla WHERE id=".$valor;
				$result_delete	= $db->Execute($sql);
				if($result_delete){
					return true;
				}else{
					return true;
				}
			}else{
				return false;
			}
		}
	}
	/*
	 *Funcion que permite de forma automatica eliminar los datos 
	 * @param string $tabla es la tabla a la cual le haremos la eliminacion
	 * @param string $id identificadores a eliminar	  
	 * @return string $mensaje el cual es el estado de lo que se halla realizado
	 */
	function habilitaContenidos($data)
	{
			//cargo el datos para hacer la ruta
			if(isset($_GET['type']))
			{
				$ruta	=	"<script>document.location='index.php?type=".$_GET['type']."'</script>";
			}
			elseif(isset($_GET['id']))
			{
				$ruta	=	"<script>document.location='index.php?id=".$_GET['id']."'</script>";
			}
			global $db;
			//verifico el estado actual del contenido
			$verific	=	sprintf("SELECT visible FROM principal WHERE id=%s",$data);
			$result		=	$db->Execute($verific);

			if($result->fields['visible'] == 0)//si esta deshabilitado lo habilita
			{
				$dato	=	1;
				echo "<script>alert('Se ha Procesado con Exito.')</script>";
				echo $ruta;
			}
			elseif($result->fields['visible'] == 1)
			{
				$dato	=	0;
				echo "<script>alert('Se ha Procesado con Exito.')</script>";
				echo $ruta;
			}
			$sql =sprintf("UPDATE principal SET visible=%s WHERE id=%s",$dato,$data); 
			$result_delete	= $db->Execute($sql);
			if($result_delete)
			{
				return $mensaje;
			}
			else
			{
				echo "<script>alert('La Operación No se llevo a cabo')</script>";
				echo $ruta;
				//return $mensaje;
			}

	}
	/*
	 *Funcion que permite de forma automatica hacer el guardado de los datos sin necesidad de pasar ningun parametro
	 * @param string $tabla el cual es la tabla a la cual le haremos la insercion
	 * @param int $accion	accion realizar  1 = Insertar 2 = Actualizar
	 * @param string $condicion esta condicion sera usada a la hora de hacer la actualizacion
	 * @return string $mensaje el cual es el estado de lo que se halla realizado
	 */
	function insertarDatos($tabla,$accion='',$condicion='',$datos='')
	{
		//variable base de datos @see config/conexion.php
		global $db;
		//lo primero que debo hacer es capturar el post
		if($datos == '')
		{
			$variables	=	$_POST;
		}
		else
		{
			$variables	=	$datos;
		}
		//ahora debo verificar si la tabla a la cual le voy a insertar existe
		$query_tabla	=	sprintf("DESC %s",$tabla);
		//ejecuto la consulta
		$result_tabla	=	$db->Execute($query_tabla);
		if(!$result_tabla)
		{
			//si la tabla no existe retorna un mensaje
			$mensaje	=	sprintf("<div style='border:1px solid blue'>Probablemente la tabla <b>%s</b> no existe en el base de datos. Verifique e intente nuevamente</div>",$tabla);
			return $mensaje;
		}
		//de lo contrario empieza el proceso de verificacion de los campos del POST con los de la tabla
		else
		{
			//creo un arreglo con los campos de la tabla
			$campos_tabla	=	array();
			while(!$result_tabla->EOF)
			{
				array_push($campos_tabla,$result_tabla->fields['Field']);
				$result_tabla->MoveNext();
			}
			//valido el tipo de accion
			switch($accion)
			{
				//insertar
				case 1:
						//ahora proceso a recorrer el post y hacer la busqueda dentro de los campos de la tabla
						foreach($variables as $key=>$info)
						{
							if(isset($variables[$key]))
							{
								$key1	=	explode(",",$key);	
								//donde $key seria el nombre del campo a buscar en el arreglo de campos de la tabla
								$variable =	array_search($key1[0],$campos_tabla);
								//valido que no me retorne nada, si es asi quiere decir que el campo no existe en la tabla, esto evitara que manden campos que no convengan
								if(!empty($variable))
								{
									//valida si es requerido
									if(strtolower($key1[1]) == 'r')
									{
										//$errores	=	'';
										//realiza las validaciones
										if($variables[$key]== '')
										{
												
											$errores	.=	"<li>El campo <b>".$key1[0]."</b> es requerido, por lo tanto no debe estar vacio</li>";
											if($key1[0] == 'titulo')
											{
												$mensaje	=	$this->validaTitulo($_POST['id_padre'],$_POST[$key]);
												if(!empty($mensaje))
												{
													$errores	=	$mensaje;
												}
											}
										}
										else
										{
											if($key1[0] == 'titulo')
											{
												$mensaje	=	$this->validaTitulo($_POST['id_padre'],$variables[$key]);
												if(!empty($mensaje))
												{
													$errores	=	$mensaje;
												}
											}
											//armo los campos a los cuales le insertare la informacion
											$campos		.=	sprintf("".$key1[0].",");
											//armo los valores para cada campo
											$valores	.=	sprintf("'".$info."',");
										}
									
									}
									else
									{
										//armo los campos a los cuales le insertare la informacion
											$campos		.=	sprintf("".$key.",");
											//armo los valores para cada campo
											$valores	.=	sprintf("'".$info."',");
									}
								}
							}
						}
						$campos		=	substr ($campos, 0, strlen($campos) - 1);
						$valores	=	substr ($valores, 0, strlen($valores) - 1);
						
						//genero el ultimo orden
						$ultimo_orden	=	$this->getUltimoOrden($variables['id_padre']);
						
						//empiezo con el armado del insert
						$insert		=	sprintf("INSERT INTO %s (%s,orden) VALUES (%s,'%s')",$tabla,$campos,$valores,$ultimo_orden+1);
				break;
				//actualizar	
				case 2;
						//ahora proceso a recorrer el post y hacer la busqueda dentro de los campos de la tabla
						foreach($variables as $key=>$info)
						{
							$key1	=	explode(",",$key);	
							//donde $key seria el nombre del campo a buscar en el arreglo de campos de la tabla
							$variable =	array_search($key1[0],$campos_tabla);
							//valido que no me retorne nada, si es asi quiere decir que el campo no existe en la tabla, esto evitara que manden campos que no convengan
							if(!empty($variable))
							{
								if(!empty($variable))
								{
									//valida si es requerido
									if(strtolower($key1[1]) == 'r')
									{
										//$errores	=	'';
										//realiza las validaciones
										if($variables[$key]== '')
										{
												
											$errores	.=	"<li>El campo <b>".$key1[0]."</b> es requerido, por lo tanto no debe estar vacio</li>";
											if($key1[0] == 'titulo')
											{
												$mensaje	=	$this->validaTitulo($_POST['id_padre'],$variables[$key],$_GET['editar']);
												if(!empty($mensaje))
												{
													$errores	=	$mensaje;
												}
											}
										}
										else
										{
											if($key1[0] == 'titulo')
											{
												$mensaje	=	$this->validaTitulo($_POST['id_padre'],$variables[$key],$_GET['editar']);
												if(!empty($mensaje))
												{
													$errores	=	$mensaje;
												}
											}
										//armo los campos a los cuales le insertare la informacion
											$campos		.=	sprintf($key1[0]."='".$info."',");
										}
									}
									else
									{
										$campos		.=	$key."='".$info."',";
									}
								}

							}
							
						}
						$campos		=	substr ($campos, 0, strlen($campos) - 1);
						//valido que el condicional venga
						if(empty($condicion))
						{
							$mensaje 	=	sprintf("<div style='border:1px solid blue'>No Existe una condicion para la Actualiza&oacute;n. Por favor Verifique</div>");
							return $mensaje;
						}
						else
						{
							//empiezo con el armado del insert
							$insert		=	sprintf("UPDATE %s SET %s WHERE %s",$tabla,$campos,$condicion);
						}
				break;	
			}
			if($errores == '')
			{
				//echo $insert;
				//ahora debo probar que el sistema funciona
				$result_insert	= $db->Execute($insert);
				//verifico que se halla insertado
				if($result_insert)
				{
						switch($accion)
						{
							case 1:
									//valido si es tipo 10
									if($_POST['tipo_contenido']==10 or $_POST['tipo_contenido']==21)
									{
										$ultimo_id	=	$db->Insert_ID();
										//lo inserto en la tabla de relacion
										$query_insert_nuevo	=	$db->Execute(sprintf("INSERT INTO atributos (producto) VALUES('%s')",$ultimo_id))or die(sprintf("INSERT INTO atributos (producto) VALUES('%s')",$db->Insert_ID()));
										//procedo a hacer la relacion de las categorias que se le asignaran a este video
										foreach($_POST['marca'] as $insert_marca)
										{
											$query_insert_categoria		=	$db->Execute(sprintf("INSERT INTO relacion_contenidos(id,id_padre) VALUES('%s','%s')",$ultimo_id,$insert_marca));
										}
										
									}
									//die(sprintf("INSERT INTO atributos (producto) VALUES('%s')",$db->Insert_ID()));
									$mensaje 	=	sprintf("<div style='border:1px solid blue'>Se ha Insertado con exito el nodo <b>%s</b>.</div>",$ultimo_id);
								break;
							case 2:
									$mensaje 	=	sprintf("<div style='border:1px solid blue'>Se ha Actualizado con exito.</div>");
								break;	
								
						}
				}
				else
				{
					$mensaje 	=	sprintf("<div style='border:1px solid blue'>La insercion no se llevo a cabo</div>");
				}
			}
			else
			{
				$mensaje	=	$errores;
			}
			return $mensaje;
			
		}
		
	}
	/*
	 * Funcion que sirve para evitar los ataques de SQL que hagan desde cualquiera de los metodos de transferencia de datos del portal.
	 * @param string $strVar el cual es el dato al cual le queremos quitar la inyeccion en caso de que venga.
	 * @param $key como la funcion lo que hace el leer el metodo GET y POST este sera el key de cada arreglo.
	 * @reurn $strVar el cual sera el listado de los campos que inicialmente ingresaron pero ya limpios de cualquier ataque.
	 */
	 function evitaSql($strVar,$key)
     {
        if (is_array($strVar))
        {
            foreach($strVar as $id => $valor)
            {
                $strVar[$id] = $this->evitaSql($valor,$id);
            }
        }
        else
        {
            //Fase 1
            //Reemplazamos caracteres no alfanumericos y especiales
            $final = ereg_replace("[^a-zA-Z0-9[áéíóú]ñ\.;,@#<>%/\&_\: \-]","",$strVar);
            //Fase 2
            //Reemplazamos cadenas peligrosas como select etc, con expresiones regulares
            $final = ereg_replace("^[Ss][Ee][Ll][Ee][Cc][Tt]$|^[Ii][Nn][Ss][Ee][Rr][Tt]$|^[Dd][Ee][Ll][Ee][Tt][Ee]$|^[Dd][Rr][Oo][Pp]$|^[Ss][Cc][Rr][Ii][Pp][Tt]$|^[Jj][Aa][Vv][Aa][Ss][Cc][Rr][Ii][Pp][Tt]$|^[Cc][Oo][Oo][Kk][Ii][Ee]$|^[Gg][Rr][Aa][Nn][Tt]$","",$final);
            //Fase 3
            // Hacemos un strip tags (quitar etiquetas html y php) y definimos arreglo de caracteres adicionales a filtrar
            if($key == 'Text1' || $key == '_INFO_ADICIONAL')//Si es el campo descripcion permitimos ciertas etiquetas
            {
                $peligros = array("--", "'","*","\\");
                $final = strip_tags($final, '<p><br><a><table><td><tr><th><img><b><li><ul><ol><strong><font><div><hr><object><param><iframe><b><small>');
            }
            else
            {
                $peligros = array(";", "--", "'","<",">","#","*","=","\\");
                $final = strip_tags($final);
            }
            //Fase 4
            //Reemplazamos caracteres peligrosos definidos en la fase 3
            $final = str_replace($peligros, "",$final);
            $strVar = $final;

        }
      return $strVar;
    }
    /*
     * Funcion que inicia la seguridad del administrador y del Front Page
     * @return los datos limpios de ataques.
	*/
    function iniciaSeguridad()
    {
   		 //inicio la seguridad del metodo GET
		foreach($_GET as $key=>$info)
		{
			$_GET[$key]	=	$this->evitaSql($info,$key);
		}
		//inicio la seguridad del metodo POST
    	foreach($_POST as $key=>$info)
		{
			if($key!='contenido' and $key!='mapa' and $key!='imagen' and $key!='titulo' and $key!='link' and $key!='resumen')
			{
				$_POST[$key]	=	$this->evitaSql($info,$key);
			}
		}
    }
    /*
     * Funcion que sirve para averiguar el ultimo orden asignado a cada id de cada rama
     * @param int $id_padre	rama a la cual le averiguaremos el ultimo orden
     * @return $orden el cual es el numero del ultimo orden asignado a esa rama
	 */
    function getUltimoOrden($id_padre)
    {
    	//variable base de datos @see config/conexion.php
    	global $db;
    	//creo el query
    	$query_orden	=	sprintf("SELECT MAX(orden) as orden FROM %s WHERE id_padre=%s",_TABLA_PRINCIPAL,$id_padre);
    	//ejecuto la consulta
    	$result_orden	=	$db->Execute($query_orden);
    	//retorno el resultado
    	return $result_orden->fields['orden'];
    }
    /*
     * Funcion para subir y bajar contenidos segun el orden
     * @param string $accion	el cual dira si el orden sube o baja
     * @param int $id el cual sera el id al cual se le canbiara el orden
     * @param int $orden_actual el cual es el orden en el cual se encuentra el contenido en la actualidad
     * @param int $id_actual id contenido en el que nos encontramos
     * @return nuevo orden.
	*/
    function reordenarContenido($accion,$id,$orden_actual,$id_actual)
    {
    	//variable base de datos @see config/conexion.php
    	global $db;
    	//subir contenido
    	if(strtolower($accion) == 's')
    	{
    		//consulto el orden anterior
    		$sqlsu			=	$db->Execute(sprintf("SELECT * FROM %s WHERE orden < %s AND id_padre=%s ORDER BY orden DESC",_TABLA_PRINCIPAL,$orden_actual,$id_actual)) or die(sprintf("SELECT * FROM %s WHERE orden < %s ORDER BY orden DESC",_TABLA_PRINCIPAL,$orden_actual));
    	//	die(sprintf("SELECT * FROM %s WHERE orden < %s AND id_padre=%s",_TABLA_PRINCIPAL,$orden_actual,$id_actual));
    		if($sqlsu)
    		{
	    		if($sqlsu->fields['orden'])
	    		{
	    			//ahora debo insertar el nuevo orden
	    			$nuevo_orden	=	$db->Execute(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$sqlsu->fields['orden'],$id)) or die(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$sqlsu->fields['orden'],$id));
		    		//pongo el orden a la categoria que antes estaba como anterior para que se vea el cambio
	    			$nuevo_orden_2	=	$db->Execute(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$orden_actual,$sqlsu->fields['id'])) or die(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$orden_actual,$sqlsu->fields['id']));
	    			return true;
	    		}
	    		
    		}		
		}
		//bajar el contenido
    	elseif(strtolower($accion) == 'b')
    	{
    		//consulto el orden anterior
		    $sqlsu			=	$db->Execute(sprintf("SELECT * FROM %s WHERE orden = %s+1  AND id_padre=%s ORDER BY orden DESC",_TABLA_PRINCIPAL,$orden_actual,$id_actual)) or die(sprintf("SELECT * FROM %s WHERE orden < %s ORDER BY orden DESC",_TABLA_PRINCIPAL,$orden_actual));
    		if($sqlsu)
    		{
	    		if($sqlsu->fields['orden'])
	    		{
		    		//ahora debo insertar el nuevo orden
		    		$nuevo_orden	=	$db->Execute(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$sqlsu->fields['orden'],$id)) or die(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$sqlsu->fields['orden'],$id));
		    		//pongo el orden a la categoria que antes estaba como anterior para que se vea el cambio
		    		$nuevo_orden_2	=	$db->Execute(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$orden_actual,$sqlsu->fields['id'])) or die(sprintf("UPDATE %s SET orden=%s WHERE id = %s",_TABLA_PRINCIPAL,$orden_actual,$sqlsu->fields['id']));
		    		return true;
	    		}
    		}
    	}
    	
    }
    /*
     * Funcion qe permite el envio del mail 
     * @param $para destinatario.
     * @param $subject asunto del mensaje.
     * @param $body cuerpo del mensaje
     * @param $altbody
     * @param $mailFROM remitente
     * @param $mailNameCompany nombre Compañia o empresa
     * @return boolean
	 */
    function SendMAIL($para,$subject,$body,$altbody='',$mailFROM,$mailNameCompany)
	{
	//	echo "aqui";
		$mail = new phpmailer();
	
		$mail->PluginDir = _DIR_PLUGIN;
		$mail->Mailer = _MAILER;
		$mail->Host = _HOST_MAIL; # Editar el Host smtp
		$mail->SMTPAuth = _SMTP_AUTH;
		$mail->Username = _SMTP_USER; # editar el usuario
		$mail->Password = _SMTP_PASS; # Editar el password
		$mail->IsHTML(_ES_HTML);
		$mail->From = $mailFROM;
		$mail->FromName = $mailNameCompany;
		$mail->Subject = $subject;
		$email = $para;
		$body = $body;
		$mail->Body = $body;
		$mail->AltBody = $altbody;
		$mail->Timeout=2;
		$mail->AddAddress($email);
		$intentos=1; 
		
		if($mail->Send())
		  return 1;
		else
		  return 0;  
	}
	/*
	 * Funcion que permite realizar una busqueda a cualquier tabla.
	 * @param string $tabla el cual es la tabla a la cual consultare.
	 * @param string $condicion sera el condicional de la consulta
	 * @param string $campos campos que queremos traer con la consulta
	 * @return array $resultado
	 */
    function consultaUniversal($tabla,$condicion,$campos='*')
    {
    	//variable base de datos @see config/conexion.php
    	global $db;
    	//realizo el query
    	$query	=	sprintf("SELECT %s FROM %s WHERE %s ",$campos,$tabla,$condicion);
    	//echo $query."<br>"; 
    	//ejecuto la consulta
    	$result	=	$db->Execute($query);
    	//declaro el arreglo para la info
    	$resultado	=	array();
    	if($result->NumRows() != 0)
    	{
	    	//recorro el resultado
	    	while(!$result->EOF)
	    	{
	    		//pongo la info en el arreglo
	    		array_push($resultado,$result->fields);
	    		//paso a la siguiente linea
	    		$result->MoveNext();
	    	}
    	}
    	//retorno el arreglo
    	return $resultado;
    }
    /*
     * Funcion que traduce el nombre del id que se le envia
     * @param int $id el contenido al cual le queremos consultar el nombre.
     * @return nombre del contenido
	 */
    function getNombreContenido($id)
    {
    	global $db;
    	$nombre	=	$this->consultaUniversal('principal','id='.$id,$campos='titulo'); 
    	return $nombre[0]['titulo'];	
    }
    /*
     * Funcion que obtiene la relacion universal de los contenidos
     */
    function obtenerRelUniversal($tabla,$condicion)
    {
		//debo traer los accesorios relacionados con est producto
		$eventos	=	$this->consultaUniversal($tabla,$condicion,$campos='*');
		return		$eventos;
    }
    /*
     * Funcion que relaciona las tiendas con el producto
     * @param  string $valor el cual son las tiendas separadas por comas.
     * @param int $id el cual es el id al cual le relacionaran
     * @return la insercion
	*/
    function relacionTiendas($valor,$id)
    {
    	global $db;
    	//realizo el proceso
    	$valores	=	explode(",",$valor);
    	//primero borro todo lo que este relacionado con ese producto y con ese tipo de relacion
    	$query_borrado	=	$db->Execute(sprintf("DELETE FROM relacion_universal WHERE id=%s",$id)) or die(sprintf("DELETE FROM relacion_universal WHERE id=%s",$id));
    	//recorro ese resultado
    	foreach($valores as $tiendas)
    	{
    		//valido que no sea vacio
    		if(!empty($tiendas))
    		{
    			//ahora procedo a insertar
    			$query_insertado	=	$db->Execute(sprintf("INSERT INTO relacion_universal (id,id_padre,tipo) VALUES('%s','%s','%s')",$id,$tiendas,5)) or die(sprintf("INSERT INTO relacion_universal (id,id_padre,tipo) VALUES('%s','%s','%s')",$id,$tiendas,5));
    		}
    	}
    }
    /*
     * funcion que validara si el titulo existe en la misma rama
     * @param int $padre el cual es el id del padre a verificar
     * @param string $titulo el cual sera el titulo que verificaremos
     * @param int $id el cual es el id que verificaremos
     * @return string $mensaje este sera el texto que nos dira si el titulo ya existe.
	 */
    function validaTitulo($padre,$titulo,$id='')
    {
    	global $db;
    	if($id=='')
    	{
	    	//valido el titulo
	    	$query_validacion	=	sprintf("SELECT titulo FROM principal WHERE id_padre=%s AND titulo LIKE '%s'",$padre,$titulo);
	    	//echo $query_validacion." - 1 "; 
	    	//ejecuto la consulta
	    	$result				=	$db->Execute($query_validacion);
	    	//valido si retorno mensaje
	    	if($result->NumRows() > 0)
	    	{
	    		$mensaje		=	"Ya Existe un Contenido creado con el titulo <b>".$titulo."</b>";
	    	}
    	}
    	else
    	{
    		//valido el titulo
	    	$query_validacion	=	sprintf("SELECT titulo FROM principal WHERE id_padre=%s AND titulo LIKE '%s' AND id != %s",$padre,$titulo,$id);
	    	//echo $query_validacion." - 2"; 
	    	//ejecuto la consulta
	    	$result				=	$db->Execute($query_validacion);
	    	//valido si retorno mensaje
	    	if($result->NumRows() > 0)
	    	{
	    		$mensaje		=	"Ya Existe un Contenido creado con el titulo <b>".$titulo."</b>";
	    	}
    	}
    	return $mensaje;
    }
    /*
     * Funcion que me permite hacer recursividad hacia atras por medio de un id en una tabla de relacion
     * @param int $id id al cual le sacaremos las migas
     * @return array $miga el cual sera el arreglo con la miga recursiva
     */
    function retornaMigaProducto($id)
    {
    	if(!empty($id))
    	{
    		//verifico a que contenido esta relacional el producto en cuention
    		$dato	=	$this->consultaUniversal('relacion_contenidos','id='.$id,'id_padre');
    		$datos	=	array();
    		if(!empty($dato[0]['id_padre']))
    		{
    			$miga	=	$this->BusquedaRecursiva(($dato[0]['id_padre']),$datos);
    		}
    	}	
    	
    	return $miga; 
    }
    /*
     * Funcion que me traera el titulo del contenido que se este visitando en el momento que le damos click
     * @param int $id el cual es el id al cual le sacaremos el titulo
     * @return string $titulo titulo del contenido 
     */
    function obtenerTitulo($id)
    {
    	$titulo	=	$this->consultaUniversal("principal"," id=".$id,'titulo');
    	return $titulo[0]['titulo'];
    }
	/*
	 * Funcion que traduce el mes
	 */
	function TraducirMes($mes)	
	{
		//realizo el switch de la variable del mes para traducirlo a español
		switch ($mes)
		{
			CASE '01':$mes='Enero'; Break;
		    CASE '02':$mes='Febrero'; Break;
		    CASE '03':$mes='Marzo'; Break;
		    CASE '04':$mes='Abril'; Break;
		    CASE '05':$mes='Mayo'; Break;
		    CASE '06':$mes='Junio'; Break;
		    CASE '07':$mes='Julio'; Break;
		    CASE '08':$mes='Agosto'; Break;
		    CASE '09':$mes='Septiembre'; Break;
		    CASE '10':$mes='Octubre'; Break;
		    CASE '11':$mes='Noviembre'; Break;
		    CASE '12':$mes='Diciembre'; Break;
		}
		return $mes;
	}
	/*
	 * Funcion que permite la traduccion del dia
	 */
	function TraducirDia($dia)	
	{
		//realizo el switch de la variable del mes para traducirlo a español
		switch ($dia)
		{
			CASE '0':$dia='Domingo'; Break;
		    CASE '1':$dia='Lunes'; Break;
		    CASE '2':$dia='Martes'; Break;
		    CASE '3':$dia='Miercoles'; Break;
		    CASE '4':$dia='Jueves'; Break;
		    CASE '5':$dia='Viernes'; Break;
		    CASE '6':$dia='Sabado'; Break;
		}
		return $dia;
	}
	/*
	 * Funcion que lista los banners para cada seccion
	 * @param int $id el cual sera el id de la seccion visitada
	 * @return $banners el cual sera un arreglo con el listado de banners seleccionado de forma aleatoria para cada seccion 
	 */
	function banners($id,$cantidad)
	{
		global $db;
		//realizo el query que traera los banner para esa seccion
		$preguntas	=	$db->GetAll(sprintf("SELECT * FROM relacion_banners as r,principal as p WHERE r.seccion=%s AND r.banner=p.id ORDER BY p.orden ASC",$id));
		//die(sprintf("SELECT * FROM relacion_banners as r,principal as p WHERE r.seccion=%s AND r.banner=p.id",$id));
		if(count($preguntas) < $cantidad)
		{
			//echo "por aca<hr>";
				$contador	=	0;
				$cuenta		=	0;
		 		foreach($preguntas as $data)
				{
				 	//echo $valor."<hr>";
				 	$cuenta++;
				 	$preguntas_final[$contador]['banner']			=	$data['banner'];
					$preguntas_final[$contador]['titulo']			=	$data['titulo'];
					$preguntas_final[$contador]['adjunto']			=	$data['adjunto'];
					$preguntas_final[$contador]['imagen']			=	$data['imagen'];
					$preguntas_final[$contador]['tipo_contenido']	=	$data['tipo_contenido'];
					$preguntas_final[$contador]['link']				=	$data['link'];
					$preguntas_final[$contador]['valor']			=	$contador;		
					$preguntas_final[$contador]['contador']			=	$contador;
					$contador++;
				 }
				 //para rellenar lo que haga falta del arreglo debo saber cuanto le falta a la cantidad que pedi restandole la cantidad real
				 $cantidad_final_banner		=	$cantidad - count($preguntas);
				 //asigno el final para el for siguiente
				 $final_for					=	($cantidad_final_banner + $cuenta)-1;
				// echo $contador."<hr>";
				// echo $cantidad_final_banner;
				 for($a=$cuenta;$a<=$final_for;$a++)
				 {
				 	$preguntas_final[$a]['banner']			=	0;
					$preguntas_final[$a]['titulo']			=	'';
					$preguntas_final[$a]['adjunto']			=	'';
					$preguntas_final[$a]['imagen']			=	'diseno/paute.jpg';
					$preguntas_final[$a]['tipo_contenido']	=	36;
					$preguntas_final[$a]['link']			=	_DOMINIO;
					$preguntas_final[$a]['valor']			=	$a;		
					$preguntas_final[$a]['contador']		=	$a;
				 }
				 //ahora armo un arreglo con la info vacia
				// var_dump($preguntas_final);
				 return $preguntas_final;
			//echo "hay menos de lo que me pide";
		}
		elseif($preguntas >= $cantidad)
		{
			//echo "o por aca";
			if(count($preguntas) > 0)
			{
				$cantidad_banners	=	 count($preguntas);
				$num = Array();
				 reset($num);
				 for($i=1;$i<=$cantidad;$i++)
				 {
				   $num[$i]=rand(0,$cantidad_banners-1);
				    if($i>1)
				    {
				       for($x=1; $x<$i; $x++)
				       {
				         if($num[$i]==$num[$x])
				         {
				           $i--;
				           break;
				         }
				      }
				   }
				 }
				 $contador	=	0;
				 foreach($num as $valor)
				 {
				 	//echo $valor."<hr>";
				 	$preguntas_final[$contador]['banner']			=	$preguntas[$valor]['banner'];
					$preguntas_final[$contador]['titulo']			=	$preguntas[$valor]['titulo'];
					$preguntas_final[$contador]['adjunto']			=	$preguntas[$valor]['adjunto'];
					$preguntas_final[$contador]['imagen']			=	$preguntas[$valor]['imagen'];
					$preguntas_final[$contador]['tipo_contenido']	=	$preguntas[$valor]['tipo_contenido'];
					$preguntas_final[$contador]['link']				=	$preguntas[$valor]['link'];
					$preguntas_final[$contador]['valor']			=	$valor;		
					$preguntas_final[$contador]['contador']			=	$contador;
					$contador++;
				 }
				// var_dump($preguntas);
				 //echo "<hr>";
				 //var_dump($num);
				return $preguntas_final;
			}
			else
			{
				echo "No hay banners asignados a esta seccion";
			}
		}
	}
	/*
	 * Funcion qe contara los clicks
	 * @param int $banner el cual sera el id del banner que se esta visitando
	 * @param string $url el cual sera la url donde debe redirigir el vinculo cuando se haga la suma
	 * @param string $imagen la cual sera la imagen que tiene el banner en la actualidad
	 * @return el redireccionamiento a la pagina seleccionada
	 */
	function cuentaClicks($banner,$url,$imagen)
	{
		global $db;
		//primero verifico si el banner ys esta registrado en la página web
		//query que realizara el update de los clics al banner
		$query_insert_clic	=	$db->Execute(sprintf("INSERT INTO vista_links (banner,url,imagen,fecha_hora) 
										VALUES ('%s','%s','%s','%s')",
										$banner,
										$url,
										$imagen,
										date("Y-m-d h:i:s"))) or die(sprintf("INSERT INTO vista_links (banner,url,imagen,fecha_hora) 
										VALUES ('%s','%s','%s','%s')",
										$banner,
										$url,
										$imagen,
										date("Y-m-d H:i:s")));
		//si se inserta 
		if($query_insert_clic)
		{
			header("location:".$url);
		}
	}
	function diferenciaDias($fecha_actual,$fecha_final)
	{
		$fecha_1	=	explode("-",$fecha_actual);
		$fecha_2	=	explode("-",$fecha_final);
		
		$ano1 = $fecha_1[0];
		$mes1 = $fecha_1[1];
		$dia1 = $fecha_1[2];
		
		//defino fecha 2
		$ano2 = $fecha_2[0];
		$mes2 = $fecha_2[1];
		$dia2 = $fecha_2[2];
		
		//calculo timestam de las dos fechas
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
		$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2);
		
		//resto a una fecha la otra
		$segundos_diferencia = $timestamp1 - $timestamp2;
		//echo $segundos_diferencia;
		
		//convierto segundos en días
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		
		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		$dias_diferencia = abs($dias_diferencia);
		
		//quito los decimales a los días de diferencia
		$dias_diferencia = floor($dias_diferencia);
		
		return $dias_diferencia; 
	}
	/*
	 * Funcion que crea una fecha de vencimiento
	 * @param string $fec_emision la cual sera la fecha actual desde la cual se sacara la fecha de vencimiento
	 * @param int $cant_dias el cual sera la cantidas de dias que se le daran como vencimiento
	 * @return string $fec_vencimiento la cual sera la fecha que retornara
	 */
	function fechaVencimiento($fec_emision,$cant_dias)
	{
		$fecha = explode("-",$fec_emision);
		$can_dias = $cant_dias;
		$dyh = getdate(mktime(0, 0, 0, $fecha[1], $fecha[2], $fecha[0]) + 24*60*60*$can_dias);
		$dia			 =	($dyh['mday'] < 10)?"0".$dyh['mday']:$dyh['mday'];
		$mes			 =	($dyh['mon'] < 10)?"0".$dyh['mon']:$dyh['mon'];
		$fec_vencimiento = $dyh['year']."-".$mes."-".$dia;
		return $fec_vencimiento; 
	}
	function alimentaXML()
	{
		global $db;
		global $funciones;
		$marcas	=	$db->GetAll(sprintf("SELECT * FROM principal WHERE id_padre=%s AND visible=1 AND eliminado=0 ORDER BY orden ASC",435));
		//var_dump($noticias_home);
		//Abrimos el archivo y leemos su contenido
		//Actualizamos el archivo con el nuevo valor
		$fp = fopen('imageLoopData.xml',"w+");
		$datos	=	'<imageLoop>';
		foreach($marcas as $data)
		{
			$datos	.='<img url="![CDATA['.$data['link'].']]"><![CDATA[images/'.$data['imagen'].']]></img>';
		}	
		$datos.='</imageLoop>';	
		fwrite($fp, $datos, 8000);
		fclose($fp); 
	}

	function traerUrl($id)
	{
		global $db;
		$query_url	=	$db->GetAll(sprintf("SELECT * FROM principal WHERE id='%s'",$id));
		if(!empty($query_url[0]['url_amigable']))
		{
			$url	=	$query_url[0]['url_amigable'];
		}
		else
		{
			$url	=	"index.php?id=".$query_url[0]['id'];
		}
		return $url;
	}
}
?>
