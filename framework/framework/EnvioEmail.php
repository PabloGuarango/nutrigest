<?php

/**
 * clase para el envio de email
 * @definimos los parametros necesarios para enviar ls mensajes
 */
	 

class EnvioEmail {


	private $mensaje;
	private $cabeceras;
	private $para;
	private $de;
	private $ver;
	private $tipo;
	private $error;
	private $asunto;
	private $nombreEnvia;
	private $metodo;
	
	
     public function __construct( Registro $registro ) 
    {
		$this->registro = $registro;
    	$this->empezarLimpio();
    }
    
    public function empezarLimpio()
	{
		// no detro del constructor poque este objeto s epuede reutilizar en cada nuevo email
		$this->ver = false;
		$this->error = 'El mensaje no pudo ser enviado por: ';
		$this->mensaje = '';
	}
	
	/**
	 * definimos para quien es
	 * @param String the recipient
	 * @return bool
	 */
	public function paraQuien( $para )
	{
		if(eregi("\r",(urldecode($para))) || eregi("\n",(urldecode($para))))
		{
				
			// error - inyeccion en las cabeceras
							
			$this->ver();
			$this->error .= ' El email del receptor contiene inyeccion en las cabeceras, posiblemente causada por SPAM. ';
			return false;
			
				
		}
		elseif( ! eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $para) )
		{
			// mal - error de email incorrecto
				
			$this->ver();
			$this->error .= ' La direccion email a la que se va a enviar el correo es incorrecta. ';
			return false;
				
		}
		else
		{
			//bien - procedemos a definir el destinatario!
			$this->para = $para;
			return true;
			
		}
		
	}
	
	/**
	 * Crear mensaje de correo electrónico de texto (opuesto de plantilla)
	 * @param String el mensaje
	 * @return void
	 */
	public function crearMensaje( $mensaje )
	{
		$this->mensaje .= $mensaje;
	}
	
	/**
	 * definimos el correo del que envia,primero la cabeceras definidas despues las agramos al header
	 * @param String direccion email (si no esta en uso, tomamos el email desde un array
	 * @return bool
	 */
	public function quienEnvia( $email )
	{
		if( $email == '' )
		{
			// si no hay email del que envia usamos desde el registro que tenemos
			
			$this->cabeceras = 'De: '.$this->registro->obtenerConfig('emailAdmin');
			$this->de = $this->registro->obtenerConfig('emailAdmin');
			return true;
		}
		else
		{
			if( strpos( ( urldecode( $email ) ), "\r" ) === true || strpos( ( urldecode( $email ) ), "\n" ) === true )
			{
				// mal - inyeccion en las cabeceras
				$this->ver();
				$this->error .= ' El email contiene inyeccion en las cabeceras, posiblemente causada por SPAM. ';
				return false;
				
			}
			elseif( ! preg_match( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})^", $email ) )
			{
				// mal - email incorrecto
				$this->ver();
				$this->error .= ' La direccion email no es correcta/valida. ';
				return false;
			}
			else
			{
				//bien - definimos los parametros
				$this->cabeceras = 'De: '.$email;
				$this->de = $email;
				return true;
			}

		}
	}
	
	public function quienEnviaSinReglas( $email )
	{
		$this->cabeceras = 'De: ' . $email;
	}
	
	/**
	 * agregamso las filas de cabeceras en la cabecera del mensaje, NOTA primero debemos definir quien envia
	 * @param String la informacion a agregar
	 * @return void
	 */
	public function agregarCabecera( $paraAgregar )
	{
		$this->cabeceras .= "\r\n" .	$paraAgregar;
	}
	
	/**
	 * Locks the email to prevent sending
	 * @return void
	 */
	public function ver()
	{
		$this->ver = true;
	}
	
	/**
	 * si tenemos definidas plantillas de los emails a enviar las enviamos desde la carpeta en la que tenemos las plantillas
	 * @return mensaje = contenido
	 */
	 
	public function crearDesdePlantilla()
    {
	    $partes = func_get_args();
	    $contenido = "";
	    foreach( $partes as $parte )
	    {
		    
		    if( strpos( $parte, DIRECTORIO_PRINCIPAL.'plantillasemail/' ) === false )
		    {
			    $parte = DIRECTORIO_PRINCIPAL.'plantillasemail/' . $parte.'.tpl.php';
		    }
		    if( file_exists( $parte ) == true )
		    {
			    $contenido .= file_get_contents( $parte );
		    }
		    
	    }
	    $this->mensaje =  $contenido;
    }
    
    public function reemplazarEtiquetas( $etiquetas )
    {
	    // contamos las etiquestas si existen
		
	    if( sizeof($etiquetas) > 0 )
	    {
	    	foreach( $etiquetas as $etiq => $dato )
		    {
			    // si las etiquetas estan en un array , necesitamos hacer mas que solo buscar y reemplazar!
			    if( ! is_array( $dato ) )
			    {
			    	// reemplazamos el contenido	    	
			    	$nuevoContenido = str_replace( '{' . $etiq. '}', $dato, $this->mensaje );
					
			    	// actualizamos el contenido de la pagina
			    	$this->mensaje = $nuevoContenido;
		    	}
		    }
	    }
	    
    }
    
    public function definirMetodo( $metodo )
	{
		$this->metodo = $metodo;
	}
	
	public function definirAsunto( $asunto )
	{
		$this->asunto = $asunto;
	}
	
	/** 
	 * enviar el email
	 * @return void
	 */
	public function enviar()
	{
		switch( $this->metodo)
		{
			case 'enviarEmail':
				return $this->enviarConSendmail();
				break;
			case 'smtp':
				return $this->enviarConSmtp();
				break;
			default:
				return $this->enviarConSendmail();
				
		}
	}
	
	/**
	 * enviar email usando SENDMAIL de PHP
	 * @return void
	 */
	public function enviarConSendmail()
	{
		if($this->ver == true)
		{
			return false;
		}
		else
		{
			if( ! @mail($this->para, $this->asunto, $this->mensaje, $this->cabeceras) )
			{
				$this->error .= ' problemas enviando con la funcion email de PHP.';
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	public function nombreEnvia( $nombre )
	{
		$this->nombreEnvia = $nombre;
	}
	
	public function enviarConSMTP()
	{
  		//todavia toca definir todos los parametros que se utilizaran para este metodo mientras tanto utilizaremos una funcion descargada jejejje
	}
	
	

    
    
}
?>