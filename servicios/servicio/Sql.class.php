<?php

namespace servicios\servicio;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {
	var $miConfigurador;
	function getCadenaSql($tipo, $variable = '') {

		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );

		switch ($tipo) {


			/**
			 * Clausulas específicas
			 */

			case 'buscarCoordinador' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "CRA_EMP_NRO_IDEN AS ID_COORDINADOR, ";
				$cadenaSql .= "CRA_COD AS CODIGO_CARRERA, ";
				$cadenaSql .= "CRA_NOMBRE AS CARRERA, ";
				$cadenaSql .= "CONCAT (DOC_NOMBRE , CONCAT(' ', DOC_APELLIDO)) AS COORDINADOR ";
				$cadenaSql .= "FROM MNTAC.ACCRA, MNTAC.ACDOCENTE ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "DOC_NRO_IDEN=CRA_EMP_NRO_IDEN ";
				$cadenaSql .= "AND CRA_COD =".$variable;
				break;


			case 'buscarEstudiantes' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "EST_COD, ";
				$cadenaSql .= "EST_NOMBRE ";
				$cadenaSql .= "FROM MNTAC.ACEST ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "EST_ESTADO= 'A' ";
				$cadenaSql .= "AND (EST_ESTADO_EST= 'A' ";
				$cadenaSql .= "OR EST_ESTADO_EST= 'B' ";
				$cadenaSql .= "OR EST_ESTADO_EST= 'T' ";
				$cadenaSql .= "OR EST_ESTADO_EST= 'V' ";
				$cadenaSql .= "OR EST_ESTADO_EST= 'J' ) ";
				$cadenaSql .= "AND EST_IND_CRED='S' ";
				if($variable!='' ){
					$cadenaSql .= " AND EST_CRA_COD=".$variable;
				}
				$cadenaSql .= " ORDER BY EST_COD ";
				break;

			case 'buscarDocentesTG' :
				$cadenaSql=" SELECT DIR_NRO_IDEN, ";
				$cadenaSql.= "CONCAT (DIR_NOMBRE , CONCAT(' ', DIR_APELLIDO)) AS Nombre ";
				$cadenaSql.=" FROM MNTAC.ACDIRECTORGRADO";
				$cadenaSql.=" WHERE DIR_ESTADO='A'";
				$cadenaSql.= " ORDER BY Nombre ";
				//echo $cadenaSql;
				break;

			case 'buscarDocentes' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "DOC_NRO_IDEN, ";
				$cadenaSql .= "CONCAT (DOC_NOMBRE , CONCAT(' ', DOC_APELLIDO)) AS Nombre ";
				$cadenaSql .= "FROM MNTAC.ACDOCENTE ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "DOC_ESTADO_REGISTRO='A' ";
				$cadenaSql .= "AND DOC_ESTADO='A' ";
				if($variable!='' ){
					$cadenaSql .= "AND DOC_NRO_IDEN =".$variable;
				}
				//$cadenaSql .= "ROWNUM<=15";
				$cadenaSql .= " order by DOC_NOMBRE ";
				//echo $cadenaSql;
				break;

			/*case 'buscarCarreras' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "CRA_COD CODIGO, ";
				$cadenaSql .= "CRA_NOMBRE NOMBRE, ";
				$cadenaSql .= "CRA_TIP_CRA TIPO ";
				$cadenaSql .= "FROM MNTAC.ACCRA, ";
				$cadenaSql .= "MNTAC.ACTIPCRA ";
 				$cadenaSql .= "WHERE ";
 				$cadenaSql .= "CRA_TIP_CRA=TRA_COD ";

 				if($variable){
	 				if($variable['tipo']!='' ){
	 					$cadenaSql .= " AND TRA_NIVEL= '".$variable['tipo']."'";
	 				}
	 				if($variable['codigo']!='' ){
	 					$cadenaSql .= " AND CRA_COD=".$variable['codigo'];
	 				}
 				}
 				$cadenaSql .= " order by CRA_NOMBRE ";
 				echo $cadenaSql;
				break;*/

				case 'buscarCarreras' :
					$cadenaSql = "SELECT DISTINCT ";
					$cadenaSql .= "CRA_NOMBRE AS NOMBRE, ";
					$cadenaSql .= "first_value(CRA_COD) over (partition by CRA_NOMBRE order by CRA_COD asc) AS CODIGO ";
					$cadenaSql .= "FROM MNTAC.ACCRA, ";
					$cadenaSql .= "MNTAC.ACTIPCRA ";
					$cadenaSql .= "WHERE ";
					$cadenaSql .= "CRA_TIP_CRA=TRA_COD ";

					if($variable){
						if($variable['tipo']!='' ){
							$cadenaSql .= " AND TRA_NIVEL= '".$variable['tipo']."'";
						}
						if($variable['codigo']!='' ){
							$cadenaSql .= " AND CRA_COD=".$variable['codigo'];
						}
					}
					$cadenaSql .= " order by CRA_NOMBRE ";
					echo $cadenaSql;
					break;

			case 'buscarPromedio' :
				$cadenaSql = "SELECT ";

				$cadenaSql .= " EST_NOMBRE AS NOMBRE, ";
				$cadenaSql .= " EST_CRA_COD, ";
				$cadenaSql .= " Fa_Promedio_Nota(".$variable['codigo'].") AS PROMEDIO, ";
				$cadenaSql .= " REG_RENDIMIENTO_AC, ";//rendimiento académico
 				$cadenaSql .= " EST_ESTADO_EST, ";//estado: j,a,..
				$cadenaSql .= " CRA_TIP_CRA, ";
				$cadenaSql .= " TRA_NOMBRE, ";
				$cadenaSql .= " TRA_NIVEL ";

				$cadenaSql .= " FROM MNTAC.ACEST, ";
				$cadenaSql .= " MNTAC.REGLAMENTO, ";
				$cadenaSql .= " MNTAC.ACCRA ACCRA, ";
				$cadenaSql .= " MNTAC.ACTIPCRA ACTIPCRA ";

				$cadenaSql .= " WHERE EST_COD =".$variable['codigo'];
				$cadenaSql .= " AND REG_ANO= ".$variable['ano'];
				$cadenaSql .= " AND REG_PER= ".$variable['periodo'];
				$cadenaSql .= " AND REG_ESTADO= 'A'";
				$cadenaSql .= " AND REG_EST_COD=EST_COD";
				$cadenaSql .= " AND ACEST.EST_CRA_COD=ACCRA.CRA_COD";
				$cadenaSql .= " AND ACTIPCRA.TRA_COD=ACCRA.CRA_TIP_CRA";
				echo $cadenaSql;
				break;

				case 'buscarPromedio2' :
					$cadenaSql = "SELECT ";

					$cadenaSql .= " EST_NOMBRE AS NOMBRE, ";
					$cadenaSql .= " EST_CRA_COD, ";
					$cadenaSql .= " Fa_Promedio_Nota(".$variable['codigo'].") AS PROMEDIO, ";

					$cadenaSql .= " EST_ESTADO_EST, ";//estado: j,a,..
					$cadenaSql .= " CRA_TIP_CRA, ";
					$cadenaSql .= " TRA_NOMBRE, ";
					$cadenaSql .= " TRA_NIVEL ";

					$cadenaSql .= " FROM MNTAC.ACEST, ";
					$cadenaSql .= " MNTAC.ACCRA ACCRA, ";
					$cadenaSql .= " MNTAC.ACTIPCRA ACTIPCRA ";

					$cadenaSql .= " WHERE EST_COD =".$variable['codigo'];
					$cadenaSql .= " AND ACEST.EST_CRA_COD=ACCRA.CRA_COD";
					$cadenaSql .= " AND ACTIPCRA.TRA_COD=ACCRA.CRA_TIP_CRA";

					break;


			case 'buscarAsignaturas' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "PEN_CRA_COD, ";
				$cadenaSql .= "PEN_SEM, ";
				$cadenaSql .= "ASI_COD, ";
				$cadenaSql .= "ASI_NOMBRE, ";
				$cadenaSql .= "PEN_CRE ";
				$cadenaSql .= "FROM MNTAC.ACPEN, ";
				$cadenaSql .= "MNTAC.ACASI ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "PEN_ASI_COD=ASI_COD ";

				if($variable['codigo']!=''){
					$cadenaSql .= "AND ASI_COD=".$variable['codigo'];
				}

				if($variable['semestre']!=''){
					$cadenaSql .= "AND PEN_SEM=".$variable['semestre'];
				}

				if($variable['carrera']!='' && $variable['pensum']!=''){
					$cadenaSql .= "AND ASI_ESTADO='A' ";
					$cadenaSql .= "AND PEN_ESTADO='A' ";
					$cadenaSql .= "AND PEN_CRA_COD=".$variable['carrera'];
					$cadenaSql .= " AND PEN_NRO=".$variable['pensum'];
				}
				break;

			case 'buscarPeriodo' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "APE_ANO, ";
				$cadenaSql .= "APE_PER ";
				$cadenaSql .= "FROM MNTAC.ACASPERI ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "APE_ESTADO='X'";
				break;

			case 'buscarPeriodoAnterior' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "APE_ANO, ";
				$cadenaSql .= "APE_PER ";
				$cadenaSql .= "FROM MNTAC.ACASPERI ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "APE_ESTADO='P'";
				break;

			case 'buscarAsignatura' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "ASI_COD, ";
				$cadenaSql .= "ASI_NOMBRE, ";
				$cadenaSql .= "PEN_CRE ";
				$cadenaSql .= "FROM MNTAC.ACPEN, ";
				$cadenaSql .= "MNTAC.ACASI ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "PEN_ASI_COD=ASI_COD ";
				$cadenaSql .= "AND ASI_COD=".$_REQUEST['asignatura'];
				break;

			case 'buscarPensums' :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "DISTINCT PEN_NRO ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= "MNTAC.ACPEN ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "PEN_ESTADO='A' ";
				//$cadenaSql .= "AND PEN_SEM=1 ";
				$cadenaSql .= "AND PEN_CRA_COD=".$variable;
				$cadenaSql .= " order by PEN_NRO ";
				break;

			case 'buscarPlanEstudio':
				$cadenaSql="SELECT est_cra_cod, est_pen_nro ";
				$cadenaSql.="FROM acest ";
				$cadenaSql.="WHERE est_cod=".$variable['codigo'];
				break;

			case 'buscarCreditosPlan':
				$cadenaSql="SELECT PLAN_CREDITOS ";
				$cadenaSql.="FROM acplanestudio ";
				$cadenaSql.="WHERE PLAN_PEN_NRO=".$variable;
				break;

			case 'creditosCursados':
				$cadenaSql="SELECT ins_creditos_aprobados,  ";
				$cadenaSql.="ins_parametros_plan ";
				$cadenaSql.="FROM sga_carga_inscripciones ";
				$cadenaSql.="WHERE ins_est_cod=".$variable['codigo'];
				break;


			case 'buscarEspaciosAprobados':
				$cadenaSql="SELECT not_asi_cod, not_cra_cod, not_cred, not_cea_cod";
				$cadenaSql.=" FROM acnot";
				$cadenaSql.=" WHERE not_est_cod =".$variable['codigo'];
				$cadenaSql.=" AND not_cra_cod= (SELECT est_cra_cod FROM acest WHERE est_cod=".$variable['codigo'].")";
				$cadenaSql.=" AND not_nota >= '30'";
				$cadenaSql.=" AND not_est_reg like '%A%'";
				break;

		}

		return $cadenaSql;
	}
}
?>
