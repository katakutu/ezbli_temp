var element = 'body';

$.ajax({
		url:"/Main/list/pulsa",
		method:'GET',
    beforeSend:function(){
      var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
			$('#operator').html(wait);
		}
	}).done(function(response){
		$('#operator').html(response);
	}).fail(function(response){
		var response = response.responseJSON;
		sweetAlert("Oops...",response.message, "error");
	})

$.ajax({
		url:"/Main/list/vouchergame",
		method:'GET',
    beforeSend:function(){
      var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
			$('#vouchergame').html(wait);
		}
	}).done(function(response){
		$('#vouchergame').html(response);
	}).fail(function(response){
		var response = response.responseJSON;
		sweetAlert("Oops...",response.message, "error");
	})

$("#operator").change(function(){
  $.ajax({
  		url:"/Main/nominal_pulsa/"+$("#operator").val(),
  		method:'GET',
      beforeSend:function(){
        var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
  			$('#nominal').html(wait);
  		}
  	}).done(function(response){
  		$('#nominal').html(response);
  	}).fail(function(response){
  		var response = response.responseJSON;
  		sweetAlert("Oops...",response.message, "error");
  	})
})

$("#topuptype").change(function(){
  $.ajax({
  		url:"/Main/list/"+$("#topuptype").val(),
  		method:'GET',
      beforeSend:function(){
        var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
  			$('#topuplist').html(wait);
  		}
  	}).done(function(response){
  		$('#topuplist').html(response);
  	}).fail(function(response){
  		var response = response.responseJSON;
  		sweetAlert("Oops...",response.message, "error");
  	})
})

$("#tagihantype").change(function(){
	var val = $(this).val();
	if(val == "pln"){
		var echo = "<option value=\"PLNNONTAGLISB\" >Non-Taglis</option><option value=\"PLNPOSTPAIDB\" >Post-Paid</option><option value=\"PLNPREPAIDB\" >Pre-Paid</option>";
		$("#tagihanlist").html(echo);
	}

	else if(val == "bpjskes"){
		var echo = "<option value=\"bpjskes\" >BPJSKES</option>";
		$("#tagihanlist").html(echo);
	}

	else if(val == "bpjstk"){
		var echo = "<option value=\"bpjstk\" >BPJSTK</option>";
		$("#tagihanlist").html(echo);
	}

	else if(val == "pgn"){
		var echo = "<option value=\"pgn\" >PGN</option>";
		$("#tagihanlist").html(echo);
	}

})

$("#servicetype").change(function(){
	var val = $(this).val();
	if(val == "kai"){
		var echo = "<label>Dari Stasiun: </label><select name=\"asal\" id=\"kaisrc\" required></select><label>Ke Stasiun: </label><select name=\"tujuan\" id=\"kaidest\" required></select><label>Tanggal: </label><input type=\"date\" id=\"tanggal\" name=\"tanggal\" required><label>Pilih Kereta: </label><select name=\"no_kereta\" id=\"no_kereta\" required></select><label>Pilih Kelas: </label><select name=\"kode_gerbong\" id=\"kode_gerbong\" required></select><label>Pilih Gerbong: </label><select name=\"no_gerbong\" id=\"no_gerbong\" required></select><label>Pilih Subclass: </label><select name=\"subclass\" id=\"subclass\" required><option selected disabled>- Pilih Subclass</option><option value=\"A\">A</option><option value=\"B\">B</option><option value=\"C\">C</option></select><label>Jumlah Penumpang (Selain bayi): </label><input type=\"number\" max=4 min=1 name=\"dewasa\" id=\"dewasa\" required><div id=\"adult\"></div><label>Infant: </label><input type=\"number\" max=4 name=\"bayi\" min=0 id=\"bayi\" required><div id=\"infant\"></div><label>Pilih Seat: </label><select multiple=\"multiple\" name=\"kursi[]\" id=\"kursi\" required></select>";
		$("#service").html(echo);
		var last_valid_selection = null;
    $('#kursi').change(function(event) {
      if ($(this).val().length > 4 || $(this).val().length > $("#dewasa").val()) {
        $(this).val(last_valid_selection);
      } else {
        last_valid_selection = $(this).val();
      }
    });
		$.ajax({
				url:"/Main/list/kaistation",
				method:'GET',
		    beforeSend:function(){
		      var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
					$('#kaisrc').html(wait);
					$('#kaidest').html(wait);
				}
			}).done(function(response){
				$('#kaisrc').html(response);
				$('#kaidest').html(response);
			}).fail(function(response){
				var response = response.responseJSON;
				sweetAlert("Oops...",response.message, "error");
			})
		$("#tanggal, #kaisrc, #kaidest").change(function(){
			$.ajax({
					url:"/Main/list/kaischedule",
					method:'POST',
					data: $("#tiket").serialize(),
			    beforeSend:function(){
			      var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#no_kereta').html(wait);
					}
				}).done(function(response){
					$('#no_kereta').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
		$("#tanggal, #kaisrc, #kaidest, #no_kereta").change(function(){
			$.ajax({
					url:"/Main/list/kaiclass",
					method:'POST',
					data: $("#tiket").serialize(),
					beforeSend:function(){
						var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#kode_gerbong').html(wait);
					}
				}).done(function(response){
					$('#kode_gerbong').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
		$("#tanggal, #kaisrc, #kaidest, #no_kereta, #kode_gerbong").change(function(){
			$.ajax({
					url:"/Main/list/kaigerbong",
					method:'POST',
					data: $("#tiket").serialize(),
					beforeSend:function(){
						var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#no_gerbong').html(wait);
					}
				}).done(function(response){
					$('#no_gerbong').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
		$("#dewasa").change(function(){
			var jmldws = $("#dewasa").val();
			var form = '';
			for(i=0; i<jmldws; i++){
				form += "<label>Nama : </label><input type=\"text\" name=\"adult_name[]\" required><label>Tanda Pengenal: </label><input type=\"text\" name=\"adult_id[]\" maxlength=24><label>Tanggal Lahir: </label><input type=\"date\", name=\"adult_date_of_birth[]\"><label>Nomor HP: </label><input type=\"number\" name=\"adult_phone[]\">";
			}
			$("#adult").html(form);
		})
		$("#bayi").change(function(){
			var jmlby = $("#bayi").val();
			var form = '';
			for(i=0; i<jmlby; i++){
				form += "<label>Nama : </label><input type=\"text\" name=\"infant_name[]\" required><label>Tanggal Lahir: </label><input type=\"date\", name=\"infant_date_of_birth[]\">";
			}
			$("#infant").html(form);
		})
		$("#tanggal, #kaisrc, #kaidest, #no_kereta, #kode_gerbong, #no_gerbong").change(function(){
			$.ajax({
					url:"/Main/list/kaiseat",
					method:'POST',
					data: $("#tiket").serialize(),
					beforeSend:function(){
						var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#kursi').html(wait);
					}
				}).done(function(response){
					$('#kursi').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
	}

	else if(val == "travel"){
		var echo = "<label>Pilih Agen</label><select name=\"kodeAgen\" id=\"kodeAgen\" required></select><label>Pilih Keberangkatan</label><select name=\"idKeberangkatan\" id=\"idKeberangkatan\" required></select><label>Pilih Kedatangan: </label><select name=\"idKedatangan\" id=\"idKedatangan\"></select><label>Tanggal: </label><input type=\"date\" id=\"tanggal\" name=\"tanggal\"><label>Jumlah Penumpang: </label><input type=\"number\" id=\"penumpang\" name=\"penumpang\" max=8 min=1>";
		$("#service").html(echo);
		$.ajax({
				url:"/Main/list/travelGetAgen",
				method:'GET',
		    beforeSend:function(){
		      var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
					$('#kodeAgen').html(wait);
				}
			}).done(function(response){
				$('#kodeAgen').html(response);
			}).fail(function(response){
				var response = response.responseJSON;
				sweetAlert("Oops...",response.message, "error");
			})
		$("#kodeAgen").change(function(){
			$.ajax({
					url:"/Main/list/travelGetKeberangkatan",
					method:'POST',
					data: $("#tiket").serialize(),
			    beforeSend:function(){
			      var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#idKeberangkatan').html(wait);
					}
				}).done(function(response){
					$('#idKeberangkatan').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
		$("#idKeberangkatan").change(function(){
			$.ajax({
					url:"/Main/list/travelGetKedatangan",
					method:'POST',
					data: $("#tiket").serialize(),
					beforeSend:function(){
						var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#idKedatangan').html(wait);
					}
				}).done(function(response){
					$('#idKedatangan').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
		$("#tanggal").change(function(){
			$.ajax({
					url:"/Main/list/travelGetJadwal",
					method:'POST',
					data: $("#tiket").serialize(),
					beforeSend:function(){
						var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
						$('#tanggal').html(wait);
					}
				}).done(function(response){
					$('#tanggal').html(response);
				}).fail(function(response){
					var response = response.responseJSON;
					sweetAlert("Oops...",response.message, "error");
				})
		})
	}

})

$("#vouchergame").change(function(){
  $.ajax({
  		url:"/Main/nominal_vouchergame/"+$("#vouchergame").val(),
  		method:'GET',
      beforeSend:function(){
        var wait = "<option value=\"\" selected disabled>- Mohon tunggu</option>";
  			$('#nominal_vouchergame').html(wait);
  		}
  	}).done(function(response){
  		$('#nominal_vouchergame').html(response);
  	}).fail(function(response){
  		var response = response.responseJSON;
  		sweetAlert("Oops...",response.message, "error");
  	})
})
