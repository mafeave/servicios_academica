<?php

$conexion2 = "academica_mysql";
$esteRecursoDB2 = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion2 );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {

	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';

	if ($codigo != '') {

		if (is_numeric ( $codigo )) {
			$variable['codigo']=$codigo;

			if (strlen ( $codigo ) > 11 || strlen ( $codigo ) < 5) {
				$mensaje = "El c&oacute;digo debe tener m&aacute;ximo 11 digitos y m&iacute;nimo 5";
				$this->deliver_response ( 204, $mensaje, $mensaje);
			} else {
				$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "creditosCursados", $variable);
				$consultaCreditos = $esteRecursoDB2->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );

				if($consultaCreditos){
					//Créditos cursado y aprobados por el estudiante
					$creditosCursados=json_decode($consultaCreditos[0]['ins_parametros_plan']);

					//Número de Créditos del plan de estudio
					$creditosPlan=json_decode($consultaCreditos[0]['ins_creditos_aprobados']);

					if($creditosCursados->{'total'}>0){
						$porcentajeCursado=$creditosPlan->{'total'}*100/$creditosCursados->{'total'};
					}

					if ($porcentajeCursado >=0) {
						$this->deliver_response ( 200, "Porcentaje Cursado: ", $porcentajeCursado );
					} else {
						$mensaje = "No se pudo calcular el porcentaje cursado";
						$this->deliver_response ( 204, $mensaje, $mensaje);
					}
				}else{
					$mensaje = "No se encontraron datos para el estudiante";
					$this->deliver_response ( 300, $mensaje, $mensaje);
				}
			}

		}else {
			$mensaje = "El valor para realizar la consulta debe ser num&eacute;rico";
			$this->deliver_response ( 205, $mensaje, $mensaje );
		}

	}else{
		$mensaje = "C&oacute;digo del estudiante sin especificar";
		$this->deliver_response ( 206, $mensaje, $mensaje );
	}
} else {
	$this->deliver_response ( 400, "Petición Inválida", null );
}
