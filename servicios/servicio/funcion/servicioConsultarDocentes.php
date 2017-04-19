<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$identificacion = isset ( $_REQUEST ['identificacion'] ) ? $_REQUEST ['identificacion'] : '';

	if ($identificacion != '') {

		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarDocentes", $identificacion );
		$matrizDocentes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizDocentes){
			for($i=0; $i<count($matrizDocentes); $i++){
				foreach ( $matrizDocentes [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizDocentes [$i] [$index] );
					}
				}
			}
		}

		if ($matrizDocentes != false) {
			$this->deliver_response ( 200, "Docentes: ", $matrizDocentes );
		} else {
			$this->deliver_response ( 300, "No se encontraron docentes", null );
		}
	}else{
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarDocentes" );
		$matrizDocentes = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		for($i=0; $i<count($matrizDocentes); $i++){
			foreach ( $matrizDocentes [$i] as $index => $dato ) {

				if (is_numeric ( $index )) {
					unset ( $matrizDocentes [$i] [$index] );
				}
			}
		}

		if ($matrizDocentes != false) {
			$this->deliver_response ( 200, "Docentes: ", $matrizDocentes );
		} else {
			$this->deliver_response ( 300, "No se encontraron docentes", null );
		}
	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
