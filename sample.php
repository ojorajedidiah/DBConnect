<?php
include('databaseConnection.class.php');


try {
  $errMsg='';
  $type='mysql';
  $param=array("host" => "myHost","username" => "myUserName", "password" => "myPassword", "dbname" =>"myDatabase");
  $db=new databaseConnection($type,$param);
  if ($db->isLastConnectSuccessful()) {
    $con = $db->connect();
    $sql = "SELECT * FROM messages WHERE msg = 'sample'";
    $stmt = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    foreach ($stmt->fetchAll() as $row) {
      $rtn = false;
      $errMsg.="This Message already exist!";
    }
  } else {
    $errMsg.=$db->connectionError();
  }
  $db->closeConnection();
} catch (PDOException $e) {
  $errMsg.=$db->connectionError();
}

