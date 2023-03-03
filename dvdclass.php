<?php

Class dvd {

  public $id=0;
  public $title="Titre vide";
  public $meuble="ETAGERE";
  public $etagere=0;
  public $rang=0;
  public $niveau=0;

  private $dbhost="mysqlboorce.mysql.database.azure.com";

  private $initialized=false;
  private $changed=false;
  private $saved=false;

  public function getId() { return $this->id; }
  public function getTitle() { return $this->title; }
  public function getMeuble() { return $this->meuble; }
  public function getEtagere() { return $this->etagere; }
  public function getRang() { return $this->rang; }
  public function getRangStr() {
    if( $this->rang==1 ) {
      return "arrière";
    } elseif ($this->rang==2) {
      return "avant";
    } else {
      return "non défini";
    }
  }
  public function getNiveau() { return $this->niveau; }
  public function getNiveauStr() {
    if( $this->niveau==1 ) {
      return "bas";
    } elseif ($this->niveau==2) {
      return "haut";
    } else {
      return "non défini";
    }
  }
  
  public static function searchID($sid) {
    $instance = new self();
    $instance->getFromDBId($sid);
    return $instance;
  }
  
  public static function searchTitle ($stitle) {
    $instance = new self();
    $instance->getFromDBTitle($stitle);
    return $instance;  
  }

  protected function getFromDBId($sid) {
    $request="dvd.DVDNUMBER=".$sid;
    $this->getFromDB($request);
  }
  
  protected function getFromDBTitle($stitle) {
    $request="dvd.DVDTITLE='".$stitle."'";
    $this->getFromDB($request);
  }
  
  protected function getFromDB($request) {
    $hostname = $this->dbhost;
    $username = "dvduser";
    $password = "Al@3#45jh8lapI@89";
    $db = "dvdbase";

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);

    if ($dbconnect->connect_error) {
      die("Database connection failed: " . $dbconnect->connect_error);
    }
    
    $query = mysqli_query($dbconnect, "SELECT dvd.DVDTITLE, dvd.DVDNUMBER, pos.MEUBLE, pos.ETAGERE, pos.RANGEE, pos.NIVEAU FROM DVDLIST dvd, DVDPOSITION pos where pos.MEUBLE=dvd.DVDCATEGORY and dvd.DVDNUMBER>=pos.POSDEBUT and dvd.DVDNUMBER<=pos.POSFIN and ".$request)
    or die (mysqli_error($dbconnect));
    $row = mysqli_fetch_array($query);
    $this->fillData($row);
  }
  
  public static function newDvd() {
    $instance = new self();
    $hostname = $this->dbhost;
    $username = "dvduser";
    $password = "Al@3#45jh8lapI@89";
    $db = "dvdbase";

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);

    if ($dbconnect->connect_error) {
      die("Database connection failed: " . $dbconnect->connect_error);
    }
    
    $query = mysqli_query($dbconnect, "SELECT max(dvd.DVDNUMBER) FROM DVDLIST dvd")
    or die (mysqli_error($dbconnect));
    $row = mysqli_fetch_array($query);
    
    $instance->id=$row[0]+1;
    $instance->etagere='ETAGERE';
    return $instance;
  }
  
  protected function fillData($row) {
    $this->id=$row['DVDNUMBER'];
    $this->title=$row['DVDTITLE'];
    $this->meuble=$row['MEUBLE'];
    $this->etagere=$row['ETAGERE'];
    $this->rang=$row['RANGEE'];
    $this->niveau=$row['NIVEAU'];
    $this->initialized=true;
  }
  
  public function setId($newid) {
    $this->id=$newid;
    $this->changed=true;
  }

  public function setTitle($newTitle) {
    $this->title=$newTitle;
    $this->changed=true;
  }

  public function setMeuble($newMeuble) {
    $this->meuble=$newMeuble;
    $this->changed=true;
  }

  private function initPos() {
    $hostname = $this->dbhost;
    $username = "dvduser";
    $password = "Al@3#45jh8lapI@89";
    $db = "dvdbase";

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);

    if ($dbconnect->connect_error) {
      die("Database connection failed: " . $dbconnect->connect_error);
    }
    $query = mysqli_query($dbconnect, "SELECT pos.MEUBLE, pos.ETAGERE, pos.RANGEE, pos.NIVEAU FROM DVDPOSITION pos where pos.MEUBLE=\"".$this->meuble."\" and ".$this->id.">=pos.POSDEBUT and ".$this->id."<=pos.POSFIN")
    or die (mysqli_error($dbconnect));
    while ($row = mysqli_fetch_array($query)) {
      $this->etagere=$row['ETAGERE'];
      $this->rangee=$row['RANGEE'];
      $this->niveau=$row['NIVEAU'];
    }
    $this->changer=true;
  }
  
  public function pushAdd() {
    $this->initPos();
    $hostname = $this->dbhost;
    $username = "dvduser";
    $password = "Al@3#45jh8lapI@89";
    $db = "dvdbase";

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);

    if ($dbconnect->connect_error) {
      die("Database connection failed: " . $dbconnect->connect_error);
    }
    $query = mysqli_query($dbconnect, "insert into DVDLIST (DVDNUMBER, DVDTITLE,DVDCATEGORY,DVDPOSITION) values ({$this->id},\"{$this->title}\",\"{$this->meuble}\",0)")
    or die (mysqli_error($dbconnect));
    
  }

  public function pushEdit() {
    $this->initPos();
    $hostname = $this->dbhost;
    $username = "dvduser";
    $password = "Al@3#45jh8lapI@89";
    $db = "dvdbase";

    $dbconnect=mysqli_connect($hostname,$username,$password,$db);

    if ($dbconnect->connect_error) {
      die("Database connection failed: " . $dbconnect->connect_error);
    }
    $query = mysqli_query($dbconnect, "update DVDLIST set DVDTITLE=\"{$this->title}\", DVDCATEGORY=\"{$this->meuble}\" where DVDNUMBER={$this->id}")
    or die (mysqli_error($dbconnect));
    
  }

  
}

?>
