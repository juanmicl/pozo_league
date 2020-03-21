<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->helper('session');
        
        $this->load->model('Users_model');
    }

	public function login()
	{
        if(isLoggedIn()){
            header('Location: /dashboard');
            exit;
        }

        $data = [
            'title' => 'Login',
            'loggedIn' => isLoggedIn()
        ];

        if($this->input->post())
        {
            if(checkRecaptcha($_POST["g-recaptcha-response"]))
            {
                // Check empty fields
                if(!$_POST['user'] || !$_POST['pass']){
                    $data['error'] = "Please enter all fields";
                }

                // Check username in db
                if($this->Users_model->countUser($_POST['user']) == 0){
                    $data['error'] = 'Username or Password invalid';
                }

                // Check password
                $hash = $this->Users_model->getUserPass($_POST['user']);
                if (!password_verify($_POST['pass'], $hash)){
                    $data['error'] = 'Username or Password invalid';
                }

                // Check banned
                if($this->Users_model->getUserRankByUsername($_POST['user']) == -1){
                    $data['error'] = 'You are banned';
                }

                // Generate token and log in
                if(empty($data['error'])){
                    $token = $this->genToken();
                    $this->Users_model->setUserToken($_POST['user'], $token);
                    setcookie("token", $token, time() + 720000);
                    header('Location: /');
                    exit;
                }
            } else {
                $data['error'] = 'You need to complete Captcha';
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('auth/login');
        $this->load->view('templates/footer');
    }



    public function register()
	{
        if(isLoggedIn()){
            header('Location: /dashboard');
            exit;
        }

        $data = [
            'title' => 'Register',
            'loggedIn' => isLoggedIn()
        ];

        if($this->input->post())
        {
            if(checkRecaptcha($_POST["g-recaptcha-response"]))
            {
                // Check empty fields
                if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['pass']) || empty($_POST['rpass'])){
                    $data['error'] = "Completa todos los campos";
                }

                //Compare first to second password
                if ($_POST['pass'] != $_POST['rpass']){
                    $data['error'] = 'Las contraseñas no coinciden';
                }

                // Check if the username is legit
                if (!ctype_alnum($_POST['username']) || strlen($_POST['username']) < 4 || strlen($_POST['username']) > 25){
                    $data['error'] = 'El nombre de usuario solo puede contener números y letras con una longitud de entre 4-25 caracteres';
                }

                // Validate email
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                    $data['error'] = 'Ingresa un email válido';
                }

                // Check username in db
                if ($this->Users_model->countUser($_POST['username']) > 0){
                    $data['error'] = 'Este nombre de usuario ya está registrado';
                }

                // Check email in db
                if ($this->Users_model->countEmail($_POST['email']) > 0){
                    $data['error'] = 'Este email ya está registrado';
                }

                // Register new user on db
                if (empty($data['error'])) {
                    var_dump($this->input->post());
                    $hash = password_hash($_POST['pass'], PASSWORD_BCRYPT);
                    $this->Users_model->setUser($this->input->post('username'), $hash, $this->input->post('email'));
                    header('Location: /login');
                    exit;
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('auth/register');
        $this->load->view('templates/footer');
    }

    public function logout()
    {
        unset($_COOKIE['token']);
        setcookie("token", "", time() + 720000);
        header('location: /');
    }

    private function genToken()
    {
        return substr(str_shuffle(password_hash(microtime(), PASSWORD_BCRYPT)), 0, 50);
    }
}