<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matches extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->helper('session');
		$this->load->model('Users_model');
    }

	public function matches()
	{
		$this->load->model('Summoners_model');
		$this->load->model('Matches_model');
		$this->load->model('Players_model');
		$is_logged_in = isLoggedIn();

		if ($is_logged_in) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
		}

		$matches_formatted = [];
		$match_id = null;
		$matches = $this->Matches_model->getMatches(20);
		foreach ($matches as $match) {
			if ($match_id != $match->id) {
				$matches_formatted[$match->match_id] = [
					'data' => [
						$match->game_id,
						$match->game_duration,
						$match->game_mode,
						$match->game_type,
						$match->game_version,
						$match->map_id,
						'bans' => [
							$match->ban01_id,
							$match->ban02_id, $match->ban03_id, $match->ban04_id, $match->ban05_id, $match->ban06_id, $match->ban07_id, $match->ban08_id, $match->ban09_id, $match->ban10_id,
							$match->date
						],
					],
					'players' => []
				];
				array_push(
					$matches_formatted[$match->match_id]['players'],
					[
						'summoner_id' => $match->summoner_id,
						'summoner_name' => $match->summoner_name,
						'champion_id' => $match->champion_id,
						'lane' => $match->lane,
						'win' => $match->win,
						'kills' => $match->kills,
						'deaths' => $match->deaths,
						'assists' => $match->assists,
						'first_blood' => $match->first_blood,
						'penta_kills' => $match->penta_kills
					]
				);
				$match_id = $match->match_id;
			} else {
				array_push(
					$matches_formatted[$match->match_id]['players'],
					[
						'summoner_id' => $match->summoner_id,
						'summoner_name' => $match->summoner_name,
						'champion_id' => $match->champion_id,
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
		}

		echo(json_encode($matches_formatted));

		$data = [
			'title' => 'Partidas',
			'matches' => $matches,
			'user_data' => $user_data,
			'is_admin' => $is_admin,
			'loggedIn' => $is_logged_in
		];

		$this->load->view('templates/header', $data);
        $this->load->view('matches/matches', $data);
        $this->load->view('templates/footer');
	}
}