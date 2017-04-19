<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$tipo = isset ( $_REQUEST ['tipo'] ) ? $_REQUEST ['tipo'] : '';
	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';

	if ($tipo != '' || $codigo != '') {
		$variable['tipo']=$tipo;
		$variable['codigo']=$codigo;
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarCarreras", $variable);

		$matrizCarreras = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizCarreras){
			for($i=0; $i<count($matrizCarreras); $i++){
				foreach ( $matrizCarreras [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizCarreras [$i] [$index] );
					}
				}
			}
		}

		if ($matrizCarreras != false) {
			$this->deliver_response ( 200, "Carreras: ", $matrizCarreras );
		} else {
			$this->deliver_response ( 300, "No se encontraron carreras", null );
		}
	}else{
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarCarreras");
		$matrizCarreras = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		for($i=0; $i<count($matrizCarreras); $i++){
			foreach ( $matrizCarreras [$i] as $index => $dato ) {

				if (is_numeric ( $index )) {
					unset ( $matrizCarreras [$i] [$index] );
				}
			}
		}

		if ($matrizCarreras != false) {
			$this->deliver_response ( 200, "Carreras: ", $matrizCarreras );
		} else {
			$this->deliver_response ( 300, "No se encontraron carreras", null );
		}
	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
