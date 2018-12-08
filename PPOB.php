<?php
class PPOB extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->webdata->load();
		if (!$this->session->userdata('auth_user')) {
			redirect(base_url('masuk'));
		}
	}

	public function ppobPost($page){
		$invoice = $this->invoice_make();
		$moota_code = 	PpobTransactionModel::generatePriceRandomCode();
    if($page == "pulsa" or $page == "vouchergame"){
			$rules = [
	            'required' 	=> [
	                ['nominal']
	            ]
	          ];

	    $validate 	= $this->validation->check($rules,'post');
			$produk_id = $this->input->post('nominal');
			switch ($page) {
				case 'pulsa':
					$rules = [
			            'required' 	=> [
			                ['phone']
			            ]
			          ];
					$validate 	= $this->validation->check($rules,'post');
					$pulsaList = new ApiHelperPulsa;
					$data['id'] = NULL;
					$list = $pulsaList->produk($data);
					foreach ($list as $value) {
						if($value['produk_id'] == $this->input->post('nominal')){
							$nominal = $value['nominal'];
							$price = $value['harga'];
							$name = $value['voucher'];
							$needed = $this->input->post('phone');
							break;
						}
					}
					break;

				case 'vouchergame':
					$vouchergameList = new ApiHelperVoucherGame;
					$productID = NULL;
					$list = $vouchergameList->productListGame($productID);
					foreach ($list as $value) {
						if($value['productID'] == $this->input->post('nominal')){
							$nominal = $value['nominal'];
							$price = $value['harga'];
							$name = $value['voucher'];
							break;
						}
					}
					break;

				default:
					$this->webdata->show_404();
					break;
			}
		}

		else if ($page == "tiket") {
			$rules = [
				'required' => [
						['asal'], ['tujuan'], ['tanggal'], ['no_kereta'], ['subclass'], ['dewasa'], ['bayi'], ['penumpang'], ['kode_gerbong'], ['no_gerbong'], ['kursi']
				]
			];
			$validate 	= $this->validation->check($rules,'post');
			if(count($this->input->post('kursi')) != $this->input->post('dewasa')){
				$this->webdata->show_404();
				return;
			}
			$data['asal'] = $this->input->post('asal');
			$data['tujuan'] = $this->input->post('tujuan');
			$data['tanggal'] = $this->input->post('tanggal');
			$data['no_kereta'] = $this->input->post('no_kereta');
			$data['dewasa'] = $this->input->post('dewasa');
			$data['bayi'] = $this->input->post('bayi');
			$data['kode_gerbong'] = $this->input->post('kode_gerbong');
			$data['no_gerbong'] = $this->input->post('no_gerbong');
			$data['pilih_kursi'] = 'manual';
			$data['subclass'] = $this->input->post('subclass');
			$data['kursi'] = $this->input->post('kursi')[0];
			for($i = 0; $i < $data['dewasa']; $i++){
				$data['penumpang']['adult'][$i]['adult_name'] = $this->input->post('adult_name')[$i];
				$data['penumpang']['adult'][$i]['adult_id'] = $this->input->post('adult_id')[$i];
				$data['penumpang']['adult'][$i]['adult_date_of_birth'] = $this->input->post('adult_date_of_birth')[$i];
				$data['penumpang']['adult'][$i]['adult_phone'] = $this->input->post('adult_phone')[$i];
			}
			for($i = 0; $i < $data['bayi']; $i++){
				$data['penumpang']['infant'][$i]['infant_name'] = $this->input->post('infant_name')[$i];
				$data['penumpang']['infant'][$i]['infant_id'] = $this->input->post('infant_id')[$i];
				$data['penumpang']['infant'][$i]['infant_date_of_birth'] = $this->input->post('infant_date_of_birth')[$i];
				$data['penumpang']['infant'][$i]['infant_phone'] = $this->input->post('infant_phone')[$i];
			}
			$helper = new ApiHelperKAI;
			$booking = $helper->kai_book($data);
			$data['kode_booking'] = $booking['bookingCode'];
			$data['harga'] = $booking['totalPrice'];
			$name = $booking['TrainName']." Class ".$booking['subClass']." ".$data['dewasa']." Seats";
			$nominal = $booking['ticketPrice'];
			$price = $booking['totalPrice'] + $booking['fee'];
			$produk_id = 'KAI';
			$needed = json_encode($data);
		}

		else if($page == "tagihan"){
			$rules = [
							'required' 	=> [
									['tagihan'], ['productCode'],['idPel']
							]
						];
			$validate 	= $this->validation->check($rules,'post');
			$tagihan = $this->input->post('tagihan');
			switch ($tagihan) {
				case 'pln':
					$helper = new ApiHelperPLN;
					$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $denom = $this->input->post('denom'), $miscData = $this->input->post('miscData'));
					if($inquiry['responseCode'] != "00"){
						$this->webdata->show_404();
						return;
					}
					$name = $inquiry['data']['transactionName'];
					$nominal = $inquiry['data']['nilaiTagihan'];
					$price = $inquiry['totalTagihan'];
					break;

				case 'bpjskes':
					$helper = new ApiHelperBPJSKes;
					$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $jumlahBulan = $this->input->post('jumlahBulan'), $noHP = $this->input->post('noHP'));
					if($inquiry['responseCode'] != "00"){
						$this->webdata->show_404();
						return;
					}
					$name = $inquiry['nama']." BPJSKES";
					$nominal = $inquiry['tagihan'];
					$price = $inquiry['total'];
					break;

				case 'bpjstk':
					$helper = new ApiHelperBPJSTK;
					$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $denom = $this->input->post('denom'), $miscData = $this->input->post('miscData'));
					if($inquiry['responseCode'] != "00"){
						$this->webdata->show_404();
						return;
					}
					$name = $inquiry['data']['transactionName'];
					$nominal = $inquiry['data']['nilaiTagihan'];
					$price = $inquiry['totalTagihan'];
					break;

				case 'pgn':
					$helper = new ApiHelperPGN;
					$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
					if($inquiry['responseCode'] != "00"){
						$this->webdata->show_404();
						return;
					}
					$name = $inquiry['nama']." PGN";
					$nominal = $inquiry['tagihan'];
					$price = $inquiry['totalTagihan'];
					break;

				default:
					$this->webdata->show_404();
					break;
			}
			$needed = $this->input->post('idPel');
			$produk_id = $this->input->post('productCode');
		}

		else if ($page == "cicilan") {
			$rules = [
				'required' => [
					['productCode'], ['idPel']
				]
			];
			$validate 	= $this->validation->check($rules,'post');
			$helper = new ApiHelperMultifinance;
			$inquiry = $helper->inquiry($this->input->post('productCode'), $this->input->post('idPel'));
			$name = $inquiry['nama'];
			$nominal = $inquiry['angsuran'] + $inquiry['denda'] + $inquiry['biayaTagih'];
			$price = $inquiry['totalBayar'];
			$needed = $this->input->post('idPel');
			$produk_id = $this->input->post('productCode');
		}

		else if($page == "topup"){
			$rules = [
							'required' 	=> [
									['topuptype'], ['productCode'], ['idPel']
							]
						];
			$validate 	= $this->validation->check($rules,'post');
			$topuptype = $this->input->post('topuptype');
			$productCode = $this->input->post('productCode');
			switch ($topuptype) {
				case 'gopay':
					$topUpList = new ApiHelperGoPay;
					$product = $topUpList->options('ppTopupProductList', 'GOJEK');
					$list = $product['productList'];
					foreach ($list as $value) {
						if($value['productCode'] == $productCode){
							$name = $value['productDesc'];
							$nominal = $value['productDenom'];
							$price = $value['productPrice'];
							break;
						}
					}
					break;

				case 'ovo':
					$topUpList = new ApiHelperOvo;
					$product = $topUpList->options('ppTopupProductList', 'GRAB');
					$list = $product['productList'];
					foreach ($list as $value) {
						if($value['productCode'] == $productCode){
							$name = $value['productDesc'];
							$nominal = $value['productDenom'];
							$price = $value['productPrice'];
							break;
						}
					}
					break;

				case 'etoll':
					$topUpList = new ApiHelperEToll;
					$product = $topUpList->options('ppTopupProductList', 'ETOLL');
					$list = $product['productList'];
					foreach ($list as $value) {
						if($value['productCode'] == $productCode){
							$name = $value['productDesc'];
							$nominal = $value['productDenom'];
							$price = $value['productPrice'];
							break;
						}
					}
					break;

				default:
					$this->webdata->show_404();
					break;
			}
			$needed = $this->input->post('idPel');
			$produk_id = $this->input->post('productCode');
		}

		else{
			$this->webdata->show_404();
			return;
		}

		$config_store 		= ConfigStoreModel::first();
		$user 				= $this->middleware->getUser();
		$transaction = new PpobTransactionModel;
		if($user){
			$transaction->id_user 	= $user->id;
		}
		$transaction->invoice = $invoice;
		$transaction->product_name = $name." ".(string) $nominal;
		$transaction->product_id = $produk_id;
		$transaction->data = $needed;
		$transaction->status = 'order';
		$transaction->total = $price;
		$transaction->moota_code = $moota_code;
		$transaction->grand_total = $moota_code + $price;
		$transaction->expired 				= date('Y-m-d h:i:s',
												strtotime(date('Y-m-d h:i:s'). ' + '.$config_store->day.' days + '.
															$config_store->hour.' hours + '.
															$config_store->min.' minutes')
												 );
		$transaction->save();
		$data['__MENU']			= 'ppob_checkout';
		$data['transaction'] 	= $transaction;
		$data['account'] 		= AccountModel::publish()->get();

		$mail 					= new Magicmailer;
		$mail->addAddress($transaction->user->email,$transaction->user->name);
			$mail->Body    			= $this->blade->draw('email.invoice.ppob-order',$data);
			$mail->Subject 			= 'Invoice Pembelian Anda';
			$mail->AltBody 			= 'Detail Invoice Pembelian anda';
		$mail->send();

		$url 		= '/PPOB/detail?invoice='.$transaction->invoice;
		redirect($url);
	}

	private function invoice_make(){
		$invoice 			= strtoupper(getToken(8));
		while (true) {
			$check_invoice 	= PpobTransactionModel::invoice($invoice)->first();
			if(!$check_invoice){
				return $invoice;
				break;
			}else{
				$invoice 	= strtoupper(getToken(8));
			}
		}
	}

	public function detail(){
		$rules = [
            'required' 	=> [
                ['invoice']
            ]
          ];
		$validate 	= $this->validation->check($rules,'get');
		if(!$validate->correct){
					$this->webdata->show_404();
		}
		$data['__MENU']			= 'ppob_checkout';
		$data['transaction'] = PpobTransactionModel::invoice($this->input->get('invoice'))->first();
		$data['account'] 		= AccountModel::publish()->get();
		echo $this->blade->draw('website.checkout.ppob-checkout',$data);
	}

  public function vouchergamePost(){
    $rules = [
            'required' 	=> [
                ['nominal_vouchergame']
            ]
          ];

    $validate 	= $this->validation->check($rules,'post');
    echo $this->input->post('nominal_vouchergame')."\n";
    return;
	}

	public function resend(){
		$rules = [
					'required' 	=> [
				    	    ['invoice']
				    ]
				  ];

		$transaction = PpobTransactionModel::invoice($this->input->get('invoice'))->first();
		$data['__MENU']			= 'checkout';
		$mail 					= new Magicmailer;
		$email['transaction'] 	= $transaction;
		$email['account'] 		= AccountModel::publish()->get();
		$mail->addAddress($transaction->user->email, $transaction->user->name);
	    $mail->Body    			= $this->blade->draw('email.invoice.ppob-order',$email);
	    $mail->Subject 			= 'Invoice Pembelian Anda';
    	$mail->AltBody 			= 'Detail Invoice Pembelian anda';
		$mail->send();
		$url 		= '/PPOB/detail?invoice='.$transaction->invoice;
		redirect($url);
	}

	public function email($invoice){
		$transaction 	= PpobTransactionModel::desc()->invoice($invoice)->first();
		$data['transaction']	= $transaction;
		$data['account'] 		= AccountModel::publish()->get();
		echo $this->blade->draw('email.invoice.ppob-order',$data);
		return;
	}
}
