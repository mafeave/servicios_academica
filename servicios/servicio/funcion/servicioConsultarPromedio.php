<?php

$conexion = "academica";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';
	$ano = isset ( $_REQUEST ['ano'] ) ? $_REQUEST ['ano'] : '';
	$periodo = isset ( $_REQUEST ['periodo'] ) ? $_REQUEST ['periodo'] : '';

	if ($codigo != '' || $ano != '' || $periodo != '') {
		$variable['codigo']=$codigo;
		$variable['ano']=$ano;
		$variable['periodo']=$periodo;

			$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarPromedio", $variable);
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
} else {

	$this->deliver_response ( 400, "Peticion Inválida", null );
}
