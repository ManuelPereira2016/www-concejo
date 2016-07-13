<?php 

include ("config.php");

class model {
	protected $connect;
	protected $query = ""; // $valores sera un string ya formateado y preparado con los valores de la consulta.
	protected $path = "";
	
	public function __construct() {
		$this->connect = new mysqli(servidor, usuario, pass, bd);
		$this->connect->query("SET NAMES 'UTF8'");
		
		if ($this->connect != true ) {
			$error = "ERROR EN LA CONEXION A LA BASE DE DATOS";
			return $error;
		}
	}

	public function setQuery($sql)
	{
		$this->query = $sql;
	}

	public function setPath($dir, $uploadName)
	{
		$this->path = getcwd() . "/../" . $dir . $uploadName;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function saveFile($uploaded)
	{
		$error = "";
		$res = move_uploaded_file($uploaded, $this->path);
		return $res;
	}

	public function exec()
	{
		$ret = $this->connect->query($this->query);
		return $ret;
			
	}
}
?>
























	

	
