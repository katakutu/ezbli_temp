<?php
class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->webdata->load();

		//expired transaction
		$transaction	= TransactionModel::where('expired', '<=', date('Y-m-d H:i:s'))->where('status','order')->get();
		foreach ($transaction as $key => $result) {
			TransactionModel::where('id',$result->id)->update(['status' => 'expired']);
		}

	}

	public function test_test(){
		// $product 	= ProductModel::leftJoin('vendors','vendors.id','=','products2.id_vendor')->orderBy('vendors.vendor_status_code','desc')->orderBy('price','desc')->get();
		$product 		= ProductModel::prioritized()->select('products2.id as product_id','vendors.id as vendor_id')->get();
		dd($product);
		$vendor 		= VendorModel::orderBy('vendor_status_code','desc')->get();

		// foreach($vendor as $result){
		// 	foreach($result->products as $p){
		// 		echo $p->id." -> ".$p->price_txt."<br>";
		// 	}
		// }
		foreach ($product as $key => $value) {
			echo $value->product_id." -> ".$value->price_txt."</br>";
		}
		die();
	}

	public function test_rajaongkir($waybill, $code){
		$data = RajaOngkir::findProvince($id_province);

		var_dump($data);
		echo '<br>';
		var_dump($data->status);
		echo '<br>';
		var_dump($data->results);
	}

	public function track_and_update_ontheway_transaction(){
		$otw_trans = TransactionModel::where('status','ontheway')->get();

		foreach ($otw_trans as $key => $one_trans) {
			$some_shipping_trans = $one_trans->shipping()->get();
			$done_status = true;

			foreach ($some_shipping_trans as $key => $one_shipping) {
				$config['waybill'] = $one_shipping->waybill;
				$config['courier'] = $one_shipping->code;

				$data_shipping 	= RajaOngkir::getWaybill($config);
				$delivered 		= $data_shipping->results->delivered;
				$summary		= $data_shipping->results->summary;

				if ($summary->status == "DELIVERED"){
					foreach ($one_shipping->detail()->get() as $key => $one_detail) {
						$one_detail->update(['status' => 'delivered']);
					}
				}
				else {
					$done_status = false;
				}
			}

			if ($done_status){
				$one_trans->update(['status' => 'done']);
			}
		}
	}

	public function email(){

		$data['vendor'] 	= VendorModel::first();
		echo $this->blade->draw('email.vendor.register',$data);
	}

	public function apiservice(){

		$data['category'] 	= ApiCategoryModel::asc('name')->get();
		echo $this->blade->draw('api.index',$data);
	}

	public function progresbar(){
		echo $this->blade->draw('website.progress.index');
	}

	public function test(){
		print_r(selisih(10,100,5));
	}

	public function expiredstatus(){
		$date 			= strtotime(date('Y-m-d H:i:s'));
		$transaksi 		= TransactionModel::where('status','order')->get();

		foreach ($transaksi as $result) {
			if (strtotime($result->expired) < $date) {
				$result->status 		= 'expired';
				$result->readed 		= 0;
				$result->save();
			}
		}
	}

	public function expiredorder(){
		$date 			= strtotime(date('Y-m-d H:i:s'));
		$transaksi 		= TransactionDetailModel::select('transactions_detail.*')
  						->join('transactions', 'transactions_detail.id_transaction','=', 'transactions.id')
  						->where('transactions.status','approved')
  						->where('transactions_detail.status','=','unconfirmed')->get();

		foreach ($transaksi as $result) {
			if (isset($result->expired_date)) {
				if (strtotime($result->expired_date) < $date) {
					$result->status 		= 'cancel';
					$result->cancel_reason 	= 'Cancel Otomatis Karena Tidak Ada Respon';
					$result->readed 		= 0;
					$result->save();
				}
			}
		}
	}

	public function index(){
		$data['category_expand']		= true;
		$data['slider'] 				= SliderModel::publish()->get();
		$data['popular'] 				= ProductModel::publish()->active()->desc('view')->take('4');
		$data['sale'] 					= ProductModel::publish()->active()->where('sale',1)->desc('view')->take('4');
		$data['bestseller'] 			= ProductModel::publish()->selectRaw("products2.* , (SELECT count(id) from transactions_detail
												where transactions_detail.id_product = products2.id) as `sold`")
												->take('5')->desc('updated_at')->orderBy('sold','desc')->get();
		$data['random_product']			= ProductModel::publish()->active()->inRandomOrder()->take(16)->get();
		$topsub 						= ProductSubCategoryModel::selectRaw("products_categories.*, (SELECT count(id) from products2
												where products2.id_subcategory = products_subcategories.id and products2.status = 'publish') as `total` ")
												->join('products_categories','products_categories.id','=','products_subcategories.id_category')
												->groupBy('products_categories.id')
												->take(2)->get();
	    $data['popular_category']	 	= $topsub;
	    $data['newest'] 				= ProductModel::publish()->desc()->take(4);
		$data['top_product_category'] 	= ProductCategoryModel::take('4')->asc('name')->get();
		$data['partnership'] 			= PartnershipModel::desc()->get();
		$data['testimoni'] 				= TestimoniModel::desc()->get();
		$topcat 						= ProductSubCategoryModel::select('products_subcategories.*')
											->join('products_categories','products_categories.id','=','products_subcategories.id_category')
											->where('products_categories.display',1)
											->pluck('id')->toArray();
		$data['category_special'] 		= ProductModel::publish()
											->whereIn('id_subcategory', $topcat)
											->desc('view')->take(4);
		$data['nama_category'] 			= ProductCategoryModel::where('display', 1)->first();
		$data['premium_product']		= ProductModel::premiumproduct()->inRandomOrder()->take(6);
		$data['premium_chosen']			= ProductModel::premiumproduct()->inRandomOrder()->take(4);

		// Update the view of each group product shown in view
		$data['popular']->increment('view');
		$data['sale']->increment('view');
		$data['newest']->increment('view');
		$data['category_special']->increment('view');
		$data['premium_product']->increment('view');
		$data['premium_chosen']->increment('view');
		// Update ends here

		$data['popular'] 			= $data['popular']->get();
		$data['sale'] 				= $data['sale']->get();
		$data['newest']				= $data['newest']->get();
		$data['category_special']	= $data['category_special']->get();
		$data['premium_product']	= $data['premium_product']->get();
		$data['premium_chosen']		= $data['premium_chosen']->get();

		$result 					= $this->filter();
		$data['search'] 		= $result->search;
		echo $this->blade->draw('website.home.index',$data);
	}

	public function list($pages){
		switch($pages){
			case "pulsa":
				$pulsaList = new ApiHelperPulsa;
				$data['id'] = NULL;
				$list = $pulsaList->produk($data);
				$product = array_column($list, 'voucher');
				$product = array_unique($product);
				foreach ($product as $value) {
						echo "<option value='".$value."'>".$value."</option>";
				}
				break;

			case "vouchergame":
				$voucherGameList = new ApiHelperVoucherGame;
				$productID = NULL;
				$list = $voucherGameList->productListGame($productID);
				$product = array_column($list, 'voucher');
				$product = array_unique($product);
				foreach ($product as $value) {
						echo "<option value='".$value."'>".$value."</option>";
				}
				break;

			case "gopay":
				$topUpList = new ApiHelperGoPay;
				$product = $topUpList->options($cmd = 'ppTopupProductList', $data = 'GOJEK');
				$list = $product['productList'];
				foreach ($list as $value){
					echo "<option value='".$value['productCode']."'>".$value['productDesc']."</option>";
				}
				break;

			case "ovo":
				$topUpList = new ApiHelperOvo;
				$product = $topUpList->options($cmd = 'ppTopupProductList', $data = 'GRAB');
				$list = $product['productList'];
				foreach ($list as $value){
					echo "<option value='".$value['productCode']."'>".$value['productDesc']."</option>";
				}
				break;

			case "etoll":
				$topUpList = new ApiHelperEToll;
				$product = $topUpList->options($cmd = 'ppTopupProductList', $data = 'ETOLL');
				$list = $product['productList'];
				foreach ($list as $value){
					echo "<option value='".$value['productCode']."'>".$value['productDesc']."</option>";
				}
				break;

			case "kaistation":
				$helper = new ApiHelperKAI;
				$stationList = $helper->kai_station();
				foreach ($stationList as $value) {
					echo "<option value='".$value['code']."'>".$value['name']." (".$value['group'].")"."</option>";
				}
				break;

			case "kaischedule":
				$helper = new ApiHelperKAI;
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal')))
				];
				echo "<option selected disabled>- Pilih Kereta</option>";
				$schedule = $helper->kai_search($data);
				$trainNumber = array_unique(array_column($schedule['schedule'], 'trainNumber'));
				$trainNumber = array_values($trainNumber);
				if($schedule['errCode'] != "0"){
					echo "<option value=''>".$schedule['msg']."</option>";
				}
				else{
					$i=0;
					foreach($schedule['schedule'] as $value) {
						if($value['trainNumber'] == $trainNumber[$i]){
							echo "<option value='".$trainNumber[$i]."'>".$value['trainName']." (".$value['departTime']."-".$value['arriveTime'].")</option>";
							$i++;
							if($i >= count($trainNumber)) break;
						}
					}
					break;
				}
				break;

			case "kaiclass":
				$helper = new ApiHelperKAI;
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
					'no_kereta' => $this->input->post('no_kereta'),
				];
				echo "<option selected disabled>- Pilih Kelas</option>";
				$schedule = $helper->kai_search($data);
				$flag=0;
				foreach ($schedule['schedule'] as $value) {
					if($value['trainNumber'] == $data['no_kereta']){
						if($value['codeClass'] == "E"){
							echo "<option value='EKS'>Eksekutif (".$value['adult'].")</option>";
							$flag++;
						}
						else if($value['codeClass'] == "B"){
							echo "<option value='BISBIS'>Bisnis (".$value['adult'].")</option>";
							$flag++;
						}
						else if($value['codeClass'] == "K"){
							echo "<option value='EKON'>Ekonomi (".$value['adult'].")</option>";
							$flag++;
						}
						if($flag==3) break;
					}
				}
				break;

			case "kaigerbong":
				$helper = new ApiHelperKAI;
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
					'no_kereta' => $this->input->post('no_kereta'),
					'kode_gerbong' => $this->input->post('kode_gerbong'),
				];
				echo "<option selected disabled>- Pilih Gerbong</option>";
				$seatmap = $helper->kai_seatmap($data);
				$gerbong = $seatmap['seat_map'];
				foreach ($gerbong as $value) {
					if($value[0] == $this->input->post('kode_gerbong')){
						echo "<option value='".$value[1]."'>".$value[1]."</option>";
					}
				}
				break;

			case "kaiseat":
				$helper = new ApiHelperKAI;
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
					'no_kereta' => $this->input->post('no_kereta'),
					'kode_gerbong' => $this->input->post('kode_gerbong'),
					'no_gerbong' => $this->input->post('no_gerbong')
				];

				$seatmap = $helper->kai_seatmap($data);
				foreach ($seatmap['seat_map'] as $value) {
					if($value[0] == $this->input->post('kode_gerbong') && $value[1] == $this->input->post('no_gerbong')){
						foreach ($value[2] as $key) {
							if($key[5] == 0)
								echo "<option value='".$key['2'].$key['3']."'>".$key['2'].$key['3']."</option>";
						}
						break;
					}
				}
				break;

			case "travelGetAgen":
				$helper = new ApiHelperTravel;
				$travelGetAgen = $helper->travelGetAgen();
				echo "<option value='' selected disabled>- Pilih Agen</option>";
				foreach ($travelGetAgen['agen'] as $value) {
					echo "<option value='".$value['kode']."'>".$value['nama']."</option>";
				}
				break;

			case "travelGetKeberangkatan":
				$helper = new ApiHelperTravel;
				$travelGetKeberangkatan = $helper->travelGetKeberangkatan($kodeAgen = $this->input->post('kodeAgen'));
				echo "<option value='' selected disabled>- Pilih Keberangkatan</option>";
				foreach ($travelGetKeberangkatan['result'] as $value) {
					foreach ($value['keberangkatan'] as $key) {
						echo "<option value='".$key['id']."'>".$key['cabangAsal']." (".$value['asal'].")"."</option>";
					}
				}
				break;

			case "travelGetKedatangan":
				$helper = new ApiHelperTravel;
				$travelGetKedatangan = $helper->travelGetKedatangan($kodeAgen = $this->input->post('kodeAgen'), $idKeberangkatan = $this->input->post('idKeberangkatan'));
				foreach ($travelGetKedatangan['result'] as $value) {
					foreach ($value['kedatangan'] as $key) {
						echo "<option value='".$key['id']."'>".$key['cabangTujuan']." (".$value['tujuan'].")"."</option>";
					}
				}
				break;

			case "travelGetJadwal":
				$helper = new ApiHelperTravel;
				$travelGetJadwalKeberangkatan = $helper->travelGetJadwalKeberangkatan($kodeAgen = $this->input->post('kodeAgen'), $idKeberangkatan = $this->input->post('idKeberangkatan'), $idKedatangan = $this->input->post('idKedatangan'), $tanggal = $this->input->post('tanggal'), $penumpang = $this->input->post('penumpang'));
				foreach ($travelGetJadwalKeberangkatan['result'] as $value) {
					foreach ($value['kedatangan'] as $key) {
						echo "<option value='".$key['id']."'>".$key['cabangTujuan']." (".$value['tujuan'].")"."</option>";
					}
				}
				break;

			default:
				$this->webdata->show_404();
		}
	}

	public function nominal_pulsa($voucher){
		$pulsaList = new ApiHelperPulsa;
		$nominal = array();
		$data['id'] = NULL;
		$list = $pulsaList->produk($data);
		foreach ($list as $key => $value) {
			if($value['voucher'] == $voucher){
				echo "<option value='".$value['produk_id']."'>".$value['nominal']."</option>";
			}
		}
	}

	public function nominal_vouchergame($voucher){
		$pulsaList = new ApiHelperVoucherGame;
		$nominal = array();
		$productID = NULL;
		$list = $pulsaList->productListGame($data);
		foreach ($list as $key => $value) {
			if($value['voucher'] == $voucher){
				echo "<option value='".$value['productID']."'>".$value['nominal']."</option>";
			}
		}
	}

	public function about(){
		$this->webdata->show_404();
		$data['__MENU']		= 'about';
		echo $this->blade->draw('website.about.index',$data);
	}

	public function contact($page="index"){
		$this->webdata->show_404();
		$data['__MENU']		= 'contact';

		if($page=="index"){
			echo $this->blade->draw('website.about.index',$data);
		}
		else if($page=="submit"){

			$this->validation->ajaxRequest();

			$rules = [
					    'required' 	=> [
					        ['name'],['email'],['subject'],['message']
					    ],
					    'email'		=> [
					    	['email']
					    ]
					  ];

			$validate 	= $this->validation->check($rules,'post');

			if(!$validate->correct){
				echo goResult(false,$validate->data);
				return;
			}

			$inbox 			= new InboxModel;
			$inbox->name 	= $this->input->post('name');
			$inbox->email 	= $this->input->post('email');
			$inbox->subject = $this->input->post('subject');
			$inbox->phone 	= $this->input->post('phone');
			$inbox->message = $this->input->post('message');

			if(!$inbox->save()){
				echo goResult(false,"maaf ada kesalahan silahkan coba kembali");
				return;
			}

			echo goResult(true,"Pesan anda telah dikirimkan");
			return;

		}
		else{
			$this->webdata->show_404();
		}

	}

	public function subscribe(){

		$this->validation->ajaxRequest();

		$rules = [
				    'required' 	=> [
				        ['email']
				    ],
				    'email'		=> [
				    	['email']
				    ]
				  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			echo goResult(false,$validate->data);
			return;
		}

		$subscribe 			= new SubscribesModel;
		$subscribe->email 	= $this->input->post('email');

		if(!$subscribe->save()){
			echo goResult(false,"maaf ada kesalahan silahkan coba kembali");
			return;
		}

		echo goResult(true,"success");
		return;

	}

	public function confirmation($page="index"){
		$data['__MENU']		= 'confirmation';

		if($page=="index"){
			$data['account']	= AccountModel::publish()->desc()->get();
			echo $this->blade->draw('website.confirmation.index',$data);
			return;
		}
		else if($page=="submit"){

			$this->validation->ajaxRequest();

			$rules = [
					    'required' 	=> [
					        ['name'],['email'],['phone'],['account'],['total'],
					        ['account_name'],['account_bank'],['account_number']
					    ],
					    'email'		=> [
					    	['email']
					    ],
					    'numeric'	=> [
					    	['total']
					    ]
					  ];

			$validate 	= $this->validation->check($rules,'post');

			if(!$validate->correct){
				echo goResult(false,$validate->data);
				return;
			}

			$account 					= AccountModel::publish()->find($this->input->post('account'));

			if(!$account){
				echo goResult(false,"Maaf akun rekening tidak di temukan");
				return;
			}

			$confirmation 						= new ConfirmationModel;
			$confirmation->name 				= $this->input->post('name');
			$confirmation->email 				= $this->input->post('email');
			$confirmation->phone 				= $this->input->post('phone');

			$confirmation->account_name 		= $this->input->post('account_name');
			$confirmation->account_bank 		= $this->input->post('account_bank');
			$confirmation->account_number 		= $this->input->post('account_number');

			$confirmation->admin_account_name 	= $account->name;
			$confirmation->admin_account_bank 	= $account->bank;
			$confirmation->admin_account_number	= $account->number;
			$confirmation->total 				= $this->input->post('total');

			if(empty($_FILES['image']['name'])){
				echo goResult(false,"gambar bukti transfer harus di isi");
				return;
			}

			$filename 						= 'CONFIRMATION '.limit_string($this->input->post('name')).' ('.date('Ymdhis').')';

			$upload 						= $this->upfiles->uploadImage(content_dir('images/lg/confirmation'),'image',$filename);

			if(!$upload->status){
				echo goResult(false,$upload->result);
				return;
			}

			$filename 				= $upload->result->file_name;

			$confirmation->image 		= $filename;

			if(!$confirmation->save()){
				echo goResult(false,"maaf ada kesalahan silahkan coba kembali");
				return;
			}

			echo goResult(true,"success");
			return;
		}
		else{
			$this->webdata->show_404();
		}
	}

	public function track($page="index"){

		$data['__MENU']		= 'transaction';

		if($page=="index"){

			if($this->input->get('invoice')){

				$transaction 	= TransactionModel::invoice($this->input->get('invoice'));

				if(isset($transaction->id)){
					$data['transaction'] 	= $transaction;
				}
			}

			echo $this->blade->draw('website.tracking.index',$data);
			return;

		}
		else if($page=="waybill"){

			$this->validation->ajaxRequest();

			$rules = [
					    'required' 	=> [
					        ['invoice'],['transaction']
					    ]
					  ];

			$validate 	= $this->validation->check($rules,'post');

			if(!$validate->correct){
				echo goResult(false,$validate->data);
				return;
			}

			$transaction 	= TransactionModel::invoice($this->input->post('invoice'));

			if(!$transaction){
				echo goResult(false,"Transaksi tidak di temukan");
				return;
			}

			$detail 		= TransactionDetailModel::where('id_transaction',$transaction->id)
														->find($this->input->post('transaction'));

			if($detail->waybill=="" || $detail->waybill==null){
				echo goResult(false,"Maaf No Resi Tidak Tersedia");
				return;
			}

			$config['waybill'] 			= $detail->waybill;
			$config['courier'] 			= strtolower($detail->transaction->courier_code);

			$waybill 					= RajaOngkir::getWaybill($config);

			if(!$waybill->auth){
				echo goResult(false,$waybill->msg);
				return;
			}

			$data['waybill'] 			= $waybill->msg;
			echo goResult(true,$this->blade->nggambar('website.tracking.waybill',$data));
			return;

		}
		else{
			$this->webdata->show_404();
		}
	}

	public function tos($page="index",$id=null){

		$data['__MENU'] 	= 'tos';

		if($page=="index"){

			$data['tos']	= TosModel::aktif()->desc()->get();
			echo $this->blade->draw('website.tos.index',$data);
			return;

		}else if($page=="detail" && $id!=null){

			$tos 			= TosModel::find($id);

			if(!$tos){
				$this->webdata->show_404();
			}

			$data['tos'] 	= $tos;
			echo $this->blade->draw('webdata.tos.content',$data);
			return;

		}else{
			$this->webdata->show_404();
		}
	}

	public function faq($page="index",$id=null){

		$data['__MENU'] 	= 'tos';

		if($page=="index"){

			// $data['faq_category']	= FaqCategoryModel::get();
			$data['faq']			= FaqModel::aktif()->get();

			echo $this->blade->draw('website.faq.index',$data);
			return;
		}else if($page=="detail" && $id!=null){

			$faq 	= FaqModel::find($id);

			if(!$faq){
				$this->webdata->show_404();
			}

			$data['faq'] 	= $faq;
			echo $this->blade->draw('website.faq.content',$data);
			return;
		}else if($page=="category" && $id !=null ){

			$category 			= FaqCategoryModel::find($id);

			if(!$category){
				$this->webdata->show_404();
			}

			$data['category'] 	= $category;
			echo $this->blade->draw('website.faq.content',$data);
			return;
		}else{
			$this->webdata->show_404();
		}

	}

	public function testimoni($page="index",$id=null){

		$data['__MENU'] 	= 'testimoni';

		if($page=="index"){

			$data['testimoni'] 	= TestimoniModel::desc()->get();
			echo $this->blade->draw('website.testimoni.index',$data);
			return;

		}else if($page=="detail" && $id!=null){

			$testimoni 			= TestimoniModel::find($id);

			if(!$testimoni){
				$this->webdata->show_404();
			}

			$data['testimoni'] 	= $testimoni;
			echo $this->blade->draw('website.testimoni.content',$data);
			return;

		}else{

			$this->webdata->show_404();

		}
	}

// additional
	public function dashboard($page='index',$id=null){
		$data['__MENU']		= 'dashboard';
		if ($page=='historytransaction') {
			if(!is_numeric($page)){
				$page 	= 0;
			}

			if ($this->input->get('per_page')) {
				$page 	= $this->input->get('per_page');
			}

			$paginate					= new Aksa_pagination;
			$data['page']				= $page;
			$data['total']				= TransactionModel::get();
			$data['transaction']  		= TransactionModel::take(10)->skip($page*10)->get();
			$data['pagination'] 		= $paginate->paginate(base_url('main/dashboard/historytransaction/'),3,10,count($data['total']),$page);
			echo $this->blade->draw('user.dashboard.history',$data);
		} elseif($page=='detailtransaction' && $id!=null) {
			$data['transaction']  		= TransactionModel::find($id);
			echo $this->blade->draw('user.dashboard.detailtransaction',$data);
		} elseif ($page=='profile') {
			$data['user']				= UserModel::find(4);
			// echo json_encode($data['user']);
			// echo "<br>";
			// echo json_encode(DefuseLib::decrypt($data['user']->password));
			// exit();
			echo $this->blade->draw('user.dashboard.profile',$data);
		} else {
			echo $this->blade->draw('user.dashboard.index',$data);
		}
	}
	public function dashboard_2($url='page',$page=0){
		$data['__MENU']		= 'dashboard';
		// pagination
		if(!is_numeric($page)){
			$page 	= 0;
		}

		if ($this->input->get('per_page')) {
			$page 	= $this->input->get('per_page');
		}

		$paginate					= new Aksa_pagination;
		$data['page']				= $page;
		$data['total']				= TransactionModel::get();
		$data['transaction']  		= TransactionModel::take(10)->skip($page*10)->get();
		$data['pagination'] 		= $paginate->paginate(base_url('main/dashboard/page/'),3,10,count($data['total']),$page);
		echo $this->blade->draw('user.dashboard.history',$data);
	}

// end additional function




	public function error(){
		echo $this->blade->draw('website.error.404');
// 		$this->webdata->show_404();
	}

	private function filter(array $option=[]){

		$search 				= [
										'category' 		=> 'all',
										'subcategory' 	=> null,
										'price_min' 	=> 0,
										'price_max' 	=> 10000000,
										'order'			=> 'newest',
										'view' 			=> 'grid',
										'show' 			=> 16
									  ];

		$product 				= ProductModel::publish();

		$keyword 				= $this->input->get('q');

		$product 				= $product->where('name','like','%'.$keyword.'%');

		$all_order 				= ['newest','highest','lowest'];
		$all_view 				= ['grid','list'];


		$data_category 			= [];

		$search['view'] 		= ($this->input->get('view')=="list") ? 'list' : 'grid';

		$search['category'] 	= 'all';
		$search['subcategory'] 	= null;

		$id 				= (isset($option['category'])) ? $option['category'] : $this->input->get('category');
		$category 			= ProductCategoryModel::find($id);
		if(isset($category->id)){

			$search['category'] = $category->id;

			if(count($category->subs)>0){
				foreach ($category->subs as $result) {
					$data_category[]  = $result->id;
				}
			}else{
				$data_category[] 	= null;
			}


		}

		$id 				= (isset($option['subcategory'])) ? $option['subcategory'] : $this->input->get('subcategory');
		$subcategory 		= ProductSubCategoryModel::find($id);

		if(isset($subcategory->id)){
			$search['category']    	= $subcategory->category->id;
			$search['subcategory'] 	= $subcategory->id;
			$search['data_sub'] 	= $subcategory->category->subs;

			unset($data_category);
			$data_category[]  		= $subcategory->id;
		}

		if(count($data_category)>0){
			$product 				= $product->whereIn('id_subcategory',$data_category);
		}

		if($this->input->get('price_min') || $this->input->get('price_max')){
			$price_min 			= (int) ($this->input->get('price_min')) ? $this->input->get('price_min') : 0;
			$price_max 			= (int) ($this->input->get('price_max')) ? $this->input->get('price_max') : 5000000;
			$product 			= $product->whereBetween('price', [$price_min, $price_max]);

			$search['price_min'] = $price_min;
			$search['price_max'] = $price_max;
		}

		$all_show 				= ['12','24','30'];

		$order 					= (!in_array($this->input->get('order'), $all_order)) ? 'newest' : $this->input->get('order');
		$view 					= (!in_array($this->input->get('view'), $all_view)) ? 'grid' : $this->input->get('view');
		$show 					= (!in_array($this->input->get('show'), $all_show)) ? '12' : $this->input->get('show');
		$page 					= (int) ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

		$search['order'] 		= $order;
		$search['view'] 		= $view;
		$search['show'] 		= $show;

		$total 					= $product->get()->count();
		$take 					= $show;

		switch ($order) {
			case 'highest':
				$product 				= $product->orderBy('price','desc')->take($take)->skip($page*$take)->get();
				break;
			case 'lowest':
				$product 				= $product->orderBy('price','asc')->take($take)->skip($page*$take)->get();
				break;

			default:
				$product 				= $product->orderBy('id','desc')->take($take)->skip($page*$take)->get();
				break;
		}

		$link 					= preg_replace('~(\?|&)per_page=[^&]*~','',$_SERVER['REQUEST_URI']);
		$paginate				= new Aksa_pagination;
		$pagination 			= $paginate->paginate($link,5,$take,$total,$page);

		$data['search'] 		= $search;
		$data['search'] 		= false;
		$data['product'] 		= false;
		$data 					= toObject($data);

		$data->search 			= toObject($search);
		$data->product 			= $product;
		$data->pagination 		= $pagination;

		return $data;
	}

}
