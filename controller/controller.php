<?php require_once(dirname(__FILE__)."/../model/model.php");

class controller {
	protected $con;

	public function __construct() {
		$this->con = new model(); // Obtengo objeto de conexion.
		date_default_timezone_set('America/Caracas');
		setlocale(LC_ALL, "es-ES", "es_ES", "es_ES.utf-8", "Spanish_Venezuela.1252"); 
	}

	public function cleanInput($text) {
		$text = trim($text);
		$text = stripslashes($text);
		$text = htmlspecialchars($text);
		return $text;
	}
	
	public function verifyAdmin($user, $pass) {
		if (empty($user) || empty($pass))
			$error = "Por favor introduzca su usuario y su contraseña";

		$sql = sprintf("SELECT `usuario`, `password` FROM `user` WHERE usuario = '%s'", $user);		
		$this->con->setQuery($sql);

		if (empty($error)) {
			$ret = $this->con->exec();

			if ($ret && $ret->num_rows > 0)
			{
				$row=$ret->fetch_array();
				$passdb = $row['password'];
			} else
				$error = "El nombre de usuario no coincide";

			if ($passdb == md5($pass))
			{
				$_SESSION['usuario'] = $user;
				$_SESSION['pass'] = md5($pass);
				header('location: index.php'); // Redirige al index.php
				die;
			} else
				$error = "Clave incorrecta";
		}
		return $error;
	}
	
// ------------------------------ CODIGO PARA CONTROLAR TABLA ARTICULOS ------------
	
	public function getData($tableName, $order) {
		$args = func_num_args();

		if ($args == 4)
			$sql = sprintf("SELECT * FROM %s WHERE mes = '%s' ORDER BY dia ASC", $tableName, func_get_arg(3));
		elseif ($args == 3)
			$sql = sprintf("SELECT * FROM %s WHERE id = %s", $tableName, func_get_arg(2));
		elseif ($args == 2)
			$sql = sprintf("SELECT * FROM %s ORDER BY id %s", $tableName, $order);
			// Order is DESC or ASC
		
		$this->con->setQuery($sql);
		$ret = $this->con->exec();

		if ($args == 3)
			$list = $ret->fetch_assoc();
		else {
			$list = array();
			while ($fila  = $ret->fetch_assoc())
			{
				array_push($list, $fila);
			}
		}
		$ret->free();		
		return $list;
	}

	public function getDataWhere($tableName, $where) {

		$sql = sprintf("SELECT * FROM %s WHERE %s = '%s'", $tableName, $where[0],
														   $where[1]);
		
		$this->con->setQuery($sql);
		$ret = $this->con->exec();

		$list = $ret->fetch_assoc();

		$ret->free();
		return $list;
	}

	public function delData($tableName, $id) {
		// Remove from filesystem too if have files (image|documento|video)
		$path = "";
		$fila = $this->getData($tableName, null, $id);

		if ($tableName == 'articulos' || $tableName == 'biografias' || $tableName == 'galeria_imagenes' || $tableName == 'junta') {
			$path = "../galeria/" . $fila['image'];
			$thumb_path = "../galeria/thumb_" . $fila['image'];
			if (file_exists($thumb_path)) unlink($thumb_path);
		}
		elseif ($tableName == 'biblioteca')
			$path = "../docs/" . $fila['documento'];
		elseif ($tableName == 'galeria_videos')
			$path = "../video/" . $fila['video'];

		if (file_exists($path)) unlink($path);

		$sql = sprintf("DELETE FROM %s WHERE id = %s", $tableName, $id);
		$this->con->setQuery($sql);
		$ret = $this->con->exec();
		return $ret;
	}
	
	public function addData($tableName, $arg0) {
		$args = func_num_args();

		if ($tableName != 'encuestaOpciones') {
			for ($i = 0; $i < $args; $i++) {
				if (empty(func_get_arg($i)))
					$error = "Por favor rellene cada uno de los campos.";
			}
		}

		if (empty($error)) {
			switch ($args) {
				case 4:
					$sql = sprintf("INSERT INTO %s VALUES (null, '%s', '%s', '%s')", 
									$tableName, $arg0, func_get_arg(2), 
									func_get_arg(3));
					break;
				case 5:
					$sql = sprintf("INSERT INTO %s VALUES (null, '%s', '%s', '%s', '%s')", 
									$tableName, $arg0, func_get_arg(2), 
									func_get_arg(3), func_get_arg(4));
					break;
				case 6:
					$sql = sprintf("INSERT INTO %s VALUES (null, '%s', '%s', '%s', '%s', '%s')", 
									$tableName, $arg0, func_get_arg(2), 
									func_get_arg(3), func_get_arg(4), 
									func_get_arg(5));
					break;
				case 7:
					$sql = sprintf("INSERT INTO %s VALUES (null, '%s', '%s', '%s', '%s', '%s', '%s')", 
									$tableName, $arg0, func_get_arg(2), 
									func_get_arg(3), func_get_arg(4), 
									func_get_arg(5), func_get_arg(6));
					break;
			}
			$this->con->setQuery($sql);
			$ret = $this->con->exec();
			return $ret;
		}
		return $error;
	}

	public function editData($tableName, $id, $fields, $arg0){
		// Check how many arguments are IN
		$args = func_num_args();

		for ($i = 0; $i < $args; $i++) {
			if (empty(func_get_arg($i)))
				$error = "Por favor rellene cada uno de los campos.";
		}
		
		if (empty($error))
		{
			// $fields = 'titulo, resumen, articulo, image' is OK
			$where = explode(",", $fields);

			// SWITCH the ARGS Value to update data on db dynamically
			switch ($args) {
				case 4:
					$set = sprintf("%s = '%s'", $where[0], $arg0);
					break;
				case 5:
					$set = sprintf("%s = '%s', %s = '%s'", 
									$where[0], $arg0, 
									$where[1], func_get_arg(4));
					break;
				case 6:
					$set = sprintf("%s = '%s', %s = '%s', %s = '%s'", 
									$where[0], $arg0, 
									$where[1], func_get_arg(4), 
									$where[2], func_get_arg(5));
					break;
				case 7:
					$set = sprintf("%s = '%s', %s = '%s', %s = '%s', %s = '%s'", 
									$where[0], $arg0, 
									$where[1], func_get_arg(4), 
									$where[2], func_get_arg(5), 
									$where[3], func_get_arg(6));
					break;
				case 8:
					$set = sprintf("%s = '%s', %s = '%s', %s = '%s', %s = '%s'
									, %s = '%s'", 
									$where[0], $arg0, 
									$where[1], func_get_arg(4), 
									$where[2], func_get_arg(5), 
									$where[3], func_get_arg(6),
									$where[4], func_get_arg(7));
					break;
			}
			$sql = sprintf("UPDATE %s SET %s WHERE id = %s", $tableName, $set, $id );
			$this->con->setQuery($sql);
			$ret = $this->con->exec();
			return $ret;
		}
		return $error;
	}

	public function contador() {
		$fila = $this->getData("contador", null, 1);
		$visitas = $fila['value'] + 1;
		$this->editData("contador", 1, "value", $visitas);

		return $visitas;
	}

	public function getContent($tableName, $startPos, $amount) {
		$sql = sprintf("SELECT * FROM %s ORDER BY id DESC LIMIT %s,%s", $tableName, $startPos, $amount);
		$this->con->setQuery($sql);
		$ret = $this->con->exec();
		$list = array();

		while ($fila  = $ret->fetch_assoc())
		{
			array_push($list, $fila);
		}

		$ret->free();		
		return $list;
		
	}

	public function getAmount($tableName) {

		$sql = sprintf("SELECT COUNT(*) AS total FROM %s", $tableName);
		$this->con->setQuery($sql);
		$ret = $this->con->exec();

		$fila  = $ret->fetch_assoc();
		$num = $fila['total'];

		$ret->free();
		return $num;
	}

	public function borrarDoc($filename) {
		$path = "../docs/" . $filename;
		if (file_exists($path)) unlink($path);
		
	}

	public function uploadfile()
	{
		$error = "";
		$types = "application/pdf";
		
		if ($_FILES['doc']['type'] == $types && $_FILES['doc']['size'] <= 20000 * 1024)
		{
			$this->con->setPath("docs/", $_FILES['doc']['name']);
			$res = $this->con->saveFile($_FILES['doc']['tmp_name']);
			if (!$res)
				$error = "Ocurrio un error al guardar el archivo.";
		} else
			return $error = "Formato de archivo no soportado.";
		
		return $error;
	}
	
	public function uploadfoto()
	{
		$error = "";
		$mediatypes = array("image/jpg", "image/jpeg", "image/gif", "image/png");
		
		if (in_array($_FILES['foto']['type'], $mediatypes) && $_FILES['foto']['size'] <= 5000 * 1024) {
			$this->con->setPath("galeria/", $_FILES['foto']['name']);
			$thumb_path = getcwd() . "/../galeria/" . "thumb_" . $_FILES['foto']['name'];
			$res = $this->con->saveFile($_FILES['foto']['tmp_name']);
			
			// Creamos el thumbnaill
			$img = $this->imageCreateFromAny($this->con->getPath());
			$thumb = imagecreatetruecolor(150,150);
			ImageCopyResized($thumb, $img,0,0,0,0,150, 150,ImageSX($img),ImageSY($img));	
			header("Content-Type: image/jpeg");
			ImageJPEG($thumb, $thumb_path,75);
			
			if (!$res)
				$error = "Ocurrio un error al guardar la foto.";
		} else
			$error = "Formato de imagen no soportado.";
		
		return $error;
	}

	public function uploadVideo()
	{
		$error = "";
		$mediatypes = array("video/mp4", "video/3gp", "video/webm");
		
		if (in_array($_FILES['video']['type'], $mediatypes) && $_FILES['video']['size'] <= 409600 * 1024) {
			$this->con->setPath("video/", $_FILES['video']['name']);
			$res = $this->con->saveFile($_FILES['video']['tmp_name']);
					
			if (!$res)
				$error = "Ocurrio un error al guardar el video.";
		} else
			$error = "Formato de video no soportado o el tamaño del video esta por encima de lo aceptado.";
		
		return $error;
	}
	
	public function cortartexto($texto)
	{
		// Verifica si la longitud del texto a cortar es menor al maximo ingresado de caracteres
		if (strlen($texto) <  1000)
			return $texto;
	
		// Caso contrario que la longitud sea mayor sigue adelante y ejecuta la func substr
		$textoCortado = substr($texto, 0, 1000);
		$ultimoEspacio = strripos($textoCortado, "\n");
	
		if ($ultimoEspacio !== false)
			$textoCortado = substr($textoCortado, 0, $ultimoEspacio);
		else
			$textoCortado .= "...";
	
		return $textoCortado;
	}
	
	public function imageCreateFromAny($filepath) {
		
		$type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
		$allowedTypes = array(
			1,  // [] gif
			2,  // [] jpg
			3,  // [] png
			6   // [] bmp
		);
		if (!in_array($type, $allowedTypes)) {
			return false;
		}
		switch ($type) {
			case 1 :
            $im = imageCreateFromGif($filepath);
			break;
			case 2 :
            $im = imageCreateFromJpeg($filepath);
			break;
			case 3 :
            $im = imageCreateFromPng($filepath);
			break;
			case 6 :
            $im = imageCreateFromwBmp($filepath);
			break;
		}   
    return $im; 
}

}?>
