<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// RITO API
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

class Main extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->helper('session');
		$this->load->model('Users_model');

		$this->lol_api = new LeagueAPI([
			LeagueAPI::SET_KEY              => $this->config->item('lol_api_key'),
			LeagueAPI::SET_TOURNAMENT_KEY   => "",
			LeagueAPI::SET_REGION           => Region::EUROPE_WEST,
			LeagueAPI::SET_VERIFY_SSL       => false,
			LeagueAPI::SET_DATADRAGON_INIT  => true,
			LeagueAPI::SET_INTERIM          => true,
			LeagueAPI::SET_CACHE_RATELIMIT  => true,
			LeagueAPI::SET_CACHE_CALLS      => true,
		]);
    }

	public function home()
	{
		checkAlert();
		$is_logged_in = isLoggedIn();

		$this->load->model('Summoners_model');
		if ($is_logged_in) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
		}

		$data = [
			'title' => '<i class="fas fa-trophy text-warning"></i> Ranking',
			'user_data' => $user_data,
			'is_admin' => $is_admin,
			'loggedIn' => $is_logged_in,
			'summoners' => $this->Summoners_model->getSummoners()
		];

		$this->load->view('templates/header', $data);
        $this->load->view('main/home', $data);
        $this->load->view('templates/footer');
	}

	public function settings()
	{
		checkToken();

		$data = [
			'title' => 'Settings',
			'userData' => $this->UsersModel->getUser($_COOKIE['token']),
			'loggedIn' => true,
			'isAdmin' => isAdmin()
		];
		
		if ($this->input->post('avatar')) {
			if (preg_match("/https:\/\/i.imgur.com\/(.*)/", $this->input->post('avatar'))) {
				$this->UsersModel->editAvatar($data['userData']->id, $this->input->post('avatar'));
				exit('ok');
			} else {
				exit("error");
			}
        }
		
		$this->load->view('templates/header', $data);
		$this->load->view('dashboard/settings');
        $this->load->view('templates/footer');
	}

	public function terms()
	{
		$data = [
			'title' => 'Terminos y Condiciones',
			'loggedIn' => false
		];

		$this->load->view('templates/header', $data);
        $this->load->view('terms');
        $this->load->view('templates/footer');
	}

}