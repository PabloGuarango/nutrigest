<?php
/*
/*esta clase permite una coneccion multimple a bases de datos*/

class BaseDatos{
	
	//array para varias conecciones
	private $conexiones = array();
	
	//ver si la coneccion esta activa
	private $conexionActiva = 0;
	
	// crear cache de las consultas
	private $queryCache = array();
	
	//crear cahe de los datos de las consultas
	private $cacheDatos = array();
	
	//numero de consultas durante la ejecucion de los procesos
	private $contador = 0 ;
	
	//grabar ultima consulta
	private $ultima;
	
	//referencia al registro de objetos
	private $registro;
	
	//constructor del objeto de la base de datos
	public function __construct(Registro $registro)
	{
		$this->registro = $registro;
	}
	
	//creamos la funcion para conectar a la base de datos
	public function nuevaConexion($host,$usuario,$clave,$base)
	{
		$this->conexiones[]= new mysqli($host,$usuario,$clave,$base);
		$idConeccion = count ($this->conexiones) - 1;
		if(mysqli_connect_errno())
		{
			trigger_error('Error conectando al HOST.' .$this->conexiones[$idConeccion]->error, E_USER_ERROR);
		}
		return $idConeccion;
	}
		
	//funcion para verificar si la conexion esta activa o no
	public function activarConexion(int $nuevo)
	{
		$this->conexionActiva = $nuevo;
	}
		
	//ejecutar las consultas
	public function ejecutarQuerry ($consulta)
	{
		if(!$resultado = $this->conexiones[$this->conexionActiva]->query($consulta))
		{
			trigger_error('Error en la ejecucion de la CONSULTA:'.$consulta.' - '.$this->conexiones[$this->conexionActiva]->error, E_USER_ERROR);
		}
		else
		{
			$this->ultima = $resultado;
		}
	}
		
	/*
	empezamos con las consultas mas destacadasa o mas impirtantes DELETE, UPDATE, SELECT
	*/
	
	//ahora crearemos la funcion para borrar registros
	//$tabla=>nombre de la tabla
	//$condicion=>condiciones de borrado
	//$limite=>limite de la ejecucion
	public function borrarDatos($tabla,$condicion,$limite)
	{
		$limite = ( $limite == '') ? '' : ' LIMIT ' . $limite;
		$borrar = "DELETE FROM {$tabla} WHERE {$condicion} {$limite}";
		$this->ejecutarQuerry($borrar);
	}
	
	
	//ahora vamos a actualizar los datos de una tabla
	public function actualizarDatos($tabla,$cambios,$condicion)
	{
		$actualiza = "UPDATE ".$tabla." SET";
		foreach ($cambios as $clave => $valor)
		{
			$actualiza .= "`".$clave."` ='{$valor}',";
		}
		$actualiza = substr($actualiza,0,-1);
		if ($condicion != '')
		{
			$actualiza .= "WHERE ".$condicion;
		}
		$this->ejecutarQuerry($actualiza);
		return true;
	}
		
	//ahora para insertar datos en una tabla
	public function insertarDatos($tabla,$datos)
	{
		$campos = '';
		$valores = '';
		
		foreach($datos as $c => $v)
		{
			$campos .= " `$c`,";
			$valores .= (is_numeric($v) && (intval($v) == $v ) ? $v."," : "'$v',");
		}
		$campos = substr($campos,0,-1);
		$valores = substr($valores,0,-1);
		$insertar = "INSERT INTO $tabla ({$campos}) VALUES({$valores})";
		$this->ejecutarQuerry($insertar);
		return true;
	}
		
	//tambien podemos limpiar nuestros datos ingresados
	public function limpiarDatos($valor)
	{
		//stripslashes
		if(get_magic_quotes_gpc())
		{
			$valor = stripslashes($valor);
		}
		if (version_compare(phpversion(),"4.3.0" == "-1"))
		{
			$valor = $this->conexiones[$this->conexionActiva]->escape_string($valor);
		}
		else 
		{
			$valor = $this->conexiones[$this->conexionActiva]->real_escape_string( $valor );
		}

		return $valor;
	}
		
	//ahora necesitamos obtener los datos de la consulta ejecutada
	public function obtenerFilas()
	{
		return $this->ultima->fetch_array(MYSQLI_ASSOC);
	}
		
	//obtenemos el numero de filas o resultados de una consulta
	public function numeroFilas()
	{
		return $this->ultima->num_rows;
	}
	
	// tambien podemos saber el numero de filas afectadas con la consuklta ejecutada
	public function filasAfectados()
	{
		return $this->ultima->affected_rows;
	}
	
	//almacenamos en cache las consultas
	
	public function cacheQuerry( $consulta )
    {
    	if( !$resultado = $this->conexiones[$this->conexionActiva]->query( $consulta) )
    	{
		    trigger_error('Error ejecutando y cacheando la CONSULTA: '.$this->conexiones[$this->conexionActiva]->error, E_USER_ERROR);
		    return -1;
		}
		else
		{
			$this->queryCache[] = $resultado;
			return count($this->queryCache)-1;
		}
    }
	
	//obtenemos el numero de filas desde el cache
	public function numeroFilasDesdeCache( $cache_id )
    {
    	return $this->queryCache[$cache_id]->num_rows;	
    }
	
	//resultado de las filas desde cache
	public function resultadosDesdeCache( $cache_id )
    {
    	return $this->queryCache[$cache_id]->fetch_array(MYSQLI_ASSOC);
    }
	
	//cache de datos
	public function cacheDatos( $datos )
    {
    	$this->cacheDatos[] = $datos;
    	return count( $this->dataCache )-1;
    }
	
	//datos desde el cahe de datos
	public function datosDesdeCache( $cache_id )
    {
    	return $this->cacheDatos[$cache_id];
    }
	

	//averiguamos el id de la ultima inservion
	 public function ultimoInsertID()
    {
	    return $this->conexiones[ $this->conexionActiva]->insert_id;
    }
	
	//cerramos las conecciones establecidas
	public function __deconstruct()
	{
		foreach($this->conexiones as $conexion)
		{
			$conexion->close();
		}
	}
		
	//cerramos la conexiones activa
	public function cerrarConexionActiva()
	{
		$this->conexiones[$this->conexionActiva]->close();
    }
    
}