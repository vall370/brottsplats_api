<?php 
    class DbConnect{
        private $con;


        function connect(){

            include_once dirname(__FILE__)  . '/Constants.php';
            error_reporting(E_ALL);
            $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

            mysqli_set_charset($this->con ,'utf8');
            
            if(mysqli_connect_errno()){

                echo "Failed  to connect " . mysqli_connect_error(); 

                return null; 

            }
            return $this->con; 

        }
    }