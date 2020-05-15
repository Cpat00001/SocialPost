<?php
  class Pages extends Controller {
    public function __construct(){
    }
    public function index(){
      $data = [
        'title' => 'Welcome to dzielposty App',
        'description'=>'Simple social network based on MVC Framework'
      ];
     
      $this->view('pages/index', $data);
    }

    public function about(){
      $data = [
        'title' => 'About Us',
        'description'=>'Aplikacja do dzielenia sie postami z innymi uzytkownikami'
      ];

      $this->view('pages/about', $data);
    }
  }
