<?php
class User extends Model 
{

    // A prepared statement for use in functions
    protected $stmt = NULL;



    public function __construct() 
    {
        return 'Class User loaded successfully <br />';
    }

    public function getName($name) 
    {
        $sth = $this->db->query("SELECT name FROM vdm_users WHERE vdm_users.username = '$name'");
        $sth->setFetchMode(PDO::FETCH_BOTH);
        $row = $sth->fetch();
        return $row;
    }


    // Get the name of the current User
    public function getCurrentName() 
    {
        try 
        {
            // Require getCurrentName() current procudure
            require '../SQL/ps_getCurrentName.php';

            $this->stmt->execute(array($_SESSION['username']));
            $row = $this->stmt->fetch();
            return $row['name'];
        }
        catch (PDOException $e) 
        {
            echo "There was a system error.<br>".$e->getMessage(); 
        }
        return $name;
    }

}

?>