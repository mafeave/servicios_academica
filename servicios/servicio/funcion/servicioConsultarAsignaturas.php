<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$carrera = isset ( $_REQUEST ['carrera'] ) ? $_REQUEST ['carrera'] : '';
	$pensum = isset ( $_REQUEST ['pensum'] ) ? $_REQUEST ['pensum'] : '';
	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';
	$semestre = isset ( $_REQUEST ['semestre'] ) ? $_REQUEST ['semestre'] : '';

	if ($carrera != '' || $pensum != '' || $codigo != '' || $semestre != '') {
		$variable['carrera']=$carrera;
		$variable['pensum']=$pensum;
		$variable['codigo']=$codigo;
		$variable['semestre']=$semestre;

		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarAsignaturas", $variable);
		$matrizAsignaturas = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizAsignaturas){
			for($i=0; $i<count($matrizAsignaturas); $i++){
				foreach ( $matrizAsignaturas [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizAsignaturas [$i] [$index] );
					}
				}
			}
		}

		if ($matrizAsignaturas != false) {
			$this->deliver_response ( 200, "Asignaturas: ", $matrizAsignaturas );
		} else {
			$this->deliver_response ( 300, "No se encontraron asignaturas", null );
		}
	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
