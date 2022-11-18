
<?php
include('databaseConnection.class.php');

class movementRecords extends databaseConnection
{
  // declare properties
  private $rec = array();
  private $recordStatus;

  function fetchAllRecords()
  {
  }

  function fetchSpecificRecord($condition)
  {
    $tmp = 0;
    $result = $this->dbConnect->query($this->getSELECTStatement($condition));
    // $this->rec=$result->fetch_assoc();

    while ($rw = $result->fetch_assoc()) {
      $tmp = array_push($this->rec, $rw);
    }
    $this->recordStatus = ($tmp > 0) ? "RIF" : "RNF";
  }

  public function isLastQuerySuccessful()
  {
    return ($this->recordStatus == 'RIF') ? true : false;
  }

  public function getRecordset()
  {
    return $this->rec;
  }

  private function getSELECTStatement($cn = '')
  {
    $rtn = "SELECT * FROM movement m LEFT JOIN assessment i ON m.audID=i.audID ";

    $rtn .= ($cn != '') ? ' WHERE ' . $cn : '';
    return $rtn;
  }
}

?>