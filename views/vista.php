<?php 
require_once(dirname(__FILE__)."/../controller/controller.php");
require_once(dirname(__FILE__)."/../Twig/Autoloader.php");
	
class vista extends controller {
	protected $visitas;
	protected $twig;
	protected $siteController;

	public function __construct() {
		Twig_Autoloader::register();
		$this->siteController = new controller();
		$filter = new Twig_SimpleFilter('cortartexto', array($this->siteController, 'cortartexto'));	
		$loader = new Twig_Loader_Filesystem(dirname(__FILE__)."/../templates");
		$this->twig = new Twig_Environment($loader);
		$this->twig->addFilter($filter);		

		$this->visitas = $this->siteController->contador();
		date_default_timezone_set('America/Caracas');
		setlocale(LC_ALL, "es-ES", "es_ES", "es_ES.utf-8", "Spanish_Venezuela.1252"); 
	}

	public function session()
	{
		if (empty($_SESSION))
			session_start();

		if (empty($_SESSION['usuario'])){
			header('location: /concejo/404.php');
			die;
		}

		if ( !empty($_GET['salir']) ) {
			session_unset();
			session_destroy();
			header('location: /concejo/index.php');
			die;
		}
	}

	public function crearHeader()
	{
		echo $this->twig->render('header.twig.html', array('fecha' => strftime("%A, %d de %B del %Y")));
	}

	public function votacion()
	{
		$fila = 0;
		$lista = 0;
		$total= 0;

		$fila = $this->siteController->getData("encuesta", null, 1);
		$lista = $this->siteController->getData("encuestaOpciones", "ASC");

		$opc = $_GET['opc'];
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($lista)) {
			foreach ($lista as $key) {
				$total = ($total + $key['valor']);
			}
		}

		echo $this->twig->render('votacion.twig.html', array('SERVER' => $submitUrl,
												   'opc' => $opc, 
												   'total' => $total, 
												   'fila' => $fila,
												   'lista' => $lista
												   ));
	}

	public function crearPanel()
	{
		$currentPage = $_SERVER['REQUEST_URI'];
		$url = $_SERVER['HTTP_HOST'];
		$usuario = "";
		$fila = 0;
		
		if (!empty($_SESSION['usuario']))
			$usuario = $_SESSION['usuario'];

		$fila = $this->siteController->getData("encuesta", null, 1);
		
		echo $this->twig->render('panel_derecho.twig.html', array('currentPage' => $currentPage,
												   'visitas' => $this->visitas, 
												   'url' => $url, 
												   'usuario' => $usuario,
												   'titulo' => $fila['titulo'],
												   'fecha' => $fila['fecha']
												   ));
	}

	public function crearFooter()
	{
		echo $this->twig->render('footer.twig.html');
	}

	public function vision()
	{
		echo $this->twig->render('vision.twig.html');
	}
	
	public function comision()
	{
		echo $this->twig->render('comisiones.twig.html');
	}
	
	public function himno()
	{
		echo $this->twig->render('himno.twig.html');
	}
	
	public function historia()
	{
		echo $this->twig->render('historia.twig.html');
	}

	public function calendario()
	{
		//Check what month is now.
		$month = strftime("%B");

		$lista = $this->siteController->getData("calendario", null, null, $month);

		echo $this->twig->render('calendario.twig.html', array('lista' => $lista,
																'mes' => $month ));
	}

	public function login()
	{
		$php = 'ingresar.php';
		$error = "";
		if (!empty($_GET['error']))
			$error = $_GET['error'];

		echo $this->twig->render('login.twig.html', array('php' => $php,
														  'error' => $error
														  ));
	}

	public function contacto($name, $email, $message)
	{
		$error = "";
		$fecha = strftime("%A %d de %B del %Y");
		if (empty($name) || empty($email) || empty($message))
			$error = "Error enviando mensaje, llene cada uno de los campos";

		if (empty($error))
			$this->siteController->addData("contacto", $name, $email, $message, $fecha);

		echo $this->twig->render('contacto.twig.html', array('error' => $error));
	}

	public function index()
	{
		$amount = 8; // Cantidad de elementos a mostrar.
		if (!isset($_GET['pag'])) {$pagActual=1;} else {$pagActual=$_GET['pag'];}
		
		$pagAnterior = $pagActual-1;
		$pagSiguiente = $pagActual+1;
		
		$lista =$this->siteController->getContent("articulos", (($pagActual-1)*$amount), $amount);
		$filas = $this->siteController->getAmount("articulos");
		$paginas = ceil($filas/$amount);
		
		echo $this->twig->render('listado_articulos.twig.html', array('lista' => $lista, 
																	  'paginas' => $paginas, 
																	  'pagAnterior' => $pagAnterior, 
																	  'pagSiguiente' => $pagSiguiente
																	  ));
	}

	public function mostrar()
	{
		$id = $_GET['mostrar'];
		$fila = $this->siteController->getData("articulos", null, $id);
		
		echo $this->twig->render('articulo.twig.html', array('titulo' => $fila['titulo'], 
															 'fecha' => $fila['fecha'], 
															 'resumen' => $fila['resumen'], 
															 'imagen' => $fila['image'], 
															 'articulo' => $fila['articulo']));
	}
	
	public function biografias()
	{
		$amount = 9; // Cantidad de elementos a mostrar.
		$getID = "";
		$fila = "";

		if (!isset($_GET['pag'])) {$pagActual=1;} else {$pagActual=$_GET['pag'];}
		
		$pagAnterior = $pagActual-1;
		$pagSiguiente = $pagActual+1;
		
		$lista = $this->siteController->getContent("biografias", (($pagActual-1)*$amount), $amount);
		$filas = $this->siteController->getAmount("biografias");
		$paginas = ceil($filas/$amount);
		
		// User want to see only ONE Biography
		if (!empty($_GET['biografias_id'])) {
			$getID = $_GET['biografias_id'];
			$fila = $this->siteController->getData("biografias", null, $getID);
		}
				
		echo $this->twig->render('biografias.twig.html', array('lista' => $lista, 
															   'getID' => $getID, 
															   'fila' => $fila, 
															   'paginas' => $paginas, 
															   'pagAnterior' => $pagAnterior, 
															   'pagSiguiente' => $pagSiguiente
															   ));
	}
	
	public function directiva()
	{
		$lista = $this->siteController->getData("junta", "ASC");
		
		echo $this->twig->render('junta_directiva.twig.html', array('lista' => $lista));
	}

	public function biblioteca()
	{
		$lista = $this->siteController->getData("biblioteca", "DESC");
		
		echo $this->twig->render('biblioteca.twig.html', array('lista' => $lista));
	}

	public function fotos()
	{
		$amount = 9; // Cantidad de elementos a mostrar.

		if (!isset($_GET['pag'])) {$pagActual=1;} else {$pagActual=$_GET['pag'];}

		$pagAnterior = $pagActual-1;
		$pagSiguiente = $pagActual+1;

		$lista = $this->siteController->getContent("galeria_imagenes", (($pagActual-1)*$amount), $amount);
		$filas = $this->siteController->getAmount("galeria_imagenes");
		$paginas = ceil($filas/$amount);

		echo $this->twig->render('imagenes.twig.html', array('lista' => $lista, 
															 'paginas' => $paginas, 
															 'pagAnterior' => $pagAnterior, 
															 'pagSiguiente' => $pagSiguiente
															   ));
			
	}

	public function vid()
	{
		echo $this->twig->render('video.twig.html');
		
	}

	public function map()
	{
		echo $this->twig->render('mapa.twig.html');

	}
	
	public function admArticulos()
	{
		// Chequea la sesion
		$this->session();
		$error = "";
		$add = 0;
		$dele = 0;
		$edit = 0;
		$id = "";
		$fila = "";
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;
		if (!empty($_POST['edit']))
			$edit = 1;
		if (!empty($_POST['error']))
			$error = $_POST['error'];

		if (!empty($_GET['id'])) {
			$id = $_GET['id'];
			$fila = $this->siteController->getData("articulos", null, $id);
		}

		$lista = $this->siteController->getData("articulos", "DESC");
			
		echo $this->twig->render('admArticulos.twig.html', array('lista' => $lista, 
														  'fila' => $fila,
														  'error' => $error, 
														  'id' => $id,
														  'SERVER' => $submitUrl,
														  'add' => $add, 
														  'edit' => $edit, 
														  'dele' => $dele
														  ));
	}
	
	public function admBiografias()
	{
		// Chequea la sesion
		$this->session();
		$error = "";
		$add = 0;
		$dele = 0;
		$edit = 0;
		$fila = "";
		$lista = "";
		$id = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;
		if (!empty($_POST['edit']))
			$edit = 1;
				
		if (!empty($_GET['id'])) {
			$id = $_GET['id'];
			$fila = $this->siteController->getData("biografias", null, $id);
		}

		$lista = $this->siteController->getData("biografias", "DESC");
			
		echo $this->twig->render('admBiografias.twig.html', array('lista' => $lista, 
														  'fila' => $fila,
														  'error' => $error, 
														  'id' => $id,
														  'SERVER' => $submitUrl,
														  'add' => $add, 
														  'edit' => $edit, 
														  'dele' => $dele
														  ));
	}
	
	public function admJunta()
	{
		// Chequea la sesion
		$this->session();
		$error = "";
		$edit = 0;
		$id = "";
		$fila = "";
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['edit']))
			$edit = 1;
				
		if (!empty($_GET['id'])) {
			$id = $_GET['id'];
			$fila = $this->siteController->getData("junta", null, $id);
		}

		$lista = $this->siteController->getData("junta", "ASC");
			
		echo $this->twig->render('admJunta.twig.html', array('lista' => $lista, 
														  'fila' => $fila,
														  'error' => $error, 
														  'id' => $id,
														  'SERVER' => $submitUrl,
														  'edit' => $edit
														  ));
	}

	public function admEncuestas()
	{
		// Chequea la sesion
		$this->session();

		$error = "";
		$edit = 0;
		$add = 0;
		$dele = 0;
		$fila = "";
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;
		if (!empty($_POST['edit']))
			$edit = 1;

		$fila = $this->siteController->getData("encuesta", null, 1);

		$lista = $this->siteController->getData("encuestaOpciones", "ASC");
			
		echo $this->twig->render('admEncuestas.twig.html', array('lista' => $lista, 
														  'fila' => $fila,
														  'error' => $error, 
														  'add' => $add,
														  'dele' => $dele,
														  'SERVER' => $submitUrl,
														  'edit' => $edit
														  ));
	}
	
	public function admDocs()
	{
		// Chequea la sesion
		$this->session();
		$error = "";
		$add = 0;
		$dele = 0;
		$edit = 0;
		$id = "";
		$fila = "";
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;
		if (!empty($_POST['edit']))
			$edit = 1;
				
		if (!empty($_GET['id'])) {
			$id = $_GET['id'];
			$fila = $this->siteController->getData("biblioteca", null, $id);
		}

		$lista = $this->siteController->getData("biblioteca", "DESC");
			
		echo $this->twig->render('admDocs.twig.html', array('lista' => $lista, 
														  'fila' => $fila,
														  'error' => $error, 
														  'id' => $id,
														  'SERVER' => $submitUrl,
														  'add' => $add, 
														  'edit' => $edit, 
														  'dele' => $dele
														  ));
	}
	
	public function admFotos()
	{
		// Chequea la sesion
		$this->session();
		$error = "";
		$amount = 6; // Cantidad de elementos a mostrar.
		$add = 0;
		$dele = 0;
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;

		if (!isset($_GET['pag'])) {$pagActual=1;} else {$pagActual=$_GET['pag'];}
		$pagAnterior = $pagActual-1;
		$pagSiguiente = $pagActual+1;

		$lista = $this->siteController->getContent("galeria_imagenes", (($pagActual-1)*$amount), $amount);
		$filas = $this->siteController->getAmount("galeria_imagenes");
		$paginas = ceil($filas/$amount);
			
		echo $this->twig->render('admFotos.twig.html', array('lista' => $lista, 
														  'SERVER' => $submitUrl,
														  'add' => $add, 
														  'dele' => $dele,
														  'pagAnterior' => $pagAnterior,
														  'pagSiguiente' => $pagSiguiente, 
														  'paginas' => $paginas
														  ));
	}
	
	public function admVideos()
	{
		// Chequea la sesion
		$this->session();
		$error = "";
		$amount = 3; // Cantidad de elementos a mostrar.
		$add = 0;
		$dele = 0;
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;

		if (!isset($_GET['pag'])) {$pagActual=1;} else {$pagActual=$_GET['pag'];}
		$pagAnterior = $pagActual-1;
		$pagSiguiente = $pagActual+1;

		$lista = $this->siteController->getContent("galeria_videos", (($pagActual-1)*$amount), $amount);
		$filas = $this->siteController->getAmount("galeria_videos");
		$paginas = ceil($filas/$amount);
			
		echo $this->twig->render('admVideo.twig.html', array('lista' => $lista, 
														  'SERVER' => $submitUrl,
														  'add' => $add, 
														  'dele' => $dele,
														  'pagAnterior' => $pagAnterior,
														  'pagSiguiente' => $pagSiguiente, 
														  'paginas' => $paginas
														  ));
	}

	public function admSesiones()
	{
		$this->session();
		$error = "";
		$add = "";
		$dele = "";
		$lista = "";
		$submitUrl = $_SERVER["REQUEST_URI"];

		if (!empty($_POST['add']))
			$add = 1;
		if (!empty($_POST['dele']))
			$dele = 1;

		$lista = $this->siteController->getData("calendario", "ASC");
			
		echo $this->twig->render('admSesiones.twig.html', array('lista' => $lista, 
														  'error' => $error, 
														  'SERVER' => $submitUrl,
														  'add' => $add, 
														  'dele' => $dele
														  ));
	}

	public function admContacto()
	{
		$this->session();
			
		echo $this->twig->render('admContacto.twig.html');
	}

	public function admHome()
	{
		echo $this->twig->render('admHome.twig.html');
	}
	
}
?>
