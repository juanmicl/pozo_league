<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Players extends CI_Controller {

	function __construct()
    {
		parent::__construct();
        $this->load->helper('session');
        $this->load->model('Users_model');
        $this->load->model('Players_model');
    }

	public function add_players()
	{
		checkAlert();
        checkToken();
        
        if (!isAdmin()) {
            header('Location: /error/404');
            exit();
        }

        if (isLoggedIn()) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
		}

		$data = [
			'title' => 'Añadir Jugadores',
			'user_data' => $user_data,
			'is_admin' => $is_admin,
			'loggedIn' => isLoggedIn()
        ];
        
        if ($this->input->post('players')) {
            try {
                $players = explode(',', $this->input->post('players'));
                foreach ($players as $player) {
                    $this->Players_model->setPlayer($player);
                }
                header('Location: /');
                exit;
            } catch (Exception $e) {
                $data['error'] = 'separalos por comas cabrón';
            }
        }

		$this->load->view('templates/header', $data);
        $this->load->view('players/add_players', $data);
        $this->load->view('templates/footer');
    }
    
    public function profile($summoner_name = null)
	{
        $summoner_name = urldecode($summoner_name);

		if ($this->Players_model->countPlayer($summoner_name) == 0) {
			header('Location: /404');
            exit();
		}

        $player_data = $this->Players_model->getPlayerByName($summoner_name);
        
        if (isLoggedIn()) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
		}

		$data = [
			'title' => $summoner_name,
            'user_data' => $user_data,
            'player_data' => $player_data,
			'is_admin' => $is_admin,
			'loggedIn' => isLoggedIn()
        ];
		
		$this->load->view('templates/header', $data);
        $this->load->view('players/profile', $data);
        $this->load->view('templates/footer');
	}
}