<?php

/*
/*clase objeto de la clase de plantilla
*/

class Pagina{
			
		//	neceisitamos algunas variables
		// el titulo de la pagina
		private $titulo = '';
		//las etiquetas en un array
		private $etiquetas = array();
		//tambien etiquetas pasadas despues de hacer un post de las msimas
		private $postPasoEtiquetas = array();
		//partes de la plantilla
		private $partes = array();
		//por supuesto el contenido de la pagina
		private $contenido = '';
		//paso adicional de datos APD
		private $apd = array();
		
		//creamos el objeto de la pagina
		
		public function __construct (Registro $registro)
		{
			$this->registro = $registro;
		}
		
		//necesitamos fijar el titulo de la pagina actual esto lo hacemos con los metodos set y get
		
		public function obtenerTitulo()
		{
			return $this->titulo;
		}
		
		public function fijarTitulo($titulo)
		{
			$this->titulo = $titulo;
		}
		
		//vamos a actualizar la variable contenido despues de anadir una nueva parte de la plantilla y reemplazarla posteriormente	
		public function fijarContenido($contenido)
		{
			$this->contenido = $contenido;
		}
			
		
		//af=gregar etiquetas a la plantilla y reemplazar con los valores y datos
		public function agregarEtiqueta($clave, $dato)
		{
			$this->etiquetas[$clave] = $dato;
		}
			
		//adicional vamos a retirar las etiquetas puestas por mucho tiempo es lgoica del codifo
		public function quitarEtiquetas($clave)
		{
			unset ($this->etiquetas[$clave]);
		}
		
		
		//vamos a obtener las etiquetas que necesitamos reemplazar
		
		public function obtenerEtiquetas()
		{
			return $this->etiquetas;
		}
			
		
		//adicional ponemos y obtenemos las etiquetas pasadas por el metodo post
		public function agregarPPEtiquetas($clave,$dato)
		{
			$this->postPasoEtiquetas[$clave] = $dato; 
		}
		
		public function obtenerPPEtiquetas()
		{
			return $this->postPasoEtiquetas;
		}
			
		//agregamos las partes de la plantilla pero todavia no el contenido de la pagina
		public function agregarPartesPlantilla($etiqueta,$parte, $reemplazos = array())
		{
			$this->partes[$etiqueta] = array('plantilla' => $parte,'reemplazo' => $reemplazos);
		}
			
		//agregamos paso adicional a los datos mediante APF
		public function agregarPasoAdicionalDatos($bloque,$etiqueta,$condicion,$extraEtiq,$dato)
		{
			$this->apd[$bloque] = array($etiqueta => array('condicion' => $condicion,'etiqueta' => $extraEtiq,'dato' => $dato));
		}
		
		
		//obtenemos las partes de la plantilla
		public function obtenerPartes()
		{
			return $this->partes;
		}
		//obtenemos los datos pasados adicionalmente
		public function obtenerPasoAdicionalDatos()
		{
			return $this->apd;
		}
			
		// ahora necesitamos acceso a un especificado blque definido
		public function obtenerBloque($etiqueta)
		{
			preg_match('#<!-- START '. $etiqueta . ' -->(.+?)<!-- END '. $etiqueta . ' -->#si', $this->contenido, $almacen);
			$almacen = str_replace('<!-- START '. $etiqueta . ' -->', "", $almacen[0]);
			$almacen = str_replace ('<!-- END '  . $etiqueta . ' -->', "", $almacen);
			return $almacen;
		}
			
		//obviamente nos hace falta obtener el contenido de la pagina
		public function obtenerContenido()
		{
			return $this->contenido;
		}
			
		//finalmente vamos a imprimr todo loq ue tenemos hasta ahorsa
		public function obtenerContenidoParaImprimir()
		{
			$this->contenido = preg_replace ('#{form_(.+?)}#si', '', $this->contenido);	
			$this->contenido = preg_replace ('#{nbd_(.+?)}#si', '', $this->contenido);	
			$this->contenido = str_replace('</body>', '<!-- Generado por Guarango Pablo e Imbaquingo Jonathan -->
	</body>', $this->contenido );
			return $this->contenido;
		}

}