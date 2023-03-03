<?php
header('Content-Type: application/json');

// $_SERVER['REQUEST_METHOD']=='GET' => liste
// $_SERVER['REQUEST_METHOD']=='POST' => Une mise � jour d'une donn�e existente
// $_SERVER['REQUEST_METHOD']=='PUT' => Ajout d'une donn�e
// $_SERVER['REQUEST_METHOD']=='DELETE' => Supression !

require_once("dvdclass.php");
require_once("dvdlist.php");
if($_SERVER['REQUEST_METHOD']=='GET') {
    $searchkey=$_GET["search"];
    if($searchkey=="") {
        $searchkey="*";
    }
    $mydata=dvds::search($searchkey);
} else if ($_SERVER['REQUEST_METHOD']=='DELETE') {
    $key = $_GET["id"];
    $mydata=dvds::delItem($key);
}

echo json_encode($mydata);

?>