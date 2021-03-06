<?php

class User{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
    // register User in DB table users
    public function register($data){
        $this->db->query("INSERT INTO users(name,email,password) VALUES(:name,:email,:password)");
        //bind values
        $this->db->bind(':name',$data['name']);
        $this->db->bind(':email',$data['email']);
        $this->db->bind(':password',$data['password']); 

        //execute query
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
    //Login user
    public function login($email,$password){
        //create DB query
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        $hashed_password = $row->password;

        if(password_verify($password,$hashed_password)){
            return $row;
        }else{
            return false;
        }
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
    // method to find a user by given user_id from $post->user_id
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id',$id);

        $row = $this->db->single();
        return $row;
    }
}