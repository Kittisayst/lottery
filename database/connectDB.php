<?php
class connectDB
{
    function getConnection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "db_lottery";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
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
