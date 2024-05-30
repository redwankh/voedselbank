<?php
// een sessie beginngen voor inloggen nodig
session_start();

$hostname = "localhost";
$dbname     = "voedselbank";

$username   = 'root';
$password   = '';

try
{
    // een connectie met database maken
    $pdo = new PDO( 'mysql:host=' . $hostname . ';dbname=' . $dbname, $username, $password );

}
catch ( PDOException $e )
{
    echo 'Connection failed: ' . $e->getMessage();
}