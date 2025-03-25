<?php
class Modelo {
	
	static function obtenerAnuncios() {
		
		$anuncios = array(
			array(
				"date" => "02-12-2022",
				"header" => "Vivamus consequat feugiat",
				"content" => "Quisque luctus purus nec pretium rutrum. Lorem ipsum dolor sit amet.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio1.jpg")),
			),
			array(
				"date" => "10-12-2022",
				"header" => "Aenean non metus quam",
				"content" => "Curabitur auctor venenatis mi, sit amet vestibulum lacus fermentum in.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio2.jpg")),
			),
			array(
				"date" => "10-12-2022",
				"header" => "Phasellus tempor lacinia",
				"content" => "Duis id erat eget elit imperdiet tristique. Quisque luctus purus nec pretium.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio3.jpg")),
			),
			array(
				"date" => "03-01-2023",
				"header" => "Sed sit amet bibendum mi",
				"content" => "Aliquam erat volutpat. Aenean massa risus, eleifend sed posuere ac.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio4.jpg")),
			),
			array(
				"date" => "04-01-2023",
				"header" => "Aenean faucibus nec",
				"content" => "Mauris hendrerit eleifend lectus. Morbi in eleifend ex. In hac habitasse.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio5.jpg")),
			),
			array(
				"date" => "10-01-2023",
				"header" => "Sed quis tellus dolor",
				"content" => "Interdum et malesuada fames ac ante ipsum primis in faucibus.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio6.jpg")),
			),
			array(
				"date" => "12-01-2023",
				"header" => "Nulla elit nulla iaculis",
				"content" => "Quisque nec libero fringilla, vestibulum magna quis, scelerisque lectus.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio7.jpg")),
			),
			array(
				"date" => "13-01-2023",
				"header" => "Quisque fermentum sed",
				"content" => "Suspendisse potenti. Donec id finibus lacus, eget pellentesque diam.",
				"image" => base64_encode(file_get_contents("./anuncios/anuncio8.jpg")),
			)
		);
		
		return $anuncios;
	}
	
	static function seleccionarAnuncios() {
		
		$anuncios = Modelo::obtenerAnuncios();
		
		$seleccion = array();
		$indices = array();
		
		$items = 4;
		
		for ($x = 0; $x < $items; $x++) {
			
			do {
				$indice = mt_rand(0, count($anuncios) - 1);
			}
			while (in_array($indice, $indices));
			
			array_push($indices, $indice);
			array_push($seleccion, $anuncios[$indice]);
		}
			
		return $seleccion;
	}
}


class Controlador {
	
	function OnAction() {
		
		$peticion = &$_GET;
		
		$respuesta = array();
	
		if ($peticion['action'] == 'anuncios') {
		
			$anuncios = Modelo::seleccionarAnuncios();
			$respuesta = $this->getRespuestaOk($anuncios);
		}
		else {
			
			$respuesta = $this->getRespuestaError(1, "Acción incorrecta");
		}
			
		echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
	}
	
	function getRespuestaOk($anuncios) {

        $respuesta = array(
			"error" => false,
			"anuncios" => $anuncios
		);        
		
		return $respuesta;
	}
	
	function getRespuestaError($code, $message) {
		
        $respuesta = array(
			"error" => true,
			"code" => $code,
            "message" => $message
		);
		
		return $respuesta;
	}
	
	function OnError() {
		
		$respuesta = $this->getRespuestaError(0, "Error de acceso");
		
		echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
	}
}

class App {
	
	private $controlador = null;
	private $eventos = array();
	
	function __construct() {
		
		$this->eventos = array(
			'action' => 'OnAction',
		);
	}
	
	function despachar() {
		
		$manejador = 'OnError';
		
		foreach ($this->eventos as $event => $handler) {

			if (isset($_GET[$event])) {

				$manejador = $handler;
				break;
			}
		}
		
		$this->controlador->$manejador();
	}
	
	function init() {
		
		$this->controlador = new Controlador();
		$this->despachar();
	}
}

$app = new App();

$app->init();