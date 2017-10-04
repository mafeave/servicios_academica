<?php

namespace servicios\servicio;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/builder/InspectorHTML.class.php");
include_once ("core/builder/Mensaje.class.php");
include_once ("core/crypto/Encriptador.class.php");

// Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
// metodos mas utilizados en la aplicacion

// Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
// en camel case precedido por la palabra Funcion
class Funcion {
	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $miConfigurador;
	var $error;
	var $miRecursoDB;
	var $crypto;

	function servicioConsultarCoordinador () {
		include_once ($this->ruta . "funcion/servicioConsultarCoordinador.php");
	}
	function servicioConsultarEstudiantes () {
		include_once ($this->ruta . "funcion/servicioConsultarEstudiantes.php");
	}
	function servicioConsultarDocentes () {
		include_once ($this->ruta . "funcion/servicioConsultarDocentes.php");
	}
	function servicioConsultarDocentesTG () {
		include_once ($this->ruta . "funcion/servicioConsultarDocentesTG.php");
	}
	function servicioConsultarCarreras () {
		include_once ($this->ruta . "funcion/servicioConsultarCarreras.php");
	}
	function servicioConsultarPromedio () {
		include_once ($this->ruta . "funcion/servicioConsultarPromedio.php");
	}
	function servicioConsultarAsignaturas () {
		include_once ($this->ruta . "funcion/servicioConsultarAsignaturas.php");
	}
	function servicioConsultarPeriodo () {
		include_once ($this->ruta . "funcion/servicioConsultarPeriodo.php");
	}
	function servicioConsultarPensums () {
		include_once ($this->ruta . "funcion/servicioConsultarPensums.php");
	}
	function servicioPorcentajeCursado () {
		include_once ($this->ruta . "funcion/servicioPorcentajeCursado.php");
	}
	function consultarPeriodoAnterior () {
		include_once ($this->ruta . "funcion/consultarPeriodoAnterior.php");
	}
	function procesarAjax() {
		include_once ($this->ruta . "funcion/procesarAjax.php");
	}

	function codifica_utf8($dat) // -- It returns $dat encoded to UTF8
	{
		if (is_string($dat)) return utf8_encode($dat);
		if (!is_array($dat)) return $dat;
		$ret = array();
		foreach($dat as $i=>$d) $ret[$i] = $this->codifica_utf8($d);
		return $ret;
	}

	function decodifica_utf8($dat) // -- It returns $dat decoded from UTF8
	{
		if (is_string($dat)) return utf8_decode($dat);
		if (!is_array($dat)) return $dat;
		$ret = array();
		foreach($dat as $i=>$d) $ret[$i] = $this->decodifica_utf8($d);
		return $ret;
	}

	function deliver_response($status,$status_message,$data){

		 ob_clean();
		 //echo "<script>document.title = '" . $status . " - " .  $status_message . "';</script>";
		 $json_response = json_encode ( $data, JSON_PRETTY_PRINT );
		 echo $json_response;
		 exit;

	}
	function action() {
		$resultado = true;

		// Aquí se coloca el código que procesará los diferentes formularios que pertenecen al bloque
		// aunque el código fuente puede ir directamente en este script, para facilitar el mantenimiento
		// se recomienda que aqui solo sea el punto de entrada para incluir otros scripts que estarán
		// en la carpeta funcion

		// Importante: Es adecuado que sea una variable llamada opcion o action la que guie el procesamiento:

		if (isset ( $_REQUEST ['procesarAjax'] )) {
			$this->procesarAjax ();
		} elseif (isset ( $_REQUEST ['servicio'] )) {

			switch ($_REQUEST ['servicio']) {
				case 'servicioConsultarEstudiantes' :
					$resultado = $this->servicioConsultarEstudiantes ();
					break;
				case 'servicioConsultarDocentes' :
					$resultado = $this->servicioConsultarDocentes ();
					break;
				case 'servicioConsultarDocentesTG' :
					$resultado = $this->servicioConsultarDocentesTG ();
					break;
				case 'servicioConsultarCarreras' :
					$resultado = $this->servicioConsultarCarreras ();
					break;
				case 'servicioConsultarPromedio' :
					$resultado = $this->servicioConsultarPromedio ();
					break;
				case 'servicioConsultarAsignaturas' :
					$resultado = $this->servicioConsultarAsignaturas ();
					break;
				case 'servicioConsultarPeriodo' :
					$resultado = $this->servicioConsultarPeriodo ();
					break;
				case 'servicioConsultarPensums' :
					$resultado = $this->servicioConsultarPensums ();
					break;
				case 'servicioPorcentajeCursado' :
					$resultado = $this->servicioPorcentajeCursado ();
					break;
				case 'consultarPeriodoAnterior' :
					$resultado = $this->consultarPeriodoAnterior ();
					break;
				case 'servicioConsultarCoordinador' :
					$resultado = $this->servicioConsultarCoordinador ();
					break;

			}
		}

		return $resultado;
	}
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();

		$this->ruta = $this->miConfigurador->getVariableConfiguracion ( "rutaBloque" );

		$this->miMensaje = \Mensaje::singleton ();

		$conexion = "academica";

		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

		if (! $this->miRecursoDB) {

			$this->miConfigurador->fabricaConexiones->setRecursoDB ( $conexion, "tabla" );
			$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		}
	}
	public function setRuta($unaRuta) {
		$this->ruta = $unaRuta;
	}
	public function setSql($a) {
		$this->sql = $a;
	}
	function setFuncion($funcion) {
		$this->funcion = $funcion;
	}
	public function setFormulario($formulario) {
		$this->formulario = $formulario;
	}
}

?>
