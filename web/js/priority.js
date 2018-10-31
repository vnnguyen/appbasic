//********************************global variable*********************//
var i_count = (document.getElementById("sortable-list-second").childElementCount)? document.getElementById("sortable-list-second").childElementCount: 0;
var tour_id = $('.list-day-tour').data('id');//
var customer;
var other;
var our;
var loca;
var str_priority =[];
var obj_edit = new Object();
sortItem();
$.ajax({
	url: "/appbasic/web/priority/list_location",
	type:'GET',
	data: {tour_id: tour_id},
	success:function(response)
	{
			var obj = JSON.parse(response);
			if(Object.keys(obj).length > 0) {
				customer = obj.customer_request;
				loca = obj.location;
				var data_location = $.map(loca, function (object) {
						object.id = object.location;
						object.text = object.text || object.location; // replace name with the property used for the text
						return object;
					});
					if (data_location != null) {
						$('.day-tour:last').find('.location').html('');
						$('.day-tour:last').find('.location').append($('<option>', {
							value: '',
							text : ''
						})).select2({
							data: data_location,
							placeholder: "Select option",
							allowClear: true
						});
						data_location = null;
					}
				var data_cus = $.map(customer, function (object) {
					object.id = object.content;
						object.text = object.text || object.content; // replace name with the property used for the text
						return object;
					});
					// var data_other = $.map(other, function (object) {
					// 	object.id = 1;
					// 	object.text = object.text || object.content; // replace name with the property used for the text
					// 	return object;
					// });
					// var data_our = $.map(our, function (object) {
					// 	object.id = 1;
					// 	object.text = object.text || object.content; // replace name with the property used for the text
					// 	return object;
					// });
					$('.day-tour:last').find('.customer_request').append($('<option>', {
						value: '',
						text : ''
					})).select2({
						// maximumSelectionLength: 3,
						// multiple: true,
						data: data_cus,
						placeholder: "Select option",
						// initSelection : function (element, callback) {
					 //        var datainit = {id: "0", text: "Select option"};
					 //        callback(datainit);
					 //    },
					 allowClear: true
					});
					$('.day-tour:not(:last-child)').find('select').attr('disabled', 'disabled');
				}
				// $.each(obj, function(key,val){
		  //            console.log(key);
		  //            console.log(val); //depending on your data, you might call val.url or whatever you may have
		  //       });
		},
		error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
	});
// $(document).on('change', '.customer_request', function(){
// 	var clicked = $(this);
// 	var str;
// 	var data = clicked.select2('data');
// 	if (data.length > 0) {
// 		str = data[0]['text'];
// 	}
// 	if (str != undefined) {
// 		$.ajax({
// 			url: "/appbasic/web/priority/list_location",
// 			type:'POST',
// 			data: {str_cust: str},
// 			success:function(response)
// 			{
// 				var obj = JSON.parse(response);
// 				if(Object.keys(obj).length > 0) {
// 					location = obj.location;
// 					var data_location = $.map(location, function (object) {console.log(object); return;
// 						object.id = object.location;
// 						object.text = object.text || object.location; // replace name with the property used for the text
// 						return object;
// 					});return;
// 					if (data_location != null) {
// 						$(clicked).closest('.day-tour').find('.location').html('');
// 						$(clicked).closest('.day-tour').find('.location').append($('<option>', {
// 							value: '',
// 							text : ''
// 						})).select2({
// 							data: data_location,
// 							placeholder: "Select option",
// 							allowClear: true
// 						});
// 						data_location = null;
// 					}
// 				}
// 				return true;
// 			},
// 			error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
// 		});
// 	}
// });
$(document).on('change', '.location', function(){
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	var str;
	var data = clicked.select2('data');
	if (data.length > 0) {
		str = data[0]['text'];
	}
	if (str != undefined) {
		$.ajax({
			url: "/appbasic/web/priority/list_other",
			type:'GET',
			data: {str_pos: str},
			success:function(response)
			{
				// console.log(response); return;
				var obj = JSON.parse(response);
				if(Object.keys(obj).length > 0) {
					other = obj.other_company;
					our = obj.our_company;
					var data_other = $.map(other, function (object) {
						object.id = object.content;
							object.text = object.text || object.content; // replace name with the property used for the text
							return object;
						});
					var data_our = $.map(our, function (object) {
						object.id = object.content;
							object.text = object.text || object.content; // replace name with the property used for the text
							return object;
						});
					if (data_other != null) {
						$(current).find('.other_company').html('');
						$(current).find('.other_company').append($('<option>', {
							value: '',
							text : ''
						})).select2({
							data: data_other,
							placeholder: "Select option",
							allowClear: true
						});
						data_other = null;
					}
					if (data_our != null) {
						$(current).find('.our_company').html('');
						$(current).find('.our_company').append($('<option>', {
							value: '',
							text : ''
						})).select2({
							data: data_our,
							placeholder: "Select option",
							allowClear: true
						});
						data_our = null;
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
		});
	}
});
$(document).on('change', '.our_company', function(){
	var current = $(this).closest('.day-tour');
	current.find('.btn-disable').fadeIn();
});
$(document).on('click', '.btn-save-priority', function() {
	/////////////get all priority//////////////////////
	var list = $('.day-tour');
	list.each(function(){
		var cust = $(this).find('.customer_request').val();
		var pos = $(this).find('.location').val();
		var oth = $(this).find('.other_company').val();
		var ourcom = $(this).find('.our_company').val();
		var obj = new Object();
		if (cust != null && pos != null && ourcom != null && oth != null) {
			obj.customer_request = cust;
			obj.location = pos;
			obj.other_company = oth;
			obj.our_company = ourcom;
			str_priority.push(obj);
		}
	});
	$.ajax({
		url: "/appbasic/web/priority/updateajax",
		type:'POST',
		data: {str_priority: ((str_priority.length > 0)? str_priority: ""), tour_id: tour_id},
		success:function(response)
		{
			str_priority = [];
			successAlert('success', 'save success');
		},
		error: function(xhr, ajaxOptions, thrownError) { alert('No response from server'); }
	});
});
$(document).on('click', '.btn-disable', function() {
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	var selects = $(current).find('select');
	var state = true;
	selects.each(function(){
		if ($(this).val() == "" || $(this).text() == "") {
			warningAlert('error', 'some select no option');
			state = false;
		}
	});//select2("enable", false);
	if (state == true) {
		// var data = $('#priority_frm').serializeArray().reduce(function(obj, item) {
		//     obj[item.name] = item.value;
		//     return obj;
		// }, {});
		// delete data._csrf;
		// str_priority.push(data);
		$(selects).select2('enable', false);
		$('.tooltip').hide();
		current.find('.wrap-cancel').remove();//.remove();
		addNewRow();
		clicked.hide();
	}
});
$(document).on('click', '.edit', function(){
	obj_edit = [];
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	var state = "no";
	var btn;
	$('.day-tour').each(function(){
		btn = $(this).find('.btn-cancel');
		if (btn.length > 0) {
			state = "yes";
		}
	});
	if (state == "no") {
		var cust = current.find('.customer_request').val();
		var pos = current.find('.location').val();
		var oth = current.find('.other_company').val();
		var ourcom = current.find('.our_company').val();
		if (cust != null && pos != null && ourcom != null && oth != null) {
			obj_edit.customer_request = cust;
			obj_edit.location = pos;
			obj_edit.other_company = oth;
			obj_edit.our_company = ourcom;
		}
		current.find('select').attr('disabled',false);
		var html_btn_cancel = '<div class="col-md-2  wrap-cancel"> <button type="button" class="btn btn-default btn-cancel"  data-popup="tooltip" title="cancel">cancel</button> </div>';
		if (current.find('.wrap-cancel').length <= 0) {
			current.find('.priority').append(html_btn_cancel);
		}
		// console.log();return;

		var data_location = $.map(loca, function (object) {
			object.id = object.location;
			object.text = object.text || object.location;
			return object;
		});
		current.find('.location').append($('<option>', {
			value: '',
			text : ''
		})).select2({
			data: data_location,
			placeholder: "Select option",
			initSelection: function(element, callback){
				var datainit ={id: pos, text: pos};
				callback(datainit);
			},
			allowClear: true
		});

		var data_cus = $.map(customer, function (object) {
			object.id = object.content;
			object.text = object.text || object.content;
			return object;
		});
		current.find('.customer_request').append($('<option>', {
			value: '',
			text : ''
		})).select2({
			data: data_cus,
			placeholder: "Select option",
			initSelection: function(element, callback){
				var datainit ={id: cust, text: cust};
				callback(datainit);
			},
			allowClear: true
		});
	}
});
$(document).on('click', '.btn-cancel', function(){
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	var selects = $(current).find('select');
	if (obj_edit != null) {
		var cust = current.find('.customer_request');
		cust.select2({
			initSelection: function(element, callback){
				var datainit ={id: obj_edit.customer_request, text: obj_edit.customer_request};
				callback(datainit);
			}
		});
		var pos = current.find('.location');
		pos.select2({
			initSelection: function(element, callback){
				var datainit ={id: obj_edit.location, text: obj_edit.location};
				callback(datainit);
			}
		});
		var oth = current.find('.other_company');
		oth.select2({
			initSelection: function(element, callback){
				var datainit ={id: obj_edit.other_company, text: obj_edit.other_company};
				callback(datainit);
			}
		});
		var ourcom = current.find('.our_company');
		ourcom.select2({
			initSelection: function(element, callback){
				var datainit ={id: obj_edit.our_company, text: obj_edit.our_company};
				callback(datainit);
			}
		});
		$(selects).select2('enable', false);
		current.find('.wrap-cancel').remove();
		current.find('.btn-disable').hide();
	}

});
function addNewRow() {
	var html = '<li class="ui-sortable-handle day-tour"> <span class="badge i-count"></span> <div class="col-md-12 row priority-form "> <div class="priority"> <div class="col-md-3 form-group"> <label>Location</label> <select class="form-control location" name="location"></select> </div> <div class="col-md-3 form-group"> <label>Other company</label> <select class="form-control other_company" name="other_company"></select> </div> <div class="col-md-3 form-group"> <label>Our company</label> <select class="form-control our_company" name="our_company"></select> </div> <div class="col-md-3 form-group"> <label>Customer request</label> <select class="form-control customer_request" name="customer_request" ></select> </div> <div class="col-md-1  wrap-ok"> <button type="button" class="btn btn-success  btn-disable"  data-popup="tooltip" title="Add new"><i class="fa fa-plus"></i></button> </div> </div> </div> <div class="bottom-link text-right"> <ul class="wrap-links"> <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="Move up"> <i class="fa fa-arrow-up"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="Move down"> <i class="fa fa-arrow-down"></i> </a></li> <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="edit"> <i class="fa fa-edit"></i></a></li> <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="delete"> <i class="fa fa-remove"></i></a></li> </ul> </div> </li>';
	if ($('.list-day-tour').append(html)) {
		sortItem();
		var data_cus = $.map(customer, function (object) {
			object.id = object.content;
				object.text = object.text || object.content; // replace name with the property used for the text
				return object;
			});
		var data_location = $.map(loca, function (object) {
			object.id = object.location;
				object.text = object.text || object.location; // replace name with the property used for the text
				return object;
			});
		$(document).find('.location:last').append($('<option>', {
			value: '',
			text : ''
		})).select2({
			data: data_location,
			placeholder: "Select option",
			allowClear: true
		});
		$(document).find('.customer_request:last').append($('<option>', {
			value: '',
			text : ''
		})).select2({
			data: data_cus,
			placeholder: "Select option",
			allowClear: true
		});
	}
}
$(function() {
	// show or hide button of day tour
	$(document).on({
		mouseover: function(){
			$(this).find('.bottom-link').stop().fadeIn();
		},
		mouseout: function(){
			$(this).find('.bottom-link').stop().fadeOut();
		},
	},'.day-tour');
	//sort element day tour
	$( "#sortable-list-second" ).sortable({
		stop: function( event, ui ) {
			sortItem();
		},
	});
	$(document).on({
		mouseenter: function(){
			$(this).css({
				'border': "1px solid #ccc",
				'box-shadow': '0 1px 6px 1px #ccc',
			});
			$(document).on('hover',' .bottom-link ul li a', function(){
				$(this).tooltip();
			});
		},
		mouseleave: function(){
			$(this).css({
				'border': "1px solid #ddd",
				'box-shadow': 'none',
			});
		}
	},'.day-tour');
});//
// this event run when click to move a day tour up
$(document).on("click" , "a.move-up" ,function(e){
	var clicked=$(this);
	var prev = clicked.closest('.day-tour').prev();
	var current = clicked.closest('.day-tour');
	if (current.index() > 0){
		animateMoveUp(current, prev);
	}
});
// this event run when click to move a day tour down
$(document).on("click" , "a.move-down" ,function(e){
	var clicked=$(this);
	var next = clicked.closest('.day-tour').next();
	var current = clicked.closest('.day-tour');
	animateMove(current, next);
});
$(document).on("click", '.delete-item', function(){
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	current.slideUp(function(){
		if (current.remove()) {
			sortItem();
		}
	});
});
//*******************function delete*************//

//*******************function move***************//
function animateMove(current, where){
	var oldOffset = current.position();
	current.insertAfter(where);
	var newOffset = current.position();

	var temp = current.clone().appendTo($( "#sortable-list-second" ));
	temp.find('.tooltip').hide();
	current.find('.tooltip').hide();
	temp.css({
		'position': 'absolute',
		'left': oldOffset.left,
		'top': oldOffset.top,
		'zIndex': 1000
	});

	current.hide();
	temp.animate({
		'top': newOffset.top,
		'left': newOffset.left
	},
	{
		duration:500,
		complete: function() {
			current.show('slow',function(){
				$(this).animate({
					width: "100%"
				},{
					duration: 300,
					start: function(){
						$(this).css({
							'box-shadow': '0 1px 15px 6px #00B2C9',
						});
					},
					complete: function(){
						$(this).css({
							'box-shadow': 'none',
							'transition': 'box-shadow 2s',
						});
					}
				});
			});
			if (temp.remove()) {
				sortItem();
			}
		}
	});
}
function animateMoveUp(current, where){
	var oldOffset = current.position();
	current.insertBefore(where);
	var newOffset = current.position();

	var temp = current.clone().appendTo($( "#sortable-list-second" ));
	temp.find('.tooltip').hide();
	current.find('.tooltip').hide();
	temp.css({
		'position': 'absolute',
		'left': oldOffset.left,
		'top': oldOffset.top,
		'zIndex': 1000,
	});
	current.hide();
	temp.animate({
		'top': newOffset.top,
		'left': newOffset.left
	}, 500, function() {
		current.show('slow',function(){
			$(this).animate({
				width: "100%",
			},{
				duration: 1,
				start: function(){
					$(this).css({
						'box-shadow': '0 1px 15px 6px #00B2C9',
					});
				},
				complete: function(){
					$(this).css({
						'box-shadow': 'none',
						'transition': 'box-shadow 2s',
					});
				}
			});
		});
		if (temp.remove()) {
			sortItem();
		}
	});
}

//on drop element
function successAlert(type, text){
	new PNotify({
		title: 'Notice',
		text: text,
		type: type,
		delay: 2500,
		history: false,
		remove: true,
		buttons: {
			sticker: false
		},
	});
}
function warningAlert(type, text){
	new PNotify({
		title: 'Notice',
		text: text,
		type: type,
		delay: 3500,
		history: false,
		remove: true,
		buttons: {
			sticker: false
		},
	});
}
function sortItem() {
	$('.day-tour:first').find('.move-up').hide();
	$('.day-tour:last').find('.move-down').hide();
	$('.day-tour:not(:first)').find('.move-up').show();
	$('.day-tour:not(:last)').find('.move-down').show();
	var c_i = 0;
	var c = $('.day-tour').find('.i-count');
	c.each(function(){
		c_i++;
		$(this).text(c_i);
	});
	return false;
}