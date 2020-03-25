<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// RITO API
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Definitions\Region;

class Summoners extends CI_Controller {

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
        $this->load->model('Summoners_model');
    }

	public function add_summoners()
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
        
        if ($this->input->post('summoners')) {
            try {
                $summoners = explode(',', $this->input->post('summoners'));
                foreach ($summoners as $summoner) {
                    $this->Summoners_model->setSummoner($summoner);
                }
                header('Location: /');
                exit;
            } catch (Exception $e) {
                $data['error'] = 'separalos por comas cabrón';
            }
        }

		$this->load->view('templates/header', $data);
        $this->load->view('summoners/add_summoners', $data);
        $this->load->view('templates/footer');
    }

    public function verify($step = null)
	{
        checkToken();

        $user_data = $this->Users_model->getUser($_COOKIE['token']);

        if ($user_data->summoner_id != null) {
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
                $summoner_icon = $this->lol_api->getSummonerByName(strtolower($this->input->post('summoner_name')))->profileIconId;
                if (password_verify($data['user_data']->username."|".$summoner_icon, $this->input->post('hash'))) {
                    if ($this->Summoners_model->countSummoner($this->input->post('summoner_name')) < 1) {
                        $summoner_id = $this->Summoners_model->setSummoner($this->input->post('summoner_name'));
                    } else {
                        $summoner_id = $this->Summoners_model->getSummonerByName($this->input->post('summoner_name'))->id;
                    }
                    $this->Users_model->setSummonerId($data['user_data']->id, $summoner_id);
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
        $this->load->view('summoners/verify', $data);
        $this->load->view('templates/footer');
    }

    public function inscribe_today()
	{
        checkToken();

        $user_data = $this->Users_model->getUser($_COOKIE['token']);

        if ($user_data->summoner_id == null) {
            header('Location: /verificar');
            exit();
        } else {
            $summoner_data = $this->Users_model->getSummoner($user_data->id);
        }

		$data = [
			'title' => 'Inscripción',
            'user_data' => $user_data,
            'summoner_data' => $summoner_data,
            'summoner_active' => $this->check_same_day($summoner_data->active),
			'is_admin' => isAdmin(),
			'loggedIn' => isLoggedIn()
        ];
        
        if ($this->input->post('cacadelavaca')) {
            try {
                $this->Summoners_model->updateActive($summoner_data->id);
                header('Location: /lista-inscritos');
                exit();
            } catch (Exception $e) {
                $data['error'] = 'Error al inscribirse, intentalo de nuevo';
            }
        }

		$this->load->view('templates/header', $data);
        $this->load->view('summoners/inscribe', $data);
        $this->load->view('templates/footer');
    }

    public function active_list()
	{
        $is_logged_in = isLoggedIn();
		if ($is_logged_in) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
        }

        $summoners = $this->Summoners_model->getSummoners();
        $active_summoners = [];
        
        foreach ($summoners as $summoner) {
            if ($this->check_same_day($summoner->active)) {
                array_push($active_summoners, $summoner);
            }
        }

		$data = [
			'title' => count($active_summoners).' Inscritos',
			'user_data' => $user_data,
			'is_admin' => $is_admin,
			'loggedIn' => $is_logged_in,
			'summoners' => $active_summoners
		];

		$this->load->view('templates/header', $data);
        $this->load->view('summoners/active_list', $data);
        $this->load->view('templates/footer');
	}
    
    public function profile($summoner_name = null)
	{
        $summoner_name = urldecode($summoner_name);

		if ($this->Summoners_model->countSummoner($summoner_name) == 0) {
			header('Location: /404');
            exit();
        }
        $this->load->model('Matches_model');
        $this->load->helper('lol');
        $summoner_data = $this->Summoners_model->getSummonerByName($summoner_name, 30);
        
        if (isLoggedIn()) {
			$user_data = $this->Users_model->getUser($_COOKIE['token']);
			$is_admin = isAdmin();
		} else {
			$user_data = null;
			$is_admin = false;
        }
        
        $matches_formatted = [];
		$match_id = null;
		$matches = $this->Matches_model->getMatchesBySummoner($summoner_name, 30);
		foreach ($matches as $match) {
			if ($match_id == null || $match_id != $match->match_id) {
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
			'title' => $summoner_name,
            'user_data' => $user_data,
            'summoner_data' => $summoner_data,
            'matches' => $matches_formatted,
			'is_admin' => $is_admin,
			'loggedIn' => isLoggedIn()
        ];
		
		$this->load->view('templates/header', $data);
        $this->load->view('summoners/profile', $data);
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