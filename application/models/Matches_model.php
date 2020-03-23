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
        if ($limit != null && $offset = null) {
            $sql = "SELECT matches.*, players.*, summoners.summoner_name FROM `matches` INNER JOIN players ON players.match_id = matches.id INNER JOIN summoners ON players.summoner_id = summoners.id ORDER BY matches.date DESC LIMIT ? OFFSET ?";
            return $this->db->query($sql, [$limit, $offset])->result();
        } else if ($limit != null) {
            $sql = "SELECT matches.*, players.*, summoners.summoner_name FROM `matches` INNER JOIN players ON players.match_id = matches.id INNER JOIN summoners ON players.summoner_id = summoners.id ORDER BY matches.date DESC LIMIT ?";
            return $this->db->query($sql, [$limit])->result();
        } else {
            return $this->db->query("SELECT * FROM `matches` ORDER BY `date` DESC")->result();
        }
    }

    public function getMatch($id)
    {
        $sql = "SELECT * FROM summoners WHERE id = ?";
        return $this->db->query($sql, [$id])->result()[0];
    }

    public function setMatch($game_id, $game_duration, $game_mode, $game_type, $game_version, $map_id, $bans, $date)
    {   
        $sql = "INSERT INTO `matches` (`id`, `game_id`, `game_duration`, `game_mode`, `game_type`, `game_version`, `map_id`, `ban01_id`, `ban02_id`, `ban03_id`, `ban04_id`, `ban05_id`, `ban06_id`, `ban07_id`, `ban08_id`, `ban09_id`, `ban10_id`, `date`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $this->db->query($sql, [$game_id, $game_duration, $game_mode, $game_type, $game_version, $map_id, $bans[0], $bans[1], $bans[2], $bans[3], $bans[4], $bans[5], $bans[6], $bans[7], $bans[8], $bans[9], $date]);
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