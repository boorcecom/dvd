<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
body {
  font-family: 'MS Sans Serif', Geneva, sans-serif;
}
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  font-size:2vw;
}
.fabutton {
  background: none;
  padding: 0px;
  border: none;
}
</style>
</head>
<body>
<?php
require_once("dvdclass.php");
require_once("dvdlist.php");

if(isset($_POST['mode'])) {
  $mode=$_POST['mode'];
} else {
  $mode='search';
}

if (isset($_POST['entry'])) {
  $entry=$_POST['entry'];
}

if($mode=='push') {
  $dvdedit=new dvd();
  $dvdedit->setId($entry);
  $dvdedit->setTitle($_POST['dvdtitle']);
  $dvdedit->setMeuble($_POST['meuble']);
  if($_POST['editmode']=='edit') {
    $dvdedit->pushEdit();
    // mode édition
    
  } else {
    $dvdedit->pushAdd();
    // mode création
    unset($entry);
  }
  $mode='edit';
}

echo "<div style=\"font-size:8vw;\">dvdtheque";
if($mode=='search') {
  $newmode='canedit';
} else {
  $newmode='search';
}
echo "<form action=\"index.php\" method=\"post\"><input type=\"hidden\" name=\"mode\" value=\"{$newmode}\"><input type=\"submit\" value=\" bascule mode &#128257\" style=\"font-size:2vw;\" class=\"fabutton\">";
if ( isset($_POST['searchtitle']) ) {
  echo "<input type=\"hidden\" name=\"searchtitle\" value=\"{$_POST['searchtitle']}\"/>";
}
echo "</form></div>";
if($mode=='login') {
  echo "In Login mode !";
} else {
  if($mode=='edit') {
    if(isset($entry)) {
      $dvdedit=dvd::searchID($entry);
    } else {
      $dvdedit=dvd::newDvd();
    }
    echo "<form action=\"index.php\" method=\"post\"><input type=\"hidden\" name=\"mode\" value=\"push\"/><input type=\"hidden\" name=\"editmode\" value=\"{$_POST['editmode']}\">";
  if ( isset($_POST['searchtitle']) ) {
    echo "<input type=\"hidden\" name=\"searchtitle\" value=\"{$_POST['searchtitle']}\"/>";
  }
  echo "<div style=\"font-size:2vw;\">Id du DVD:<input type=\"text\" name=\"entry\" style=\"font-size:2vw;\" value=\"{$dvdedit->getId()}\"/> 
Titre :<input type=\"text\" name=\"dvdtitle\" style=\"font-size:2vw;\" value=\"{$dvdedit->getTitle()}\"/><BR></div>
<input type=\"radio\" name=\"meuble\" style=\"font-size:2vw;\" value=\"ETAGERE\"";
    if($dvdedit->getMeuble()=='ETAGERE') {
      echo "checked=\"checked\"";
    }
    echo "><label for=\"ETAGERE\" style=\"font-size:2vw;\"/>ETAGERE</label><br>
<input type=\"radio\" name=\"meuble\" style=\"font-size:2vw;\" value=\"ENFANTS\"";
    if($dvdedit->getMeuble()=='ENFANTS') {
      echo "checked=\"checked\"";
    }
    echo "><label for=\"ENFANTS\" style=\"font-size:2vw;\"/>ENFANTS</label><br>
<input type=\"submit\" value=\"Valider &#127383;\" style=\"font-size:2vw;\" class=\"fabutton\"/>
</form>";
    $dvdedit=null;
  }
  echo "<form action=\"index.php\" method=\"post\">
  <p style=\"font-size:5vw;\">recherche de dvd par titre
  <input type=\"text\" list=\"dvdlist\" name=\"searchtitle\" style=\"font-size:5vw;\"/>
  <datalist id=\"dvdlist\">";
  $dvdfulllist=dvds::search("*");
  foreach($dvdfulllist->getList() as $dvditem) {
    echo "<option value=\"{$dvditem->getTitle()}\">\n";
  }
  $dvdfulllisst=null;
  echo "</datalist><input type=\"hidden\" name=\"mode\" value=\"{$mode}\"/>
  <input type=\"submit\" value=\"&#128270;\" style=\"font-size:5vw;\" class=\"fabutton\"/></p></form>";
  if( isset($_POST['searchtitle']) )
  {
    echo "<table align=\"center\" width=\"100%\">
    <tr>
    <td>Numéro</td>
    <td>Titre</td>
    <td>Meuble</td>
    <td>étage</td>
    <td>rangée (1=au fond)</td>
    <td>ligne (1=en bas)</td>";
    if($mode=='canedit') {
      echo "<td>édition</td>";
    }
    echo "</tr>";
    
    $request = $_POST["searchtitle"];
    
    $dvdlist=dvds::search($request);
    
    foreach($dvdlist->getList() as $dvd) {
      echo
       "<tr>
        <td>{$dvd->getId()}</td>
        <td>{$dvd->getTitle()}</td>
        <td>{$dvd->getMeuble()}</td>
        <td>{$dvd->getEtagere()}</td>
        <td>{$dvd->getRangStr()}</td>
        <td>{$dvd->getNiveauStr()}</td>";
       if($mode=='canedit' or $mode=='edit'){
        echo "<td><form action=\"index.php\" method=\"post\">
    <input type=\"hidden\" name=\"mode\" value=\"edit\"><input type=\"hidden\" name=\"editmode\" value=\"edit\"><input type=\"hidden\" name=\"entry\" value=\"{$dvd->getId()}\">
    <input type=\"submit\" value=\"&#128221\"  style=\"font-size:2vw;\" class=\"fabutton\">
    <input type=\"hidden\" name=\"searchtitle\" value=\"{$request}\">
    </form></td>";
       }
       echo "</tr>\n";
    }
    echo "</table>";
    if($mode=='canedit' or $mode=='edit'){
        echo "<form action=\"index.php\" method=\"post\">
    <input type=\"hidden\" name=\"mode\" value=\"edit\">
    <input type=\"hidden\" name=\"editmode\" value=\"create\">
    <input type=\"submit\" value=\"Nouveau &#127381\"  style=\"font-size:2vw;\" class=\"fabutton\">
    <input type=\"hidden\" name=\"searchtitle\" value=\"{$request}\">
    </form>";
    }
  }
}
?>
</body>
</html>
