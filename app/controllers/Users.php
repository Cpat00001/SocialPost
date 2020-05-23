<?php
    class Users extends Controller{
        public function __construct(){
            $this->userModel = $this->model('User');
        }
        public function register(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //process method
                //Sinitize POST data
                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
                //Init data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_error' => ''
                ];
                //check email exists and validate email
                if(empty($data['email'])){
                    $data['email_err'] = 'Please provide an email';
                }else{
                    if($this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Sorry email already in use';
                    }
                }
                //check name exists and validate name
                if(empty($data['name'])){
                    $data['name_err'] = 'Please provide a name';
                }
                //check and validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter a password';
                }
                //check password if exists and min length 6 characters
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                }elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be minimum 6 characters';
                }
                //check for confirm password
                if(empty($data['confirm_password'])){
                    $data['confirm_password_error'] = 'Please confirm password';
                }else{
                if(($data['password'] != $data['confirm_password'])){
                    $data['confirm_password_error'] = "Password doesn't match";
                 }
                }
                //make sure that theres no errors
                if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err'])
                && empty($data['confirm_password_error'])){
                    
                    //No errors in input fields - proceed with form
                    //HASH password
                    $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);
                    //Register User
                    if($this->userModel->register($data)){
                        flash('register_success','You are registered and you can log in');
                        redirect('users/login');
                    }else{
                        die('Something went WRONG');
                    }
                }else{
                    //Load view if there are any error
                    $this->view('users/register', $data);
                }
            }else{
                //Init data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_error' => ''
                ];
                //Load view
                $this->view('users/register', $data);
            }
        }
        public function login(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //process method
                //sanitize $_POST
                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => ''    
                ];
                // check if $data['email'] submitted
                if(empty($data['email'])){
                    $data['email_err'] = 'Please provide an email';
                }
                //check if password id submitted
                if(empty($data['password'])){
                    $data['password_err'] = 'Password not provided';
                }
                //check for user/email
                if($this->userModel->findUserByEmail($data['email'])){
                    //User found
                }else{
                    $data['email_err'] = 'No user found';
                }
                //check if errors are empty if yes->proceed with form/ if NOT display errors
                if(empty($data['email_err']) && empty($data['password_err'])){
                        // check and set logged in user
                        $loggedInUser = $this->userModel->login($data['email'],$data['password']);

                        if($loggedInUser){
                            //create a session
                            $this->createUserSession($loggedInUser);
                        }else{
                            $data['password_err'] = 'Password incorrect';

                            //load view z incorrect password
                            $this->view('users/login', $data);
                        }
                }else{
                    $this->view('users/login', $data);
                }
            }else{
                //Init data
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => ''    
                ];
                //Load view
                $this->view('users/login', $data);
            }
        }
        //method to create user session
        public function createUserSession($user){
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            redirect('posts');
        }
        //destroy session and logout/redirect
        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            redirect('users/login');
        }
    }
