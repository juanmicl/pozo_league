<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->helper('session');
        $this->load->model('UsersModel');
    }

	public function breaches($page = 1)
	{
		checkToken();
		checkAlert();
		
		$this->load->model('Breaches_model');
		$this->load->model('Transactions_model');
		
        $n_breaches = $this->Breaches_model->countBreaches();
		$limit = 6; // items per page
		$n_pages = ceil($n_breaches/$limit);
		if ($page < 1) {$page = 1; } elseif ($page > $n_pages) { $page = $n_pages; }
		// Calculate the offset for the query
		$offset = ($page - 1)  * $limit;

		$pagination_data = [
			'pages' => $n_pages,
			'page' => $page
		];

        $breaches_full = [];
		$breaches = $this->Breaches_model->getBreaches($limit, $offset);
		$user_data = $this->UsersModel->getUser($_COOKIE['token']);

		foreach ($breaches as $breach) {
			$breach = json_decode(json_encode($breach), true);
            if ($this->Transactions_model->countTransaction($user_data->id, $breach['item_id']) == 1) {
				$result = array_merge($breach, ['unlocked' => true]);
			} else {
				$result = array_merge($breach, ['unlocked' => false]);
			}
            array_push($breaches_full, $result);
		}

		$data = [
			'title' => 'Breaches',
			'breaches' => $breaches_full,
			'pagination_data' => $pagination_data,
			'userData' => $user_data,
			'loggedIn' => isLoggedIn(),
			'isAdmin' => isAdmin()
        ];
		
		$this->load->view('templates/header', $data);
        $this->load->view('items/breaches', $data);
        $this->load->view('templates/footer');
    }
    
    public function combos($page = 1)
	{
		checkToken();
		checkAlert();
		
		$this->load->model('Combos_model');
		$this->load->model('Transactions_model');

		$n_combos = $this->Combos_model->countCombos();
		$limit = 6; // items per page
		$n_pages = ceil($n_combos/$limit);
		if ($page < 1) {$page = 1; } elseif ($page > $n_pages) { $page = $n_pages; }
		// Calculate the offset for the query
		$offset = ($page - 1)  * $limit;

		$pagination_data = [
			'pages' => $n_pages,
			'page' => $page
		];

        $combos_full = [];
		$combos = $this->Combos_model->getCombos($limit, $offset);
		$user_data = $this->UsersModel->getUser($_COOKIE['token']);

        foreach ($combos as $combo) {
			$combo = json_decode(json_encode($combo), true);
            if ($this->Transactions_model->countTransaction($user_data->id, $combo['item_id']) == 1) {
				$result = array_merge($combo, ['unlocked' => true]);
			} else {
				$result = array_merge($combo, ['unlocked' => false]);
			}
            array_push($combos_full, $result);
		}

		$data = [
			'title' => 'Combos',
			'combos' => $combos_full,
			'pagination_data' => $pagination_data,
			'userData' => $this->UsersModel->getUser($_COOKIE['token']),
			'loggedIn' => isLoggedIn(),
			'isAdmin' => isAdmin()
        ];
		
		$this->load->view('templates/header', $data);
        $this->load->view('items/combos', $data);
        $this->load->view('templates/footer');
	}

	public function new_item()
	{
		checkToken();
		checkAlert();

		$this->load->model('Combos_model');
		$this->load->model('Breaches_model');

		$data = [
			'title' => 'New breach',
			'userData' => $this->UsersModel->getUser($_COOKIE['token']),
			'loggedIn' => isLoggedIn(),
			'isAdmin' => isAdmin()
		];
		
		if ($this->input->post('publish') == "combo") {
			// Check empty fields
			if($this->input->post('lines') || $this->input->post('name') || $this->input->post('link')) {
				// Insert Combo
				$this->Combos_model->setCombo(
					$this->input->post('name'), $data['userData']->id, $this->input->post('link'), 3, 0, $this->input->post('lines')
                );
                header('Location: /combos');
                exit();
			} else {
				$data['error'] = "Please enter all fields";
			}
        } elseif ($this->input->post('publish') == "breach") {
			// Check empty fields
			if($this->input->post('lines') || $this->input->post('name') || $this->input->post('link') || $this->input->post('site') || $this->input->post('passwords')) {
				if ($this->input->post('passwords') == "dehashed") {
					$hashed = 0;
				} else {
					$hashed = 1;
				}
				// Insert Breach
				$this->Breaches_model->setBreach(
					$this->input->post('name'), $data['userData']->id, $this->input->post('link'), 7, $this->input->post('site'), $hashed, $this->input->post('lines')
                );
                header('Location: /databases');
                exit();
			} else {
				$data['error'] = "Please enter all fields";
			}
		}
		
		$this->load->view('templates/header', $data);
		$this->load->view('items/new_item');
        $this->load->view('templates/footer');
	}
}