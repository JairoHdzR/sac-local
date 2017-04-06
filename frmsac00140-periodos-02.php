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

?>
