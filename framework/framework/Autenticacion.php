<?php


class Autenticacion{
	
	//variables
	private $usuario;
	private $estaLogueado = false;
	private $procesable = false;
    private $registro;
	
	//constructor de los registros
	
	public function __construct ( Registro $registro )
	{		
		 $this->registro = $registro;
	}
	
	//checamos si esta guardados los datos de la sesion 
	public function checarParaAutenticar(){
		
		//removemos la etiqueta de error desde la pagina
		$this->registro->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta('error','');
		
		
		//si existe datos en variales de la sesion llamamos a la funcion autenticarSesion
		
		if( isset($_SESSION['id_autorizacion_sesion']) && intval($_SESSION['id_autorizacion_sesion']) > 0)
		{
			
			$this->autenticarSesion( intval($_SESSION['id_autorizacion_sesion']));
			
			if($this->estaLogueado == true)
			{
				
				$this->registro->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta('error','');
			}
				
				//si el usuario no esta logueado pero tenemos datos en la sesion		
			else
			{
				$this->registro->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta('error','<p>Error: Su nombre de Usuario o Password no son correctos, por favor intente nuevamente.<p>');
				
			}
		}
			
    	elseif( isset(  $_POST['emailInicio'] ) &&  $_POST['emailInicio'] != '' && isset( $_POST['passwordInicio'] ) && $_POST['passwordInicio'] != '')
    	{
    		$this->pasarAutenticacion( $_POST['emailInicio'] , $_POST['passwordInicio'], true );
			
    		if( $this->estaLogueado == true )
	    	{
	    		$this->registro->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta('error', 'Ya esta logueado aqui');
	    	
	    	}
			
			else
	    	{
	    		$this->registro->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta('error', '<p>Error: Direccion Email o Password no son correctos, por favor intente nuevamente 2.</p>');	
	    	}
    	}
		
		//si no se ingresaron los datos de usuario y password defnimos el error
		
    	elseif ( isset( $_POST['Ingresar']) )
    	{
    		$this->registro->obtenerObjeto('plantilla')->obtenerPagina()->agregarEtiqueta('error', '<p>Error: Debes ingresar tu direccion de Email y tu Password.3..</p>');	
    	}

    }
	
	//autenticamos el usuario con datos de sesion
	
	private function autenticarSesion( $uid )
    {
    	require_once(DIRECTORIO_PRINCIPAL.'registro/Usuario.php');
    	$this->usuario = new Usuario( $this->registro, intval( $_SESSION['id_autorizacion_sesion'] ), '', '' );
    	
    	if( $this->usuario->esValido() )
    	{
    		if( $this->usuario->esActivo() == false )
    		{
    			$this->estaLogueado = false;
    			$this->razonNoLogin = 'inactivo';
    		}
    		elseif ($this->usuario->estaBaneado() == true)
    		{
    			$this->estaLogueado = false;
				$this->razonNoLogin = 'baneado';
    		}
			else
			{
				$this->estaLogueado = true;
			}
    		
    	}
    	else
    	{
    		$this->estaLogueado = false;
    		$this->razonNoLogin = 'nousuario';
    	}
    	if( $this->estaLogueado == false )
    	{
    		$this->salir();
    	}
    	
    }
	
	// vamos a autenticar con los datos de usuario ingresados
	
	private function pasarAutenticacion ($u, $p, $sesion = true)
	{
		$this->procesable = true;
		require_once (DIRECTORIO_PRINCIPAL.'registro/Usuario.php');
		$this->usuario = new Usuario($this->registro, 0 , $u, $p);
		
		if ($this->usuario->esValido())
		{
			if ($this->usuario->esActivo() == false)
			{
				$this->estaLogueado = false;
				$this->razonNoLogin = 'inactivo';
			}
			elseif($this->usuario->estaBaneado() == true)
			{
				$this->estaLogueado = false;
				$this->razonNoLogin = 'baneado';
			}
			else
			{
				$this->estaLogueado = true;
				if ( $sesion = true)
				{
					$_SESSION['id_autorizacion_sesion'] = $this->usuario->obtenerUserID();
				}
			}
		}
		
		else
		{
			$this->estaLogueado = false;
			$this->razonNoLogin = 'credencialesInvalidas';
		}
	}



	
	
	//funciones adicionales para el correcto funcionamiento
	
	    function salir() 
		{
			$_SESSION['id_autorizacion_sesion'] = '';
			$this->estaLogueado = false;
			$this->usuario = null;
		}
	
	public function forzarLogin( $username, $password )
	{
		$this->pasarAutenticacion( $username, $password, true );
	}
    
    public function estaLogueado()
    {
	    return $this->estaLogueado;
    }
    
    public function esProcesable()
    {
    	return $this->procesable;
    }
    
    public function obtenerUsuario()
    {
    	return $this->usuario;
    }
		
}