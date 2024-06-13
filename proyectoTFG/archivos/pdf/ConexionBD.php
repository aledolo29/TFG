<?php
abstract class ConexionBD
{
    private static $server = 'domingueza@ruizgijon.ddns.net';
    private static $db = 'domingueza_general';
    private static $user = 'domingueza';
    private static $password = 'dOmingueza10';
    public static function connectDB()
    {
        try {
            $connection = new PDO("mysql:host=" . self::$server . ";dbname=" . self::$db .
                ";charset=utf8", self::$user, self::$password);
        } catch (PDOException $e) {
            echo "No se ha podido establecer conexión con el servidor de bases de datos.<br>";
            die("Error: " . $e->getMessage());
        }
        return $connection;
    }
}
