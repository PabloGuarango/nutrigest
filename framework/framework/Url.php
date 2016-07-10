<?php

/*
/*clase para el meanejo de peticiones de url
*/
class Url{
		
	//necesitamos algnas variables
	private $urlPartes = array();
	private $urlDirectorio;
	
	//funcion constructora de registro
	public function __construct(Registro $registro)
	{
		$this->registro = $registro;
	}
	
	//fijamos el directorio de la url
	public function fijarDirectorioUrl($directorio)
	{
		$this->urlDirectorio = $directorio;
	}
		
	//partimos en partes la url recogida de las entradas url
	public function obtenerDatosUrl()
	{
		$datosUrl = (isset($_GET['page'])) ? $_GET['page'] : '';
		$this->urlDirectorio = $datosUrl;
		if ($datosUrl == '')
		{
			$this->urlPartes[] = '';
			$this->urlDirectorio = '';
			}
		else
		{
			$dato = explode('/',$datosUrl);
			while (!empty($dato) && strlen(reset($dato)) === 0)
			{
				array_shift($dato);
			}
			while (!empty ($dato) && strlen (end ($dato)) === 0)
			{
				array_pop($dato);
			}
			$this->urlPartes = $this->array_trim($dato);
		}
	}
	
	////el resto del framework necesita obtener y acceder a la URL
	public function obtenerPartesUrl()
	{
		return $this->urlPartes;
	}
		
	//tambien debemos tener en ceunta que podemos solicitar por ejemplo el directorio o url amigos, situacion, mensajes, etc para eso hacemos
	public function obtenerParteURL($cualParte)
	{
		return (isset($this->urlPartes[$cualParte])) ? $this->urlPartes[$cualParte] : 0;
	}
	
	//tambien necesitamos acceder al directorio
	public function obtenerDirectorioUrl()
	{
		return $this->urlDirectorio;
	}
	
	//eliminamos los esapcios en blanco de izquierda a derecha	
	private function array_trim( $array ) 
	{
	    while ( ! empty( $array ) && strlen( reset( $array ) ) === 0) 
	    {
	        array_shift( $array );
	    }
	    
	    while ( !empty( $array ) && strlen( end( $array ) ) === 0) 
	    {
	        array_pop( $array );
	    }
	    
	    return $array;
	}

	
	//ahora creamos la URL necesaria o la que hacemos la peticion
	public function crearURL( $partes, $qs, $admin )
	{
		$admin = ( $admin == 1 ) ? $this->registro->obtenerConfig('admin_folder') . '/' : '';
		$el_resto = '';
		foreach( $partes as $parte )
		{
			$el_resto .= $parte . '/';
		}
		$el_resto = ( $qs != '' ) ? $el_resto . '?&' .$qs : $el_resto;
		return $this->registro->obtenerConfig('siteurl') . $admin . $el_resto;
		
	}
	
		
		
		
}