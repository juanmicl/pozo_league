<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Coinbase
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Enum\Param;
// QrCode
use Endroid\QrCode\QrCode;

class Funds extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('session');
        $this->load->model('UsersModel');
        $this->load->model('AddressesModel');
        $this->load->model('DepositsModel');

        $this->client = Client::create(
			Configuration::apiKey(
				$this->config->item('coinb_key'),
				$this->config->item('coinb_secret')
			)
		);
    }

    public function deposit()
    {
        checkToken();
        checkAlert();

        $userData = $this->UsersModel->getUser($_COOKIE['token']);

        if ($this->AddressesModel->countAddress($userData->id, 'bitcoin')) {
            $addr = $this->AddressesModel->getAddress(0, 'bitcoin', $userData->id);
            $addr = $addr->address;
        } else {
            // create new address
            $account = $this->client->getPrimaryAccount();
            $address = new Address([
                'name' => $userData->username
            ]);
            $addr = $this->client->createAccountAddress($account, $address)->getRawData();
            $this->AddressesModel->newAddress(
                $addr['id'],
                $userData->id,
                $addr['address'],
                $addr['network']
            );
            $addr = $addr['address'];
        }

        $qrCode = new QrCode($addr);
        $pending = $this->DepositsModel->getByUidAndStatus($userData->id, 0);
        
		$data = [
			'title' => 'Deposit',
            'userData' => $userData,
            'addrData' => [
                'address' => $addr,
                'qrCode' => $qrCode->writeString()
            ],
			'loggedIn' => isLoggedIn(),
            'isAdmin' => isAdmin(),
            'pending' => $pending
        ];
		
		$this->load->view('templates/header', $data);
        $this->load->view('funds/deposit', $data);
        $this->load->view('templates/footer');
    }

    public function withdraw()
    {
        checkToken();
        checkAlert();

		$this->load->view('templates/header');
        $this->load->view('funds/withdraw');
        $this->load->view('templates/footer');
    }
}