$(document).ready(function(){
	var ve_ncc;
	var dv;
	var cp;
	var data_new;
	var result;
	// var pos = $(".entry_info").offset().top;
	// var bottom = $(".entry_info").offset().bottom;
	if($('#ct_tour').is(':hidden')) {
		    $('#cp_tour').removeClass('col-md-6').addClass('col-md-12');
		    $('#cp_tour').find('.cp_form').addClass('pull-right');
	}
	$('.tgl_tour').click(function(){
		$('#ct_tour').toggle();

		if($('#ct_tour').is(':hidden')) {
		    $('#cp_tour').removeClass('col-md-6').addClass('col-md-12');
		    $('#cp_tour').find('.cp_form').removeClass('fixed').addClass('pull-right');
		    $('#cp_tour').find('.cp_form').removeClass('col-md-12').addClass('col-md-6');
		    $('#cp_tour').find('#cp_table').removeClass('col-md-12').addClass('col-md-6');

		}else{
			$('#cp_tour').removeClass('col-md-12').addClass('col-md-6');
			$('#cp_tour').find('.cp_form').addClass('pull-right');
			$('#cp_tour').find('.cp_form').removeClass('col-md-6').addClass('col-md-12');
			$('#cp_tour').find('#cp_table').removeClass('col-md-6').addClass('col-md-12');
		}
		$('.select2').css('width','100%');
		// $('#cptour-ncc').select2({
		// 	placeholder: "Search",
		// 	minimumInputLength: 2,
		// 	ajax: {
		// 	    url: "/appbasic/web/cptour/search_ncc",
		// 	    dataType: 'json',
		// 	    delay: 250,
		// 	    data: function (params) {
		// 			return {
		// 				q: params.term,
		// 				page: params.page || 1
		// 			};
		// 	    },
		// 	    processResults: function (data, params) {
		// 			params.page = params.page || 1;
		// 			return  {
		// 			    results: $.map(data.items, function (obj) {
		// 								obj.id = obj.id;
		// 								obj.text = obj.text || obj.name;
		// 								return obj;
		// 							}),
		// 			    pagination: {
		// 			     	more: (params.page * 20) < data.total_count
		// 			    }
		// 			};
		// 		},
		// 		cache: true
		// 	},
		// });
	});
	$('#cptour-ncc').select2({
		placeholder: "Search",
		minimumInputLength: 2,
		ajax: {
		    url: "/appbasic/web/cptour/search_ncc",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
				return {
					q: params.term,
					page: params.page || 1
				};
		    },
		    processResults: function (data, params) {
				params.page = params.page || 1;
				return  {
				    results: $.map(data.items, function (obj) {
									obj.id = obj.id;
									obj.text = obj.text || obj.name;
									return obj;
								}),
				    pagination: {
				     	more: (params.page * 20) < data.total_count
				    }
				};
			},
			cache: true
		},
	});
	$(document).on('change', '#cptour-ncc', function(){
		var id_ncc = $(this).val();
		if (id_ncc != '') {
			$.ajax({
				url: "/appbasic/web/cptour/list_dv",
				type: "GET",
				data: {id_ncc: id_ncc},
				dataType: "json",
				success: function(response){
					var data = $.map(response, function (obj) {
						obj.id = obj.id;
						obj.text = obj.text || obj.name; // replace name with the property used for the text
						return obj;
					});
					$('#cptour-dv').html('');
					$('#cptour-dv')
									.append($('<option>', {value: '', text : ''}))
									.select2({
										placeholder: "Select a service",
										data: data,
										tags: "true",
										maximumInputLength: 20
									});
				},
				error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
			});
		}
	});
	$(document).on('change', '#cptour-dv', function(){
		var dv_id = $(this).val();
		if (dv_id != '') {
			$.ajax({
				url: "/appbasic/web/cptour/list_cp",
				type: "GET",
				data: {dv_id: dv_id},
				dataType: "json",
				success: function(response){
					if (response.error) {
						console.log(response.error); return;
					}
					//console.log(response);return;response.currency = 'USD';
					$('#cptour-sl').val(response['sl']);
					$('#cptour-gia').val(response.price);
					$('#cptour-unit').val(response.currency);
				},
				error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
			});
		}
	});
	// $(window).scroll(function(){
	//     $(".entry_info").css({
	//     	position:'fixed',
	//     	top: Math.max(0, pos - $(this).scrollTop())
	//     });
	// });
});


$(window).scroll(function(){//var posA = $(".entry_info").offset().top;
		var pos = $(".entry_info").offset().top;
		if($(this).scrollTop()>pos) {
			$(".entry_info").css({
				position: 'fixed',
				top: $(this).scrollTop()-pos
			});
		}else {
			$(".entry_info").css({
				position: 'relative',
				top: $(this).scrollTop()-0
			});
		};
  	});

