<?php

$conexion2 = "academica_mysql";
$esteRecursoDB2 = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion2 );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {
	
	$codigo = isset ( $_REQUEST ['codigo'] ) ? $_REQUEST ['codigo'] : '';
	
	if ($codigo != '') {
		$variable['codigo']=$codigo;
		
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
				$this->deliver_response ( 200, "% Cursado: ", $porcentajeCursado );
			} else {
				$this->deliver_response ( 300, "No se pudo calcular el porcentaje cursado", null );
			}
		}else{
			$this->deliver_response ( 300, "No se encontraron datos para el estudiante", null );
		}
		
	}else{
		$this->deliver_response ( 300, "Código del estudiante sin especificar", null );
	}
} else {
	
	$this->deliver_response ( 400, "Peticion Inválida", null );
}


