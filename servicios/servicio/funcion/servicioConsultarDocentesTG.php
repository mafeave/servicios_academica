<?php

$conexion3 = "academica4";
$esteRecursoDB3 = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion3 );

if (isset ( $_REQUEST ['servicio'] ) && $_REQUEST ['servicio'] != '') {
	
		$atributos ['cadena_sql'] = $this->sql->getCadenaSql ( "buscarDocentesTG" );
		$matrizDocentes = $esteRecursoDB3->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
		
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
	
} else {
	
	$this->deliver_response ( 400, "Peticion Inválida", null );
}


