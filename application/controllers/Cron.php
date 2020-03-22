<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// RITO API
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

class Cron extends CI_Controller {

	private $lol_api;

	function __construct()
    {
		parent::__construct();
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

	public function players()
	{
		$this->load->model('Players_model');
		$players = $this->Players_model->getPlayers(10);

		foreach ($players as $player) {
			try {
				$summoner = $this->lol_api->getSummonerByName($player->summoner_name);
				$leagues = $this->lol_api->getLeagueEntriesForSummoner($summoner->id);
				$ranked[0] = "";
				$ranked[1] = "";
				foreach ($leagues as $league) {
					if ($league->queueType == "RANKED_SOLO_5x5") {
						$ranked[0] = $league->tier;
						$ranked[1] = $league->rank;
					}
				}

				if ($player->summoner_id != "") {
					$this->Players_model->updatePlayer($player->id, $summoner->profileIconId, $summoner->summonerLevel, $ranked[0], $ranked[1]);
				} else {
					$this->Players_model->updatePlayerFull($player->id, $summoner->id, $summoner->accountId ,$summoner->profileIconId, $summoner->summonerLevel, $ranked[0], $ranked[1]);
				}

				$player_points = ($player->wins*3)+($player->loses);
				$this->Players_model->updatePoints($player->id, $player_points);
			} catch (Exception $e) {
				echo 'caca: '.$player->summoner_name.'<br>';
			}
		}
	}
}