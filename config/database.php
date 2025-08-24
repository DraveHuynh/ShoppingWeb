<?php 
    function connection() {
        $servername = "localhost";
        $username = "phpuser";
        $password = "123456";
        $dbname = "web_banhang";
 
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
