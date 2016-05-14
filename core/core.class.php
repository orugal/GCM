<?php
class Core
{
	//informacion del nodo
	var $info_id;
	//informacion hijos nodo
	var $info_id_hijos;
	
	function contenido($id,$tipo='')
	{
		global $db;
		global $funciones;
		global $smarty;
		//ahora debo traer los datos del nodo visitado para asi mismo poder saber que tipo es.
		$tipo_contenido				=	($tipo=='')?$funciones->obtenerTipoNodo($id):$tipo;
		//echo $tipo_contenido;
		//informacion del nodo padre
		$this->info_id				=	$funciones->infoId($id);
		//ahora signo la informacion de los hijos del nodo
		$this->info_id_hijos		=	$funciones->obtenerListado($id);	
		//realizamos el case para saber el tipo de nodo que viene para asi mismo saber que metodo usar
		switch($tipo_contenido)
		{
			//si es tipo catalogo
			case 0:
				return  $this->tipoDefault();
				break;
				//si es tipo default con varias imagenes
			
			case  16:
				return $this->catNoticia();
			break;
				case  1:
				return $this->tipoNoticia();
			break;
			
			case  3:
			case  8:
			case  9:
				//categoria catalogo
				return $this->tipoCategoria();
			break;
			case  4:
				//categoria catalogo
				return $this->tipoCategoriaImages();
			break;
			
			case  2:
				//subcategoria catalogo
				return $this->tipoSubCategoria();
			break;
			/*
			case  9:
				//lienas catalogo
				return $this->tipolineas();
			break;*/
			case  10:
				//lienas catalogo
				return $this->tipoProducto();
			break;
			case  11://mensajes texto
			case  57://mensajes Audio
			case  14://mensajes video
				//lienas catalogo
				return $this->tipoMensajes();
			break;
			case  21:
				//lienas catalogo
				return $this->tipoImagenProducto();
			break;
			/*
			case  3:
				return $this->tipoCatalogo();
			break;*/
			case  5:
			case 44:
				return $this->tipoPhp();
			break;
			//si es tipo home
			case  6:
				return $this->home();
			break;
			//si es tipo tienda
			case  41:
				return $this->tipoTiendas();
			break;
			case  42:
				return $this->tipoSite();
			break;
			//si es tipo academia
			case  26:
				return $this->tipoAcademias();
			break;
			//si es tipo academia
			case  27:
				return $this->tipoSubAcademias();
			break;
			//si es tipo ofertero
			case  28:
				return $this->tipoofertero();
			break;
			//si es tipo eventos
			case  30:
				return $this->tipoEventos();
			break;
			//si es tipo subeventos
			case  31:
				return $this->tipoSubEventos();
			break;
			case  32:
			case  33:
				return $this->ministerios();
			break;
			//si es tipo default con varias imagenes
			case  34:
				return $this->tipoDefault2();
			break;
			case  40:
				return $this->tipoCategoriaTienda();
			break;
			case  47:
				return $this->tipoArea();
			break;
			case  48:
				return $this->tipoSubArea();
			break;
			case  49:
				return $this->tipoAreaInterna();
			break;
			case  55:
				return $this->tipoRemi();
			break;
			case  56:
				return $this->tipoSubRemi();
			break;
			case  59:
				return $this->tipoOferta();
			break;
		}
	}

/*
	 * Funcion que mostrara los tipos Reminicencias
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoRemi()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoRemi.php';
		return $var;
	}/*
	 * Funcion que mostrara los tipos Reminicencias
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoSubRemi()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoSubRemi.php';
		return $var;
	}
	
	function ministerios()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/ministerios.php';
		return $var;
	}
/*
	 * Funcion que mostrara los tipos Area
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoArea()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoArea.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos tipoSubArea
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoSubArea()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoSubArea.php';
		return $var;
	}
	
	function tipoMensajes()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoMensajes.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos tipoSubArea
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoAreaInterna()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoAreaInterna.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos Default
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoEventos()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoEventos.php';
		return $var;
	}

	/*
	 * Funcion que mostrara los tipos Default
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoSubEventos()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoSubEventos.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos Default
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoDefault()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoDefault.php';
		return $var;
	}
	function tipoCategoriaTienda()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/catTienda.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos noticia y subnoticia
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoNoticia()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoNoticia.php';
		return $var;
	}

	/*
	 * Funcion que mostrara los tipos noticia y subnoticia
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function catNoticia()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/catNoticia.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos Default copn varias imagenes
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoDefault2()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoDefault2.php';
		return $var;
	}
/*
	 * Funcion que mostrara los tipos tiendas
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoTiendas()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoTiendas.php';
		return $var;
	}
/*
	 * Funcion que mostrara los tipos mini site
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoSite()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoSite.php';
		return $var;
	}
/*
	 * Funcion que mostrara los tipos mini site
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoOferta()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoOferta.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos academias
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoAcademias()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoacademias.php';
		return $var;
	}
	/*
	 * Funcion que mostrara los tipos subacademias
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoSubAcademias()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tiposubacademias.php';
		return $var;
	}

	/*
	 * Funcion que mostrara los tipos ofertero
	 * @return $var el cual sera la plantilla a mostrar
	*/
	function tipoofertero()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS.'interfaz/tipoofertero.php';
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento del Catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoCatalogo()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/catalogo.php";
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento de las categorias del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoCategoria()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/categorias.php";
		return $var;
	}

	/*
	 * Funcion que realiza el funcionamiento de las categorias del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoCategoriaImages()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/categorias_images.php";
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento de las marcas del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoMarca()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/marcas.php";
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento de las subcategorias del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoSubCategoria()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/subcategorias.php";
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento de las lineas del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoLineas()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/lineas.php";
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento de las lineas del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoProducto()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/producto.php";
		return $var;
	}
	/*
	 * Funcion que realiza el funcionamiento de las lineas del catalogo
	 * @return $var retorna la aplicacion
	*/
	function tipoImagenProducto()
	{
		global $db;
		global $funciones;
		global $id;
		
		$var = _PLANTILLAS."interfaz/imagenproducto.php";
		return $var;
	}
	
	/*
	* Funcion que muestra el home
	* @return el home
	*/
	function home()
	{
		global $db;
		$var = _PLANTILLAS."interfaz/home.php";
		return $var;
	}
	/*
	* Funcion que muestra el home
	* @return el home
	*/
	function tipoPhp()
	{
		global $db;
		$var = _PLANTILLAS."interfaz/tipoPhp.php";
		return $var;
	}
	/*
	* Funcion que lista las opciones del cabezote
	* @return el cabezote con su funcionamiento
	*/
	function listarCabezote()
	{	
		global $db;
		$var = _PLANTILLAS."interfaz/cabezote.php";
		return $var;
	}
	/*
	 * funcion que lista los nodos hijos del menu principal
	 * @return el la plantilla del menu principal
	*/
	function listarMenuPrincipal()
	{
		global $funciones;
		global $smarty;	
		global $db;
		//realizo el query para traer las opciones del menu principal
		$menu_principal	=	$funciones->obtenerListado(_PRINCIPAL,'',20);
		//asigno a smarty
		$opciones_principal 	=	$menu_principal;
		return $opciones_principal; 
	}
	/*
	 * funcion que lista los nodos hijos del menu secundario
	 * @return el la plantilla del menu secundario
	*/
	function listarMenuSecundario()
	{
		global $funciones;
		global $smarty;	
		global $db;
		//realizo el query para traer las opciones del menu principal
		$menu_secundario	=	$funciones->obtenerListado(_SECUNDARIO,'',20);
		//asigno a smarty
		$opciones_secundario		=	$menu_secundario;
		return $opciones_secundario;
	}
}
?>