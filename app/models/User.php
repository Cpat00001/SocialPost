<?php

class User{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
    // register User in DB table users
    public function register(){
        $this->db->query("INSERT INTO users(name,email,password) VALUES(:name,:email,:password)");
    }

    // method to find a user with registered email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email',$email);

        $row = $this->db->single();

        //check row
        if($this->db->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}