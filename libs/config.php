<?php
namespace Todolist;

use \PDO;

class Config
{
    /**
     * @var null Database Connection
     */
    public $db      = null;
    public $dbtype  = 'mysql';
    public $dbhost  = 'localhost';
    public $dbname  = 'todolist';
    public $dbuser  = 'root';
    public $dbpass  = '';
    /**
     * Controller is created, open a database connection.
     */
    function __construct($db)
    {
        $this->openConnection();
    }
    
    private function openConnection()
    {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $this->db = new PDO($this->dbtype . ':host=' . $this->dbhost . ';dbname=' . $this->dbname, $this->dbuser, $this->dbpass, $options);
    }

    /**
     * @param string $modelName The name of the model
     * @return object model
     */
    public function loadModel($modelName)
    {
        //require 'app/models/' . strtolower($modelName) . '.php';
        $model = "Todolist\Models\\".$modelName;
        // return new model (and pass the database connection to the model)
        return new $model($this->db);
    }
}
