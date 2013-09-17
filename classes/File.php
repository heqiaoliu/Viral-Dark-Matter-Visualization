<?php
class File {

    const PS_INPUT_FILE = '../SQL/ps_inputFile.php';
    const PS_GET_FILE_ID_FROM_FILE_NAME = '../SQL/ps_getFileIdFromFileName.php';
    const PS_GET_REPLICATE = '../SQL/ps_getReplicate.php';
    const PS_LIST_OLD_FILES = '../SQL/ps_listOldFiles.php';
    private $db = NULL;

    // Common methods
    
    public function __construct() {}

    public function setDatabaseConnection($databaseConnection) {
        $this->db = $databaseConnection;
    }

    /* CREATE */
    // Create a File
    public function createFile($file_name, $name, $exp_date, $upload_date, $bacteria_id, $replicate_num, $notes) {
        $sth = $this->db->prepare("INSERT INTO file (file_name, name, exp_date, upload_date, bacteria_id, replicate_num, notes) VALUES ( '$file_name', '$name', '$exp_date', '$upload_date', '$bacteria_id', '$replicate_num', '$notes' );");
        $sth->execute();
    }

    /* READ */
    // Read a File 1, No Duplicates!
    public function readFile($type, $name) {
        $sth = $this->db->query("SELECT file_id, file_name, name, exp_date, upload_date, bacteria_id, replicate_num, notes FROM file WHERE $type='$name' LIMIT 1;");
        $sth->setFetchMode(PDO::FETCH_BOTH);
        $row = $sth->fetch();
        return $row;
    }

    // Read a File, Duplicates allowed.
    public function readFiles_getRowsAffected($type, $name) {
        $sth = $this->db->query("SELECT file_id, file_name, name, exp_date, upload_date, bacteria_id, replicate_num, notes FROM file WHERE $type='$name';");
        $rows_affected = $sth->rowCount();
        return $rows_affected;
    }

    // Read Files
    public function readFiles() {
        $sth = $this->db->query("SELECT file_id, file_name, name, exp_date, upload_date, bacteria_id, replicate_num, notes FROM file;");
        $sth->setFetchMode(PDO::FETCH_BOTH);
        $obj = $sth->fetchAll();
        return $obj;
    }

    // Read Files with REFerences, bact_external_id (More useful) instead of bacteria_id.  Joins file with bacteria table for select.  
    public function readFiles_getRef() {
        $sth = $this->db->query("SELECT f.file_id, f.file_name, f.name, f.exp_date, f.upload_date, b.bact_external_id, f.replicate_num, f.notes FROM file f INNER JOIN bacteria b on b.bacteria_id=f.bacteria_id;");
        $sth->setFetchMode(PDO::FETCH_BOTH);
        $objs = $sth->fetchAll();
        return $objs;
    }

    /* UPDATE */
    // Update a File
    public function updateFile($file_name, $name, $exp_date, $upload_date, $bacteria_id, $replicate_num, $notes, $type, $old) {
        $sth = $this->db->prepare("UPDATE file SET file_name='$file_name', name='$name', exp_date='$exp_date', upload_date='$upload_date', bacteria_id='$bacteria_id', $replicate_num='$replicate_num', notes='$notes' WHERE medium_File_name='$old_medium_File_name';");
        $sth->execute();
    }

    // Update a file when you only have the bact_external_id instead of the bact_id (safer this way). Finds the row to update according to file_id rather than file_name
    public function updateFileRef($file_name, $name, $exp_date, $upload_date, $bacteria_id, $replicate_num, $notes, $type, $old) {
        $sth = $this->db->prepare("UPDATE file f SET f.file_name='$file_name', f.name='$name', f.exp_date='$exp_date', f.upload_date='$upload_date', f.bacteria_id='b.bacteria_id', f.replicate_num='$replicate_num', f.notes='$notes' FROM file f INNER JOIN bacteria b ON b.bacteria_id=f.bacteria_id WHERE f.file_id=$file_id;");
        $sth->execute();
    }

    /* DELETE */
    // Delete a File
    public function deleteFile($file_name) {
        $sth = $this->db->prepare("DELETE FROM file WHERE file_name='$file_name';");
        $sth->execute();
        return $sth->rowCount();
    }

    ////////////////////////////////
    // New methods below.  Older 
    // methods should be deleted soon

    public function inputFile($fileName, $name, $expDate, $bacteriaId, $notes) 
    {
        try 
        {
            // Require ps_getBacteriaIdFromExternalId() current procedure
            require self::PS_INPUT_FILE;
            
            $this->stmt->bindParam(':fileName', $fileName);
            $this->stmt->bindParam(':name', $name);
            $this->stmt->bindParam(':expDate', $expDate);
            $this->stmt->bindParam(':bacteriaId', $bacteriaId);
            $this->stmt->bindParam(':notes', $notes);
            $this->stmt->execute();

            require self::PS_GET_FILE_ID_FROM_FILE_NAME;
            $this->stmt->execute(array($fileName));
	    $row=$this->stmt->fetch();
	    return $row['file_id'];
            //return $row['file_id'];
        }
        catch (PDOException $e) 
        {
        }
            echo "There was a system error inputting the file into the database.  Please alert your system administrator. <br>".$e->getMessage()."<br />"; 
    }

    public function getReplicate($bactid)
    {
        try
        {
            require self::PS_GET_REPLICATE;

            $this->stmt->execute(array($bactid));
            $row = $this->stmt->fetch();
            echo '    row: '.print_r($row).'    ';
            return $row['replicate_num'];
        }
        catch (PDOException $e)
        {
            echo "There was a system error creating the replicate number.  Please alert your system administrator. <br>".$e->getMessage()."<br />";
        }
    }

    // This function was used to migrate old database to new database.  9/23/2012
    public function listOldFiles()
    {
        try
        {
            require self::PS_LIST_OLD_FILES;

            $this->stmt->execute();

            echo '<table cellpadding="10">';

            while($row = $this->stmt->fetch())
            {
                //echo '    row: '.print_r($row).'    '; 
                echo '<tr>';
                echo '<td>'.$row['file_id'].'</td>'.'<td>'.$row['file_name'].'</td>'.'<td>'.$row['name'].'</td>'.'<td>'.$row['exp_date'].'</td>'.'<td>'.$row['plate_name'].'</td>'.'<td>'.$row['upload_date'].'</td>'.'<td>'.$row['bact_external_id'].'</td>'.'<td>'.$row['replicate_num'].'</td>'.'<td>'.$row['notes'].'</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        catch (PDOException $e)
        {
            echo "WHAT ARE YOU DOING WRONG??? JEEZUS. <br>".$e->getMessage()."<br />";
        }
    }

}


?>
