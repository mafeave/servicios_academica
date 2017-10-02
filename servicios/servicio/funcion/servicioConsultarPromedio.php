<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$conexion2 = "academica_mysql";
$esteRecursoDB2 = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion2 );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';
	$ano = isset ( $_REQUEST ['ano'] ) ? $_REQUEST ['ano'] : '';
	$periodo = isset ( $_REQUEST ['periodo'] ) ? $_REQUEST ['periodo'] : '';
	$sin_rendimiento = isset ( $_REQUEST ['sin_rendimiento'] ) ? $_REQUEST ['sin_rendimiento'] : '';

	if (($codigo != '' || $ano != '' || $periodo != '') && $sin_rendimiento != '' ) {
		$variable['codigo']=$codigo;

		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarPromedio2", $variable);
		$matrizDatos = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizDatos){

			for($i=0; $i<count($matrizDatos); $i++){
				$matrizDatos[$i]['PROMEDIO'] = str_replace(",", ".",$matrizDatos[$i]['PROMEDIO']);

				foreach ( $matrizDatos [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizDatos [$i] [$index] );
					}
				}
			}

		}

		if ($matrizDatos != false) {
			$this->deliver_response ( 200, "Promedio: ", $matrizDatos );
		} else {
			$this->deliver_response ( 300, "No se pudo calcular el promedio", null );
		}
	}

	else if ($codigo != '' || $ano != '' || $periodo != '') {
		$variable['codigo']=$codigo;
		$variable['ano']=$ano;
		$variable['periodo']=$periodo;

		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarPromedio", $variable);
		$matrizDatos = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

		if($matrizDatos){
			$porcentajeCursado=0;
			$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "creditosCursados", $variable);
			$consultaCreditos = $esteRecursoDB2->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

			if($consultaCreditos){
				//Créditos cursados y aprobados por el estudiante
				$creditosCursados=json_decode($consultaCreditos[0]['ins_parametros_plan']);

				//Número de Créditos del plan de estudio
				$creditosPlan=json_decode($consultaCreditos[0]['ins_creditos_aprobados']);

				if($creditosCursados->{'total'}>0){
					$porcentajeCursado=$creditosPlan->{'total'}*100/$creditosCursados->{'total'};

				}

			}

			$matrizDatos[0]['PORCENTAJE'] = "".$porcentajeCursado;
			for($i=0; $i<count($matrizDatos); $i++){
				$matrizDatos[$i]['PROMEDIO'] = str_replace(",", ".",$matrizDatos[$i]['PROMEDIO']);

				foreach ( $matrizDatos [$i] as $index => $dato ) {

					if (is_numeric ( $index )) {
						unset ( $matrizDatos [$i] [$index] );
					}
				}

			}
		}

		if ($matrizDatos != false) {
			$this->deliver_response ( 200, "Promedio: ", $matrizDatos );
		} else {
			$this->deliver_response ( 300, "No se pudo calcular el promedio", null );
		}

	}
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
