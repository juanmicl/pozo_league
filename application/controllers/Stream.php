<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stream extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->helper('session');
		$this->load->model('Users_model');
    }

	public function ranking()
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
			'summoners' => $this->Summoners_model->getSummonersAndAwards()
        ];
        
        $this->load->view('stream/ranking', $data);
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
    
    public function matches($page = 1)
	{
		$this->load->model('Summoners_model');
		$this->load->model('Matches_model');
		$this->load->model('Players_model');
		$this->load->helper('lol');
		$is_logged_in = isLoggedIn();

		if ($is_logged_in) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
		}

		$n_matches = $this->Matches_model->countMatches();
		$limit = 6*10; // items per page
		$n_pages = ceil($n_matches/($limit/10));
		if ($page < 1) {$page = 1; } elseif ($page > $n_pages) { $page = $n_pages; }
		
		$offset = ($page - 1)  * $limit;

		$pagination_data = [
			'pages' => $n_pages,
			'page' => $page
		];

		$matches_formatted = [];
		$match_id = null;
		$matches = $this->Matches_model->getMatches($limit, $offset);
		foreach ($matches as $match) {
			if ($match_id == null || $match_id != $match->match_id) {
				$matches_formatted[$match->match_id] = [
					'data' => [
						'game_id' => $match->game_id,
						'game_duration' => $match->game_duration,
						'game_version' => $match->game_version,
						'bans' => [
							$match->ban01_id, $match->ban02_id, $match->ban03_id, $match->ban04_id, $match->ban05_id, $match->ban06_id, $match->ban07_id, $match->ban08_id, $match->ban09_id, $match->ban10_id
						],
						'date' => new DateTime($match->date)
					],
					'players' => []
				];
				$match_id = $match->match_id;
			} else {
				$matches_formatted[$match->match_id] = [
					'data' => [
						'game_id' => $match->game_id,
						'game_duration' => $match->game_duration,
						'game_version' => $match->game_version,
						'bans' => [
							$match->ban01_id,
							$match->ban02_id, $match->ban03_id, $match->ban04_id, $match->ban05_id, $match->ban06_id, $match->ban07_id, $match->ban08_id, $match->ban09_id, $match->ban10_id
						],
						'date' => new DateTime($match->date)
					],
					'players' => $matches_formatted[$match->match_id]['players']
				];
			}
			array_push(
				$matches_formatted[$match->match_id]['players'],
				[
					'summoner_id' => $match->summoner_id,
					'summoner_name' => $match->summoner_name,
					'champion_name' =>  getChampionIDToname($match->champion_id),
					'lane' => $match->lane,
					'win' => $match->win,
					'kills' => $match->kills,
					'deaths' => $match->deaths,
					'assists' => $match->assists,
					'first_blood' => $match->first_blood,
					'penta_kills' => $match->penta_kills
				]
			);
		}

		$data = [
			'title' => 'Partidas',
			'matches' => $matches_formatted,
			'pagination_data' => $pagination_data,
			'user_data' => $user_data,
			'is_admin' => $is_admin,
			'loggedIn' => $is_logged_in
		];

        $this->load->view('stream/matches', $data);
	}
}