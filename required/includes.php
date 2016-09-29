<!-- In this lesson, we want to create a couple more methods so that we can make queries to the db -->
<?php
    class Database
    {
        private $host   = 'localhost';
        private $user   = 'root';
        private $pass   = 'root';
        private $dbname = 'myBlog';
        private $dbh;
        private $error;
        private $stmt;
        public $rowNum;

        public function __construct()
        {
            // Set DSN
            $dsn = 'mysql:host='. $this->host . ';dbname='. $this->dbname;
            // Set Options
            $options = array
            (
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            // Create new PDO
            try
            {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            }
            catch(PDOException $e)
            {
                $this->error = $e->getMessage();
            }
        }

        public function query($query)
        {
            //then we have to set our prepared statement
            $this->stmt = $this->dbh->prepare($query);
        }

        //this function will bind our data
        //In order to prepare our SQL queries, we need to bind the inputs with the placeholders we put in place.
        public function bind($param, $value, $type = null){
            //then we are going to check to see if the type is null and pass in the type
            if(is_null($type))
            {
                //then create a switch statement
                switch(true)
                {
                    //now, this is where we are going to check to see if our data is an integer, a boolean or null
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                    //all we are doing here is just checking what type of data is being passed so that it goes into the //database as that type of value
                }
            }

            $this->stmt->bindValue($param, $value, $type);
            //once we have completed this line, it ends the construction of our bind method
        }

        //this will execute the prepared function
        public function execute()
        {
            return $this->stmt->execute();
        }

        public function lastInsertId()
        {
            return $this->dbh->lastInsertId();
        }

        //fetch the data
        public function resultset()
        {
            $this->execute();

            if($this->stmt->rowCount() == 1)
            {
                $this->rowNum = 1;
                return $this->stmt->fetch(PDO::FETCH_ASSOC);
            }
            else
            {
                $this->rowNum = 0;
                return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }