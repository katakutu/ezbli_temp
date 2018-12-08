<?php
class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->webdata->load();
		$this->MobileRestapi = new MobileRestapi;
		$this->MobileRestapi->basicValidateKey();
	}

//bisa
	public function index(){
		// $this->MobileRestapi->response('OK, API KEY VALID');
		$province 			= RajaOngkir::getProvince();
		$this->MobileRestapi->response($province);
	}

//bisa
	public function homedata(){
		$data['bestseller'] 	= ProductModel::selectRaw("products2.* , (SELECT count(id) from transactions_detail
												where transactions_detail.id_product = products2.id) as `sold`")
												->take('6')->desc('updated_at')->orderBy('sold','desc')->get();
	    $data['newest'] 				= ProductModel::publish()->desc()->take(6)->get();
		$this->MobileRestapi->response($data);
	}

// LOGIN

	private function authorization(){
		$result 	= toObject(apache_request_headers());
		// $this->MobileRestapi->response(goExplode($result->Authorization,' ',0));
		if(!isset($result->Authorization)){
			$this->MobileRestapi->error("Opps! Authorization Header Required");
		}

		if(goExplode($result->Authorization,' ',0)!=="User"){
			$this->MobileRestapi->error("Invalid Authorization");
		}

		$token 		= goExplode($result->Authorization,' ',1);

		if(!$token){
			$this->MobileRestapi->error("Invalid Authorization");
		}

		$jwt 		= toObject(JWT::decode($token));


		if(!isset($jwt->data)){
			$this->MobileRestapi->error("Invalid Token Authorization");
		}

		$now 		= strtotime(date('Y-m-d h:i:s A T'));

		if($now>$jwt->exp){
			$this->MobileRestapi->error("JWT Token Already Expired from ".date("Y-m-d h:i:s A T",$jwt->exp)." please try sign in again!");
		}

		$userToken 	= DefuseLib::decrypt($jwt->data->token);

		$user 		= UserModel::active()->token($userToken)->first();
		if(!$user){
			$this->MobileRestapi->error("Opps! User Not Found");
		}

		return $user;
	}


	private function getuser(){
		$result 	= toObject(apache_request_headers());

		if(!isset($result->Authorization)){
			return false;
		}

		if(goExplode($result->Authorization,' ',0)!=="User"){
			return false;
		}

		$token 		= goExplode($result->Authorization,' ',1);

		if(!$token){
			return false;
		}

		$jwt 		= toObject(JWT::decode($token));


		if(!isset($jwt->data)){
			return false;
		}

		$now 		= strtotime(date('Y-m-d h:i:s A T'));

		if($now>$jwt->exp){
			return false;
		}

		$userToken 	= DefuseLib::decrypt($jwt->data->token);

		$user 		= UserModel::active()->token($userToken)->first();
		if(!$user){
			return false;
		}

		return $user;
	}


	public function getinfo(){
		$user 		= $this->authorization();
		$this->MobileRestapi->response($user->toArray());
	}

//gphm
	public function authentication(){
		$rules = [
					    'required' 	=>	[
					    					['username'],
					    					['password'],
					    				]
					];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->response($validate->data);
		}

		$username 	= $this->input->post('username');

		 if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
	        $user	= UserModel::where('email',$username)->first();
	        if(!$user){
						$this->MobileRestapi->error("Maaf Email belum terdaftar");
					}
	    }
	    else {
	        $user	= UserModel::where('phone',$username)->first();
	        if(!$user){
						$this->MobileRestapi->error("Maaf No Telepon belum terdaftar");
					}
	    }

		if($user->status == "register"){
			$this->MobileRestapi->error("Selesaikan pendaftaran anda terlebih dahulu");
		}

		if($user->status == "blocked"){
			$this->MobileRestapi->error("Maaf akun anda telah kami non aktifkan untuk berberapa alasan, silahkan hubungi kami");
		}

		$password 				= DefuseLib::decrypt($user->password);

		if($password!==$this->input->post('password')){

			if($user->password_old!=""){
				$password_old 			= DefuseLib::decrypt($user->password_old);

				if($password_old===$this->input->post('password')){
					$this->MobileRestapi->error("itu merupakan password lama anda pada ".tgl_indo($user->password_old_date));
				}
			}

			$this->MobileRestapi->error("maaf password yang anda masukkan tidak sesuai");
		}

		$user->ipaddress 		= $this->input->ip_address();
		$user->save();

		if(!$user->save()){
			$this->MobileRestapi->error("ada sesuatu yang salah silahkan coba kembali");
		}

		$issuedAt   = time();
		$notBefore  = $issuedAt + 10;  //Adding 10 seconds
		$expire     = $notBefore + 604800; // Adding 60 seconds
		$serverName = base_url(); /// set your domain name

		$data = [
		    'iat'  => $issuedAt,         // Issued at: time when the token was generated
		    'jti'  => $user->token,
		    'iss'  => $serverName,       // Issuer
		    'nbf'  => $notBefore,        // Not before
		    'exp'  => $expire,           // Expire
		    'data' => [                  // Data related to the logged user you can set your required data
						'token'   	=> DefuseLib::encrypt($user->token), 	 // id from the users table
		              ]
		];

		$token = JWT::encode($data);
		$data['user']	= $user->toArray();
		$data['token']	= $token;
		$this->MobileRestapi->response($data);
	}

	public function open_ticket($url="index", $id=NULL){
		if (!$this->session->userdata('auth_user')) {
			redirect(base_url('masuk'));
		}
		$data['__MENU'] = 'open_ticket';
		switch($url){

			case "index":
				$data['data'] = OpenTicketModel::where('id_vendor', $this->vendor->id)->where('receiver', 'vendor')->get();
				echo $this->blade->draw('vendor.support_ticket.index', $data);

				return;

			case "detail":
				$update = OpenTicketModel::where('id', $id)->update(['readed'=>1]);
				$data['data'] = OpenTicketModel::where('id', $id)->first();
				echo $this->blade->draw('vendor.support_ticket.content', $data);
				return;

			case "reply":
				$message = OpenTicketModel::where('invoice', $id)->orderBy('created_at','desc')->first();
				if($message->id_vendor == $this->vendor->id){
					$rules = [
						'required' => [
							['message'],
						]
					];
					$data = new OpenTicketModel;
					$validate = $this->validation->check($rules, 'post');

					if(!$validate->correct){
						$this->restapi->error($validate->data);
					}
					$data->id_vendor = $this->vendor->id;
					$data->id_user = $message->id_user;
					$data->invoice = $id;
					$data->category = $message->category;
					$data->message = $this->input->post('message');
					$data->receiver = 'customer';
					$data->subject = $message->subject;
					$data->save();

					redirect('/vendor/open_ticket');

				}
		}
	}

	public function open_ticket_user($url="index", $id=NULL){
		if (!$this->session->userdata('auth_user')) {
			redirect(base_url('masuk'));
		}
		switch($url){
			case "index":
				$data['data'] = OpenTicketModel::where('id_user', $this->user->id)->where('receiver', 'customer')->get();
				echo $this->blade->draw('user.support_ticket.index', $data);
				return;
			case "submit":
				$rules = [
					'required' =>[
						['category'],
						['judul'],
						['invoice'],
					]
				];

				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->restapi->error($validate->data);
					return;
				}

				$invoice 	= TransactionModel::where('invoice', $this->input->post('invoice'))->first();
				if(!$invoice){
					$this->restapi->error("Maaf produk tidak di temukan !");
					return;
				}

				if($_FILES['image']['name']!=""){

				$filename 				= 'TICKET '.limit_string($this->user->name).'  ('.date('Ymdhis').')';

				$upload 				= $this->upfiles->uploadImage(content_dir('images/paycheck'),'image',$filename);

				if(!$upload->status){
					$error_upload 		= $upload->result;
					$this->restapi->error($upload->result);
				}

				$filename 				= $upload->result->file_name;

				$option['origin']		= content_dir('images/lg/users/'.$filename);
				$option['filename']		= $filename;
			}

				$id_vendor = TransactionDetailModel::where('id_transaction', $invoice->id)->first();
				$message = new OpenTicketModel;
				$message->id_user 		= $this->user->id;
				$message->id_vendor		= $id_vendor->id_vendor;
				$message->subject	= $this->input->post('judul');
				$message->invoice	= $this->input->post('invoice');
				$message->category	= $this->input->post('category');
				$message->attachment 	= $filename;
				$message->receiver = "vendor";
				$message->message	= $this->input->post('message');

				if(!$message->save()){
					$this->restapi->error("Maaf ada sesuatu yang salah coba kembali nanti");
				}

				else{
					redirect('/user/open_ticket/');
				}

			case "detail":
				$update = OpenTicketModel::where('id', $id)->update(['readed' => '1']);
				$data['data'] = OpenTicketModel::where('id_user', $this->user->id)->where('receiver', 'customer')->get();
				$data['content'] = OpenTicketModel::where('id', $id)->first();
				if($data['data']){
					echo $this->blade->draw('user.support_ticket.content', $data);
					return;
				}
				else{
					$this->webdata->show_404();
				}

			case "reply":
				$message = OpenTicketModel::where('invoice', $id)->orderBy('created_at', 'desc')->first();
				if($message->id_user == $this->user->id){
					$rules = [
						'required' => [
							['message'],
						]
					];
					$data = new OpenTicketModel;
					$validate = $this->validation->check($rules, 'post');

					if(!$validate->correct){
						$this->restapi->error($validate->data);
					}
					$data->id_user = $this->user->id;
					$data->id_vendor = $message->id_vendor;
					$data->invoice = $id;
					$data->message = $this->input->post('message');
					$data->receiver = 'vendor';
					$data->subject = $message->subject;
					$data->save();

					redirect('/user/open_ticket');
				}

			default:
				$this->webdata->show_404();
			}
		}

// TRACKING
//fix
	public function tracking(){

		$rules 		= [
						'required' 	=> [
					        ['invoice']
					    ],
					  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}


		$transaction 		= TransactionModel::with('shipping.detail')->invoice($this->input->post('invoice'))->first();

		if(!$transaction){
			$this->MobileRestapi->error("Invoice Not Found!");
		}

		$this->MobileRestapi->response($transaction->toArray());

	}



// Register USER
// fix
	public function registeruser(){
		$rules 		= [
					    'required' 	=> [
					        ['name'],['email'],['phone']
					    ],
					    'email'		=> [
					    	['email']
					    ]
					  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}


		$user 	= UserModel::where('email',$this->input->post('email'))->first();

		if(isset($user->id)){
			if($user->status=="active" || $user->status=="blocked"){
				$this->MobileRestapi->error("maaf email telah terpakai");
			}

			if($user->image!=""){
				remFile(content_dir('images/lg/users/'.$user->image));
				remFile(content_dir('images/md/users/'.$user->image));
				remFile(content_dir('images/sm/users/'.$user->image));
				remFile(content_dir('images/xs/users/'.$user->image));
			}

			$user->delete();
		}

		$user_phone 		= UserModel::where('phone',$this->input->post('phone'))->first();

		if(isset($user_phone->id)){
			if($user_phone->status=="active" || $user_phone->status=="blocked"){
				$this->MobileRestapi->error("maaf no telepon telah terpakai");
			}

			if($user_phone->image!=""){
				remFile(content_dir('images/lg/users/'.$user->image));
				remFile(content_dir('images/md/users/'.$user->image));
				remFile(content_dir('images/sm/users/'.$user->image));
				remFile(content_dir('images/xs/users/'.$user->image));
			}

			$user_phone->delete();
		}

		$user 				= new UserModel;
		$user->token 		= $this->middleware->freshToken('user');
		$user->name 		= remove_symbols($this->input->post('name'));
		$user->email 		= $this->input->post('email');
		$user->phone 		= $this->input->post('phone');
		$user->status 		= 'register';
		$user->ipaddress 	= $this->input->ip_address();

		if(!$user->save()){
			$this->MobileRestapi->error("maaf ada kesalahan silahkan ulangi kembali");
		}

		$tmp['user'] 			= $user;
		// Sending As Email
		$mail 					= new Magicmailer;
	    $mail->addAddress($user->email, $user->name);
	    $mail->Body    			= $this->blade->draw('email.user.register',$tmp);
	    $mail->Subject 			= 'Konfirmasi Email Pendaftaran anda';
    	$mail->AltBody 			= 'silahkan aktivasi email pendaftaran anda';
		$mail->send();

		// Sending As SMS
		$text 					= string_newline($this->blade->draw('sms.registeruser',$tmp));
		//$this->zenzivasms->send($user->phone,$text);

		$this->MobileRestapi->success($user);
		// break;
	}

//fix insyaallah
	public function resendregisteruser(){
		$rules 		= [
					    'required' 	=> [
					        ['email']
					    ],
					    'email'		=> [
					    	['email']
					    ]
					  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$user 			= UserModel::status('register')
									->where('email',$this->input->post('email'))->first();

		if(!$user){
			$this->MobileRestapi->error("maaf pengguna tidak di temukan!");
		}

		$user->token 		= $this->middleware->freshToken('user');
		if(!$user->save()){
			$this->MobileRestapi->error("maaf ada sesuatu yang salah silahkan coba kembali");
		}

		$tmp['user'] 	= $user;
		// Sending As Email
		$mail 			= new Magicmailer;
	    $mail->addAddress($user->email, $user->name);
	    $mail->Body    	= $this->blade->draw('email.user.register',$tmp);
	    $mail->Subject 	= 'Konfirmasi Email Pendaftaran anda';
    	$mail->AltBody 	= 'silahkan aktivasi email pendaftaran anda';
		$mail->send();

		// Sending As SMS
		$text 					= string_newline($this->blade->draw('sms.registeruser',$tmp));
		//$this->zenzivasms->send($user->phone,$text);

		$this->MobileRestapi->success($user);
	}

//fix
	public function confirmregisteruser(){
		$rules 		= [
					    'required' 	=> [
					        ['token']
					    ],
					    'length'		=> [
					    	['token',4]
					    ]
					  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$token 		= strtoupper($this->input->post('token'));

		$user 		= UserModel::status('register')->token($token)->first();

		if(!$user){
			$this->MobileRestapi->error("maaf token yang anda masukkan salah");
		}

		$this->MobileRestapi->success($user);

	}

//insyaallah fix (blm coba)
	public function submitregisteruser(){
		$rules 		= [
					    'required' 	=> [
					        ['token'],['name'],
					        ['password'],['password_confirmation'],
					        ['province'],['city'],['district'],
					        ['address'],['agreement']
					    ],
					    'equals'	=> [
					    	['password_confirmation','password']
					    ],
					    'length'		=> [
					    	['token',4]
					    ]
					  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$token 		= strtoupper($this->input->post('token'));

		$user 		= UserModel::status('register')->token($token)->first();

		if(!$user){
			$this->MobileRestapi->error("maaf pengguna yang baru terdaftar tidak di temukan");
		}

		$province 		= $this->input->post('province');
		$city 			= $this->input->post('city');
		$district 		= $this->input->post('district');

		// Check destination
		$destination 		= RajaOngkir::findDistrict(goExplode($district,'//',0),goExplode($city,'//',0),goExplode($province,'//',0));

		if(!$destination->status){
			$this->MobileRestapi->error($destination->results);
		}

		if(!empty($_FILES['image'])){

			$filename 				= 'USER '.limit_string($user->name).'  ('.date('Ymdhis').')';

			$upload 				= $this->upfiles->uploadImage(content_dir('images/lg/users'),'image',$filename);

			if(!$upload->status){
				$error_upload 		= $upload->result;
				$this->MobileRestapi->error($upload->result);
			}

			$filename 				= $upload->result->file_name;

			$option['origin']		= content_dir('images/lg/users/'.$filename);
			$option['filename']		= $filename;

			// RESIZE TO MEDIUM
			$option['size']			= 350;
			$option['path']			= content_dir('images/md/users/');
			$this->upfiles->resize($option);

			// RESIZE TO SMALL
			$option['size']			= 150;
			$option['path']			= content_dir('images/sm/users/');
			$this->upfiles->resize($option);

			// RESIZE TO SMALL
			$option['size']			= 80;
			$option['path']			= content_dir('images/xs/users/');
			$this->upfiles->resize($option);

			if($user->image!=""){
				remFile(content_dir('images/lg/users/'.$user->image));
				remFile(content_dir('images/md/users/'.$user->image));
				remFile(content_dir('images/sm/users/'.$user->image));
				remFile(content_dir('images/xs/users/'.$user->image));
			}

			$user->image 			= $filename;
		}

		$user->token 	= getToken(20).date('Ymdhis');
		$user->password = DefuseLib::encrypt($this->input->post('password'));
		$user->name 	= $this->input->post('name');
		$user->address 	= $this->input->post('address');
		$user->zipcode 	= $this->input->post('zipcode');
		$user->province = $province;
		$user->city 	= $city;
		$user->district = $district;
		$user->status 	= 'active';
		if(!$user->save()){
			$this->MobileRestapi->error("maaf ada yang salah , silahkan ulang kembali");
		}

		$issuedAt   = time();
		$notBefore  = $issuedAt + 10;  //Adding 10 seconds
		$expire     = $notBefore + 604800; // Adding 60 seconds
		$serverName = base_url(); /// set your domain name

		$data = [
		    'iat'  => $issuedAt,         // Issued at: time when the token was generated
		    'jti'  => $user->token,
		    'iss'  => $serverName,       // Issuer
		    'nbf'  => $notBefore,        // Not before
		    'exp'  => $expire,           // Expire
		    'data' => [                  // Data related to the logged user you can set your required data
						'token'   	=> DefuseLib::encrypt($user->token), 	 // id from the users table
		              ]
		];

		$response['user'] 		= $user->toArray();
		$response['jwt'] 		= $token;

		$this->MobileRestapi->response($response);

	}


	public function test(){
		$real 	= JWT::decode($this->input->post('token'));
		$real 	= toObject($real);
		$unixtime = $real->nbf;
		echo date("m/d/Y h:i:s A T",$unixtime);
		//$this->MobileRestapi->response($real);
	}
// End Register USER

// get order
	public function transaction($page="index"){
		$user 				= $this->authorization();

		switch ($page) {
			case 'index':

				$transaction 		= $this->filterTransaction($user);

				$this->MobileRestapi->response($transaction);
				break;
			case 'detail':

				$rules 		= [
							    'required' 	=> [
							        ['transaction']
							    ],
							  ];

				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$transaction 		= TransactionModel::with('shipping.detail')
										->mine($user->id)->find($this->input->post('transaction'));

				$this->MobileRestapi->response($transaction->toArray());

				break;
			default:
				$this->MobileRestapi->error("API Not Found!");
				break;
		}
	}

//insyaallah fix (blm coba)
	private function filterTransaction($user){

		$keyword 					= (!$this->input->post('keyword')) ? '' : $this->input->post('keyword');
		$take 						= (!$this->input->post('show')) ? '10' : $this->input->post('show');
		$page 						= (!$this->input->post('page')) ? '0' : $this->input->post('page');

		$ava_order 					= ['asc','desc'];
		$ava_order_by 				= ['created_at','status','name','invoice'];
		$ava_status 				= ['order','done','confirmation','cancel','process'];

		$order 						= (!in_array(strtolower($this->input->post('order')),$ava_order)) ?
											'desc' : strtolower($this->input->post('order'));
		$order_by 					= (!in_array(strtolower($this->input->post('order_by')),$ava_order_by)) ?
											'created_at' : strtolower($this->input->post('order_by'));

		$transaction 	 			= TransactionModel::mine($user->id);

		$transaction_filter 		= TransactionModel::mine($user->id)->where(function($query) use ($keyword) {
											$query->where('invoice','like','%'.$keyword.'%')
												  ->orWhere('name','like','%'.$keyword.'%')
												  ->orWhere('email','like','%'.$keyword.'%')
												  ->orWhere('status','like','%'.$keyword.'%')
												  ->orWhere('created_at','like','%'.$keyword.'%');
									  });

		if(in_array($this->input->post('status'), $ava_status)){
			$transaction_filter 	= $transaction_filter->where('status',$this->input->post('status'));
		}

		$transaction_filter 		= $transaction_filter->orderBy($order_by,$order)->take($take)->skip($page*$take)->get();

		$info['total'] 				= $transaction->count();
		$info['sum'] 				= "Rp. ".number_format($transaction->sum('grand_total'),0,',','.');

		$info['order_sum'] 			= "Rp. ".number_format($transaction->where('status','order')->sum('grand_total'),0,',','.');
		$info['order_count'] 		= $transaction->where('status','order')->count();

		$info['confirmation_sum'] 	= "Rp. ".number_format($transaction->where('status','confirmation')->sum('grand_total'),0,',','.');
		$info['confirmation_count']	= $transaction->where('status','confirmation')->count();

		$info['process_sum'] 		= "Rp. ".number_format($transaction->where('status','process')->sum('grand_total'),0,',','.');
		$info['process_count'] 		= $transaction->where('status','process')->count();

		$info['done_sum'] 			= "Rp. ".number_format($transaction->where('status','done')->sum('grand_total'),0,',','.');
		$info['done_count'] 		= $transaction->where('status','done')->count();

		$info['cancel_sum'] 		= "Rp. ".number_format($transaction->where('status','cancel')->sum('grand_total'),0,',','.');
		$info['cancel_count'] 		= $transaction->where('status','cancel')->count();

		$filter['show'] 			= $take;
		$filter['page'] 			= $page;
		$filter['keyword'] 			= $keyword;
		$filter['order'] 			= $order;
		$filter['order_by'] 		= $order_by;

		$data['filter'] 			= $filter;
		$data['transaction'] 		= $transaction_filter;
		$data['info_transaction'] 	= toObject($info);

		return $data;
	}
// get order

// Member Area
	// need to be checked
	public function genpass(){
		$this->MobileRestapi->response(DefuseLib::encrypt("berkah2017"));
	}

//blm coba
	public function actionforgot($value=''){
		$rules 		= [
					    'required' 	=> [
					        ['token'],['password'],['confirmation_password']
					    ],
					    'lengthMin'	=> [
					    	['password',8],
					    	['confirmation_password',8],
					    ],
					    'equals' 	=> [
					    	['confirmation_password','password']
					    ]
					  ];

		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$token 			= $this->input->post('token');

		$user 			= UserModel::where('status','!=','register')
							->where('restore_status',1)
							->token($token)->first();

		if(!$user){
			$this->MobileRestapi->error("user not found! , please do restore again");
		}

		if (strtotime('-1 day') < strtotime($user->restore_date)) {

			$user->restore_status 	= 0;
			$user->restore_date 	= null;
			$user->save();

		 	$this->MobileRestapi->error("waktu pemulihan telah berakhir , silahkan pulihkan kembali");
		}

		$user->password_old 		= $user->password;
		$user->password_old_date 	= date('Y-m-d');
		$user->password 			= DefuseLib::encrypt($this->input->post('password'));
		$user->token 				= getToken('20').'-'.date('Ymdhis');
		$user->restore_status 		= 0;
		$user->restore_date 		= null;

		if(!$user->save()){
			$this->MobileRestapi->error("ada sesuatu yang salah silahkan coba kembali");
		}

		$this->MobileRestapi->response("success");
	}
// End Member Area

// -------------------------------- CART SECTION
	private function cartToken(){

		if(!$this->input->post('token')){

			$cart 				= new CartModel;
			$cart->token 		= getToken('8').'-'.date('Ymdhis');
			$cart->ipaddress 	= $this->input->ip_address();
			$cart->user_agent	= @$_SERVER['HTTP_USER_AGENT'];
			$cart->expired 		= add_day(7);

			if(!$cart->save()){
				$this->MobileRestapi->error("Couldnt Create new token cart , try again later");
			}

			return $cart;

		}else{

			$token 	= $this->input->post('token');

			$cart 	= CartModel::token($token)->first();

			if(!$cart){
				$this->MobileRestapi->error("Undifined Token Cart");
			}

			return $cart;
		}

	}

	private function cartItem($cart,$id){
		$item 	= CartItemModel::where('id_cart',$cart->id)->where('id_product',$id)->first();

		if(!$item){
			$item 			= new CartItemModel;
			$item->id_cart 	= $cart->id;
		}

		return $item;
	}

//baru token yg fix
	public function cart($page){
		switch ($page) {
			case 'token':

				$cart 	= $this->cartToken();
				$this->MobileRestapi->success($cart);

				break;
			case 'show':

				$rules = [
							'required' 	=> [
								['token']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}
				$cart 	= $this->cartToken();
				$this->MobileRestapi->success($cart);

				break;
			case 'add':

				$rules = [
							'required' 	=> [
								['product']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$product 	= ProductModel::active()->find($this->input->post('product'));

				if(!$product){
					$this->MobileRestapi->error("Maaf produk tidak di temukan");
				}

				$cart 				= $this->cartToken();

				$item 				= $this->cartItem($cart,$product->id);

				$qty 				= (int) (($this->input->post('qty')) ? $this->input->post('qty') : (@$item->qty + 1));
				$qty 				= ($qty<=0) ? 1 : $qty;

				$item->vendor_name 	= 'Administrator';

				if($product->made=="vendor"){
					$item->id_vendor 	= $product->vendor->id;
					$item->vendor_name 	= $product->vendor->name;
				}

				$item->id_product 	= $product->id;
				$item->image 		= $product->image_sm_dir;
				$item->qty 			= $qty;
				$item->url 			= $product->url;
				$item->name 		= $product->name;
				$item->max 			= $product->max;
				$item->min 			= $product->min;
				$item->weight 		= $product->weight;
				$item->weight_total = $item->weight * $item->qty;
				$item->diameter 	= $product->diameter;
				$item->length 		= $product->length;
				$item->price 		= $product->price;
				$item->price_false 	= $product->price_false;
				$item->price_total 	= $item->price * $item->qty;
				$item->margin 		= $product->markup_margin;
				$item->margin_total = $product->markup_margin * $item->qty;
				$item->nomarkup 	= $product->price_nomarkup;
				$item->nomarkup_total = $product->price_nomarkup * $item->qty;
				$item->made 		= $product->made;
				$item->save();

				$response['cart'] 	= $cart;
				$response['product']= $product;
				$response['message']= "Produk di tambahkan ke keranjang";

				$this->MobileRestapi->success($response);

				break;
			case 'update':

				$rules = [
							'required' 	=> [
								['product'],['token'],['qty']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$product 	= ProductModel::active()->find($this->input->post('product'));

				if(!$product){
					$this->MobileRestapi->error("Maaf produk tidak di temukan");
				}

				$cart 				= $this->cartToken();

				$item 				= $this->cartItem($cart,$product->id);

				$qty 				= (int) (($this->input->post('qty')) ? $this->input->post('qty') : 1);

				$qty 				= ($qty<=0) ? 1 : $qty;

				$item->vendor_name 	= 'Administrator';

				if($product->made=="vendor"){
					$item->id_vendor 	= $product->vendor->id;
					$item->vendor_name 	= $product->vendor->name;
				}

				$item->id_product 	= $product->id;
				$item->image 		= $product->image_sm_dir;
				$item->qty 			= $qty;
				$item->url 			= $product->url;
				$item->name 		= $product->name;
				$item->max 			= $product->max;
				$item->min 			= $product->min;
				$item->weight 		= $product->weight;
				$item->weight_total = $item->weight * $item->qty;
				$item->diameter 	= $product->diameter;
				$item->length 		= $product->length;
				$item->price 		= $product->price;
				$item->price_false 	= $product->price_false;
				$item->price_total 	= $item->price * $item->qty;
				$item->margin_total = $product->markup_margin * $item->qty;
				$item->nomarkup_total = $product->price_nomarkup * $item->qty;
				$item->made 		= $product->made;
				$item->save();

				$response['cart'] 	= $cart;
				$response['product']= $product;
				$response['message']= "Produk di perbarui di keranjang";

				$this->MobileRestapi->success($response);

				break;
			case 'remove':

				$rules = [
							'required' 	=> [
								['product'],['token']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$product 	= ProductModel::active()->find($this->input->post('product'));

				if(!$product){
					$this->MobileRestapi->error("Maaf produk tidak di temukan");
				}

				$cart 				= $this->cartToken();

				$item 				= $this->cartItem($cart,$product->id);
				$item->delete();

				$response['cart'] 	= $cart;
				$response['product']= $product;
				$response['message']= "Produk telah di hapus dari keranjang";
				$this->MobileRestapi->success($response);
				break;

			default:
				$this->MobileRestapi->error("Bad Request");
				break;
		}

	}

//blm cek
	public function checkoutgenerate(){
		$rules = [
					'required' 	=> [
						['token']
				    ]
				  ];


		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$token 	= $this->input->post('token');

		$mycart 	= CartModel::token($token)->first();

		if(!$mycart){
			$this->MobileRestapi->error("Maaf keranjang belanja tidak di temukan !");
		}

		$cart 	= [];

		foreach ($mycart->items as $result) {

			if($result->made!="admin"){
				$cart[$result->id_vendor]['id'] 		= $result->id_vendor;
				$cart[$result->id_vendor]['name']		= $result->vendor_name;
				$cart[$result->id_vendor]['courier'] 	= VendorCourierModel::with('courier')
															->where('id_vendor',$result->id_vendor)->get()->toArray();
				$cart[$result->id_vendor]['cart'][] 	= $result;
				@$cart[$result->id_vendor]['weight'] 	+= $result->weight;
				// @$cart[$result->id_vendor]['nomarkup'] 	+= $result->nomarkup_total;
				// @$cart[$result->id_vendor]['margin'] 	+= $result->margin_total;
				@$cart[$result->id_vendor]['total'] 	+= $result->price_total;

			}else{
				$cart['admin']['id'] 		= 'admin';
				$cart['admin']['name']		= 'Admin';
				$cart['admin']['courier'] 	= CourierModel::publish()->asc()->get()->toArray();
				$cart['admin']['cart'][] 	= $result;
				@$cart['admin']['weight'] 	+= $result->weight;
				// @$cart['admin']['nomarkup'] += $result->nomarkup_total;
				// @$cart['admin']['margin'] 	+= $result->margin_total;
				@$cart['admin']['total'] 	+= $result->price_total;
			}
		}

		$temp 					= [];

		foreach ($cart as $result) {
			$temp[] 			= $result;
		}

		$data['token'] 			= $token;
		$data['cart'] 			= toObject($temp);
		$data['config_store'] 	= ConfigStoreModel::first();
		$data['admin_courier']	= CourierModel::publish()->asc()->get();

		$this->MobileRestapi->success($data);
	}

//blm cek
	public function checkoutsubmit(){

		$rules = [
					'required' 	=> [
						['token']
				    ]
				  ];


		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$token 	= $this->input->post('token');

		$cart 	= CartModel::token($token)->first();

		if(!$cart){
			$this->MobileRestapi->error("Maaf keranjang belanja tidak di temukan !");
		}

		if(!$cart->items){
			$this->MobileRestapi->error('Maaf keranjang belanja anda kosong!');
		}

		$rules = [
					'required' 	=> [
				    	    ['province'],['city'],['district'],
				    	    ['name'],['email'],['phone'],['zipcode'],['address'],['agreement']
				    ],
				    'email'		=> [
				    		['email']
				    ]
				  ];


		$validate 	= $this->validation->check($rules,'post');

		if(!$validate->correct){
			$this->MobileRestapi->error($validate->data);
		}

		$province 		= $this->input->post('province');
		$city 			= $this->input->post('city');
		$district 		= $this->input->post('district');

		// Check destination
		$destination 		= RajaOngkir::findDistrict(goExplode($district,'//',0),goExplode($city,'//',0),goExplode($province,'//',0));

		if(!$destination->status){
			$this->MobileRestapi->error($destination->results);
		}


		$cart 			= [];
		$weight 		= 0;

		foreach ($cart->items as $key => $result) {

			$weight 							+= $result->weight_total;

			$vendor 							= ($result->made=="admin") ? 'admin' : $result->id_vendor;

			if($result->made=='admin'){
				$cart[$vendor]['vendor']			= 'admin';
			}else{
				$cart[$vendor]['vendor']			= VendorModel::active()->find($result->id_vendor)->toArray();
				if(!$cart[$vendor]['vendor']){
					continue;
				}
			}

			$cart[$vendor]['made'] 				= $result->made;
			$cart[$vendor]['cart'][] 			= $result;
			$cart[$vendor]['weight'] 			= @$cart[$vendor]['weight'] + $result->weight_total;

		}

		$cart 				= toObject($cart);

		$courier_cost 		= 0;
		$grand_total 		= 0;

		$config_store 		= ConfigStoreModel::first();

		$found 				= false;
		$invoice 			= strtoupper(getToken(8));

		while ($found==false) {
			$check_invoice 	= TransactionModel::invoice($invoice)->first();

			if(!$check_invoice){
				break;
			}else{
				$invoice 	= strtoupper(getToken(8));
			}
		}


		$transaction 		= new TransactionModel;

		$user 				= $this->getuser();

		if($user){
			$transaction->id_user 	= $user->id;
		}

		$transaction->invoice 				= $invoice;
		$transaction->name 					= $this->input->post('name');
		$transaction->email 				= $this->input->post('email');
		$transaction->zipcode 				= $this->input->post('zipcode');
		$transaction->address 				= $this->input->post('address');
		$transaction->phone 				= $this->input->post('phone');
		$transaction->expired 				= date('Y-m-d h:i:s',
												strtotime(date('Y-m-d h:i:s'). ' + '.$config_store->day.' days + '.
														  $config_store->hour.' hours + '.
														  $config_store->min.' minutes')
											   );

		$transaction->method 				= 'manual';

		$transaction->courier_destination_province	= $destination->province_txt;
		$transaction->courier_destination_city		= $destination->city_txt;
		$transaction->courier_destination_district	= $destination->district_txt;


		$transaction->courier_cost 			= $courier_cost;
		$transaction->total 				= $this->cart->total();
		$transaction->grand_total 			= $transaction->courier_cost + $transaction->total;

		if(!$transaction->save()){
			$this->MobileRestapi->error('Transaction Failed Please try again');
		}
		// Bagian Paling Angellll

		foreach ($cart as $key => $result) {

			$custom_courier 	= $this->input->post('courier_custom_'.$key);

			if($custom_courier){

				$rules = [
						'required' 	=> [
					    	['custom_courier_name_'.$key],
					    	['custom_courier_admin_'.$key],
					    	['custom_courier_phone_'.$key]
					    ]
				];

				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$transaction->delete();
					$this->MobileRestapi->error($validate->data);
				}

				// insert shipping detail
				$shipping 					= new TransactionShippingModel;

				$shipping->type 			= 'custom';

				$shipping->id_transaction 	= $transaction->id;
				$shipping->name 			= $this->input->post('custom_courier_name_'.$key);
				$shipping->admin_name 		= $this->input->post('custom_courier_admin_'.$key);
				$shipping->phone 			= $this->input->post('custom_courier_phone_'.$key);
				$shipping->weight 			= $result->weight;
				$shipping->info 			= $this->input->post('custom_courier_info_'.$key);


				if($result->made=="admin"){
					$shipping->origin_province 			= $config_store->province;
					$shipping->origin_city 				= $config_store->city;
				}else{
					$shipping->origin_province 			= $result->vendor->courier_province;
					$shipping->origin_city 				= $result->vendor->courier_city;
				}


				$shipping->destination_province 	= $destination->province_txt;
				$shipping->destination_city 		= $destination->city_txt;
				$shipping->destination_district 	= $destination->district_txt;


			}else{

				$rules = [
						'required' 	=> [
					    	['courier_service_'.$key],['courier_packet_'.$key]
					    ]
				];

				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($key);
				}

				$config['destination']	= goExplode($district,'//',0);
				$config['weight']		= $result->weight;
				$config['service']		= goExplode($this->input->post('courier_packet_'.$key),'//',0);

				if($result->made=="admin"){
					$courier 				= CourierModel::publish()->find($this->input->post('courier_service_'.$key));

					if(!$courier){
						$this->MobileRestapi->error('Layanan kurir tidak di temukan !');
					}

					$config['courier']		= $courier->code;
					$config['origin']		= goExplode($config_store->city,'//',0);

				}else{
					$courier 				= VendorCourierModel::mine($result->vendor->id)
												->where('id_courier',$this->input->post('courier_service_'.$key))->first();

					if(!$courier){
						$this->MobileRestapi->error('Layanan kurir tidak di temukan !');
					}

					$config['courier']		= $courier->courier->code;
					$config['origin']		= goExplode($result->vendor->courier_city,'//',0);

				}


				$cost 					= RajaOngkir::result($config);

				if(!$cost->status){
					$transaction->delete();
					$this->MobileRestapi->error($cost->results);
				}

				// insert shipping detail
				$shipping 					= new TransactionShippingModel;

				$shipping->id_transaction 	= $transaction->id;
				$shipping->name 			= $cost->results->courier_name;
				$shipping->code 			= $cost->results->courier_code;
				$shipping->weight 			= $cost->results->weight;
				$shipping->service 			= $cost->results->service->name;
				$shipping->estimation 		= $cost->results->service->day;
				$shipping->cost 			= $cost->results->service->price;

				if($result->made=="admin"){
					$shipping->origin_province 			= $config_store->province;
					$shipping->origin_city 				= $config_store->city;
				}else{
					$shipping->origin_province 			= $result->vendor->courier_province;
					$shipping->origin_city 				= $result->vendor->courier_city;
				}

				$shipping->destination_province 	= $cost->results->destination_province;
				$shipping->destination_city 		= $cost->results->destination_city;
				$shipping->destination_district 	= $cost->results->destination_district;
			}

			if(!$shipping->save()){
				$transaction->delete();
				$this->MobileRestapi->error('Something Wrong , please try again later');
			}

			$transaction->courier_cost 			+= $shipping->cost;
			$transaction->grand_total 			+= $shipping->cost;

			foreach ($result->cart as $value) {

				$detail 				= new TransactionDetailModel;

				$detail->id_product 	= $value->id;
				$detail->id_transaction	= $transaction->id;
				$detail->id_shipping 	= $shipping->id;

				if($value->made=="admin"){
					$detail->id_vendor 		= null;
					$detail->vendor_name 	= 'admin';
				}else{
					$detail->id_vendor 		= $result->vendor->id;
					$detail->vendor_name 	= $result->vendor->name;
				}

				$detail->name 			= $value->name;
				$detail->price 			= $value->price;
				$detail->weight 		= $value->weight;
				$detail->weight_total 	= $value->weight * $value->qty;
				$detail->length 		= $value->length;
				$detail->height 		= $value->height;
				$detail->width 			= $value->width;
				$detail->diameter 		= $value->diameter;
				$detail->qty 			= $value->qty;

				$detail->total 			= $value->price * $value->qty;
				$detail->discount 		= 0;
				$detail->grand_total 	= $detail->total;
				$detail->status 		= 'order';

				if(!$detail->save()){
					$transaction->delete();
					$this->MobileRestapi->error('unable to save please try again later');
				}
			}

		}

		if(!$transaction->save()){
			$this->MobileRestapi->error('unable to save please try again later');
		}


		$this->cart->destroy();

		$mail 					= new Magicmailer;
		$tmp['transaction'] 	= $transaction;
		$tmp['account'] 		= AccountModel::publish()->get();

		// Send SMS
		$text 					= string_newline($this->blade->draw('sms.invoice',$tmp));
		//$this->zenzivasms->send($transaction->phone,$text);


		// Send Email
		$mail->addAddress($transaction->email,$transaction->name);
	    $mail->Body    			= $this->blade->draw('email.invoice.order',$tmp);
	    $mail->Subject 			= 'Invoice Pembelian Anda';
    	$mail->AltBody 			= 'Detail Invoice Pembelian anda';
		$mail->send();

		$url 		= '/checkout/success?invoice='.$transaction->invoice;
		$this->MobileRestapi->response($url);



	}
	// need to be checked

//fix
	public function account(){

		$account 		= AccountModel::get();

		$this->MobileRestapi->response($account->toArray());

	}

//blm cek
	public function confirmtransaction(){
		$rules = [
				'required' 	=> [
			    	    ['invoice'],['name'],['email'],['phone'],['account'],
			    	    ['account_name'],['account_bank'],['account_number'],
			    	    ['total']
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
			$this->MobileRestapi->error($validate->data);
		}

		$transaction 	= TransactionModel::invoice($this->input->post('invoice'))->first();

		if(!$transaction){
			$this->MobileRestapi->error("Maaf no / kode invoice tidak di temukan");
		}

		if($transaction->status=="process" || $transaction->status=="approve"){
			$this->MobileRestapi->error("Invoice ini telah kami proses silahkan check di bagian 'lacak'");
		}

		if($transaction->status=="cancel" || $transaction->status=="expired"){
			$this->MobileRestapi->error("Invoice ini tidak dapat di proses silahkan check di bagian 'lacak'");
		}

		$account 	= AccountModel::publish()->find($this->input->post('account'));

		if(!$account){
			$this->MobileRestapi->error("Akun rekening tidak kami temukan , pilih dengan benar !");
		}

		$total 							= $this->input->post('total');

		$confirmation 					= new ConfirmationModel;

		if($_FILES['image']['name']==""){
			$this->MobileRestapi->error("Bukti Gambar di perlukan");
		}

		$filename 				= 'CONFIRMATION '.limit_string($transaction->invoice).'  ('.date('Ymdhis').')';

		$upload 				= $this->upfiles->uploadImage(content_dir('images/lg/confirmation'),'image',$filename);

		if(!$upload->status){
			$error_upload 		= $upload->result;
			$this->MobileRestapi->error($upload->result);
		}

		$filename 				= $upload->result->file_name;

		$option['origin']		= content_dir('images/lg/confirmation/'.$filename);
		$option['filename']		= $filename;

		// RESIZE TO MEDIUM
		$option['size']			= 350;
		$option['path']			= content_dir('images/md/confirmation/');
		$this->upfiles->resize($option);

		// RESIZE TO SMALL
		$option['size']			= 150;
		$option['path']			= content_dir('images/sm/confirmation/');
		$this->upfiles->resize($option);

		// RESIZE TO SMALL
		$option['size']			= 80;
		$option['path']			= content_dir('images/xs/confirmation/');
		$this->upfiles->resize($option);

		$confirmation->image 			= $filename;

		$confirmation->id_transaction	= $transaction->id;

		$confirmation->admin_account_name 	= $account->name;
		$confirmation->admin_account_bank 	= $account->bank;
		$confirmation->admin_account_number = $account->number;

		$confirmation->name 			= $this->input->post('name');
		$confirmation->email 			= $this->input->post('email');
		$confirmation->phone 			= $this->input->post('phone');
		$confirmation->account_name 	= $this->input->post('account_name');
		$confirmation->account_bank 	= $this->input->post('account_bank');
		$confirmation->account_number 	= $this->input->post('account_number');
		$confirmation->total 			= $total;


		if(!$confirmation->save()){
			$this->MobileRestapi->error("Maaf ada yang salah silahkan coba kembali nanti");
		}
		$this->MobileRestapi->response("Anda berhasil mengkonfirmasi transaksi , tunggu proses dari kami");
	}

// -------------------------------- CART END SECTION

//error
	public function basicdata(){
		$data 	= ConfigModel::first();
		$this->MobileRestapi->response($data);
	}

//fix
	public function sliders(){
		$data = SliderModel::publish()->get();
		$this->MobileRestapi->response($data);
	}

//fix
	public function partnerships(){
		$data = PartnershipModel::publish()->get();
		$this->MobileRestapi->response($data);
	}

//fix
	public function categoryproduct($page="list"){
		switch ($page) {
			case 'list':
				$category 	= ProductCategoryModel::asc('name')->get();
				$this->MobileRestapi->response($category);
				break;
			case 'fulllist':
				$category 	= ProductCategoryModel::with('subs')->asc('name')->get();
				$this->MobileRestapi->response($category);
				break;
				break;
			case 'detail':

				$rules = [
							'required' 	=> [
						    	    ['id_category']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$id 			= $this->input->post('id_category');
				$category 		= ProductCategoryModel::with('subs')->find($id);
				if(!$category){
					$this->MobileRestapi->error('Category Not Found!');
				}

				$this->MobileRestapi->response($category);
				break;

			default:
				$this->MobileRestapi->error("Bad Request");
				break;
		}
	}

//list, fulllist ok
	public function categorysubproduct($page="list"){
		switch ($page) {
			case 'list':
				$category 	= ProductSubCategoryModel::asc('name')->get();
				$this->MobileRestapi->response($category);
				break;
			case 'fulllist':
				$category 	= ProductSubCategoryModel::with('category','products')->asc('name')->get();
				$this->MobileRestapi->response($category);
				break;
				break;
			case 'detail':

				$rules = [
							'required' 	=> [
						    	    ['id_subcategory']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$id 			= $this->input->post('id_subcategory');
				$category 		= ProductSubCategoryModel::with('category','products')->find($id);
				if(!$category){
					$this->MobileRestapi->error('Sub Category Not Found!');
				}

				$this->MobileRestapi->response($category);
				break;

			default:
				$this->MobileRestapi->error("Bad Request");
				break;
		}
	}

//list ok
	public function product($page="list"){
		switch ($page) {
			case 'list':

				$result 					= $this->filterproduct();
				$this->MobileRestapi->success($result);

				$response['filter'] 			= $result->search;
				$response['products'] 			= $result->product;
				$response['premium_products']	= $result->premium_product;
				$response['pagination'] 		= $result->pagination;
				$response['title'] 				= 'Pencarian Semua Produk';
				$this->MobileRestapi->success($response);
				break;
			case 'detail':
				$rules = [
							'required' 	=> [
						    	    ['product']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$id 			= $this->input->post('product');
				$product 		= ProductModel::with("images")->active()->find($id);

				if(!$product){
					$this->MobileRestapi->error('Product Not Found!');
				}

				$hidden 		= $product->getHidden();
				$product->makeVisible(['description','sub','vendor','readed','status','keyword']);

				$this->MobileRestapi->response($product);

				break;
			default:
				$this->MobileRestapi->error('Bad Request');
				break;
		}
	}

	private function filterproduct(array $option=[]){

		$search 				= [
										'category' 		=> 'all',
										'subcategory' 	=> null,
										'price_min' 	=> 0,
										'price_max' 	=> 10000000,
										'order'			=> 'newest',
										'view' 			=> 'grid',
										'show' 			=> 16
									  ];


		$product 				= ProductModel::active();
		$premium_product 		= ProductModel::premiumproduct()->active()->inRandomOrder();

		$keyword 				= $this->input->post('q');

		$product 				= $product->where('name','like','%'.$keyword.'%');

		$all_order 				= ['newest','highest','lowest'];
		$all_view 				= ['grid','list'];


		$data_category 			= [];

		$search['category'] 	= 'all';
		$search['subcategory'] 	= null;

		$id 				= (isset($option['category'])) ? $option['category'] : $this->input->post('category');
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

		$id 				= (isset($option['subcategory'])) ? $option['subcategory'] : $this->input->post('subcategory');
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
			$premium_product 		= $premium_product->whereIn('id_subcategory',$data_category);
		}

		if($this->input->post('price_min') || $this->input->post('price_max')){
			$price_min 			= (int) ($this->input->post('price_min')) ? $this->input->post('price_min') : 0;
			$price_max 			= (int) ($this->input->post('price_max')) ? $this->input->post('price_max') : 5000000;
			$product 			= $product->whereBetween('price', [$price_min, $price_max]);

			$search['price_min'] = $price_min;
			$search['price_max'] = $price_max;
		}

		$all_show 				= ['16','32','64'];

		$order 					= (!in_array($this->input->post('order'), $all_order)) ? 'newest' : $this->input->post('order');
		$view 					= (!in_array($this->input->post('view'), $all_view)) ? 'grid' : $this->input->post('view');
		$show 					= (!$this->input->post('show')) ? '16' : $this->input->post('show');
		$page 					= (int) ($this->input->post('per_page')) ? $this->input->post('per_page') : 0;

		$search['order'] 		= $order;
		$search['view'] 		= $view;
		$search['show'] 		= $show;

		$total 					= $product->get()->count();
		$take 					= $show;

		switch ($order) {
			case 'highest':
				$product 				= $product->orderBy('price','desc')->take($take)->skip($page*$take)->get();
				$premium_product 		= $premium_product->orderBy('price','desc')->take($take)->skip($page*6)->get();
				break;
			case 'lowest':
				$product 				= $product->orderBy('price','asc')->take($take)->skip($page*$take)->get();
				$premium_product 		= $premium_product->orderBy('price','asc')->take($take)->skip($page*6)->get();
				break;

			default:
				$product 				= $product->orderBy('id','desc')->take($take)->skip($page*$take)->get();
				$premium_product 		= $premium_product->orderBy('id','desc')->take($take)->skip($page*6)->get();
				break;
		}


		$pagination['take'] 	= $take;
		$pagination['result'] 	= count($product);
		$pagination['total']	= $total;
		$pagination['page'] 	= $page;

		$data['search'] 		= $search;
		$data['search'] 		= false;
		$data['product'] 		= false;
		$data['pagination']		= $pagination;

		$data['search'] 			= $search;
		$data['product'] 			= $product->toArray();
		$data['premium_product']	= $premium_product->toArray();
		$data['pagination'] 		= $pagination;

		return $data;
	}

	// track transaction
	//fix
	public function track(){
		if($this->input->post('invoice')){
			$invoice 				= $this->input->post('invoice');
			$transaction 			= TransactionModel::invoice($invoice)->with('detail')->first();

			if(!$transaction){
				$this->MobileRestapi->error('Maaf no / kode Invoice tidak di temukan');
			}

			$data['transaction']	= $transaction;
			$this->MobileRestapi->response($data);
		}
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
	// track transaction ends here

	public function bpjskes($pages){
		$helper = new ApiHelperBPJSKes;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->mitraInfo();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "inquiry":
				// Inquiry
				// $inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $jumlahBulan = $this->input->post('jumlahBulan'), $noHP =  $jumlahBulan = $this->input->post('noHP'));
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $jumlahBulan = $this->input->post('jumlahBulan'), $noHP = $this->input->post('noHP'));
				$this->MobileRestapi->response($inquiry);
				break;

			case "payment":
				// Payment PDAM
				// $payment = $helper->payment($productCode = $this->input->post('productCode'), $refID = $inquiry['refID'], $inquiry['total']);
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $jumlahBulan = $this->input->post('jumlahBulan'), $noHP = $this->input->post('noHP'));
				$payment = $helper->payment($productCode = $this->input->post('productCode'), $refID = $inquiry['refID'], $inquiry['total']);
				$this->MobileRestapi->response($payment);
				break;

			case "logpayment":
				// Log Payment
				// $log = $helper->options($cmd = 'getPaymentData', $data = $inquiry['refID']);
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'), $jumlahBulan = $this->input->post('jumlahBulan'), $noHP =  $jumlahBulan = $this->input->post('noHP'));
				$log = $helper->options($cmd = 'getPaymentData', $data = $inquiry['refID']);
				$this->MobileRestapi->response($log);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function bpjstk($pages){
		$helper = new ApiHelperBPJSTK;
		switch($pages){
			case "helloTest":
				$test = $helper->helloTest($data = $this->input->post('data'));
				$this->MobileRestapi->response($test);
				break;

			case "getLokasiKerja":
				$test = $helper->getLokasiKerja();
				$this->MobileRestapi->response($test);
				break;

			case "getProvinsiList":
				$test = $helper->getProvinsiList();
				$this->MobileRestapi->response($test);
				break;

			case "getKabupaten":
				$test = $helper->getKabupaten($provKode = $this->input->post('provKode'));
				$this->MobileRestapi->response($test);
				break;

			case "getKantorCabang":
				$test = $helper->getKantorCabang($kabKode = $this->input->post('kabKode'));
				$this->MobileRestapi->response($test);
				break;

			case "verifyKTPeL":
				$data = array(
					'nik' => $this->input->post('nik'),
					'namaKtp' => $this->input->post('namaKtp'),
					'tgLahir' => $this->input->post('tgLahir'),
					'noPonsel' => $this->input->post('noPonsel'),
				);
				$test = $helper->verifyKTPeL($data);
				$this->MobileRestapi->response($test);
				break;

			case "registration":
				$data = array(
					'nik' => $this->input->post('nik'),
					'namaKtp' => $this->input->post('namaKtp'),
					'expNik' => $this->input->post('expNik'),
					'tempatLahir' => $this->input->post('tempatLahir'),
					'tgLahir' => $this->input->post('tgLahir'),
					'kotaDomisili' => $this->input->post('kotaDomisili'),
					'alamat' => $this->input->post('alamat'),
					'kecamatan' => $this->input->post('kecamatan'),
					'kelurahan' => $this->input->post('kelurahan'),
					'kodepos' => $this->input->post('kodepos'),
					'noPonsel' => $this->input->post('noPonsel'),
					'email' => $this->input->post('email'),
					'JHT' => $this->input->post('JHT'),
					'JKK' => $this->input->post('JKK'),
					'JKM' => $this->input->post('JKM'),
					'periodeSelect' => $this->input->post('periodeSelect'),
					'jmPenghasilan' => $this->input->post('jmPenghasilan'),
					'lokasiPekerjaan' => $this->input->post('lokasiPekerjaan'),
					'jenisPekerjaan' => $this->input->post('jenisPekerjaan'),
					'jkStart' => $this->input->post('jkStart'),
					'jkStop' => $this->input->post('jkStop'),
					'notifySMS' => $this->input->post('notifySMS'),
					'kodeProvKantor' => $this->input->post('kodeProvKantor'),
					'kodeKabKantor' => $this->input->post('kodeKabKantor'),
					'kodeKantorCab' => $this->input->post('kodeKantorCab')
				);
				$test = $helper->registration($data);
				$this->MobileRestapi->response($test);
				break;

			case "bayarIuran":
				$test = $helper->bayarIuran($kodeIuran = $this->input->post('kodeIuran'));
				$this->MobileRestapi->response($test);
				break;

			case "inquiryKodeIuranByNIK":
				$test = $helper->inquiryKodeIuranByNIK($nik = $this->input->post('nik'));
				$this->MobileRestapi->response($test);
				break;

			case "hitungIuran":
				$data = array(
					'jmPenghasilan' => $this->input->post('jmPenghasilan'),
					'JHT' => $this->input->post('JHT'),
					'JKK' => $this->input->post('JKK'),
					'JKM' => $this->input->post('JKM'),
					'lokasiPekerjaan' => $this->input->post('lokasiPekerjaan'),
					'periodeSelect' => $this->input->post('periodeSelect')
				);
				$test = $helper->hitungIuran($data);
				$this->MobileRestapi->response($test);
				break;

			case "inquiryKodeIuran":
				$test = $helper->inquiryKodeIuran($kodeIuran = $this->input->post('kodeIuran'));
				$this->MobileRestapi->response($test);
				break;

			case "inquiryCetakUlang":
				$test = $helper->inquiryCetakUlang($kodeIuran = $this->input->post('kodeIuran'));
				$this->MobileRestapi->response($test);
				break;

			case "getDataPeserta":
				$test = $helper->getDataPeserta($nik = $this->input->post('nik'));
				$this->MobileRestapi->response($test);
				break;

			case "pilihProgram":
				$data = array(
					'nik' => $this->input->post('nik'),
					'JHT' => $this->input->post('JHT'),
					'JKK' => $this->input->post('JKK'),
					'JKM' => $this->input->post('JKM'),
					'periodeSelect' => $this->input->post('periodeSelect'),
					'jmPenghasilan' => $this->input->post('jmPenghasilan'),
					'lokasiPekerjaan' => $this->input->post('lokasiPekerjaan'),
					'jenisPekerjaan' => $this->input->post('jenisPekerjaan'),
					'jkStart' => $this->input->post('jkStart'),
					'jkStop' => $this->input->post('jkStop')
				);
				$test = $helper->pilihProgram($data);
				$this->MobileRestapi->response($test);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function etoll($pages){
		$helper = new ApiHelperEToll;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->info();
				$mitraInfo = $helper->info();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "topUpList":
				$product = $helper->options($cmd = $this->input->post('cmd'), $data = $this->input->post('data'));
				$this->MobileRestapi->response($product);
				break;

			//butuh cek
			case "topup":
				$topup = $helper->topup($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
				$this->MobileRestapi->response($topup);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function gopay($pages){
		$helper = new ApiHelperGoPay;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->mitraInfo();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "topUpList":
				$product = $helper->options($cmd = $this->input->post('cmd'), $data = $this->input->post('data'));
				$this->MobileRestapi->response($product);
				break;

			//butuh cek
			case "topup":
				$topup = $helper->topup($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
				$this->MobileRestapi->response($topup);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function ovo($pages){
		$helper = new ApiHelperOvo;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->info();
				$mitraInfo = $helper->info();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "topUpList":
				$product = $helper->options($cmd = $this->input->post('cmd'), $data = $this->input->post('data'));
				$this->MobileRestapi->response($product);
				break;

			//butuh cek
			case "topup":
				$topup = $helper->topup($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
				$this->MobileRestapi->response($topup);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function kai($pages){
		$helper = new ApiHelperKAI;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->info();
				$mitraInfo = $helper->info();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "stationList":
				$station = $helper->kai_station();
				$this->MobileRestapi->response($station);
				break;

			//butuh cek
			case "scheduleSearch":
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal')))
				];
				$search = $helper->kai_search($data);
				$this->MobileRestapi->response($search);
				break;

			case "seatmap":
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => $this->input->post('tanggal'),
					'no_kereta' => $this->input->post('no_kereta')
				];
				$seatmap = $helper->kai_seatmap($data);
				$this->MobileRestapi->response($seatmap);
				break;

			case "seatmapSubclass":
				$data = [
					'asal' => $this->input->post('asal'),
					'tujuan' => $this->input->post('tujuan'),
					'tanggal' => $this->input->post('tanggal'),
					'no_kereta' => $this->input->post('no_kereta'),
					'subclass' => $this->input->post('subclass')
				];
				$seatmap_subclass = $helper->kai_seatmap_subclass($data);
				$this->MobileRestapi->response($seatmap_subclass);
				break;

				//belom bikin
			case "ticketBooking":
				$data['asal'] = 'GMR';
				$data['tujuan'] = 'BD';
				$data['tanggal'] = date('Y-m-d', strtotime('+2 day'));
				$search = $helper->kai_search($data);
				$data['no_kereta'] = $search['schedule'][0]['trainNumber'];
				$seatmap = $helper->kai_seatmap($data);
				$data['subclass'] = $seatmap['seat_map'][0][2][3][4];
				$seatmap_subclass = $helper->kai_seatmap_subclass($data);
				$data['dewasa'] = 1;
				$data['bayi'] = 1;
				$data['penumpang'] = [
					'adult' => [
						[
							'adult_name' => 'MUSE',
							'adult_id' => '331234897887283674',
							'adult_date_of_birth' => '1945-08-17',
							'adult_phone' => '081234567890'
						]
					],
					'child' => NULL,
					'infant' => [
						[
							'infant_name' => 'LILY',
							'infant_date_of_birth' => '2018-08-17'
						]
					]
				];
				$data['pilih_kursi'] = 'manual';
				$data['kode_gerbong'] = $seatmap_subclass['seatMap'][0][0];
				$data['no_gerbong'] = $seatmap_subclass['seatMap'][0][1];
				$data['kursi'] = $seatmap_subclass['seatMap'][0][2][2][2].$seatmap_subclass['seatMap'][0][2][2][3];
				$book = $helper->kai_book($data);
				$this->MobileRestapi->response($book);
				break;

			case "issueTicket":
				$data = [
					'kode_booking' => $this->input->post('kode_booking'),
					'harga' => $this->input->post('harga')
				];
				$issue = $helper->kai_issue($data);
				$this->MobileRestapi->response($issue);
				break;

			case "ticketStatus":
				$data = [
					'bookingCode' => $this->input->post('kode_booking')
				];
				$cek = $helper->kai_check_book($data);
				$this->MobileRestapi->response($cek);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function multifinance($pages){
		$helper = new ApiHelperMultifinance;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->mitraInfo();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "inquiry":
				// Inquiry
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
				$this->MobileRestapi->response($inquiry);
				break;

			case "payment":
				$payment = $helper->payment($productCode = $this->input->post('productCode'), $refID = $this->input->post('refID'), $nominal = $this->input->post('totalBayar'));
				$this->MobileRestapi->response($payment);
				break;

			case "logPayment":
				$log = $helper->options($cmd = $this->input->post('cmd'), $data = $this->input->post('refID'));
				$this->MobileRestapi->response($log);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function pgn($pages){
		$helper = new ApiHelperPGN;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->mitraInfo();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "inquiry":
				// Inquiry
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
				$this->MobileRestapi->response($inquiry);
				break;

			case "payment":
				$payment = $helper->payment($productCode = $this->input->post('productCode'), $refID = $this->input->post('refID'), $nominal = $this->input->post('totalTagihan'));
				$this->MobileRestapi->response($payment);
				break;

			case "logPayment":
				$log = $helper->options($cmd = $this->input->post('cmd'), $data = $this->input->post('refID'));
				$this->MobileRestapi->response($log);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function pln($pages){
		$helper = new ApiHelperPLN;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->mitraInfo();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "inquiry":
				// Inquiry
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $idPel = $this->input->post('idPel'));
				$this->MobileRestapi->response($inquiry);
				break;

			case "payment":
				$payment = $helper->payment($productCode = $this->input->post('productCode'), $refID = $this->input->post('refID'), $nominal = $this->input->post('totalTagihan'));
				$this->MobileRestapi->response($payment);
				break;

			case "logPayment":
				$log = $helper->options($cmd = $this->input->post('cmd'), $data = $this->input->post('refID'));
				$this->MobileRestapi->response($log);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function pulsa($pages){
		$helper = new ApiHelperPulsa;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->info();
				$mitraInfo = $helper->info();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "productList":
				$data['id'] = $this->input->post('id');
				$product = $helper->produk($data);
				$this->MobileRestapi->response($product);
				break;

			case "inquiry":
				// Inquiry
				$data['phone'] = $this->input->post('phone');
				$inquiry = $helper->inquiry($data);
				$this->MobileRestapi->response($inquiry);
				break;

			case "payment":
				$data['phone'] = $this->input->post('phone');
				$inquiry = $helper->inquiry($data);
				$data['id'] = $inquiry[0]['product_id'];
				$data['harga'] = $inquiry[0]['price'];
				$payment = $helper->payment($data);
				$this->MobileRestapi->response($payment);
				break;

			case "status":
				$data['phone'] = $this->input->post('phone');
				$inquiry = $helper->inquiry($data);
				$data['id'] = $inquiry[0]['product_id'];
				$data['harga'] = $inquiry[0]['price'];
				$payment = $helper->payment($data);
				$data['id_transaksi'] = $payment['trxID'];
				$status = $helper->status($data);
				$this->MobileRestapi->response($status);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function telkom($pages){
		$helper = new ApiHelperTelkom;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->info();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "inquiry":
				// Inquiry
				$inquiry = $helper->inquiry($productCode = $this->input->post('productCode'), $noHP = $this->input->post('noHP'));
				$this->MobileRestapi->response($inquiry);
				break;

			case "payment":
				$payment = $helper->payment($productCode = $this->input->post('productCode'), $refID = $this->input->post('refID'), $nominal = $this->input->post('totalTagihan'));
				$this->MobileRestapi->response($payment);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function travel($pages){
		$helper = new ApiHelperTravel;
		switch($pages){
			case "travelGetAgen":
				// Request Mitra Info
				// $mitraInfo = $helper->info();
				$travelGetAgen = $helper->travelGetAgen();
				$this->MobileRestapi->response($travelGetAgen);
				break;

			case "travelGetKeberangkatan":
				$travelGetKeberangkatan = $helper->travelGetKeberangkatan($kodeAgen = $this->input->post('kodeAgen'));
				$this->MobileRestapi->response($travelGetKeberangkatan);
				break;

			case "travelGetKedatangan":
				$travelGetKedatangan = $helper->travelGetKedatangan($kodeAgen = $this->input->post('kodeAgen'), $idKeberangkatan = $this->input->post('idKeberangkatan'));
				$this->MobileRestapi->response($travelGetKedatangan);
				break;

			case "travelGetJadwalKeberangkatan":
				$travelGetJadwalKeberangkatan = $helper->travelGetJadwalKeberangkatan($kodeAgen = $this->input->post('kodeAgen'), $idKeberangkatan = $this->input->post('idKeberangkatan'), $idKedatangan = $this->input->post('idKedatangan'), $tanggal = $this->input->post('tanggal'), $penumpang = $this->input->post('penumpang'));
				$this->MobileRestapi->response($travelGetJadwalKeberangkatan);
				break;

			case "travelGetMapKursi":
				$travelGetMapKursi = $helper->travelGetMapKursi($kodeAgen = $this->input->post('kodeAgen'), $kodeJadwal = $this->input->post('kodeJadwal'), $tanggal = $this->input->post('tanggal'), $kodeLayoutKursi = $this->input->post('kodeLayoutKursi'), $idJadwal = $this->input->post('idJadwal'));
				$this->MobileRestapi->response($travelGetMapKursi);
				break;

			case "travelBook":
				$travelBook = $helper->travelBook($kodeAgen = $this->input->post('kodeAgen'), $kodeJadwal = $this->input->post('kodeJadwal'), $tanggal = $this->input->post('tanggal'), $namaPemesan = $this->input->post('namaPemesan'), $alamatPemesan = $this->input->post('alamatPemesan'), $telpPemesan = $this->input->post('telpPemesan'), $emailPemesan = $this->input->post('emailPemesan'), $jumlahPenumpang = $this->input->post('jumlahPenumpang'), $noKursi = $this->input->post('noKursi'), $namaPenumpang = $this->input->post('namaPenumpang'));
				$this->MobileRestapi->response($travelBook);
				break;

			case "travelCekReservasi":
				$travelCekReservasi = $helper->travelCekReservasi($kodeBooking = $this->input->post('kodeBooking'));
				$this->MobileRestapi->response($travelCekReservasi);
				break;

			case "travelPayBook":
				$travelPayBook = $helper->travelPayBook($kodeBooking = $this->input->post('kodeBooking'), $totalHarga = $this->input->post('totalHarga'));
				$this->MobileRestapi->response($travelPayBook);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	public function vouchergame($pages){
		$helper = new ApiHelperVoucherGame;
		switch($pages){
			case "mitraInfo":
				// Request Mitra Info
				// $mitraInfo = $helper->mitraInfo();
				$mitraInfo = $helper->mitraInfo();
				$this->MobileRestapi->response($mitraInfo);
				break;

			case "productList":
				// Inquiry
				$productList = $helper->productListGame($productID = $this->input->post('productID'));
				$this->MobileRestapi->response($productList);
				break;

			case "payment":
				$payment = $helper->paymentVoucherGame($productID = $this->input->post('productID'), $amount = $this->input->post('amount'), $uniqueID = $this->input->post('uniqueID'));
				$this->MobileRestapi->response($payment);
				break;

			case "voucherStatus":
				$voucherStatus = $helper->voucherGameStatus($msisdn = $this->input->post('msisdn'), $trxID = $this->input->post('trzID'));
				$this->MobileRestapi->response($voucherStatus);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}

	// API PREMIUM PRODUCT
	public function premium_product($page="list",$param1=null)
	{
		switch($page){
			case "list":
				// $default_item = 12;
				// if (!is_null($param1) && (filter_var($param1, FILTER_VALIDATE_INT) === true)) {
				// 	$default_item = $param1;
				// }
				$premium_product = ProductModel::premiumproduct()->active()->get();
				$this->MobileRestapi->response($premium_product);
				break;

			case "detail":
				$rules = [
							'required' 	=> [
						    	    ['id_item']
						    ]
						  ];


				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$id = $this->input->post('id_item');
				$detail_premium_product = ProductModel::where('id',$id)->first();

				if ($detail_premium_product->premium_status != 2) {
					$this->MobileRestapi->error("Bukan Produk Premium");
				}

				$this->MobileRestapi->response($detail_premium_product);
				break;

			default:
				$this->MobileRestapi->error('Url Not Found', 404);
				break;
		}
	}
	// API PREMIUM PRODUCT ENDS HERE

	public function vendor($page=null)
	{
		switch($page) {
			case "detail":
				$rules = [
							'required' 	=> [
						    	    ['id_user']
						    ]
						  ];

				$validate 	= $this->validation->check($rules,'post');

				if(!$validate->correct){
					$this->MobileRestapi->error($validate->data);
				}

				$id_user = $this->input->post("id_user");

				if (!VendorModel::where('id_user',$id_user)->exists()) {
					$this->MobileRestapi->error("Data Vendor tidak ditemukan");
				}

				$vendor = VendorModel::where('id_user',$id_user)->first();
				$this->MobileRestapi->response($vendor);
				break;

			default:
				$this->MobileRestapi->error("Url Not Found", 404);
				break;
		}
	}
}
