<?php

/**
 * clase para el registrar los procesos --- todo lo que se haga se registra
 */


class Registro{

	//array de objetos
	private $objetos;
	
	//array de configuraciones
	private $configuracion;
	
	//metodo constructor de la clase
	public function __construct()
	{
		
	}
		
	//funcion para crear y almacenar los objetos 
	
	public function crearAlmacenarObjeto($objeto,$clave)
	{
		require_once ($objeto.'.php');
		$this->objetos[ $clave ] = new $objeto($this);
	}
	//funcion para obtener los objetos
	public function obtenerObjeto($clave)
	{
		return $this->objetos [ $clave ];
	}
	//funcion para almacenar las configuraciones de los objetos
	public function almacenarConfig($config , $claves)
	{
		return $this->configuracion[ $claves ] = $config;
	}
		
	//funcion para obtener configuraciones
	public function obtenerConfig($clave)
	{
		return $this->configuracion [ $clave ];
	}


	public function paginaDeError( )
    {
    	$this->obtenerObjeto('plantilla')->crearDesdePlantilla('cabecera', '404', 'pie');
    }
    
    
   // crear la URL
    public function crearURL( $urlPartes, $cadenaConsulta=array() )
    {
    	return $this->obtenerObjeto('url')->crearURL( $urlBits, $cadenaConsulta, false );
    }
    
   
   //redireccionar peticiones de usuario
    public function redireccionarPagina( $url, $cabecera, $mensaje )
    {
    	$this->obtenerObjeto('plantilla')->crearDesdePlantilla('redireccion');
    	$this->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta( 'cabecera', $cabecera );
    	$this->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta( 'mensaje', $mensaje );
    	$this->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta( 'url', $url );
    	
    }
    
    
}
