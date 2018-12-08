@extends('website.template')

@section('styles')

<link rel="stylesheet" href="{{base_url()}}/assets/css/jquery-ui.min.css">
<link rel="stylesheet" href="{{base_url()}}/assets/css/tree.css">
<link rel="stylesheet" href="{{base_url()}}/assets/css/ppob.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="/assets/css/jquery-ui.min.css">
<link rel="stylesheet" href="/assets/css/tree.css">
<style type="text/css">
  .content.overflow-scroll {
    height: 330px;
  }

  /**/

  .padding_slider {
    padding-bottom: 30px;
  }
  .feature-product-area {
  	padding-bottom: 20px;
  }
  .item-sidebar {
  	width: 100%;
    height: 100px;
    object-fit: contain;
  }
  .font-sidebar {
  	max-height: none !important;
  }
  .product_icon {
  	position: absolute;
    top: 0;
    left: 0;
    z-index: 10;
  }
  .product_icon2 {
  	position: absolute;
    /*top: 0;*/
    /*left: 0;*/
    z-index: 10;
  }
  .new-icon {
	display: block;
    width: 82px;
    height: 80px;
    line-height: 57px;
    text-align: center;
    font-size: 14px;
    font-family: 'Raleway', sans-serif;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    background: #f9af51;
    /*border-radius: 100%;*/
    margin-bottom: -15px;
    -webkit-clip-path: polygon(0 79%, 87% 0, 100% 17%, 11% 100%);
    clip-path: polygon(0 50%, 55% 0, 100% 0%, 0% 90%)
  }
  .div-custom {
  	-moz-transform: rotate(-43deg) translateY(-6px);
    -webkit-transform: rotate(-43deg) translateY(-6px);
    -o-transform: rotate(-43deg) translateY(-6px);
    -ms-transform: rotate(-43deg) translateY(-6px);
    transform: rotate(-43deg) translateY(-6px);
  }
  .product-slide {
  	min-height: 420px;
  }
  .img_a {
    width: 100%;
    height: auto;
  }
  .img_b {
    width: 100%;
    height: auto;
  }
</style>
@endsection

@section('content')
<!-- slider-area-start -->
<div class="slider-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
              <div class="sidebar-widget">
                <h3 class="sidebar-title">Lihat Kategori produk</h3>
                <div id="tree-scrollbar" class="content my-tree overflow-scroll">
                    <div id="tree-category">
                      <ul>
                        <li id="id-all">
                            <button type="button" class="my-link {{match($search->category,'all','active')}}" name="category" value="all" onclick="category_click(this)"> Semua Kategori</button>
                        </li>
                        @foreach($__CATEGORY as $result)
                          <li id="id{{$result->id}}" class="{{match($search->category,$result->id,'expanded')}}" >
                            <button type="button" class="my-link {{match($search->category,$result->id,'active')}}" name="category" value="{{$result->id}}" onclick="category_click(this)"> {{$result->name}}</button>
                            @if(count($result->subs)>0)
                              <ul>
                                @foreach($result->subs as $sub)
                                <li id="id{{$result->id}}.{{$sub->id}}">
                                   <button type="button" class="my-link {{match($search->subcategory,$sub->id,'active')}}" name="subcategory" value="{{$sub->id}}" onclick="subcategory_click(this)"> {{$sub->name}}</button>
                                   @if(count($sub->subsubs)>0)
                                    <ul>
                                      @foreach($sub->subsubs as $subsub)
                                        <li id="id{{$subsub->id}}.{{$subsub->id}}">
                                         <button type="button" class="my-link {{match($search->subcategory,$subsub->id,'active')}}" name="subsubcategory" value="{{$subsub->id}}" onclick="subcategory_click(this)"> {{$subsub->name}}</button>
                                        </li>
                                      @endforeach
                                    </ul>
                                   @endif
                                </li>
                                @endforeach
                              </ul>
                            @endif
                          </li>
                        @endforeach

                      </ul>
                    </div>
                </div>
                <input type="hidden" form="form-search" name="category" value="{{$search->category}}">
                <input type="hidden" form="form-search" name="subcategory" value="{{$search->subcategory}}">
                <input type="hidden" form="form-search" name="view" value="{{$search->view}}">
              </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 padding_slider">
                <div class="main-slider">
                    <div class="slider-container">
                        <!-- Slider Image -->
                        <div id="mainSlider" class="nivoSlider slider-image">
                            @foreach($slider as $key => $result)
                                <a href="{{$result->url}}">
                                	<img src="{{$result->image_dir}}" alt="" title="#slider_page{{$key}}"/>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- product-tab-area-2-end -->
<!-- banner-area-start -->
<!-- <div class="gap-lg"></div>   -->
<!-- banner-area-end -->
<!-- all-product-area-start -->
<div class="all-product-area pb-60">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <!-- bestseller-area -->
            @if ($premium_chosen->count() > 0)
            <div class="bestseller-area dotted-style-2">
              <div class="section-title">
                <h3>Produk Premium Pilihan</h3>
              </div>
              <div class="border-1">
                <div class="">
                @foreach($premium_chosen as $result)
                  <div class="single-product single-product-sidebar white-bg">
                    @if($result->sale != 0)
                    <div class="product_icon2">
                          <div class='new-icon'><div class="div-custom">SALE</div></div>
                    </div>
                    @endif
                    <div class="product-img product-img-left">
                      <a href="{{$result->url}}">
                        <img src="{{$result->image_dir}}" class="item-sidebar" alt="" style="background: white"/>
                      </a>
                    </div>
                    <div class="product-content product-content-right">
                      <div class="pro-title">
                        <h4 class="text-readmore font-sidebar"><a href="{{$result->url}}">{{$result->name}}</a></h4>
                      </div>
                      <div class="pro-rating ">
                        {!! rate_html((int)$result->reviews->avg('rate')) !!}
                      </div>
                      <div class="price-box">
                        <span class="price product-price">
                            Rp. {{number_format($result->price,0,',','.')}}
                        </span>
                        <div class="gap-xs"></div>
            @if($result->price_false>0)
            <strike><span class="old-price product-price">{{$result->pricefalse_txt}}</span></strike>
            @endif
                      </div>
                      <div class="product-icon">
                        <div class="product-icon-left f-left">
                          <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->product_id}}" data-qty="{{$result->min}}">
                            <i class="fa fa-shopping-cart"></i>Add to Cart
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                 @endforeach
                </div>
              </div>
              <!-- seller -->
            </div>
            @endif
            <!-- special-products-area -->
            <div class="special-products-area dotted-style-2 ptb-50">
              <div class="section-title">
                <h3>Produk Sale Terbaik</h3>
              </div>
              <div class="border-1">
                <div class="">
                @foreach($sale as $result)
                  <div class="single-product single-product-sidebar white-bg">
                  	@if($result->sale != 0)
	                 	<div class="product_icon2">
	                        <div class='new-icon'><div class="div-custom">SALE</div></div>
	                    </div>
                 	@endif
                    <div class="product-img product-img-left">
                      <a href="{{$result->url}}">
                        <img src="{{$result->image_dir}}" class="item-sidebar" alt="" style="background: white"/>
                      </a>
                    </div>
                    <div class="product-content product-content-right">
                      <div class="pro-title">
                        <h4 class="text-readmore font-sidebar"><a href="{{$result->url}}">{{$result->name}}</a></h4>
                      </div>
                      <div class="pro-rating ">
                        {!! rate_html((int)$result->reviews->avg('rate')) !!}
                      </div>
                      <div class="price-box">
                        <span class="price product-price">
                            Rp. {{number_format($result->price,0,',','.')}}
                        </span>
                        <div class="gap-xs"></div>
						@if($result->price_false>0)
						<strike><span class="old-price product-price">{{$result->pricefalse_txt}}</span></strike>
						@endif
                      </div>
                      <div class="product-icon">
                        <div class="product-icon-left f-left">
                          <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->id}}" data-qty="{{$result->min}}">
                            <i class="fa fa-shopping-cart"></i>Add to Cart
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                 @endforeach
                </div>
              </div>
              <!-- coding pake slide kanan kiri -->
	              <!-- <div class="special-products-active border-1">
	                <div class="single-product-items">
	                @foreach($bestseller as $result)
	                  <div class="single-product single-product-sidebar white-bg">
	                  	@if($result->sale != 0)
	                 	<div class="product_icon">
	                        <div class='new-icon'><div class="div-custom">SALE</div></div>
	                    </div>
                 	@endif
	                    <div class="product-img product-img-left">
	                      <a href="{{$result->url}}">
	                        <img src="{{$result->image_dir}}" class="img-list-product" alt="" />
	                      </a>
	                    </div>
	                    <div class="product-content product-content-right">
	                      <div class="pro-title">
	                        <h4 class="text-readmore"><a href="{{$result->url}}">{{$result->name}}</a></h4>
	                      </div>
	                      <div class="pro-rating ">
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star-o"></i></a>
	                      </div>
	                      <div class="price-box">
	                        <span class="price product-price">
	                            Rp. {{number_format($result->price,0,',','.')}}
	                        </span>
	                      </div>
	                      <div class="product-icon">
	                        <div class="product-icon-left f-left">
	                          <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->id}}"  >
	                            <i class="fa fa-shopping-cart"></i>Add to Cart
	                          </a>
	                        </div>
	                      </div>
	                    </div>
	                  </div>
	                 @endforeach
	                </div>
	                <div class="single-product-items">
	                @foreach($bestseller as $result)
	                  <div class="single-product single-product-sidebar white-bg">
	                  	@if($result->sale != 0)
	                 	<div class="product_icon">
	                        <div class='new-icon'><div class="div-custom">SALE</div></div>
	                    </div>
                 	@endif
	                    <div class="product-img product-img-left">
	                      <a href="{{$result->url}}">
	                        <img src="{{$result->image_dir}}" class="img-list-product" alt="" />
	                      </a>
	                    </div>
	                    <div class="product-content product-content-right">
	                      <div class="pro-title">
	                        <h4 class="text-readmore"><a href="{{$result->url}}">{{$result->name}}</a></h4>
	                      </div>
	                      <div class="pro-rating ">
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star"></i></a>
	                        <a href="#"><i class="fa fa-star-o"></i></a>
	                      </div>
	                      <div class="price-box">
	                        <span class="price product-price">
	                            Rp. {{number_format($result->price,0,',','.')}}
	                        </span>
	                      </div>
	                      <div class="product-icon">
	                        <div class="product-icon-left f-left">
	                          <a href="javascript:void(0)" class="link-cart" data-id="{{$result->id}}" >
	                            <i class="fa fa-shopping-cart"></i>Add to Cart
	                          </a>
	                        </div>
	                      </div>
	                    </div>
	                  </div>
	                 @endforeach
	                </div>
	              </div> -->
              <!-- /coding pake slide kanan kiri -->
            </div>
            <!-- client-area-start  -->
	            <!-- Testimoni -->
	            <!-- <div class="client-area dotted-style-2">
	              <div class="section-title">
	                <h3>Testimoni</h3>
	              </div>
	              <div class="clinet-active border-1">
	                <div class="single-client fix white-bg">
	                  <div class="client-content">
	                    <a href="#"><p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat Praesent dapibus, volutpat Praesent  neque id cursus faucibus,. ...</p></a>
	                  </div>
	                  <div class="clint-img">
	                    <div class="client-img-left">
	                      <img src="{{base_url('assets')}}/img/client/1.jpg" alt="" />
	                    </div>
	                    <div class="client-name">
	                      <span>Mr Test</span>
	                    </div>
	                  </div>
	                </div>
	                <div class="single-client fix white-bg">
	                  <div class="client-content">
	                    <a href="#"><p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat Praesent dapibus, volutpat Praesent  neque id cursus faucibus,. ...</p></a>
	                  </div>
	                  <div class="clint-img">
	                    <div class="client-img-left">
	                      <img src="{{base_url('assets')}}/img/client/1.jpg" alt="" />
	                    </div>
	                    <div class="client-name">
	                      <span>Mr Test</span>
	                    </div>
	                  </div>
	                </div>


	              </div>
	            </div> -->
          </div>
          <!-- PPOB -->
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <!-- feature-product-area -->
            <div class="feature-product-area dotted-style-2">
              <div class="section-title">
                <h3>Pembayaran Tagihan dan Pembelian (PPOB)</h3>
              </div>
              <div class="feature-product border-1">

<!------ Include the above in your HEAD tag ---------->

   <div class="row">
     <div class="col-lg-11 bhoechie-tab-container">
         <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
           <div class="list-group">
             <a href="#" class="list-group-item active text-center">
               <h4 class="fa fa-mobile fa-3x"></h4><br/>Pulsa
             </a>
             <a href="#" class="list-group-item text-center">
               <h4 class="fa fa-ticket fa-3x"></h4><br/>Tiket
             </a>
             <a href="#" class="list-group-item text-center">
               <h4 class="fa fa-bolt fa-3x"></h4><br/>Tagihan
             </a>
             <a href="#" class="list-group-item text-center">
               <h4 class="fa fa-credit-card fa-3x"></h4><br/>Cicilan
             </a>
             <a href="#" class="list-group-item text-center">
               <h4 class="fa fa-gamepad fa-3x"></h4><br/>Voucher Game
             </a>
             <a href="#" class="list-group-item text-center">
               <h4 class="fa fa-arrow-up fa-3x"></h4><br/>Top Up
             </a>
           </div>
         </div>
         <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
             <!-- flight section -->
             <div class="bhoechie-tab-content active">
               <form action="/PPOB/ppobPost/pulsa" method="post">
                 <br>
                 <h3 style="margin-top: 0;color:#E22D26">Pembelian Pulsa</h3>
                 <h6 style="margin-top: 0;">Silahkan pilih operator dan nominal</h6>
                 <label>Pilih Operator</label>
                 <select name="operator" id="operator" required>
                  <option value="" selected disabled>- Pilih Operator</option>
                 </select>
                 <label>Tentukan Nominal</label>
                 <select name="nominal" id="nominal" required>
                  <option value="" selected disabled>- Pilih Nominal</option>
                  <option value='P734884'>5.000</option>
                  <option value='P218013'>10.000</option>
                  <option value='P386182'>20.000</option>
                  <option value='P693632'>25.000</option>
                  <option value='P791441'>50.000</option>
                  <option value='P472648'>100.000</option>
                  <option value='P199451'>150.000</option>
                  <option value='P954384'>200.000</option>
                  <option value='P656457'>300.000</option>
                  <option value='P370906'>500.000</option>
                  <option value='P305985'>1.000.000</option>
                 </select>
                 <label>Masukkan NO.HP</label>
                 <input type="number" class="form-group" name="phone" placeholder="Contoh: 08123456789" value="" required>
                 <button class="btn btn-danger">Beli</button>
               </form>
             </div>
             <!-- train section -->
             <div class="bhoechie-tab-content">
               <form action="/PPOB/ppobPost/tiket" method="post" id="tiket">
                 <br>
                 <h3 style="margin-top: 0;color:#E22D26">Booking Tiket</h3>
                 <h6 style="margin-top: 0;">Silahkan pilih Layanan dan isi yang diperlukan</h6>

                 <label>Pilih Service</label>
                 <select name="service" id="servicetype" required>
                  <option value="" selected disabled>- Pilih Service</option>
                  <option value="kai">KAI</option>
                  <option value="travel">Travel</option>
                 </select>
                 <div id="service">
                 </div>
                 <button class="btn btn-danger">Pesan</button>
               </form>
             </div>

             <!-- pln search -->
             <div class="bhoechie-tab-content">
               <form action="/PPOB/ppobPost/tagihan" method="post">
                 <br>
                 <h3 style="margin-top: 0;color:#E22D26">Pembayaran Tagihan</h3>
                 <h6 style="margin-top: 0;">Silahkan pilih Tagihan dan ID Pelanggan</h6>

                 <label>Pilih Tagihan</label>
                 <select name="tagihan" id="tagihantype" required>
                  <option value="" selected disabled>- Pilih Tagihan</option>
                  <option value="pln" >PLN</option>
                  <option value="bpjskes" >BPJS Kesehatan</option>
                  <option value="bpjstk" >BPJS Ketenagakerjaan</option>
                  <option value="pgn" >PGN</option>
                 </select>

                 <label>Pilih Tipe</label>
                 <select name="productCode" id="tagihanlist" required>
                  <option value="" selected disabled>- Pilih Tipe Tagihan</option>
                  <option value="PLNNONTAGLISB" >Non-Taglis</option>
                  <option value="PLNPOSTPAIDB" >Post-Paid</option>
                  <option value="PLNPREPAIDB" >Pre-Paid</option>
                 </select>

                 <label>Masukkan ID Pelanggan</label>
                 <input type="number" class="form-group" name="idPel" placeholder="" value="" required>
                 <button class="btn btn-danger">Bayar</button>
               </form>
             </div>
             <div class="bhoechie-tab-content">
               <form action="/PPOB/ppobPost/cicilan" method="post">
                 <br>
                 <h3 style="margin-top: 0;color:#E22D26">Pembayaran Tagihan</h3>
                 <h6 style="margin-top: 0;">Silahkan pilih Tagihan dan ID Pelanggan</h6>

                 <label>Pilih Cicilan</label>
                 <select name="productCode" required>
                  <option value="" selected disabled>- Pilih Cicilan</option>
                  <option value="FNFIF" >FIF Finance</option>
                  <option value="FNWOMD" >Wahana Ottomitra Multiartha Finance</option>
                  <option value="MEGAFIND" >Mega Finance</option>
                  <option value="FNCOLUMD" >Columbia Finance</option>
                  <option value="FNBAFD" >Bussan Auto Finance</option>
                 </select>

                 {{-- <label>Masukkan Nominal</label>
                 <input type="number" class="form-group" name="nominal" placeholder="" value="" required> --}}

                 <label>Masukkan ID Pelanggan</label>
                 <input type="number" class="form-group" name="idPel" placeholder="" value="" required>
                 <button class="btn btn-danger">Bayar</button>
               </form>
             </div>
             <div class="bhoechie-tab-content">
               <form action="/PPOB/ppobPost/vouchergame" method="post">
                 <br>
                 <h3 style="margin-top: 0;color:#E22D26">Pembelian Voucher Game</h3>
                 <h6 style="margin-top: 0;">Silahkan pilih voucher dan nominal</h6>
                 <label>Pilih Voucher</label>
                 <select name="vouchergame" id="vouchergame" required>
                  <option value="" selected disabled>- Pilih Voucher</option>
                 </select>
                 <label>Tentukan Nominal</label>
                 <select name="nominal" id="nominal_vouchergame" required>
                  <option value="" selected disabled>- Pilih Nominal</option>
                  <option value='G502579'>510 Points</option>
                  <option value='G425387'>2.100 Points</option>
                  <option value='G874292'>3.240 Points</option>
                  <option value='G778458'>5.500 Points</option>
                  <option value='G458251'>8.550 Points</option>
                  <option value='G542468'>11.600 Points</option>
                 </select>
                 <br><br>
                 {{-- <label>Masukkan NO.HP</label>
                 <input type="text" class="form-group" name="" placeholder="Contoh: 08123456789"> --}}
                 <button class="btn btn-danger">Beli</button>
               </form>
             </div>

             <div class="bhoechie-tab-content">
               <form action="/PPOB/ppobPost/topup" method="post">
                 <br>
                 <h3 style="margin-top: 0;color:#E22D26">Top Up</h3>
                 <h6 style="margin-top: 0;">Silahkan pilih Jenis Top Up dan nominal</h6>

                 <label>Pilih Top Up</label>
                 <select name="topuptype" id="topuptype" required>
                  <option value="" selected disabled>- Pilih Top Up</option>
                  <option value="gopay" >GoPay</option>
                  <option value="ovo" >Ovo</option>
                  <option value="etoll" >E-Toll</option>
                 </select>

                 <label>Pilih Tipe</label>
                 <select name="productCode" id="topuplist" required>
                  <option value="" selected disabled>- Pilih Nominal</option>
                 </select>

                 <label>Masukkan ID Pelanggan</label>
                 <input class="form-group" name="idPel" placeholder="" value="" required>
                 <button class="btn btn-danger">Bayar</button>
               </form>
             </div>
         </div>
     </div>
 </div>
              </div>

            </div>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            @if ($premium_product->count() > 0)
            <div class="feature-product-area dotted-style-2">
              <div class="section-title">
                <h3>Produk Premium</h3>
              </div>
              <div class="feature-product-active border-1">
                @foreach($premium_product as $result)
                 <div class="single-product single-product-sidebar white-bg product-slide">
                  @if($result->sale != 0)
                    <div class="product_icon">
                          <div class='new-icon'><div class="div-custom">SALE</div></div>
                      </div>
                  @endif
                  <div class="product-img pt-20">
                    <a href="{{$result->url}}">
                        <img src="{{$result->image_dir}}" class="item-img-product" alt="" style="background: white"/>
                    </a>
                  </div>
                  <div class="product-content product-i">
                    <div class="pro-title">
                      <h4 class="text-readmore">
                        <a href="{{$result->url}}">
                            {{$result->name}}
                        </a>
                      </h4>
                    </div>
                    <div class="pro-rating ">
                      {!! rate_html((int)$result->reviews->avg('rate')) !!}
                    </div>
                    <div class="price-box">
                      <span class="price product-price">
                        Rp. {{number_format($result->price,0,',','.')}}
                      </span>
                      <div class="gap-xs"></div>
            @if($result->price_false>0)
            <strike><span class="old-price product-price">{{$result->pricefalse_txt}}</span></strike>
            @endif
                    </div>
                    <div class="product-icon">
                      <div class="product-icon-left f-left">
                        <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->id}}" data-qty="{{$result->min}}">
                        <i class="fa fa-shopping-cart"></i>Add to Cart
                      </a>
                      </div>
                      <div class="product-icon-right floatright">
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif
            <!-- feature-product-area -->
            <div class="feature-product-area dotted-style-2">
              <div class="section-title">
                <h3>Produk terpopuler</h3>
              </div>
              <div class="feature-product-active border-1">
                @foreach($popular as $result)
                 <div class="single-product single-product-sidebar white-bg product-slide">
                 	@if($result->sale != 0)
	                 	<div class="product_icon">
	                        <div class='new-icon'><div class="div-custom">SALE</div></div>
	                    </div>
                 	@endif
                  <div class="product-img pt-20">
                    <a href="{{$result->url}}">
                        <img src="{{$result->image_dir}}" class="item-img-product" alt="" style="background: white"/>
                    </a>
                  </div>
                  <div class="product-content product-i">
                    <div class="pro-title">
                      <h4 class="text-readmore">
                        <a href="{{$result->url}}">
                            {{$result->name}}
                        </a>
                      </h4>
                    </div>
                    <div class="pro-rating ">
                      {!! rate_html((int)$result->reviews->avg('rate')) !!}
                    </div>
                    <div class="price-box">
                      <span class="price product-price">
                        Rp. {{number_format($result->price,0,',','.')}}
                      </span>
                      <div class="gap-xs"></div>
						@if($result->price_false>0)
						<strike><span class="old-price product-price">{{$result->pricefalse_txt}}</span></strike>
						@endif
                    </div>
                    <div class="product-icon">
                      <div class="product-icon-left f-left">
                        <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->id}}" data-qty="{{$result->min}}">
	                    	<i class="fa fa-shopping-cart"></i>Add to Cart
	                    </a>
                      </div>
                      <div class="product-icon-right floatright">
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <!-- new-product-area -->
            <div class="feature-product-area dotted-style-2">
              <div class="section-title">
                <h3>Produk terbaru</h3>
              </div>
              <div class="feature-product-active border-1">
                @foreach($newest as $result)
                 <div class="single-product single-product-sidebar white-bg product-slide">
                 	@if($result->sale != 0)
	                 	<div class="product_icon">
	                        <div class='new-icon'><div class="div-custom">SALE</div></div>
	                    </div>
                 	@endif
                  <div class="product-img pt-20">
                    <a href="{{$result->url}}">
                        <img src="{{$result->image_dir}}" class="item-img-product" alt="" style="background: white"/>
                    </a>
                  </div>
                  <div class="product-content product-i">
                    <div class="pro-title">
                      <h4 class="text-readmore">
                        <a href="{{$result->url}}">
                            {{$result->name}}
                        </a>
                      </h4>
                    </div>
                    <div class="pro-rating ">
                      {!! rate_html((int)$result->reviews->avg('rate')) !!}
                    </div>
                    <div class="price-box">
                      <span class="price product-price">
                        Rp. {{number_format($result->price,0,',','.')}}
                      </span>
                      <div class="gap-xs"></div>
        						@if($result->price_false>0)
        						<strike><span class="old-price product-price">{{$result->pricefalse_txt}}</span></strike>
        						@endif
                    </div>
                    <div class="product-icon">
                      <div class="product-icon-left f-left">
                        <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->id}}" data-qty="{{$result->min}}">
                        <i class="fa fa-shopping-cart"></i>Add to Cart
                      </a>
                      </div>
                      <div class="product-icon-right floatright">

                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <!-- banner-area-start -->
            <div class="banner-area pt-40">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="slider-single-img res">
                    @if(isset($__BANNER_PREMIUM_HOME1->url))
                    <a href="{{$__BANNER_PREMIUM_HOME1->url}}">
                        <img class="img_a" src="{{$__BANNER_PREMIUM_HOME1->image_dir}}" alt="" />
                        <img class="img_b" src="{{$__BANNER_PREMIUM_HOME1->image_dir}}" alt="" />
<!-- =======
                    <a href="#">
                        <img class="img_a" src="{{base_url()}}/contents//images/lg/banners/BANNER_Banner_Tengah_(20181119112902).jpg" alt="" />
                        <img class="img_b" src="{{base_url()}}/contents//images/lg/banners/BANNER_Banner_Tengah_(20181119112902).jpg" alt="" />
>>>>>>> Stashed changes -->
                    </a>
                    @endif
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="slider-single-img">
                    @if(isset($__BANNER_PREMIUM_HOME2->url))
                    <a href="{{$__BANNER_PREMIUM_HOME2->url}}">
                        <img class="img_a" src="{{$__BANNER_PREMIUM_HOME2->image_dir}}" alt="" />
                        <img class="img_b" src="{{$__BANNER_PREMIUM_HOME2->image_dir}}" alt="" />
<!-- =======
                    <a href="#">
                        <img class="img_a" src="{{base_url()}}/contents//images/lg/banners/BANNER_Banner_Tengah_(20181119112902).jpg" alt="" />
                        <img class="img_b" src="{{base_url()}}/contents//images/lg/banners/BANNER_Banner_Tengah_(20181119112902).jpg" alt="" /> -->
                    </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- banner-area -->
            <div class="banner-area ptb-40">
              <div class="slider-single-img res text-center">
                <a href="{{$__BANNER_MID->url}}">
                  <img class="img_a" src="{{$__BANNER_MID->image_dir}}"/>
                  <img class="img_b" src="{{$__BANNER_MID->image_dir}}"/>
                </a>
              </div>
            </div>

            <div class="gap-md"></div>
            <div class="feature-product-area dotted-style-2">
              <div class="section-title">
                <h3>Produk Terpopuler dari Kategori {{@$nama_category->name}}</h3>
              </div>
              <div class="feature-product-active border-1">
                @foreach(@$category_special as $result)
                 <div class="single-product single-product-sidebar white-bg product-slide">
                  @if($result->sale != 0)
                    <div class="product_icon">
                          <div class='new-icon'><div class="div-custom">SALE</div></div>
                      </div>
                  @endif
                  <div class="product-img pt-20">
                    <a href="{{$result->url}}">
                        <img src="{{$result->image_dir}}" class="item-img-product" alt="" style="background: white"/>
                    </a>
                  </div>
                  <div class="product-content product-i">
                    <div class="pro-title">
                      <h4 class="text-readmore">
                        <a href="{{$result->url}}">
                            {{$result->name}}
                        </a>
                      </h4>
                    </div>
                    <div class="pro-rating ">
                      {!! rate_html((int)$result->reviews->avg('rate')) !!}
                    </div>
                    <div class="price-box">
                      <span class="price product-price">
                        Rp. {{number_format($result->price,0,',','.')}}
                      </span>
                      <div class="gap-xs"></div>
                    @if($result->price_false>0)
                    <strike><span class="old-price product-price">{{$result->pricefalse_txt}}</span></strike>
                    @endif
                    </div>
                    <div class="product-icon">
                      <div class="product-icon-left f-left">
                        <a href="javascript:void(0)"  class="link-cart" data-id="{{$result->id}}" data-qty="{{$result->min}}">
                        <i class="fa fa-shopping-cart"></i>Add to Cart
                      </a>
                      </div>
                      <div class="product-icon-right floatright">
                        <!--<a hhref="javascript:void(0)"  class="link-compare" data-id="{{$result->id}}"  title="Taruh Di Daftar Perbandingan" data-toggle="tooltip"><i class="fa fa-exchange"></i></a>-->

                          <!-- wishlist dimatikan -->
                          <!-- @if(in_array($result->id,$array_id))
                                  <a href="javascript:void(0)" class="link-wishlist right" data-id="{{$result->id}}" data-id="{{$result->id}}" title="Taruh Di Daftar Keinginan"  data-toggle="tooltip">
                                      <i id="{{$result->id}}" class="fa fa-heart" style="color:red;"></i>
                                  </a>
                             @else
                                  <a href="javascript:void(0)" class="link-wishlist" data-id="{{$result->id}}" data-id="{{$result->id}}" title="Taruh Di Daftar Keinginan"  data-toggle="tooltip">
                                      <i id="{{$result->id}}" class="fa fa-heart"></i>
                                  </a>
                        @endif -->

                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>

<!-- <<<<<<< HEAD

======= -->
            <!-- banner-area-start -->
            <!-- <div class="banner-area pt-40">
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="slider-single-img res">
                    <a href="#">
                        <img class="img_a" src="/assets/images/adssmall.png" alt="" />
                        <img class="img_b" src="/assets/images/adssmall.png" alt="" />
                    </a>
                  </div>
                </div> -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <!-- <div class="slider-single-img">
                    <a href="#">
                        <img class="img_a" src="/assets/images/adssmall.png" alt="" />
                        <img class="img_b" src="/assets/images/adssmall.png" alt="" />
                    </a>
                  </div> -->
                </div>
              </div>
            </div>
<!-- >>>>>>> c3bc37f350c979f985ec2aa17d5a440a9dea3d18 -->
          </div>

        </div>
      </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.20.0/jquery.fancytree-all.js"></script>
<script type="text/javascript" src="/assets/js/pages/product_list.js"></script>
<script type="text/javascript" src="{{ base_url() }}assets/js/pages/ajax-ppob.js"></script>
@endsection
