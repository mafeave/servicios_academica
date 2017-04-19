<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$carrera = isset ( $_REQUEST ['carrera'] ) ? $_REQUEST ['carrera'] : '';

	if ($carrera != '') {
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarPensums", $carrera);
		$matrizPensums = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		for($i=0; $i<count($matrizPensums); $i++){
			foreach ( $matrizPensums [$i] as $index => $dato ) {

				if (is_numeric ( $index )) {
					unset ( $matrizPensums [$i] [$index] );
				}
			}
		}

		if ($matrizPensums != false) {
			$this->deliver_response ( 200, "Pensums: ", $matrizPensums );
		} else {
			$this->deliver_response ( 300, "No se encontró pensums para la carrera", null );
		}
	}else{
		$this->deliver_response ( 400, "No se especificó la carrera", null );
	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
