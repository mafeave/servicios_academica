<?php

$conexion = "academica2";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {
	
	$carrera = isset ( $_REQUEST ['carrera'] ) ? $_REQUEST ['carrera'] : '';
	
	if ($carrera != '') {
		
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarCoordinador", $carrera );
		$matrizCoordinador = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
		
		foreach ( $matrizCoordinador [0] as $index => $dato ) {
			if (is_numeric ( $index )) {
				unset ( $matrizCoordinador [0] [$index] );
			}
		}
		
		if ($matrizCoordinador != false) {
			$this->deliver_response ( 200, "Coordinador: ", $matrizCoordinador );
		} else {
			$this->deliver_response ( 300, "No se encontró el Coordinador del proyecto curricular", null );
		}
	}else{
		$this->deliver_response ( 400, "Carrera no especificada", null );
	}
} else {
	
	$this->deliver_response ( 400, "Peticion Inválida", null );
}


