<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<body>
<table border="1" align="center">
<tr>
  <td>Numéro</td>
  <td>Titre</td>
  <td>Meuble</td>
  <td>étage</td>
  <td>rangée (1=au fond)</td>
  <td>ligne (1=en bas)</td>
</tr>
<?php

$request = $_POST["searchtitle"];

require_once("dvdclass.php");
require_once("dvdlist.php");

$dvdlist=dvds::search($request);

foreach($dvdlist->getList() as $dvd) {
  echo
   "<tr>
    <td>{$dvd->getId()}</td>
    <td>{$dvd->getTitle()}</td>
    <td>{$dvd->getMeuble()}</td>
    <td>{$dvd->getEtagere()}</td>
    <td>{$dvd->getRangStr()}</td>
    <td>{$dvd->getNiveauStr()}</td>
   </tr>\n";

}

?>
</table>
<form action="request.php" method="post">
 <p>Titre du dvd recherché <input type="text" name="searchtitle" /></p>
 <p><input type="submit" value="OK"></p>
</form>

</body>
</html>

