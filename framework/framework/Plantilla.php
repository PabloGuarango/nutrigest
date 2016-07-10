<?php

class Plantilla{

	private $pagina;
	
	//manejamos la estructura de nuestra pagina desde un registro
	public function __construct(Registro $registro)
	{
		$this->registro = $registro;
		require_once (DIRECTORIO_PRINCIPAL . BARRA . FRAM . BARRA . FRAMEWORK . BARRA .'Pagina.php');
		$this->pagina = new Pagina($this->registro);
		}
		
	//aniadir partes a nuestra plantilla
	public function agregarPartesPlantilla($etiqueta,$parte,$datos = array())
	{
		if(strpos($parte, 'vista/') === false)
		{
			$parte = 'vista/'.$this->registro->obtenerConfig('vista').'/plantilla/'.$parte.'.tpl.php';
		}
		$this->pagina->agregarPartesPlantilla($etiqueta,$parte,$datos);
	}
			
	//ahora vamos a reemplazar las partes con los datos y etiquetas que tenemos en nuestra plantilla
	public function reemplazarPartes()
	{
		$partes = $this->pagina->obtenerPartes();
		foreach($partes as $etiqueta => $plantilla)
		{
			$contenidoPlantilla = file_get_contents($plantilla['plantilla']);
			$etiquetas = array_keys($plantilla['reemplazo']);
			$nuevasEtiquetas = array();
			foreach ($etiquetas as $etiq)
			{
				$nuevasEtiquetas[] = '{'.$etiq.'}';				
			}
			
			$valores = array_values($plantilla['reemplazo']);
			$contenidoPlantilla = str_replace($nuevasEtiquetas, $valores, $contenidoPlantilla);
			$nuevoContenido = str_replace('{'.$etiqueta.'}',$contenidoPlantilla,$this->pagina->obtenerContenido());
			$this->pagina->fijarContenido($nuevoContenido);			
		}
	}
		
	//remplazaremos etiquetas que pueden ser definidas de la siguiente manera {header} o {nombredeusuario}
	public function reemplazarEtiquetas($pp = false)
	{
		if($pp == false)
		{
			$etiquetas = $this->pagina->obtenerEtiquetas();
		}
		else
		{
			$etiquetas = $this->pagina->obtenerPPEtiquetas();
		}
		//ahora con todo
		foreach($etiquetas as $etiqueta => $datos)
		{
			//si las etiquetas estan en un array necesitamos mas que buscar y reemplazar
			if ( is_array($datos))
			{
				if($datos[0] == 'SQL')
				{
					//si los datos estan en cahe de la consulta
					$this->reemplazarDBEtiquetas($etiqueta,$datos[1]);
					}
					elseif($datos[0] == 'DATA')
					{
					//sil los datos estan cacheados
					$this->reemplazarDatosEtiquetas($etiqueta,$datos[1]);
					}
			}
			else
			{
				//reemplazar el contenido
				$nuevoContenido = str_replace('{'.$etiqueta.'}',$datos,$this->pagina->obtenerContenido());
				//actualizamos el contenido de las paginas
				$this->pagina->fijarContenido($nuevoContenido);
			}
		}
	}
	
	//reemplazar etiquetas con datos de una base de datos
	public function reemplazarDBEtiquetas( $etiqueta, $cacheId)
	{
		$bloque = '';
		$bloqueViejo = $this->pagina->obtenerBloque($etiqueta);
		$apd = $this->pagina->obtenerPasoAdicionalDatos();
		$apdClaves = array_keys($apd);
		//el codigo es itinerante a traves de los resultados del cache de la base de datos y despue slo procesa
		while($etiquetas = $this->registro->obtenerObjeto('db')->resultadosDesdeCache($cacheId))
		{
			$bloqueNuevo = $bloqueViejo;
			//ahora vemos si tenemos etiquetas APD (adicional paso de datos)
			if(in_array($etiqueta,$apdClaves))
			{
				//si existen 
				foreach($etiquetas as $nEtiq => $dato)
				{
					$bloqueNuevo = str_replace("{".$nEtiq."}",$dato,$bloqueNuevo);
					//si en la etiqueta existe al menos una con datos extras que hago
					if(array_key_exists($nEtiq,$apd[$etiqueta]))
					{
						// si existen
						$extraEtiq = $apd[$etiqueta][$nEtiq];
						//ahora vemos si los datos son iguales a la condicion
						if($dato == $extraEtiq['condicion'])
						{
							//si existe
							$bloqueNuevo = str_replace("{".$extraEtiq[$etiqueta]."}",$extraEtiq['dato'],$bloqueNuevo);
						}
						else
						{
							//removemos las etiquetas extra que no usemos
							$bloqueNuevo = str_replace("{".$extraEtiq[$etiqueta]."}",'',$bloqueNuevo);
						}
					}
				}
			}
			else
			{
				//creamos un nuevo bloque con el contenido reemplazado
				foreach($etiquetas as $nEtiq => $dato)
				{
					$bloqueNuevo = str_replace("{".$nEtiq."}",$dato,$bloqueNuevo);
				}
			}
			//cada iteracion es aniadida a la variable de la cache de la base de datos
			$bloque .= $bloqueNuevo;
			}
		$contenidoPagina = $this->pagina->obtenerContenido();
		// removemos el separador de la plantilla, limpiamos HTML
		$nuevoContenido = str_replace('<!-- START '.$etiqueta.' -->'.$bloqueViejo.'<!-- END '.$etiqueta.' -->', $bloque,$contenidoPagina);
		//actualizamos el contenidod de la pagina
		$this->pagina->fijarContenido($nuevoContenido);		
		}
	
	//reemplazando datos de la cache pero no de bases de datos
	public function reemplazarDatosEtiquetas($etiqueta,$cacheId)
	{
		$bloqueViejo = $this->pagina->obtenerBloque( $etiqueta );
		$bloque = '';
		$etiquetas = $this->registro->obtenerObjeto('db')->datosDesdeCache( $cacheId );
		
		foreach( $etiquetas as $clave => $datoEtiqueta )
		{
			$bloqueNuevo = $bloqueViejo;
			foreach ($datoEtiqueta as $etiqA => $dato) 
	       	{
	        	$bloqueNuevo = str_replace("{" . $etiqA . "}", $dato, $bloqueNuevo); 
	        }
	        $bloque .= $bloqueNuevo;
		}


		$contenidoPagina = $this->pagina->obtenerContenido();
		$nuevoContenido = str_replace( '<!-- START '.$etiqueta.' -->'.$bloqueViejo.'<!-- END '.$etiqueta.' -->', $bloque, $contenidoPagina);
		$this->pagina->fijarContenido( $nuevoContenido );
    }
	
	//ahora podemos convertir el array de datos en algunas etiquetas
	public function datosAEtiquetas($dato, $prefijo)
	{
		foreach($dato as $clave => $contenido)
		{
			$this->pagina->agregarEtiqueta($prefijo.$clave, $contenido);
		}
	}
	
	//como el titulo de la pagina es una variable dentro del objeto debemos extraerla y uego reemplazarla
	public function pasarTitulo()
	{
		$nuevoContenido = str_replace('<title>','<title>'.$this->pagina->obtenerTitulo(),$this->pagina->obtenerContenido());
		$this->pagina->fijarContenido($nuevoContenido);
	}
	
	//obtenemos la pagina
	public function obtenerPagina()
	{
		return $this->pagina;
	}
	
	//ahora vamos a construir desde la Plantilla
	public function crearDesdePlantilla()
	{
		$partes = func_get_args();
		$contenido = "";
		foreach($partes as $parte)
		{
			if(strpos($parte, 'vista/') === false)
			{
				$parte = 'vista/'.$this->registro->obtenerConfig('vista').'/plantilla/'.$parte.'.tpl.php';
			}
			if(file_exists($parte) == true )
			{
				$contenido .= file_get_contents($parte);
			}
		}
		$this->pagina->fijarContenido($contenido);
		
	}
		
	
	//ahora pasmos todo a la salida
	
	public function pasarSalida()
    {
			$this->reemplazarPartes();
			$this->reemplazarEtiquetas(false);
			$this->reemplazarPartes();
			$this->reemplazarEtiquetas(true);
			$this->pasarTitulo();
    }
}