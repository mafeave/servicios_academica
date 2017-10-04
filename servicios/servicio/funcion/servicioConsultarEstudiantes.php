<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$carrera = isset ( $_REQUEST ['carrera'] ) ? $_REQUEST ['carrera'] : '';
	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';

	if ($carrera != '') {

		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarEstudiantes", $carrera );
		$matrizEstudiantes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizEstudiantes){
			for($i=0; $i<count($matrizEstudiantes); $i++){
				foreach ( $matrizEstudiantes [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizEstudiantes [$i] [$index] );
					}
				}
			}
		}

		if ($matrizEstudiantes != false) {
			$this->deliver_response ( 200, "Estudiantes: ", $matrizEstudiantes );
		} else {
			$this->deliver_response ( 300, "No se encontraron estudiantes", null );
		}
	}

	else if ($codigo != '') {
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarEstudiante", $codigo );
		$matrizEstudiantes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizEstudiantes){
			for($i=0; $i<count($matrizEstudiantes); $i++){
				foreach ( $matrizEstudiantes [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizEstudiantes [$i] [$index] );
					}
				}
			}
		}

		if ($matrizEstudiantes != false) {
			$this->deliver_response ( 200, "Estudiantes: ", $matrizEstudiantes );
		} else {
			$this->deliver_response ( 300, "No se encontraron estudiantes", null );
		}
	}

	else{
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarEstudiantes");
		$matrizEstudiantes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		for($i=0; $i<count($matrizEstudiantes); $i++){
			foreach ( $matrizEstudiantes [$i] as $index => $dato ) {

				if (is_numeric ( $index )) {
					unset ( $matrizEstudiantes [$i] [$index] );
				}
			}
		}

		if ($matrizEstudiantes != false) {
			$this->deliver_response ( 200, "Estudiantes: ", $matrizEstudiantes );
		} else {
			$this->deliver_response ( 300, "No se encontraron estudiantes", null );
		}

	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
