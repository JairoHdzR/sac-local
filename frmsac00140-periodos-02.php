<?php
$dbs=str_pad($_GET['e'], 3, "0", STR_PAD_LEFT);
$u=$_GET['u'];
require_once '../pear/sac_config.php';
require_once "php/jqGrid.php";
require_once "php/jqGridPdo.php";
$subtable = jqGridUtils::GetParam("subgrid", 0);
$rowid = jqGridUtils::GetParam("rowid", 0);

$conn = new PDO(DB_DSN,DB_USER,DB_PASSWORD);
$conn->query("SET NAMES utf8");
$grid2= new jqGridRender($conn);
switch($_GET['u'])
{

	case 'DESARROLLO':
		$grid2->SelectCommand = "select 
				case s00139estatus
				when 0 then '-'
				else c00140estatus end as c00140estatus2
				,i00140idejercicio,c00140periodo,c00140estatus,f00140fechacierre,v00140realizocierre,
				case c00140facturacion
				when 0 then 'CERRADO'
				else 'ABIERTO' END as Facturacion,
				case c00140polizadiario
				when 0 then 'CERRADO'
				else 'ABIERTO' END as PD,
				case c00140cxp
				when 0 then 'CERRADO'
				else 'ABIERTO' END as CXP,
				case c00140bancos
				when 0 then 'CERRADO'
				else 'ABIERTO' END as Bancos,
				(select if(count(i08200id)>1,1,0) from sacmaster.sac08200 where n08200empresa = $dbs and n08200periodo = c00140periodo) as RF,
				(select if(count(i08100idresultado)>=1,1,0) from sacmaster.sac08100 where n08100empresa = $dbs and n08100periodo = c00140periodo)  as ERG
				from  sacmaster.sac00140
				join  sacmaster.sac00139 on n00139ejercicio=$rowid and n00139claveempresa=$dbs
				where n00140claveempresa=$dbs and c00140ejercicio=$rowid";
		break;
	case 'ROSYR1':
		if($dbs==753){
			$grid2->SelectCommand = "select 
				case s00139estatus
				when 0 then '-'
				else c00140estatus end as c00140estatus2
				,i00140idejercicio,c00140periodo,c00140estatus,f00140fechacierre,v00140realizocierre,
				case c00140facturacion
				when 0 then 'CERRADO'
				else 'ABIERTO' END as Facturacion,
				case c00140polizadiario
				when 0 then 'CERRADO'
				else 'ABIERTO' END as PD,
				case c00140cxp
				when 0 then 'CERRADO'
				else 'ABIERTO' END as CXP,
				case c00140bancos
				when 0 then 'CERRADO'
				else 'ABIERTO' END as Bancos,
				(select if(count(i08200id)>1,1,0) from sacmaster.sac08200 where n08200empresa = $dbs and n08200periodo = c00140periodo) as RF,
				(select if(count(i08100idresultado)>=1,1,0) from sacmaster.sac08100 where n08100empresa = $dbs and n08100periodo = c00140periodo)  as ERG
				from  sacmaster.sac00140
				join  sacmaster.sac00139 on n00139ejercicio=$rowid and n00139claveempresa=$dbs
				where n00140claveempresa=$dbs and c00140ejercicio=$rowid";
		}
		else{
			$grid2->SelectCommand = "select  '-' AS c00140estatus3, i00140idejercicio,c00140periodo,
								c00140estatus,f00140fechacierre,v00140realizocierre,
								case c00140facturacion
								when 0 then 'CERRADO'
								else 'ABIERTO' END as Facturacion,
								case c00140polizadiario
								when 0 then 'CERRADO'
								else 'ABIERTO' END as PD,
								case c00140cxp
								when 0 then 'CERRADO'
								else 'ABIERTO' END as CXP,
								case c00140bancos
								when 0 then 'CERRADO'
								else 'ABIERTO' END as Bancos,
								case c00140estatus when 0 then 'GENERAR_CE' when 1 then '-' end as 'GENERAR_CE'
								from  sacmaster.sac00140 a where n00140claveempresa=$dbs and c00140ejercicio=$rowid";
		}
				
		break;
		
	default:
		$grid2->SelectCommand = "select  '-' AS c00140estatus3, i00140idejercicio,c00140periodo,
								c00140estatus,f00140fechacierre,v00140realizocierre,
								case c00140facturacion
								when 0 then 'CERRADO'
								else 'ABIERTO' END as Facturacion,
								case c00140polizadiario
								when 0 then 'CERRADO'
								else 'ABIERTO' END as PD,
								case c00140cxp
								when 0 then 'CERRADO'
								else 'ABIERTO' END as CXP,
								case c00140bancos
								when 0 then 'CERRADO'
								else 'ABIERTO' END as Bancos,
								case c00140estatus when 0 then 'GENERAR_CE' when 1 then '-' end as 'GENERAR_CE'
								from  sacmaster.sac00140 a where n00140claveempresa=$dbs and c00140ejercicio=$rowid";
		break;		
		
		
}
$grid2->table = "sacmaster.sac00140";
$grid2->setPrimaryKeyId('i00140idejercicio');
$grid2->serialKey = true;
$grid2->dataType = 'json';
$mylabels = array("i00140idejercicio"=>"Id", 
				   "n00140claveempresa"=>"Empresa",	
"c00140ejercicio"=>"Ejercicio",	
"c00140periodo"=>"Período",
"c00140estatus2"=>"Selección",
"c00140estatus3"=>"Selección",
"c00140estatus"=>"Estatus",
"f00140fechacierre"=>"Fecha Cierre",
"v00140realizocierre"=>"Usuario",
"c00140cierreAnual"=>"Cierre Anual",
"f00140fechacierreAnual"=>"Fecha Cierre",
"v00140realizocierreAnual"=>"Usuario");

$grid2->setColModel(null, array(&$rowid),$mylabels);

$custom = <<<CUSTOM
// 
function formatImage(cellValue, options, rowObject) {
	if(cellValue=='0')
		var imageHtml = '<img src="../../../imagenes/lock.png" style="display: inherit"/>';
	else
		var imageHtml = '<img src="../../../imagenes/unlock.png" style="display: inherit"/>';
return imageHtml;
}

function unformatImage(cellValue, options, cellObject) {
    return $(cellObject.html()).attr("originalValue");
}
CUSTOM;

$grid2->setJSCode($custom);

$grid2->setUrl('frmsac00140-periodos-02.php?e='.$dbs.'&u='.$u);
$grid2->setGridOptions(array(
    "hoverrows"=>true,
    "rowNum"=>12,
    "height"=>250,
    "rowList"=>array(12,24,36),
    "sortname"=>"c00140periodo",
	"width"=>1150,
	"height"=>"auto",	
	"postData"=>array("rowid"=>$rowid)
  ));

$grid2->setColProperty("i00140idejercicio",array("hidden"=>true,"width"=>50,"editoptions"=>array("size"=>5,"readonly"=>true)));

$grid2->setColProperty("c00140estatus",array("width"=>70,"formatter"=>"js:formatImage"));
$grid2->setColProperty("Facturacion",array("width"=>70));
$grid2->setColProperty("PD",array("width"=>70));
$grid2->setColProperty("CXP",array("width"=>70));
$grid2->setColProperty("Bancos",array("width"=>70));

$grid2->setColProperty("c00140estatus2",array("width"=>70));
$grid2->setColProperty("c00140cierreAnual",array("width"=>70));
$grid2->setColProperty("f00140fechacierreAnual",array("width"=>70));
$grid2->setColProperty("v00140realizocierreAnual",array("width"=>70));
//$grid2->setSelect('n00140claveempresa',   "SELECT n00100claveempresa,v00100nombre FROM sacmaster.sac00100");
//$grid2->setselect('c00140estatus',array("1"=>"ABIERTO","0"=>"CERRADO"));
$grid2->setselect('c00140cierreAnual',array("1"=>"ABIERTO","0"=>"CERRADO"));
$grid2->setselect('c00140estatus2',array("1"=>"CERRAR","0"=>"ABRIR","-"=>"-"));

$grid2->setselect('c00140estatus2',array("1"=>"CERRAR","0"=>"ABRIR","-"=>"-"));
$grid2->setselect('RF',array("1"=>"GENERADO","0"=>"GENERAR","-"=>"-"));
$grid2->setselect('ERG',array("1"=>"GENERADO","0"=>"GENERAR","-"=>"-"));
/*
$COLORES = <<<COLORS
function(rowid,celname,value,iRow,iCol)
{
    var rowIds = $('#grid2').jqGrid('getDataIDs');
    for(var i=0,len=rowIds.length;i<len;i++)
    {
        var currRow = rowIds[i];
		var fcolor='#00CCFF';
		
        var valor = jQuery('#grid2').jqGrid('getCell',currRow,'Facturacion');
		if (valor=='CERRADO'){jQuery('#grid2').jqGrid('setCell',currRow,'Facturacion','',{background:fcolor,color:fcolor, weightfont:'bold'});}
		else{jQuery('#grid2').jqGrid('setCell',currRow,'Facturacion','',{background:'#FFFFFF',color:'#FFFFFF', weightfont:'bold'});}
	}   
}
COLORS;
$grid2->setGridEvent('loadComplete',$COLORES);*/



$myevent2 = <<<ONSELECT
function(rowid,iCol,cellcontent,e,selected,selrow)
{
	
//	var selrowId = jQuery('#grid2').jqGrid('getGridParam','selrow'); 
	// alert(  iCol );
	 $( "#idp" ).val( rowid );
	var tr=cellcontent;
 	if (rowid) 
 	{
  		if(iCol==0)
  		{
			$( "#st" ).val( cellcontent );
			$( "#idp" ).val( rowid );
  			if(tr=='ABRIR') 
			{
				var x = 'Desea Abrir el Periodo Seleccionado?';					
				document.getElementById("msgP").innerHTML = x;				
				$( "#dialog-Periodos" ).dialog( "open" );
			}
			if(tr=='CERRAR') 
			{
				var x = 'Desea Cerrar el Periodo Seleccionado?';					
				document.getElementById("msgP").innerHTML = x;
				$( "#dialog-Periodos" ).dialog( "open" );
			}
   		}
		else
		{
			
			if(iCol==6 || iCol==7 || iCol==8 || iCol==9 )
			{
				if(cellcontent=='<img src="../../../imagenes/lock.png">')
					 var x = 'Desea Abrir el Modulo?';
				else
					 var x = 'Desea Cerrar el Modulo?';
					
				document.getElementById("msgM").innerHTML = x;
				$( "#idp" ).val( rowid );
				$( "#icol" ).val( iCol );
				$( "#status" ).val( cellcontent );
				$( "#dialog-Modulos" ).dialog( "open" );
				
			}
			if(iCol==10 ){
				if(cellcontent=='GENERAR'){
				 var x = 'Generar Razonez Financieras';
				 document.getElementById("msgRF").innerHTML = x;
				 	$( "#idp" ).val( rowid );
				 	$( "#dialog-RazonesFinancieras" ).dialog( "open" );
				}
				else if (cellcontent=='GENERAR_CE'){
					var x = 'Generar Contabilidad Electronica: Catálogo y Balanza';
					document.getElementById("msgCE").innerHTML = x;
				 	$( "#idp" ).val( rowid );
				 	$( "#dialog-GenerarCE" ).dialog( "open" );
				}
				else if (cellcontent=='GENERADO'){
					 var x = 'Inf. Generada';
					  document.getElementById("msgD").innerHTML = x;
				 	$( "#dialog" ).dialog( "open" );
				}				
			}
			if(iCol==11 ){
				if(cellcontent=='GENERAR'){
				 var x = 'Generar Estado de Resultado General';
				 document.getElementById("msgERG").innerHTML = x;
				 	$( "#idp" ).val( rowid );
				 	$( "#dialog-EstadoResultadoGeneral" ).dialog( "open" );
				}
				else{
					 var x = 'Inf. Generada';
					  document.getElementById("msgD").innerHTML = x;
				 	$( "#dialog" ).dialog( "open" );
				}
			}
		}
 	}
}
ONSELECT;
$grid2->setGridEvent('onCellSelect',$myevent2);

$selectorder = <<<ORDER
function(rowid, selected,iCol) 
{ 
    if(rowid != null ) { 
        jQuery("#detail").jqGrid('setGridParam',{postData:{i00140idejercicio:rowid }}); 
        jQuery("#detail").trigger("reloadGrid"); 
		
		jQuery("#detail2").jqGrid('setGridParam',{postData:{i00140idejercicio:rowid }}); 
        jQuery("#detail2").trigger("reloadGrid"); 
		
    } 
} 
ORDER;
$grid2->setGridEvent('onSelectRow', $selectorder);


$grid2->navigator = true;
$grid2->setNavEvent('edit', 'afterSubmit', $myevent2); 
$grid2->setNavEvent('add', 'afterSubmit', $myevent2); 

$grid2->setNavOptions('add',array("closeAfterAdd"=>true,"reloadAfterSubmit"=>true,"addCaption"=>"Crear Folio de Facturacion","width"=>500,"height"=>380,"dataheight"=>320)); 
$grid2->setNavOptions('edit',array("closeAfterEdit"=>true,"reloadAfterSubmit"=>true,"editCaption"=>"Modificar Folio de Facturacion","width"=>500,"height"=>380,"dataheight"=>320));
$grid2->setNavOptions('view',array("width"=>600,"height"=>160,"dataheight"=>100));
$grid2->setNavOptions('search',array("multipleSearch"=>true));

$subtable = $subtable."_t";
$pager = $subtable."_p";
$grid2->renderGrid($subtable,$pager, true, null, array(&$rowid), true,true);
$conn = null;
?>
