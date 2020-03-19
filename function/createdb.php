<?php

class crdb extends db{
    public $table;
    public $col;

    function set($table){
        $this->table=$table;
        return $this;
    }
    function col($col){
        $this->col=$col;
        return $this;
    }

    function create(){
        $table = ucfirst($this->table);
        $sql="CREATE TABLE ".$table." (
            ".$table."Id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ".$table."Name VARCHAR(255) NOT NULL UNIQUE,
            ".$table."Description VARCHAR(255),
            ".$table."Option INT(2),
            ".$table."CreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
            ".$table."UpdateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";
        
        if ($this->query($sql)) {
            # code...
            $_SESSION['db'][]="Create table".$table;
            dblogs($sql);
        } 
    }

    function add($value="VARCHAR(45) NULL"){
        $name = ucfirst($this->col);
        $table = ucfirst($this->table);
        $sql = "ALTER TABLE ".$table."
                ADD COLUMN `".$table.$name."` ".$value." AFTER `".$table."Decription`";
        
        if ($this->query($sql)) {
            # code...
            dblogs($sql);
        } 

        }

    function pk($name){
        $name = ucfirst($name);
        $table = ucfirst($this->table);
        $sql = "ALTER TABLE ".$table."
                ADD COLUMN `".$name."Id` INT(6) UNSIGNED NOT NULL AFTER `".$table."Decription`";
        echo $sql2 = "ALTER TABLE ".$table." ADD CONSTRAINT fk_".$this->table."_".$name." FOREIGN KEY(".$name."Id) REFERENCES ".$name."(".$name."Id)";
        if ($this->query($sql)) {
            # code...
            dblogs($sql);
        } 

            if ($this->query($sql2)) {
                # code...
                
                dblogs($sql2);
            } 
    
            }

    function change($value){
        $name = ucfirst($this->col);
        $table = ucfirst($this->table);
        $sql = "ALTER TABLE ".$table." CHANGE COLUMN `".$table.$name."` `".$table.$name."` ".$value." ;";
        if ($this->query($sql)) {
            # code...
            dblogs($sql);
        } 
        }

    function insert($sql){
        if ($this->query($sql)) {
            $_SESSION['db'][]=$sql;
            dblogs($sql);
        }
    }    

    function gettype(){
        $sql = "SELECT * 
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE 
             TABLE_NAME = '".$this->table."' AND 
             COLUMN_NAME = '".$this->col."'";

        $kq = $this->query($sql)->fetchAll();
        return $kq;
    }
    
}



//CHANGE COLUMN `UsersPassWorld` `UsersPassWord` VARCHAR(100) NOT NULL ;

// ALTER TABLE `core`.`users` 
// DROP INDEX `UsersName` ;
// ;

// ADD UNIQUE INDEX `UsersName_UNIQUE` (`UsersName` ASC) VISIBLE,

//CHANGE COLUMN `UsersName` `UsersName` VARCHAR(50) NULL ;
//CHANGE COLUMN `UsersDecription` `UsersDecription` VARCHAR(100) NOT NULL ;
//ALTER TABLE Users ADD CONSTRAINT fk_group FOREIGN KEY(groupid) REFERENCES Groups(groupid);
//ADD COLUMN `s` VARCHAR(45) NULL AFTER `UsersUpdateDate`,