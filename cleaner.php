<?php 
namespace BigBang;

require_once('MySQLi_2.php');

$mysqli=new MySQLi_2("localhost","root", "root", "perso");
$mysqli->query('truncate table Stars');
$mysqli->query('truncate table Systemes');
$mysqli->query('truncate table Planetes');
$mysqli->query('truncate table Lifeforms');
?>