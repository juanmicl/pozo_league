<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Matches_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getMatches($limit = null, $offset = null)
    {
        if ($limit != null && $offset != null) {
            $sql = "SELECT matches.*, players.*, summoners.summoner_name FROM `matches` INNER JOIN players ON players.match_id = matches.id INNER JOIN summoners ON players.summoner_id = summoners.id ORDER BY matches.date DESC, players.lane DESC LIMIT ? OFFSET ?";
            return $this->db->query($sql, [$limit, $offset])->result();
        } else if ($limit != null) {
            $sql = "SELECT matches.*, players.*, summoners.summoner_name FROM `matches` INNER JOIN players ON players.match_id = matches.id INNER JOIN summoners ON players.summoner_id = summoners.id ORDER BY matches.date DESC, players.lane DESC LIMIT ?";
            return $this->db->query($sql, [$limit])->result();
        } else {
            return $this->db->query("SELECT * FROM `matches` ORDER BY `date` DESC")->result();
        }
    }

    public function getMatchesBySummoner($summoner_name, $limit)
    {
        $sql = "SELECT matches.*, players.*, summoners.summoner_name FROM `matches` INNER JOIN players ON players.match_id = matches.id INNER JOIN summoners ON players.summoner_id = summoners.id WHERE matches.game_id IN ( SELECT matches.game_id FROM matches INNER JOIN players ON players.match_id = matches.id INNER JOIN summoners ON players.summoner_id = summoners.id WHERE summoners.summoner_name = ?) ORDER BY matches.date DESC, players.lane DESC LIMIT ?";
        return $this->db->query($sql, [$summoner_name, $limit])->result();
    }

    public function getMatch($id)
    {
        $sql = "SELECT * FROM summoners WHERE id = ?";
        return $this->db->query($sql, [$id])->result()[0];
    }

    public function setMatch($game_id, $game_duration, $game_mode, $game_type, $game_version, $map_id, $date)
    {   
        $sql = "INSERT INTO `matches` (`id`, `game_id`, `game_duration`, `game_mode`, `game_type`, `game_version`, `map_id`, `date`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);";
        $this->db->query($sql, [$game_id, $game_duration, $game_mode, $game_type, $game_version, $map_id, $date]);
        return $this->db->insert_id();
    }

    public function updateBans($id, $bans_t1, $bans_t2)
    {   
        $sql = "UPDATE `matches` SET `ban01_id` = ?, `ban02_id` = ?, `ban03_id` = ?, `ban04_id` = ?, `ban05_id` = ?, `ban06_id` = ?, `ban07_id` = ?, `ban08_id` = ?, `ban09_id` = ?, `ban10_id` = ? WHERE `id` = ?;";
        $this->db->query($sql, [$bans_t1[0], $bans_t1[1], $bans_t1[2], $bans_t1[3], $bans_t1[4], $bans_t2[0], $bans_t2[1], $bans_t2[2], $bans_t2[3], $bans_t2[4], $id]);
        return $this->db->insert_id();
    }

    public function countMatches()
    {
        $sql = "SELECT COUNT(*) c FROM `matches`";
        return $this->db->query($sql)->row()->c;
    }

    public function countGame($id)
    {
        $sql = "SELECT COUNT(*) c FROM `matches` WHERE `game_id` = ?";
        return $this->db->query($sql, [$id])->row()->c;
    }
}