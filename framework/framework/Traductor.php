<?php
class Traductor {

    private $idioma	= 'es';
	private $idiom 		= array();
	
	public function __construct(Registro $registro)
	{
		$this->registro = $registro;
	}
	
	public function definirIdioma( $idioma = 'es' )
	{
		$this->idioma = $idioma;	
	}
    private function encontrarPalabras($str) 
	{
        if (array_key_exists($str, $this->idiom[$this->idioma])) {
			echo $this->idiom[$this->idioma][$str];
			return;
        }
        echo $str;
    }
    
	private function cortarPalabras($str) 
	{
        return explode('=',trim($str));
    }
	
	public function __($str) {	
        if (!array_key_exists($this->idioma, $this->idiom)) 
		{
            if (file_exists($this->idioma.'.txt')) {
                $strings = array_map(array($this,'cortarPalabras'),file($this->idioma.'.txt'));
                foreach ($strings as $k => $v) {
					$this->idiom[$this->idioma][$v[0]] = $v[1];
                }
                return $this->encontrarPalabras($str);
            }
            else 
			{
                return $str;
            }
        }
        else 
		{
            return $this->encontrarPalabras($str);
        }
    }
}
?>