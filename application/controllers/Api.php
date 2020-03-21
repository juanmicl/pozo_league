<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// RITO API
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;
use RiotAPI\LeagueAPI\Objects\ProviderRegistrationParameters;
use RiotAPI\LeagueAPI\Objects\TournamentRegistrationParameters;
use RiotAPI\LeagueAPI\Objects\TournamentCodeParameters;

class Api extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->lol_api = new LeagueAPI([
			LeagueAPI::SET_KEY              => $this->config->item('lol_api_key'),
			LeagueAPI::SET_TOURNAMENT_KEY   => "RGAPI-5d2c4b7b-4395-4b7e-a378-e362b7482481",
			LeagueAPI::SET_REGION           => Region::EUROPE_WEST,
			LeagueAPI::SET_VERIFY_SSL       => false,
			LeagueAPI::SET_DATADRAGON_INIT  => true,
			LeagueAPI::SET_INTERIM          => true,
			LeagueAPI::SET_CACHE_RATELIMIT  => true,
			LeagueAPI::SET_CACHE_CALLS      => false,
        ]);
		$this->load->model('Players_model');
	}
	
	public function matchmaking()
	{
		$players = $this->Players_model->getPlayers();

		$mmr = [
			'IRON' => [
				'IV' => 0,
				'III' => 100,
				'II' => 200,
				'I' => 300
			],
			'BRONZE' => [
				'IV' => 400,
				'III' => 500,
				'II' => 600,
				'I' => 700
			],
			'SILVER' => [
				'IV' => 800,
				'III' => 900,
				'II' => 1000,
				'I' => 1100
			],
			'GOLD' => [
				'IV' => 1200,
				'III' => 1300,
				'II' => 1400,
				'I' => 1500
			],
			'PLATINUM' => [
				'IV' => 1600,
				'III' => 1700,
				'II' => 1800,
				'I' => 1900
			],
			'DIAMOND' => [
				'IV' => 2000,
				'III' => 2100,
				'II' => 2200,
				'I' => 2300
			]
		];
		
		// Matchmaking
		while (true) {
			$medias = [];
			$matchmaking = [];
			$players_full = [];

			foreach ($players as $player) {
				if (!$this->check_same_day($player->active)) {
					continue;
				}
				$players_full[$player->id]['id'] = $player->id;
				$players_full[$player->id]['summoner_name'] = $player->summoner_name;
				$players_full[$player->id]['league'] = $player->league;
				$players_full[$player->id]['rank'] = $player->rank;
				if ($player->league != "") {
					$player_mmr = $mmr[$player->league][$player->rank];
				} else {
					$player_mmr = 800;
				}
				if ($player->wins == 0 && $player->loses == 0) {
					$player_elo = round($player_mmr/2300, 4);
				} else {
					$players_full[$player->id]['winrate'] = round($player->wins/($player->wins+$player->loses), 1);
					$player_elo = round($players_full[$player->id]['winrate'] * 0.65 + $player_mmr/2300 * 0.35, 4);
				}
				$perturbacion = (mt_rand(0, 1000) / 10000);
				if ((bool)random_int(0, 1)) {
					$players_full[$player->id]['elo'] = round($player_elo + $perturbacion, 4);
				} else {
					$players_full[$player->id]['elo'] = round($player_elo - $perturbacion, 4);
				}
			}

			usort($players_full, function($a, $b) {
				return $a['elo'] <=> $b['elo'];
			});
			$direction = 1;
			$suma = 0;
			$n_teams = floor(count($players_full)/5);
			for($t=0; $t < $n_teams; $t++) {
				for ($i=0; $i < 5; $i++) { 
					if ($direction === 1) {
						$player = array_pop($players_full);
					} else {
						$player = array_shift($players_full);
					}
					$matchmaking[$t][$i] = $player;
					$suma += $player['elo'];
					$direction = -$direction;
				}
				//$matchmaking[$t]['media'] = round($suma/5, 4);
				array_push($medias, round($suma/5, 4));
				$suma = 0;
			}
			if ((max($medias) - min($medias)) < 0.07) {
				break;
			}
		}
		//echo(json_encode($matchmaking, JSON_UNESCAPED_UNICODE));
		$n = 1;
		foreach ($matchmaking as $team) {
			echo('<h2>EQUIPO '.$n.'</h2><br>');
			foreach ($team as $player) {
				echo('- '.$player['summoner_name'].'<br>');
			}
			$n += 1;
		}
	}

	public function test () {
		$provider = new ProviderRegistrationParameters(
			['region' => Region::EUROPE_WEST,
			'url' => 'http://pozoleague.ml/callback/matches'
		]);
		$provider_id = $this->lol_api->createTournamentProvider($provider);
		
		var_dump($provider_id);

		$tournament = new TournamentRegistrationParameters([
			'providerId' => $provider_id,
			'name' => 'jornada 1'
		]);
		$tournament_id = $this->lol_api->createTournament($tournament);

		echo('Tournament ID: '.$tournament_id);
		$codes_params = new TournamentCodeParameters([
			//'allowedSummonerIds' => [ "...", ... ],
			'mapType'       => 'SUMMONERS_RIFT',
			'pickType'      => 'TOURNAMENT_DRAFT',
			'spectatorType' => 'LOBBYONLY',
			'teamSize'      => 5,
		]);
		$codes = $this->lol_api->createTournamentCodes($tournament_id, 2, $codes_params);
		var_dump($codes);

	}

	private function check_same_day($datetime1) {
        $date1 = new DateTime($datetime1);
        $date2 = new DateTime('now');
        $date1 = $date1->format('Ymd');
        $date2 = $date2->format('Ymd');

        if ($date1 == $date2) {
            return true;
        } else {
            return false;
        }
    }
}