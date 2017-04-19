<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarPeriodoAnterior");
	$matrizPeriodo = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

	foreach ( $matrizPeriodo [0] as $index => $dato ) {
		if (is_numeric ( $index )) {
			unset ( $matrizPeriodo [0] [$index] );
		}
	}

	if ($matrizPeriodo != false) {
		$this->deliver_response ( 200, "Periodo: ", $matrizPeriodo );
	} else {
		$this->deliver_response ( 300, "No se encontró el periodo académico", null );
	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
