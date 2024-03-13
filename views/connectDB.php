<?php
class connectDB
{
    function getConnection()
    {
        $servername = "localhost";
        $username = "zaplpszw_joe";
        $password = "@Z97718015c";
        $database = "zaplpszw_lottery";
        $port="2083";
        try {
            $conn = new PDO("mysql:host=$servername;port=$port;dbname=$database;charset=utf8", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            return null;
        }
    }

    function createJson($data, $message, $state)
    {
        $createJSON = array(
            "data" => $data,
            "message" => $message,
            "state" => $state
        );
        echo json_encode($createJSON);
    }
}
