<?php

#**** Database Connection Class 
#***Author of the script
#***Name: Adeleke Ojora
#***Email : adeleke.ojora@firs.gov.ng
#***Date created: 09/06/2021
#***Date modified: 26/05/2022


#   The Database Connection class is used to establish connection to a data source. 

class databaseConnection 
{
    //declaring connection  variable
    protected $dbConnect;
    public $connectionStatus;
    protected $connectionError = '';

    //activating  connection
    function __construct($conType, $conParameters)
    {
        /** 
         * @param string conType Connection Type ('mysql' or 'mssql')
         * @param array conParameters Connection Parameters 
         *      For Example Connection Parameter could be
         *      array("host" => "myHost","username" => "myUserName", 
         *      "password" => "myPassword", "dbname" =>"myDatabase")
        **/

        if (is_array($conParameters)) {
            if (strtolower($conType) == 'mssql') {
                $msSQLExtras = array("ReturnDatesAsStrings" => true);
                $dbHost = array_shift($conParameters);
                $dbString = array_push($conParameters, $msSQLExtras);
                $this->connectionStatus = $this->connectMSSQL($dbHost, $conParameters);
            } elseif (strtolower($conType) == 'mysql') {
                $dbHost = array_shift($conParameters);
                $this->connectionStatus = $this->connectMySQL($dbHost, $conParameters);
            } else {
                $this->connectionStatus = "Invalid Connection Parameters passed!";
            }
        } else {
            $this->connectionStatus = "Invalid Connection Parameters passed!";
        }
    }

    // connect to mySQL Data source
    protected function connectMySQL($host, $parameters)
    {
        try {
            //initializing  connection
            $this->dbConnect = new mysqli($host, 
                $parameters['username'],$parameters['password'],$parameters['dbname']);
            $connected = true;
        } catch (Exception $e) {
            $this->connectionError = "Unable to connect to mySQL database: " . $e->getMessage();
            $connected = false;
        }
        return $connected;
    }

    // connect to MSSQL Data source
    protected function connectMSSQL($host, $parameters)
    {
        $serverName = "sqlsrv:server=$host; Database=" . $parameters['dbname']; 
        $user = $parameters['username'];
        $pwd = $parameters['password']; 
        try {
            $this->dbConnect = new PDO("$serverName", "$user", "$pwd");
            $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connected = true;
        } catch (Exception $e) {
            $this->connectionError = "Unable to connect to MSSQL database: " . $e->getMessage();
            $connected = false;
        }
        return $connected;
    }

    //this captures the current db connection
    function connect()
    {
        return $this->dbConnect;
    }

    function closeConnection()
    {
        $this->dbConnect = null;
        return true;
    }

    //this returns the current connection Error if there exist any
    function connectionError()
    {
        return $this->connectionError;
    }

    function isLastConnectSuccessful(){
        return $this->connectionStatus;
    }

}
