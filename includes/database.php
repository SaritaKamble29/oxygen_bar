<?php
require_once(LIB_PATH_INC . DS . "config.php");

class MySqli_DB {

    private $con;
    public $query_id;

    function __construct() {
        $this->db_connect();
    }

    /* Open database connection */
    public function db_connect()
    {
        $this->con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, '', 3307);

        if (!$this->con) {
            die("Database connection failed: " . mysqli_connect_error());
        }
        
        $select_db = mysqli_select_db($this->con, DB_NAME);
        if (!$select_db) {
            die("Failed to select database: " . mysqli_error($this->con));
        }
    }

    /* Close database connection */
    public function db_disconnect()
    {
        if (isset($this->con)) {
            mysqli_close($this->con);
            unset($this->con);
        }
    }

    /* Perform a database query */
    public function query($sql)
    {
        if (trim($sql) != "") {
            $this->query_id = $this->con->query($sql);
        }

        if (!$this->query_id) {
            die("Error on this Query: <pre>" . $sql . "</pre>");
        }

        return $this->query_id;
    }

    /* Query helper functions */
    public function fetch_array($statement)
    {
        return mysqli_fetch_array($statement);
    }

    public function fetch_object($statement)
    {
        return mysqli_fetch_object($statement);
    }

    public function fetch_assoc($statement)
    {
        return mysqli_fetch_assoc($statement);
    }

    public function num_rows($statement)
    {
        return mysqli_num_rows($statement);
    }

    public function insert_id()
    {
        return mysqli_insert_id($this->con);
    }

    public function affected_rows()
    {
        return mysqli_affected_rows($this->con);
    }

    /* Escape special characters */
    public function escape($str)
    {
        return $this->con->real_escape_string($str);
    }

    /* While loop helper */
    public function while_loop($loop)
    {
        $results = array();
        while ($result = $this->fetch_array($loop)) {
            $results[] = $result;
        }
        return $results;
    }
}

$db = new MySqli_DB();
?>
