<?php

require_once("dvdclass.php");

Class dvds {

  static $dbhost="mysqlboorce.mysql.database.azure.com";
  public $dvdlist=array();
  
  
  public static function search($title) {
    $instance = new self();
    $hostname = self::$dbhost;
    $username = "dvduser";
    $password = "Al@3#45jh8lapI@89";
    $db = "dvdbase";

    $title=addslashes($title);

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);
    if ($dbconnect->connect_error) {
      die("Database connection failed: " . $dbconnect->connect_error);
    }
    if( $title=='*' ) {
      $query = mysqli_query($dbconnect, "SELECT DVDNUMBER FROM DVDLIST")
        or die (mysqli_error($dbconnect));
    } else {
      $query = mysqli_query($dbconnect, "SELECT DVDNUMBER FROM DVDLIST where MATCH(DVDTITLE) AGAINST('".$title."' IN BOOLEAN MODE)")
        or die (mysqli_error($dbconnect));
    }
    while ($row = mysqli_fetch_array($query)) {
      $newdvd=dvd::searchID($row['DVDNUMBER']);
      if(!is_null($newdvd->getId())) {
        $instance->addItem($newdvd);
      }
    }
    return $instance;
  }
  
  public function getList() {
    return $this->dvdlist;
  }
  
  public function addItem($dvd) {
    $this->dvdlist[]=$dvd;
  }
  
  public function delItem($id) {
    $instance = new self();
    $hostname = $this->dbhost;
    $username = "dvduser";
    $password = "mysqlboorce.mysql.database.azure.com";
    $db = "dvdbase";

    $title=addslashes($title);

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);
    $query = mysqli_query($dbconnect, "DELETE FROM DVDLIST where DVDNUMBER={$id}")
        or die (mysqli_error($dbconnect));
    
    
  }
  

}


?>
