<?php

    interface dbContract {
        public function insert($data) ;
        public function update($data) ; 
        public function select($column = "*") ; 
        public function delete() ;
        public function exicute() ;
    }

    class MysqlDB implements dbContract {

        private $connection ;
        private $sql ;
        private $table ; 

        public function __construct($host,$user,$password,$db,$table) {
            $this->connection = mysqli_connect($host,$user,$password,$db) ; 
            $this -> table = $table ;
        }

        public function insert($data) {
            $columns = "" ; 
            $values = "" ;
            foreach( $data as $column => $value ) {
                $columns .= " `$column` ," ; 
                $values .= " '$value' ," ;
            }
            $columns = rtrim($columns,',') ;
            $values = rtrim($values,',') ;

            $this -> sql = "INSERT INTO $this->table ($columns) VALUES ($values) " ;

            return $this ;
        }

        public function update($data) {

            $rows = "" ;
            foreach( $data as $column => $value ) {
                $rows .= " `$column` = '$value' ," ;
            }
            
            $rows = rtrim($rows,',') ;

            $this -> sql = "UPDATE TABLE $this->table SET $rows " ;

            return $this ;
        }

        public function select($column = '*') {

            $this -> sql = "SELECT $column FROM $this->table" ;
            
            return $this ;

        }

        public function delete() {

            $this -> sql = "DELETE FROM $this -> table" ;
            return $this ;
        }

        public function exicute() {
            mysqli_query($this->connection,$this -> sql ) ;
            return mysqli_affected_rows($this -> connection ) ;
        }

        public function get() {

            $query = mysqli_query($this->connection,$this->sql) ; 

            return mysqli_fetch_assoc($query) ;
        }

        public function all() {

            $query = mysqli_query($this->connection,$this->sql) ; 

            return mysqli_fetch_all($query,MYSQLI_ASSOC) ;

        }

        public function join($type,$table,$pk,$fk) {

            $this -> sql = " $type JOIN $table $pk = $fk " ;
            
            return $this ;
        }
    }

