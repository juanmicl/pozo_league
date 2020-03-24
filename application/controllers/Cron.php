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

	public function summoners()
	{
		$this->load->model('Summoners_model');
		$this->load->model('Players_model');
		$summoners = $this->Summoners_model->getSummoners(10);

		foreach ($summoners as $summoner) {
			try {
				$summoner_data = $this->lol_api->getSummonerByName($summoner->summoner_name);
				$leagues = $this->lol_api->getLeagueEntriesForSummoner($summoner_data->id);
				$ranked[0] = "";
				$ranked[1] = "";
				foreach ($leagues as $league) {
					if ($league->queueType == "RANKED_SOLO_5x5") {
						$ranked[0] = $league->tier;
						$ranked[1] = $league->rank;
					}
				}

				if ($summoner->summoner_id != "") {
					$this->Summoners_model->updateSummoner($summoner->id, $summoner_data->profileIconId, $summoner_data->summonerLevel, $ranked[0], $ranked[1]);
				} else {
					$this->Summoners_model->updateSummonerFull($summoner->id, $summoner_data->id, $summoner_data->accountId, $summoner_data->profileIconId, $summoner_data->summonerLevel, $ranked[0], $ranked[1]);
				}

				$puntuation = $this->Players_model->getPuntuation($summoner->id);

				$summoner_points = ($puntuation->wins*3)+($puntuation->loses);
				$this->Summoners_model->updatePuntuation($summoner->id, $puntuation->wins, $puntuation->loses, $summoner_points);
			} catch (Exception $e) {
				echo 'caca: '.$summoner->summoner_name.'<br>';
			}
		}
	}
}