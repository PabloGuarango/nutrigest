<?php

/*
/*clase para el meanejo de usuarios
*/


class Usuario{
	
	
	//variables para el manejo de los datos de usuarios
	
	private $id;
	private $email;
	private $activo = 0;
	private $codigo_reset ;
	private $baneado;
	private $valido = false;
	
	
	// funcion constructor de registro
	public function __construct (Registro $registro, $id=0, $email = '', $password = ''){
		
		$this->registro = $registro;
		
		if( $id=0 && $email != '' && $password != ''){
			
			$this->email = $this->registro->obtenerObjeto('db')->limpiarDatos($email);
			$pass = $password;
			
			$consulta = "SELECT * FROM usuario WHERE email='{$email}' AND pass='{$pass}' AND borrado=0";
			
			$this->registro->obtenerObjeto('db')->ejecutarQuerry($consulta);
			
			if( $this->registro->obtenerObjeto('db')->numeroFilas() == 1)
			{
			$datos = $this->registro->obtenerObjeto('db')->obtenerFilas();
			
			
			$this->id = $datos['id'];
			$this->email = $datos['email'];
			$this->activo = $datos['activo'];
			$this->codigo_reset = $datos['codigo_reset_password'];
			$this->baneado = $datos['baneado'];
			$this->valido = true;
			//echo $datos;
			}
		}
		elseif ($id > 0)
		{
			$id = intval($id);
			
			$consulta = "SELECT * FROM usuario WHERE id='{$id}' AND borrado=0";
			$this->registro->obtenerObjeto('db')->ejecutarQuerry($consulta);
			
			if( $this->registro->obtenerObjeto('db')->numeroFilas() == 1)
			{
			$datos = $this->registro->obtenerObjeto('db')->obtenerFilas();
			
			
			$this->id = $datos['id'];
			$this->email = $datos['email'];
			$this->activo = $datos['activo'];
			$this->codigo_reset = $datos['codigo_reset_password'];
			$this->baneado = $datos['baneado'];
			$this->valido = true;
			
			}
		}
	}
	
	
	//funciones adicionales
	
	public function obtenerUserID()
	{
		return $this->id;
	}
	
	public function obtenerNombreUsuario()
	{
		return $this->email;
	}
	
	public function resetPassword( $password )
	{
		
		//toca completar esta fucnion eso se hara mas adelante cuando tenga mas tiempo	
	}

	
	public function obtenerEmail()
	{
		return $this->email;
	}
	
	public function esActivo()
	{
		return ( $this->activo == 1 ) ? true : false;
	}
	
	public function estaBaneado()
	{
		return ( $this->baneado == 1 ) ? true : false;
	}
	
	public function esValido()
	{
		return $this->valido;
	}
	
}