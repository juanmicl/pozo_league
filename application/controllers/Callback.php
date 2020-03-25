<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->model('Summoners_model');
	}
	
	public function custom_games()
	{
        $this->load->model('Players_model');
        $this->load->model('Matches_model');
        
        $match_data = json_decode($this->input->raw_input_stream);

        if ($match_data->gameType != 'CUSTOM_GAME' || $match_data->mapId != 11) {
            exit('Bad game bro');
        }

        if ($this->Matches_model->countGame($match_data->gameId) > 0) {
            exit('Already on DB');
        }

        foreach ($match_data->participantIdentities as $participant) {
            if ($this->Summoners_model->countSummoner($participant->player->summonerName) < 1) {
                exit('Forbbiden game');
            }
        }

        $match_id = $this->Matches_model->setMatch(
            $match_data->gameId, $match_data->gameDuration, $match_data->gameMode,
            $match_data->gameType, $match_data->gameVersion, $match_data->mapId,
            $match_data->gameCreationDate
        );

        $bans_t1 = [];
        for ($i=0; $i < 5; $i++) {
            if (!empty($match_data->teams[0]->bans[$i])){
                array_push($bans_t1, $match_data->teams[0]->bans[$i]->championId);
            } else {
                array_push($bans_t1, null);
            }
        }

        $bans_t2 = [];
        for ($i=0; $i < 5; $i++) {
            if (!empty($match_data->teams[1]->bans[$i])){
                array_push($bans_t2, $match_data->teams[1]->bans[$i]->championId);
            } else {
                array_push($bans_t2, null);
            } 
        }

        $this->Matches_model->updateBans(
            $match_id, $bans_t1, $bans_t2
        );

        file_put_contents('./games/'.$match_data->gameId.'.json', json_encode($match_data));

        foreach ($match_data->participantIdentities as $participant) {
            foreach ($match_data->participants as $player) {
                if ($participant->participantId == $player->stats->participantId) {
                    $summoner_id = $this->Summoners_model->getSummonerByName($participant->player->summonerName)->id;
                    $this->Players_model->setPlayer(
                        $match_id, $summoner_id,
                        [
                            'champion_id' => $player->championId,
                            'lane' => $player->timeline->lane,
                            'win' => $player->stats->win,
                            'kills' => $player->stats->kills,
                            'deaths' => $player->stats->deaths,
                            'assists' => $player->stats->assists,
                            'first_blood' => $player->stats->firstBloodKill,
                            'first_tower' => $player->stats->firstTowerKill,
                            'magic_damage' => $player->stats->magicDamageDealt,
                            'physical_damage' => $player->stats->physicalDamageDealt,
                            'total_damage' => $player->stats->totalDamageDealt,
                            'total_damage_taken' => $player->stats->totalDamageTaken,
                            'total_heal' => $player->stats->totalHeal,
                            'minions_killed' => $player->stats->totalMinionsKilled,
                            'gold_earned' => $player->stats->goldEarned,
                            'cs_per_min' => $player->stats->totalMinionsKilled/($match_data->gameDuration*60),
                            'gold_per_min' => $player->stats->goldEarned/($match_data->gameDuration*60),
                            'wards_placed' => $player->stats->wardsPlaced,
                            'wards_killed' => $player->stats->wardsKilled,
                            'penta_kills' => $player->stats->pentaKills,
                            'longest_time_alive' => $player->stats->longestTimeSpentLiving,
                            'total_cc_time' => $player->stats->timeCCingOthers
                        ]
                    );
                }
            }
        }

        echo 'success';
	}
}