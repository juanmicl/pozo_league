<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// RITO API
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

class Players extends CI_Controller {

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
        $this->load->helper('session');
        $this->load->model('Users_model');
        $this->load->model('Players_model');
    }

	public function add_players()
	{
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

    public function verify($step = null)
	{
        checkToken();

        $user_data = $this->Users_model->getUser($_COOKIE['token']);

        if ($user_data->player_id != null) {
            header('Location: /');
            exit();
        }

		$data = [
            'title' => 'Verificación',
            'verify_icon' => ['id' => null, 'hash' => null],
            'user_data' => $user_data,
			'is_admin' => isAdmin(),
            'loggedIn' => isLoggedIn(),
            'error' => null
        ];
        
        $data['verify_icon']['id'] = rand(0, 28);
        $data['verify_icon']['hash'] = password_hash($data['user_data']->username."|".$data['verify_icon']['id'], PASSWORD_BCRYPT);

        if ($this->input->post('summoner_name')) {
            try {
                $player_icon = $this->lol_api->getSummonerByName(strtolower($this->input->post('summoner_name')))->profileIconId;
                if (password_verify($data['user_data']->username."|".$player_icon, $this->input->post('hash'))) {
                    if ($this->Players_model->countPlayer($this->input->post('summoner_name')) < 1) {
                        $player_id = $this->Players_model->setPlayer($this->input->post('summoner_name'));
                    } else {
                        $player_id = $this->Players_model->getPlayerByName($this->input->post('summoner_name'))->id;
                    }
                    $this->Users_model->setPlayerId($data['user_data']->id, $player_id);
                    //sweetAlert(['type' => 'sucess', 'msg' => 'Cuenta verificada, ya puedes inscribirte!'], $reload=false);
                    header('Location: /inscripcion');
                    exit();
                } else {
                    $data['error'] = "El icono que tiene ".$this->input->post('summoner_name')." no corresponde con el que te pedimos.";
                }
            } catch (Exception $e) {
                $data['error'] = 'Este nombre de invocador no existe';
            }
        }

		$this->load->view('templates/header', $data);
        $this->load->view('players/verify', $data);
        $this->load->view('templates/footer');
    }

    public function inscribe_today()
	{
        checkToken();

        $user_data = $this->Users_model->getUser($_COOKIE['token']);

        if ($user_data->player_id == null) {
            header('Location: /verificar');
            exit();
        } else {
            $player_data = $this->Users_model->getPlayer($user_data->id);
        }

		$data = [
			'title' => 'Inscripción',
            'user_data' => $user_data,
            'player_data' => $player_data,
            'player_active' => $this->check_same_day($player_data->active),
			'is_admin' => isAdmin(),
			'loggedIn' => isLoggedIn()
        ];
        
        if ($this->input->post('cacadelavaca')) {
            try {
                $this->Players_model->updateActive($player_data->id);
                header('Location: /');
                exit();
            } catch (Exception $e) {
                $data['error'] = 'separalos por comas cabrón';
            }
        }

		$this->load->view('templates/header', $data);
        $this->load->view('players/inscribe', $data);
        $this->load->view('templates/footer');
    }

    public function active_list()
	{
        $this->load->model('Players_model');
        $is_logged_in = isLoggedIn();
		if ($is_logged_in) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
        }

        $players = $this->Players_model->getPlayers();
        $active_players = [];
        
        foreach ($players as $player) {
            if ($this->check_same_day($player->active)) {
                array_push($active_players, $player);
            }
        }

		$data = [
			'title' => count($active_players).' Inscritos',
			'user_data' => $user_data,
			'is_admin' => $is_admin,
			'loggedIn' => $is_logged_in,
			'players' => $active_players
		];

		$this->load->view('templates/header', $data);
        $this->load->view('players/active_list', $data);
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