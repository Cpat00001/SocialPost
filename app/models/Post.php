<?php

class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
//bring posts from DB table posts
public function getPosts(){
    $this->db->query('SELECT *,
                     posty.id as postId,
                     users.id as userId,
                     posty.created_at as postCreated,
                     users.created_at as userCreated
                     FROM posty
                     INNER JOIN users
                     ON posty.user_id = users.id
                     ORDER BY posty.created_at DESC 
                      ');
    $results = $this->db->resultSet();
    return $results;
    }

    public function addPost($data){
        $this->db->query("INSERT INTO posty(title,user_id,body) VALUES(:title,:user_id,:body)");
        //bind values
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':body',$data['body']); 

        //execute query
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
    // updatePost
    public function updatePost($data){
        $this->db->query("UPDATE posty SET title = :title, body = :body WHERE id = :id");
        //bind values
        $this->db->bind(':id',$data['id']);
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':body',$data['body']); 

        //execute query
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function getPostById($id){
        $this->db->query('SELECT * FROM posty WHERE id = :id');
        $this->db->bind(':id',$id);

        $row = $this->db->single();
        return $row;
    }
    public function deletePost($id){
        $this->db->query('DELETE FROM posty WHERE id = :id');
        $this->db->bind(':id',$id);

        //execute query
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}