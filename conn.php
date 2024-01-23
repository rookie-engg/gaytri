<?php

// require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class DatabaseConnection
{
  private $host;
  private $username;
  private $password;
  private $database;
  public $conn;

  public function __construct()
  {
    // $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..');
    // $dotenv->load();

    $this->host = 'localhost';
    $this->username = 'root';
    $this->password = '';
    $this->database = 'gaytri';

    $conn = new mysqli(
      $this->host,
      $this->username,
      $this->password,
      $this->database
    );

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $this->conn = $conn;
  }
}


// Instantiate the DatabaseConnection class with your database credentials
$dbConnection = new DatabaseConnection();
