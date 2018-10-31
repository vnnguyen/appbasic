//////////////////////global variable////////////////////////////////////////////
var id_dv = 0;
var day_use = '';
var venue_id = 0;
var dvc_current = null;
var dv_options = null;
var option_id_current = 0;
var option_curr = null;
var option_selected = [];
var tour_id;
var form_status = 'create';
var cpt_updated_curr;


    tooltip = new PNotify({
        title: "Group",
        text: "none",
        hide: false,
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        },
        animate_speed: "fast",
        opacity: .9,
        icon: "fa fa-commenting",
        // Setting stack to false causes PNotify to ignore this notice when positioning.
        stack: false,
        auto_display: false,
        // width: "270px"
    });
    // Remove the notice if the user mouses over it.
    tooltip.get().mouseout(function() {
        tooltip.remove();
    });

	$(document).on({
		mouseover: function(event){
			var group_id = $(this).closest('td').data('group_id');
			var text = '';
			$('#body-list-cpt').find('tr').each(function(ind, tr){
				var this_group_id = $(tr).find('.group_id').data('group_id');
				if (this_group_id == group_id) {
					text +='<span class="tooltip_span">'+$(tr).find('.venue_update').text()+'</span><br />';
				}
			});
			tooltip.update({
				text: text
			});
			tooltip.open();
			tooltip.get().css({
				'top': event.clientY ,
				'left': event.clientX + 12
			});
		},
		mouseout: function(){
			tooltip.remove();
		},
		// mousemove: function(event){
		// 	tooltip.get().css({
		// 		'top': event.clientY -100,
		// 		'left': event.clientX + 12
		// 	});
		// },
		click: function(event){
			var group_id = $(this).closest('td').data('group_id');
			var text = '';
			$('#body-list-cpt').find('tr').each(function(ind, tr){
				var this_group_id = $(tr).find('.group_id').data('group_id');
				if (this_group_id == group_id) {
					text +='<span class="tooltip_span">'+$(tr).find('.venue_update').text()+'</span><br />';
				}
			});
			tooltip.update({
				text: text
			});
			tooltip.open();
			tooltip.get().css({
				'top': event.clientY ,
				'left': event.clientX + 12
			});

		}
		},"._title" );
////////////////////////////////form nhap dong////////////////////////////////////
	$('#cptourForm #cptour-dv_id').select2({
		placeholder: 'select'
	});
	$(document).on('click', '.span-add_cpt, .span-edit_cpt', function(){
		resetVar();
		var clicked = $(this);
		var parent_tr = $('#wrap-cptForm').closest('tr');
		var tr_clicked = clicked.closest('tr');
		var new_form = $('#wrap-cptForm');
		$('#wrap-cptForm').find('#cancel_btn').show();
		$('#cptourForm').find('.select2').css('width', '100%');

		$('<tr>').append($('<td colspan="8">').append(new_form)).insertAfter(tr_clicked);
		if (parent_tr.length > 0) {
			$(parent_tr).remove();
		}
		$('#cptourForm').formValidation('resetForm', true);
		if (clicked.data('cpt-id') != '' && clicked.data('cpt-id') > 0) {
			form_status = 'update';
			var cpt_id = clicked.data('cpt-id');
			$.ajax({
				method: 'GET',
				url: '/cptour/get_cpt',
				data: {cpt_id: cpt_id},
				dataType: 'json'
			}).done(function(response){
				if (response.err != undefined) { console.log(response.err); return;}
				var cpt = cpt_updated_curr = response.cpt;
				option_selected = response.cpt_op;
				// console.log(response);return;
				var dvs = response.dvs;
				var data_dv;
				if (cpt.parent_id != 0) {
					$('#option_service').hide();
					data_dv = $.map(dvs.options, function (obj) {
							// obj.name = obj.name.allReplace({'{': '(', '}': ')'});
							obj.id = obj.id;
							obj.text = obj.text || obj.name; // replace name with the property used for the text
							return obj;
						});
				} else {
					data_dv = $.map(dvs.dv, function (obj) {
							// obj.name = obj.name.allReplace({'{': '(', '}': ')'});
							obj.id = obj.id;
							obj.text = obj.text || obj.name; // replace name with the property used for the text
							return obj;
						});
					if (dvs.options && dvs.options.length > 0) {
						dv_options = dvs.options;
					}
					$('#option_service').show();
					$('#cptourForm #wrap-options').empty();

					if (dv_options != null && dv_options.length > 0) {
						$('#cptourForm #wrap-options').css('display','inline-block');
						jQuery.each(dv_options, function(index, option){
							var chk_class = '';
							var cpt_op;
							var data_cpt_option = '';
							jQuery.each(option_selected, function(k, cpt_o){
								if (option.id == cpt_o.dv_id) {
									chk_class = 'checked';
									cpt_op = cpt_o;
									return false;
								}
							});
							if (cpt_op != undefined) {
								data_cpt_option = JSON.stringify(cpt_op);
							} else {
								cpt_op = '';
							}
							//JSON.stringify -> serializes object to string
							//JSON.parse -> deserializes object from string
							var html = '<label class="checkbox-inline"> <div id="dv-'+option.id+'" class="checker" data-dv_op="'+option.id+'" data-cpt_op="'+cpt_op.id+'"><span class="'+ chk_class +'"><input class="styled" name="chk_option[]" type="checkbox" value="" '+chk_class+'></span></div> '+option.name+'</label>';
							$('#cptourForm #wrap-options').append(html);
							$('#cptourForm #wrap-options').find('.checkbox-inline:last').find('.styled').val(data_cpt_option);
						});
						$('#cptourForm #wrap-options').show();
					}
				}
				$('#cptourForm #cptour-dv_id').html('');
				$('#cptourForm #cptour-dv_id')
								.append($('<option>', {value: '', text : ''}))
								.select2({
									placeholder: "Select a service",
									data: data_dv,
									tags: "true",
									maximumInputLength: 20
								}).on("load", function(e) {
								     $(this).prop('tabindex',2);
								 }).trigger('load');
				if (cpt['group_id'] > 0) {
					$('#cptourForm').find('#group-option span').addClass('checked');
					$('#cptourForm').find('#group-option .wrap-cpt-group').fadeIn();
					$('#cptourForm').find('#group-option #cpt-group').val(cpt['group_id']);
					$('#cptourForm').find('#group-option #cpt-group').select2();
				} else {
					$('#cptourForm').find('#group-option span').removeClass('checked');
				}
				$('#cptourForm').find('#cptour-id').val(cpt['id']);
				$('#cptourForm').find('#cptour-who_pay').val(cpt['who_pay']);
				$('#cptourForm').find('#cptour-qty').val(cpt['qty']);
				$('#cptourForm').find('#cptour-num_day').val(cpt['num_day']);
				$('#cptourForm').find('#cptour-use_day').val(new Date(cpt['use_day']).yyyymmdd());
				$('#cptourForm').find('#cptour-price').val(cpt['price']);
				$('#cptourForm').find('#cptour-currency').val(cpt['currency']);
				$('#cptourForm').find('#cptour-payment_dt').val(new Date(cpt['payment_dt']).yyyymmdd());
				$('#cptourForm').find('#cptour-book_of').val(cpt['book_of']);
				$('#cptourForm').find('#cptour-pay_of').val(cpt['pay_of']);
				$('#cptourForm').find('#cptour-status_book').val(cpt['status_book']);
				$('#cptourForm #cptour-dv_id').val(cpt['dv_id']).trigger('change');
			});
		} else {
			form_status = 'create';
		}
	});
	$(document).on('click', '#cancel_btn', function(){
		var tr = $(this).closest('tr');
		var new_form = $('#wrap-cptForm');
		$(this).hide();
		$('#cp_tour').append(new_form);
		resetVar();
		$(tr).remove();
	});

	$(document).on('click', '.span-remove_cpt', function(){
		var clicked = $(this);
		var cpt_id = clicked.data('cpt-id');
		(new PNotify({
		    title: 'Confirmation Needed',
		    text: 'Are you sure?',
		    icon: false,
		    // hide: false,
		    styling: 'bootstrap3',
		    confirm: {
	            confirm: true,
	            buttons: [{
	                text: 'Ok!',
	                addClass: 'confirm-ok',
	             //    click: function(notice) {
		            //     notice.remove();
		            // }
	            }, {
	                text: 'Cancel!',
	                addClass: 'no'
	            }]
	        },
		    buttons: {
		        closer: false,
		        sticker: false
		    },
		    history: {
		        history: false
		    },
		    addclass: 'stack-modal',
		    stack: {
		        'dir1': 'down',
		        'dir2': 'right',
		        'modal': true
		    },
            after_open: function (notify) {
	            $(".confirm-ok", notify.container).focus();
	        }
		})).get().on('pnotify.confirm', function(notify) {
		    $.ajax({
				method: 'GET',
				url: '/cptour/remove_cpt',
				data: {cpt_id: cpt_id},
				dataType: 'json'
			}).done(function(response){
				console.log(response);
				if (response.success) {
					// $(clicked).closest('tr').remove();
					$('#body-list-cpt').find('tr').each(function(idex, tr){
						var cpt_id = $(tr).data('cpt_id');
						if (response.success.indexOf(cpt_id) != -1) {
							$(tr).fadeOut(400, function(){
								$(this).remove();
							});
						}
					});
				}
			});
		})

	});
///////////////////////////////////end form nhap dong///////////////////////////////



////////////////////////////////form nhap nhanh////////////////////////////////////
	$(document).on('change', '#cptourForm #cpt-group', function(){
		$('#group-option').data('cpt-group', $(this).val());
	});
	$(document).on('click', '#cptourForm #group-option .checker', function(e){
		$(this).find('span').toggleClass('checked');
		if ($(this).find('span').hasClass('checked')) {
			$('#cptourForm #group-option').find('.wrap-cpt-group').fadeIn();
			$('#cptourForm #cpt-group').select2();
			if (form_status == 'update') {
				if (cpt_updated_curr.group_id != 0) {
					$('#cptourForm #cpt-group').val(cpt_updated_curr.group_id).trigger('change');
				}
			}
		} else {
			$('#cptourForm #group-option').find('#cpt-group').val('').trigger('change');
			$('#cptourForm #group-option').find('.wrap-cpt-group').fadeOut();
		}
		$('#cptourForm').find('.select2').css('width', '100%')
	});
	$(document).on('click', '#wrap-options .checker', function(e){
		var clicked = $(this);
		$(clicked).find('span').toggleClass('checked');
		option_id_current = $(this).data('dv_op');
		$('#optionForm').formValidation('resetForm', true);
		if ($(clicked).find('span').hasClass('checked')) {
			option_curr = null;
			$('#wrap-op-auto-price').empty();
			jQuery.each(dv_options, function(i_op, op){
				if (op.id == option_id_current) {
					jQuery.each(op.cp, function(i_cp, cp){
						var name_price = (cp.conds != '')?cp.conds: 'Price';
						if (dvc_current != null && cp.period == dvc_current.dvd.code && cp.dvc_id == dvc_current.id) {
							var html = '<span class="text-muted auto_price"><small>'+name_price+': '+new Number(cp.price).format(2)+' '+cp.currency+'</small></span>'
							$('#wrap-op-auto-price').append(html);
						}
					});
				}
			});
			$('#cpt_detail_option_modal').on('show.bs.modal', function () {
				$('#optionForm').find('[name="option-dv_id"]').val(option_id_current);
				if (form_status == 'update') {
					if ($(clicked).data('cpt_op')!= 'undefined') {
						$('#optionForm').find('[name="option-cpt_op_id"]').val($(clicked).data('cpt_op'));
					}
					var data_form = $(clicked).find('.styled').val();

					if (data_form.length > 0) {
						var cp_op = JSON.parse(data_form);
						if (cp_op) {
							$('#optionForm').find('[name="option-qty"]').val(cp_op.qty);
							$('#optionForm').find('[name="option-price"]').val(cp_op.price);
							$('#optionForm').find('[name="option-currency"]').val(cp_op.currency);
							if (cp_op.note) {
								$('#optionForm').find('[name="option-note"]').val(cp_op.note);
							}
						}
					}
					// console.log(JSON.parse(data_form));
				}
				if (form_status == 'create') {
					var data_form = $(clicked).find('.styled').val();

					if (data_form.length > 0) {
						var cp_op = JSON.parse(data_form);
						if (cp_op) {
							$('#optionForm').find('[name="option-qty"]').val(cp_op.qty);
							$('#optionForm').find('[name="option-price"]').val(cp_op.price);
							$('#optionForm').find('[name="option-currency"]').val(cp_op.currency);
							if (cp_op.note) {
								$('#optionForm').find('[name="option-note"]').val(cp_op.note);
							}
						}
					}
				}
			});
			$('#cpt_detail_option_modal').modal('show');
		} else {
			$(clicked).find('.styled').prop('checked', false);
			// $.ajax({// 	method: 'GET', // 	url: '', // 	data: {}, // 	dataType: 'json'// }) // .done(function(result){// 	console.log(result); // });
		}
	});
	$('#cpt_detail_option_modal').on('shown.bs.modal', function () {
	    $('#optionForm').find('[name="option-qty"]').focus();
	});
	$(document).on('focus', '#cptourForm .wrap-ncc .select2, #cptourForm #wrap-dv .select2', function() {
	    $(this).siblings('select').select2('open');
	});
///////////////////////////////////end form nhap nhanh///////////////////////////////

$(document).ready(function(){
// $('input, select, textarea, button').removeAttr("tabindex");
////////////////////////////////form nhap nhanh////////////////////////////////////
	//validate form nhap cpt
	$('#cptourForm').formValidation({
		framework: 'bootstrap',
        icon: false,
        fields: {
            'CpTour[qty]': {
                validators: {
                    notEmpty: {
                        message: 'The Quantity is required'
                    },
                    numeric: {
                    	message: 'The Quantity must is number',
                    	thousandsSeparator: '',
                        decimalSeparator: '.'
                    }
                }
            },
            'CpTour[who_pay]': {
                validators: {
                    notEmpty: {
                        message: 'The Payer is required'
                    }
                }
            },
            'CpTour[dv_id]': {
                validators: {
                    notEmpty: {
                        message: 'The Service is required'
                    }
                }
            },
            'CpTour[price]': {
                validators: {
                    notEmpty: {
                        message: 'The Price is required'
                    }
                }
            },
            'CpTour[book_of]': {
                validators: {
                    notEmpty: {
                        message: 'The Office book is required'
                    }
                }
            },
            'CpTour[pay_of]': {
                validators: {
                    notEmpty: {
                        message: 'The Office Pay is required'
                    }
                }
            },
        }
	});


	//validate form option
	$('#optionForm').formValidation({
		framework: 'bootstrap',
        icon: false,
        fields: {
            'option-qty': {
                validators: {
                    notEmpty: {
                        message: 'The quantity is required'
                    },
                    numeric: {
                    	message: 'The quantity must is number',
                    	thousandsSeparator: '',
                        decimalSeparator: '.'
                    }
                }
            },
            'option-price': {
                validators: {
                    notEmpty: {
                        message: 'The price is required'
                    }
                }
            },
            'option-currency': {
                validators: {
                    notEmpty: {
                        message: 'The currency is required'
                    }
                }
            },
            'option-note': {
                validators: {
                    notEmpty: {
                        message: 'The note is required'
                    }
                }
            },
        }
	}).on('success.form.fv', function(e) {
        e.preventDefault();

        var form = $(e.target);
        var data_form = {
        	id: form.find('[name="option-cpt_op_id"]').val(),
        	dv_id: form.find('[name="option-dv_id"]').val(),
        	qty: form.find('[name="option-qty"]').val(),
        	price: form.find('[name="option-price"]').val(),
        	currency: form.find('[name="option-currency"]').val(),
        	note: form.find('[name="option-note"]').val()
        };
        option_curr = $('#cptourForm #wrap-options').find('#dv-'+option_id_current);
        $(option_curr).find('.styled').val(JSON.stringify(data_form));
        $(option_curr).find('input').prop('checked', true);
        $('#cpt_detail_option_modal').modal('hide');
    });
    //before hide modal
    $('#cpt_detail_option_modal').on('hide.bs.modal', function(){
    	if (option_curr == null) {
    		$('#dv-'+option_id_current).find('span').removeClass('checked');
    	} else {
    		if ($(option_curr).find('input').val() == '') {
    			$('#dv-'+option_id_current).find('span').removeClass('checked');
    		}
    	}
    	$('#optionForm').formValidation('resetForm', true);
    });
    // set date ngay thanh toan
    $('#cptourForm #cptour-payment_dt').datepicker({
    	firstDay: 1,
	    todayButton: true,
	    clearButton: true,
	    autoClose: true,
	    // range: true,
	    // multipleDatesSeparator: ' - ',
	    language: 'en',
	    dateFormat: 'yyyy/mm/dd',
	    onSelect: function(fd, d, picker) {

	    },
    });
    //set date cho ngay sd
	$('#cptourForm #selme').datepicker({
	    firstDay: 1,
	    todayButton: true,
	    clearButton: true,
	    autoClose: true,
	    // range: true,
	    // multipleDatesSeparator: ' - ',
	    language: 'en',
	    dateFormat: 'dd/mm/yyyy',
	    onSelect: function(fd, d, picker) {
	        if (!d) return;
	        day_use = fd;
	        var status_old = false;
	        if (id_dv == 0) { return;}
	        if (dvc_current != null && dvc_current.venue_id == venue_id) {
				var use_dt = day_use.formatToDate();
				if (new Date(use_dt) >= new Date(dvc_current.valid_from_dt) && new Date(use_dt) <= new Date(dvc_current.valid_until_dt)) {
					var defs = dvc_current.dvd.def.split(';');
					for (var i = 0; i < defs.length; i++) {
						var arr_dt = defs[i].split('-'),
							f_dt = arr_dt[0].formatToDate(),
							s_dt = arr_dt[1].formatToDate();
						if (use_dt >= f_dt && use_dt <= s_dt) {
							jQuery.each(dvc_current.venue.dv, function(index, dv){
								if (dv.id == id_dv) {
									$('#cptourForm #wrap-auto-price').empty();
									var valid_cps = [];
									jQuery.each(dv.cp, function(id_cp, cp){
										if (cp.period == dvc_current.dvd.code && cp.dvc_id == dvc_current.id) {
											valid_cps.push(cp)
											var name_price = (cp.conds != '')?cp.conds: 'Price';
											var html = '<span class="text-muted auto_price"><small>'+name_price+': '+new Number(cp.price).format(2)+' '+cp.currency+'</small></span>'
											$('#cptourForm #wrap-auto-price').append(html);
											status_old = true;
										}
									});
									$('#cptourForm #cptour-price').val(new Number(valid_cps[0].price).format());
									$('#cptourForm #cptour-currency').val(valid_cps[0].currency);
								}
							});
						}
					}
				}
			}
			if (status_old) { return;}
			$.ajax({
				url: "/cptour/list_cp",
				type: "GET",
				data: {dv_id: id_dv, date_selected: day_use},
				dataType: "json",
				success: function(response){
					if (response.err) {
						console.log(response.error); return;
					}
					dvc_current = response.dvc;
					jQuery.each(dvc_current.venue.dv, function(index_dv, dv){
						if (dv['id'] == id_dv) {
							$('#cptourForm #wrap-auto-price').empty();
							var valid_cps = [];
							jQuery.each(dv['cp'], function(index, cp){
								valid_cps.push(cp);
								var name_price = (cp.conds != '')?cp.conds: 'Price';
								var html = '<span class="text-muted auto_price"><small>'+name_price+': '+new Number(cp.price).format(2)+' '+cp.currency+'</small></span>'
								$('#cptourForm #wrap-auto-price').append(html);
							});
							$('#cptourForm #cptour-price').val(new Number(valid_cps[0].price).format());
							$('#cptourForm #cptour-currency').val(valid_cps[0].currency);
							return false;
						}
					});
				},
				error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
			});
	    },
	    onRenderCell: function(date, cellType) {
	        if (cellType == 'day' && date.getDate() > 27) {
	            var currentDate = date.getDate();
	            return {
	                html: '<div title="Lorem ipsum...">' + currentDate + '<span class="dp-note"></span></div>',
	                classes: 'gd1',
	                // disabled: true
	            }
	        }
	    }
	});
	//set data source venue
	$('#cptourForm #cptour-venue_id').select2({
		placeholder: "Search",
		minimumInputLength: 2,
		ajax: {
		    url: "/cptour/search_ncc",
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

	$(document).on('change', '#cptourForm #cptour-venue_id', function(){
		venue_id = $(this).val();
		if (venue_id != '') {
			$.ajax({
				url: "/cptour/list_dv",
				type: "GET",
				data: {id_ncc: venue_id},
				dataType: "json",
				success: function(response){
					console.log(response);
					var data = $.map(response.dv, function (obj) {
						// obj.name = obj.name.allReplace({'{': '(', '}': ')'});
						obj.id = obj.id;
						obj.text = obj.text || obj.name; // replace name with the property used for the text
						return obj;
					});
					$('#cptourForm #cptour-dv_id').html('');
					$('#cptourForm #cptour-dv_id')
									.append($('<option>', {value: '', text : ''}))
									.select2({
										placeholder: "Select a service",
										data: data,
										tags: "true",
										maximumInputLength: 20
									}).on("load", function(e) { 
									     $(this).prop('tabindex',2);
									 }).trigger('load');
					if (response.options) {
						dv_options = response.options;
					}
					$('#cptourForm #wrap-dv').find('.select2-selection--single').focus();
				},
				error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
			});
		}
	});
	$('#cptourForm #add_options').click(function(){
		if (dv_options != null) {
			$('#cptourForm #wrap-options').css('display','inlineBlock');
			$('#cptourForm #wrap-options').toggle();
			$('#cptourForm #wrap-options').empty();
			jQuery.each(dv_options, function(index, option){
				var chk_class = '';
				var chk_val = '';
				if (form_status == 'update') {
					if (option_selected.indexOf(parseInt(option.id)) != -1) {
						chk_class = 'checked';
					}
				}
				var html = '<label class="checkbox-inline"> <div id="dv-'+option.id+'" class="checker" data-dv_op="'+option.id+'"><span class="'+ chk_class +'"><input class="styled" name="chk_option[]" type="checkbox" value=""></span></div> '+option.name+'</label>';
				$('#cptourForm #wrap-options').append(html);
			});
			return false;
		} else {
			
		}
		return false;
	});
	$(document).on('change', '#cptourForm #cptour-dv_id', function(){
		id_dv = $(this).val();
		if (id_dv != '') {
			if (day_use == '') {
				day_use = new Date().ddmmyyyy();
			}
			var status_old = false;
			if (dvc_current != null && dvc_current.venue_id == venue_id) {
				var use_dt = day_use.formatToDate();
				if (new Date(use_dt) >= new Date(dvc_current.valid_from_dt) && new Date(use_dt) <= new Date(dvc_current.valid_until_dt)) {
					var defs = dvc_current.dvd.def.split(';');
					for (var i = 0; i < defs.length; i++) {
						var arr_dt = defs[i].split('-'),
							f_dt = arr_dt[0].formatToDate(),
							s_dt = arr_dt[1].formatToDate();
						if (use_dt >= f_dt && use_dt <= s_dt) {
							jQuery.each(dvc_current.venue.dv, function(index, dv){
								if (dv.id == id_dv) {
									$('#cptourForm #wrap-auto-price').empty();
									var valid_cps = [];
									jQuery.each(dv.cp, function(id_cp, cp){
										if (cp.period == dvc_current.dvd.code && cp.dvc_id == dvc_current.id) {
											valid_cps .push(cp);
											var name_price = (cp.conds != '')?cp.conds: 'Price';
											var html = '<span class="text-muted auto_price"><small>'+name_price+': '+new Number(cp.price).format(2)+' '+cp.currency+'</small></span>'
											$('#cptourForm #wrap-auto-price').append(html);
											status_old = true;
										}
									});
									$('#cptourForm #cptour-price').val(new Number(valid_cps[0].price).format());
									$('#cptourForm #cptour-currency').val(valid_cps[0].currency);
									if (form_status == 'update') {
										if (dv.id == cpt_updated_curr['dv_id']) {
											$('#cptourForm #cptour-price').val(new Number(cpt_updated_curr['price']).format());
											$('#cptourForm #cptour-currency').val(cpt_updated_curr['currency']);
										}
									}
								}
							});
						}
					}
				}
			}
			if (!status_old) {
				$.ajax({
					url: "/cptour/list_cp",
					type: "GET",
					data: {dv_id: id_dv, date_selected: day_use},
					dataType: "json",
					success: function(response){
						if (response.err) {
							console.log(response.error); return;
						}
						dvc_current = response.dvc;
						$('#cptourForm #cptour-qty').focus();
						$('#cptourForm #wrap-auto-price').empty();
						jQuery.each(dvc_current.venue.dv, function(index_dv, dv){
							if (dv['id'] == id_dv) {
								var valid_cps = [];
								jQuery.each(dv['cp'], function(index, cp){
									valid_cps .push(cp);
									var name_price = (cp.conds != '')?cp.conds: 'Price';
									var html = '<span class="text-muted auto_price"><small>'+name_price+': '+new Number(cp.price).format(2)+' '+cp.currency+'</small></span>'
									$('#cptourForm #wrap-auto-price').append(html);
								});
								$('#cptourForm #cptour-price').val(new Number(valid_cps[0].price).format());
								$('#cptourForm #cptour-currency').val(valid_cps[0].currency);
								if (form_status == 'update') {
									if (dv.id == cpt_updated_curr['dv_id']) {
										$('#cptourForm #cptour-price').val(new Number(cpt_updated_curr['price']).format());
										$('#cptourForm #cptour-currency').val(cpt_updated_curr['currency']);
									}
								}
								return false;
							}
						});
					},
					error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
				});
			}
		}
	});
///////////////////////////////////end form nhap nhanh/////////////////////////////

});
/////////////////////////////////format number/////////////////////////////////////
	$(document).on('keydown', '.numberOnly', function(e){

		if(this.selectionStart || this.selectionStart == 0){
			// selectionStart won't work in IE < 9

			var key = e.which;
			var prevDefault = true;

			var thouSep = ",";  // your seperator for thousands
			var deciSep = ".";  // your seperator for decimals
			var deciNumber = 2; // how many numbers after the comma

			var thouReg = new RegExp(thouSep,"g");
			var deciReg = new RegExp(deciSep,"g");

			function spaceCaretPos(val, cPos){
				/// get the right caret position without the spaces

				if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
					cPos = cPos-1;

				if(val.substring(0,cPos).indexOf(thouSep) >= 0){
					cPos = cPos - val.substring(0,cPos).match(thouReg).length;
				}

				return cPos;
			}
			
			function spaceFormat(val, pos){
				/// add spaces for thousands

				if(val.indexOf(deciSep) >= 0){
					var comPos = val.indexOf(deciSep);
					var int = val.substring(0,comPos);
					var dec = val.substring(comPos);
				} else{
					var int = val;
					var dec = "";
				}
				var ret = [val, pos];

				if(int.length > 3){

					var newInt = "";
					var spaceIndex = int.length;

					while(spaceIndex > 3){
						spaceIndex = spaceIndex - 3;
						newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
						if(pos > spaceIndex) pos++;
					}
					ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
				}
				return ret;
			}

			$(this).on('keyup', function(ev){

				if(ev.which == 8){
					// reformat the thousands after backspace keyup

					var value = this.value;
					var caretPos = this.selectionStart;

					caretPos = spaceCaretPos(value, caretPos);
					value = value.replace(thouReg, '');

					var newValues = spaceFormat(value, caretPos);
					this.value = newValues[0];
					this.selectionStart = newValues[1];
					this.selectionEnd   = newValues[1];
				}
			});

			if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
			   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
				prevDefault = false;

			if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
				e.preventDefault();
				
				if(!e.altKey && !e.shiftKey && !e.ctrlKey){

					var value = this.value;
					if((key > 95 && key < 106)||(key > 47 && key < 58) ||
						(deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
						
						var keys = { // reformat the keyCode
							48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
							96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
							110: deciSep, 188: deciSep, 190: deciSep
						};
						
						var caretPos = this.selectionStart;
						var caretEnd = this.selectionEnd;
						
						if(caretPos != caretEnd) // remove selected text
							value = value.substring(0,caretPos) + value.substring(caretEnd);
						
						caretPos = spaceCaretPos(value, caretPos);
						
						value = value.replace(thouReg, '');
						
						var before = value.substring(0,caretPos);
						var after = value.substring(caretPos);
						var newPos = caretPos+1;
						
						if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
							if(before.indexOf(deciSep) >= 0){ newPos--; }
							before = before.replace(deciReg, '');
							after = after.replace(deciReg, '');
						}
						var newValue = before + keys[key] + after;
						
						if(newValue.substring(0,1) == deciSep){
							newValue = "0"+newValue;
							newPos++;
						}
						
						while(newValue.length > 1 && 
							newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
							newValue = newValue.substring(1);
						newPos--;
					}

					if(newValue.indexOf(deciSep) >= 0){
						var newLength = newValue.indexOf(deciSep)+deciNumber+1;
						if(newValue.length > newLength){
							newValue = newValue.substring(0,newLength);
						}
					}

					newValues = spaceFormat(newValue, newPos);

					this.value = newValues[0];
					this.selectionStart = newValues[1];
					this.selectionEnd   = newValues[1];
				}
			}
		}

		$(this).on('blur', function(e){

			if(deciNumber > 0){
				var value = this.value;

				var noDec = "";
				for(var i = 0; i < deciNumber; i++)
					noDec += "0";

				if(value == "0"+deciSep+noDec)
					this.value = ""; //<-- put your default value here
				else
					if(value.length > 0){
						if(value.indexOf(deciSep) >= 0){
							var newLength = value.indexOf(deciSep)+deciNumber+1;
							if(value.length < newLength){
								while(value.length < newLength){ value = value+"0"; }
								this.value = value.substring(0,newLength);
							}
						}
						else this.value = value;// + deciSep + noDec;
					}
				}
			});
		}
	});
///////////////////////////////////////////////////////////////////////////////////

Date.prototype.ddmmyyyy = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return dd+"/"+mm+"/"+yyyy;
};
Date.prototype.yyyymmdd = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return yyyy+"/"+mm+"/"+dd;
};
String.prototype.formatToDate = function() {
	var arr_dt = this.split('/');
	if (arr_dt.length != 3) {
		return false;
	}
	return new Date(arr_dt[2]+'/'+arr_dt[1]+'/'+arr_dt[0]);
}
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
String.prototype.allReplace = function(obj) {
    var retStr = this;
    for (var x in obj) {
        retStr = retStr.replace(new RegExp(x, 'g'), obj[x]);
    }
    return retStr;
};
function tabindexFix() {
    $("input[tabindex], textarea[tabindex]").each(function () {
        $(this).on("keypress", function (e) {
            if (e.keyCode === 13)
            {
                var nextElement = $('[tabindex="' + (this.tabIndex + 1) + '"]');
                if (nextElement.length) {
                    $('[tabindex="' + (this.tabIndex + 1) + '"]').focus();
                    e.preventDefault();
                } else
                    $('[tabindex="1"]').focus();
            }
        });
    });
}
function resetVar() {
	id_dv = 0;
	day_use = '';
	venue_id = 0;
	dvc_current = null;
	dv_options = null;
	option_id_current = 0;
	option_curr = null;
	option_selected = null;
	form_status = 'create';
	//reset elements in form
	$('#cptourForm').find('.select2').css('width', '100%');
	$('#cptourForm').find('#cptour-dv_id').html('');
	$('#cptourForm').find('#group-option span').removeClass('checked');
	$('#cptourForm').find('#group-option .wrap-cpt-group').hide();
	$('#cptourForm').find('#group-option #cpt-group').val('').trigger('change');
	$('#cptourForm').find('#wrap-options').empty().hide();
	$('#cptourForm').find('#wrap-auto-price').empty();
	$('#cptourForm').find('#cptour-payment_dt').val('');
	$('#cptourForm').find('#cptour-id').val('');
	$('#cptourForm').formValidation('resetForm', true);

	return true;
}