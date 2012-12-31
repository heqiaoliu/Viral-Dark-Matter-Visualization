<?php

//////////////////////////////////////////////
// FILE:            Bacter.php
// ORIGINAL AUTHOR: Nick Turner
// PURPOSE:         
// NOTES:           
//////////////////////////////////////////////

class Bacter extends Model {

    const PS_GET_BACTERIA_ID_FROM_EXTERNAL_ID = '../SQL/ps_getBacteriaIdFromExternalId.php';
    const PS_GET_BACTERIA_ID = '../SQL/ps_getBacteriaId.php';
    //private $db = NULL;

    // Common methods

    public function __construct() {}

    /* CREATE */

    // Update a Bacteria
    public function createBacterium($bact_external_id, $bact_name, $vc_id, $vector, $genotype) {
        $sth = $this->db->prepare("INSERT INTO bacteria (bact_external_id, bact_name, vc_id, vector, genotype) VALUES ('$bact_external_id', '$bact_name', $vc_id, '$vector', '$genotype');");
        $sth->execute();
    }

    /* READ */
    // Read all bacteria
    public function readBacteria() {
        $sth = $this->db->query("SELECT bacteria_id, bact_external_id, bact_name, vc_id, vector FROM bacteria ORDER BY bact_external_id DESC");
        $sth->setFetchMode(PDO::FETCH_BOTH);
        $objs = $sth->fetchAll();
        return $objs;
    }

    // Read one bacteria
    // Parameter example - Name: Type: bact_external_id, EDT2235
    public function readBacterium($type, $name) {
        $sth = $this->db->query("SELECT bact_external_id, bact_name, vc_id, vector FROM bacteria WHERE $type='$name' LIMIT 1");
        $sth->setFetchMode(PDO::FETCH_BOTH);
        $row = $sth->fetch();
        return $row;
    }

    /* UPDATE */
    // Update a Bacteria
    public function updateBacterium($bact_external_id, $bact_name, $vc_id, $vector, $genotype, $type, $old) {
        $sth = $this->db->prepare("UPDATE bacteria SET bact_external_id='$bact_external_id', bact_name='$bact_name', vc_id='$vc_id', vector='$vector', genotype='$genotype' WHERE $type='$old';");
        $sth->execute();
    }

    ////////////////////////////////
    // New methods below.  Older 
    // methods should be deleted soon

    public function getBacteriaIdFromExternalId($bactid) 
    {
        try 
        {
            // Require ps_getBacteriaIdFromExternalId() current procudure
            require self::PS_GET_BACTERIA_ID_FROM_EXTERNAL_ID;

            $this->stmt->execute(array($bactid));
            $row = $this->stmt->fetch();
            return $row['bacteria_id'];
        }
        catch (PDOException $e) 
        {
            echo "There was a system error.<br>".$e->getMessage(); 
        }
    }

    public function getBacteriaId() 
    {
        try 
        {
            // Require ps_getBacteriaIdFromExternalId() current procudure
            require self::PS_GET_BACTERIA_ID;

            $this->stmt->execute();
            //create a json obj
            while ($row = $this->stmt->fetch())
            {
                //the json obj get each row
            }
            return $row['bacteria_id'];
        }
        catch (PDOException $e) 
        {
            echo "There was a system error.<br>".$e->getMessage(); 
        }
    }
}

?>