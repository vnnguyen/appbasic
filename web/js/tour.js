// $.ajax({
// 	url: "",
// 	type: "",
// 	data: {},
// 	dataType: "",
// 	success: function(response){},
// 	error: function(xhr, ajaxOptions, thrownError){alert(xhr.responseText);}
// });
//*********************global variable*********************//
var i_count = (document.getElementById("sortable-list-second").childElementCount)? document.getElementById("sortable-list-second").childElementCount: 0;
var first_day = $('#start_date').val(); //start day of tour
var str_id;// string to save id of day tour
var pagination = {};
var clicked_copy;
var dataSource;// to save data from list data
var current_id;// to save id on click a day
var dataSearch;//
var index_b;// to saved position before drag
var index_l;// to save position after drop
var addToTop = false; // status add element to top
var tour_id = $('#tour_id').val(); // save id of main tour
var confirm_delete = "undelete";
var date_selected;
var id_selected;
var li_trans_current;
var content_li;
var note_id;
var li_day_tour;
sortDay();
$(document).ready(function() {
	$('#note-form').formValidation({
		framework: 'bootstrap',
	        icon: false,
	        fields: {
	            content: {
	                validators: {
	                    notEmpty: {
	                        message: 'The content is required'
	                    }
	                }
	            }
	        }
	});
});
/////////////comment tour///////////////////////
	$(document).on('click', '.day-tour', function(){
		li_day_tour = $(this);
		id_selected = $(this).find('#index').val();
	});
	$(document).on('click','.note-content', function(){
		var p = $(this).closest('.note');
		note_id = $(p).data('id');
		var icon = $(p).data('icon');
		var color = $(p).data('color');
		// $('#comment_modal').on('show.bs.modal', function() {
		//     $('#note-form').formValidation('resetForm', true);
		// });
		$('#note-form').find('[name="action"]').val('edit');
		if (icon != '') {
			$('#comment_modal').find('#icon-note').val(icon);
		}
		if (color != '') {
			$('#comment_modal').find('#color-note').val(color);
		}
		$('#comment_modal').find('#message-text').val($(this).text());

		$('#comment_modal').modal("show");
	});
	$(document).on('click','.note-item', function(){
		$('#note-form').find('[name="action"]').val('create');
		$('#comment_modal').modal("show");
	});
	$(document).on({
		mouseenter: function(){
			$(this).find('.remove-note').stop().show(300);
		},
		mouseleave: function(){
			$(this).find('.remove-note').stop().hide(300);
		}
	},'.note');
	$(document).on('click', '.remove-note', function(){
		var p = $(this).closest('.note');
		$(p).slideUp(300, function(e){
			$.ajax({
				url: "/ngaymau/remove_note",
				type: "POST",
				data: {id: $(this).closest('.note').data('id')},
				success: function(response){
					new PNotify({
		    			title: 'Notice',
		    			text: 'Delete completed!',
		    			delay:2500,
		    			buttons: {
		    				closer: false,
		    				sticker: false
		    			},
		    		});
					$(p).remove();
				},
				error: function(xhr, ajaxOptions, thrownError){alert(xhr.responseText);}
			});
		});
	});
	$('#comment_modal').on('show.bs.modal', function() {
		$('#note-form')
			    .formValidation('enableFieldValidators','content', true);

	});
	$('#comment_modal').on('hide.bs.modal', function() {
		$('#note-form')
		    .formValidation('enableFieldValidators','content', false);
		$('#note-form').find('[name="content"]').val('');
		//$('#note-form').formValidation('resetForm', true);
	});
	$('#note-save').on('click', function(){
		if ($('#note-form').find('[name="content"]').val() != '') {
			var id;
			var action = $('#note-form').find('[name="action"]').val();
			var icons = $('#note-form').find('[name="icon"]').val();
			var color = $('#note-form').find('[name="color"]').val();
			var content = $('#note-form').find('[name="content"]').val();
			var div_note = $(li_day_tour).find('.tour_note');
			if (icons == null) {icons = 'none';}
			if (action == 'edit') {
				id = note_id;
			}
			var data = {
				id: id,
				ct_id: tour_id,
				day_id: id_selected,
				action: action,
				icon: icons,
				color: color,
				content: content

				};		//console.log(data);return;
			$.ajax({
				url: "/ngaymau/s_note",
				type: "POST",
				data: {data:data},
				dataType: "json",
				success: function(response){

					if (response.error) {
						console.log(response.error);
						new PNotify({
			    			title: 'Notice',
			    			text: 'Save Unsuccess!',
			    			delay:2500,
			    			buttons: {
			    				closer: false,
			    				sticker: false
			    			},
			    		});
			    		return false;
					} else {
						var icon;
						if (response.icon == 'guide') {
							icon = '<i class="fa fa-user"></i>';
						}
						if (response.icon == 'car') {
							icon = '<i class="fa fa-car"></i>';
						}
						if (response.icon == 'plane') {
							icon = '<i class="fa fa-plane"></i>';
						}
						var html = '<p class="note" data-icon="guide" data-id="' +response.id+ '" data-color="' +response.color+ '" style="color:' +response.color+ '"> ' +icon+ '<span class="note-content">' +response.content+ '</span> <span class="remove-note" style="color: rgb(0, 0, 0) ! important; "><i class="fa fa-remove"></i></span> </p>';
						var p_note = $(div_note).find('.note');
						jQuery.each(p_note, function(i, item){
							if (response.id == $(item).data('id')) {
								$(item).remove();
							}
						});
						$(div_note).append(html);
						$('#comment_modal').modal('hide');
						new PNotify({
			    			title: 'Notice',
			    			text: 'Save success!',
			    			delay:2500,
			    			buttons: {
			    				closer: false,
			    				sticker: false
			    			},
			    		});
					}
				},
				error: function(xhr, ajaxOptions, thrownError){alert(xhr.responseText);}
			});
		}
	});

/////////////translate//////////////////////////
	$(document).on('click', '.translate-save', function(){
		$('#t_form').formValidation({
	        framework: 'bootstrap',
	        icon: false,
	        fields: {
	            title: {
	                validators: {
	                    notEmpty: {
	                        message: 'The title is required'
	                    }
	                }
	            },
	            content: {
	                validators: {
	                    notEmpty: {
	                        message: 'The content is required'
	                    }
	                }
	            }
	        }
	    });
	    var title = $('#current_form').find('[name="title"]').val();
	    var content = $('#current_form').find('[name="content"]').val();
		var date = date_selected;
		if (title != '' && content != '') {
			var data = {
				ct_id: tour_id,
				day_id: id_selected,
				title: title,
				content: content
			};
			$.ajax({
				url: "/ngaymau/translate_data",
				type:'GET',
				data: {data:data},
				dataType: 'json',
				success:function(response)
				{
					if(response != null) {
						$('#list_translate').empty();
						jQuery.each(response, function(index, item){
							var li = '<li class="translate-day-tour" data-id="' +item.day_id+ '"> <div class="translate-title"> <span class="date"></span> <span class="title-text">' +item.title_t+ '</span> <span class="pull-right remove_translate" data-id="' + item.id + '"><i class="fa fa-remove"></i></span></div> <div class="translate-content collapse"> <p class="content-text">' +item.content_t+ '</p> </div> </li>';
							$('#list_translate').append(li);
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
			$('#current_form').hide().remove();
		}
	});
	$(document).on('click', '.title-tour, .translate-title', function(){
		var current = $(this);
		$(this).closest('.day-tour, .translate-day-tour').find('.content-tour, .translate-content').toggle('collapse', function(){

			var li = $(this).closest('.day-tour');
			var content = li.find('.content-tour');
			var id = $(li).find('.title-tour #index').val();
			var lis = $('#list_translate'). find('li');
			var state;
			if ($(content).is(':visible')) {
				state = true;
			}
			if(!$(content).is(':visible')){
				state = false;
			}
			if (state == true) {
				jQuery.each(lis, function(index, item){
					$(item).find('.translate-content').hide();
					if ($(item).data('id') == id) {
						$(item).find('.translate-content').show();
					}
				});
			}
		});
		$('.day-tour').each(function(index, item){
				if ($(item).find('.title-tour #index').val() != current.closest('.day-tour').find('.title-tour #index').val()) {
					$(item).find('.content-tour').slideUp();
				}
			});
	});
	$(document).on('click', '.translate-item', function(){
		var id = 'current_form';
		var a = "#" + id ;
		date_selected = $(this).closest('li.day-tour').find('.date').text();
		id_selected = $(this).closest('li.day-tour').find('#index').val();
		if ($('.translation-wrap').find(a).length > 0) {
			var li_old = $('.translation-wrap').find(a).closest('.translate-day-tour');
			if (li_old.length > 0 && li_old.data('id') != id_selected) {
				liGetContent(li_old);
			}
			$('.translation-wrap').find(a).remove();
		}
		var form = $('.translate-form').clone();
		var data = {
			ct_id: tour_id,
			day_id: id_selected,
		};
		$.ajax({
				url: "/ngaymau/translated",
				type:'GET',
				data: {data:data},
				dataType: 'json',
				success:function(response)
				{
					if(response != null) {
						$(form).find('[name="title"]').val(response.title_t);
						$(form).find('[name="content"]').val(response.content_t);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
		form.attr('id', id).show();//console.log(form.length);return;
		var li;
		var lis = $('#list_translate').find('li');

		jQuery.each(lis, function(index, item){console.log(id_selected);
			if ($(item).data('id') == id_selected) {
				li = $(item);
			}
		});
		if (li != null) {
			if (li.html() != null && $(li).find('form').length <= 0) {
				content_li = li.html();
			}
			$(li).empty();
			 $(li).append(form);
			 $(form).find('[name="title"]').focus();
		} else {
			$('.translation-wrap').append(form);
		}
		//console.log(1);
	});
	$(document).on({
	  focusout: function (e) {
	    console.log(1);
	  },
	  focusin: function (e) {
	    console.log(2);
	  }
	}, '#current_form');
	$(document).on('click', '.translate-cancel', function(){
		var li = $(this).closest('.translate-day-tour');
		liGetContent(li);
		$('.translation-wrap').find('#current_form').remove();
	});
	$(document).on("click" , ".remove_translate" ,function(e){
		var id = $(this).data('id');
		var clicked = $(this);
		li_trans_current = clicked.closest('.translate-day-tour');
		new PNotify({
	    			title: 'Confirm',
	    			text: 'Delete this item ?\n <div class="text-right"><a class="btn btn-success confirm_ok" data-id="' +id+ '">Yes</a> <a class="btn btn-default confirm_cancel">Cancel</a></div>',
	    			delay:2500,
	    			buttons: {
	    				closer: false,
	    				sticker: false
	    			},
	    		});

		return;
	    //<a class="under">undo</a>

	});
	$(document).on('click', '.confirm_ok', function(){
		var id = $(this).data('id');
		var clicked = $(this);
		var current = clicked.closest('.translate-day-tour');
		PNotify.removeAll();
		$(li_trans_current).slideUp(function(){
	    	if ($(this).remove()) {
	    		$.ajax({
					url: "/ngaymau/delete_translate",
					type:'GET',
					data: {id: id, ct_id: tour_id},
					success:function(response)
					{
						console.log(response);
					},
					error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
				});
	    		new PNotify({
	    			title: 'Notice',
	    			text: 'Delete completed!',
	    			delay:2500,
	    			buttons: {
	    				closer: false,
	    				sticker: false
	    			},
	    		});
	    	}
	    });
	});
	$(document).on('click', '.confirm_cancel', function(){
		PNotify.removeAll();
	});

//////////////////bottom Links//////////////////////////////////
	// show or hide button of day tour
	$(document).on({
		mouseover: function(){
            $('.bottom-link').stop().fadeOut();
			$(this).find('.bottom-link').stop().fadeIn();
		},
		mouseout: function(){
			$(this).find('.bottom-link').stop().fadeOut();
		},
	},'.day-tour');
	// tooltip show on hover source day
	tooltip = new PNotify({
		title: "Content",
		text: "",
		hide: false,
		width: "auto",
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
        auto_display: false
    });
	// Remove the notice if the user mouses over it.
	tooltip.get().mouseout(function() {
		tooltip.remove();
	});
	$(document).on({
		mouseover: function(){
			var text = $(this).closest('.record').find('._content').text();
			tooltip.update({
				text: text
			});
			tooltip.open();
		},
		mouseout: function(){
			tooltip.remove();
		},
		mousemove: function(event){
				tooltip.get().css({
					'top': event.clientY -100,
					'left': event.clientX + 12,
					'background': '#fff'
				});
			}
		},"._title" );
	// this function run when insert new daytour from source day
	function addtour(id, title, content, addTop)
	{
		if(title !== '' && content !== '')
		{
			if (addTop) {
				$.ajax({
					url: "/ngaymau/add_new_daytour",
					type:'POST',
					data: {title: title, content: content, tour_id: tour_id,},
					success:function(response)
					{
						if(response == 'error') {
							alert(response);
						}else{
							var html = ' <li class="ui-sortable-handle day-tour"><div class=" title-tour"><span class="badge i-count" >1</span><span class="date"></span><span class="title-text">'+ title +'</span><input type="hidden" id="index" value="'+response+'"></div> <div class="content-tour"> <p class="content-text">'+ content +'</p> </div> <div class="bottom-link text-right"> <ul class="wrap-links"> <li><a class="btn btn-default my-bottom-link add-day" data-popup="tooltip" title="Day after" > <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link add-blank-day" data-popup="tooltip" title="Day blank after"> <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link copy-day" data-popup="tooltip" title="Copy"> <i class="fa fa-copy"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="Move up"> <i class="fa fa-arrow-up"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="Move down"> <i class="fa fa-arrow-down"></i> </a></li> <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="edit"> <i class="fa fa-edit"></i></a></li> <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="delete"> <i class="fa fa-remove"></i> </a></li> <li><a class="btn btn-default my-bottom-link translate-item" data-popup="tooltip" title="Translate">T</a></li> <li><a class="btn btn-default my-bottom-link note-item" data-popup="tooltip" title="Note"><i class="fa fa-comment-o"></i></a></li></ul> </div> </li> <!-- end day-tour -->';
							if($(".list-day-tour").prepend(html)) {
								$('#edit-modal').modal('hide');
								i_count++;
								sortItem();
								sortDay();
								updatePostion();
							}
						}
					},
					error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
				});
			} else {
				var current = $('.day-tour');
				var mem;
				jQuery.each(current, function(){
					if (current_id == $(this).find('#index').val()) {
						mem = $(this);
						return;
					}
				});
				if (mem.length > 0) {
					$.ajax({
						url: "/ngaymau/add_new_daytour",
						type:'POST',
						data: {title: title, content: content, tour_id: tour_id,},
						success:function(response)
						{
							if(response == 'error') {
								alert(response);
							}else{
								var html = ' <li class="ui-sortable-handle day-tour"><div class=" title-tour"><span class="badge i-count" >1</span><span class="date"></span><span class="title-text">'+ title +'</span><input type="hidden" id="index" value="'+response+'"></div> <div class="content-tour"> <p class="content-text">'+ content +'</p> </div> <div class="bottom-link text-right"> <ul class="wrap-links"> <li><a class="btn btn-default my-bottom-link add-day" data-popup="tooltip" title="Day after" > <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link add-blank-day" data-popup="tooltip" title="Day blank after"> <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link copy-day" data-popup="tooltip" title="Copy"> <i class="fa fa-copy"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="Move up"> <i class="fa fa-arrow-up"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="Move down"> <i class="fa fa-arrow-down"></i> </a></li> <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="edit"> <i class="fa fa-edit"></i></a></li> <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="delete"> <i class="fa fa-remove"></i></span> </a></li> </ul> </div> </li> <!-- end day-tour -->';
								if ($(html).insertAfter(mem)) {
									$('#edit-modal').modal("hide");
									i_count = i_count+1;
									sortItem();
									sortDay();
									updatePostion();
									return;
								}
							}
						},
						error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
					});
				}
				$('#edit-modal').on('hide.bs.modal', function(){
					mem.length = 0;
				});
			}
		}
	}
	// event when click to add daytour from source day
	$(document).on('click', '.btnadd', function(e){
		var id = $(this).closest('tr').data('id');
		$('#select-modal').modal('hide');
		jQuery.each(dataSource, function(index, item) {
			if (item['id'] == id) {
				$('#edit-modal').on('show.bs.modal',function(event){
					$(this).find('.modal-title').text('Add new day');
					$(this).find('.modal-body #title-tour').val(item['ngaymau_title']);
					$(this).find('.note-editable').text(item['ngaymau_body']);
				});
				$('#edit-modal').modal("show");
				$('#btnSave').one('click', function(){
					var title = $('.modal-body').find('#title-tour').val();
					var content = $('.modal-body').find('.note-editable').text();
					if (id > 0) {
						addtour(item['id'], title, content, addToTop);
					}
					return false;
				});
				$("#edit-modal").on('hide.bs.modal', function () {
					$(this).removeData('bs.modal');
					id = 0;
				});
				$("#select-modal").on('hide.bs.modal', function () {
					$(this).removeData('bs.modal');
					id = 0;
				});

				// addtour(item['id'], item['ngaymau_title'], item['ngaymau_body']);
				return false;
			}
		});
	});
	//sort element day tour
	$( "#sortable-list-second" ).sortable({
		handle: ".title-tour",
		start: function( event, ui ) {
			index_b = ui.item.find('.i-count').text();
		},
		stop: function( event, ui ) {
			sortItem();
			sortDay();
			var old_index = ui.item.find('#index').val();
			index_l = ui.item.find('.i-count').text();
			if (parseInt(index_b) < parseInt(index_l)) {
				updatePostion();
			}else{
				if (parseInt(index_b) > parseInt(index_l)) {
					updatePostion();
				}
			}
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
	$(document).on({
		mouseenter: function(){
			$(this).tooltip();
		},
		mouseleave: function(){
			$(this).css({
				'border': "1px solid #ddd",
				'box-shadow': 'none',
			});
		}
	},'.day-tour .bottom-link ul li a');

//this event when click to add new day tour on top list from source day
$('#btn-add-days-tour').click(function(){
	addToTop = true;
	var clicked = $(this);
	var list;

	$('#select-modal').on('show.bs.modal', function (event) {
		if (clicked.length > 0) {
			var url ="/ngaymau/get_data";
			$.ajax({
				url: url,
				type:'POST',
				data: {tour_id: tour_id},
				dataType: 'json',
				success:function(response)
				{
					dataSource = response;
					displayPage(1,dataSource);
					showPaging(dataSource);
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
			$.ajax({
				url: "/ngaymau/get_tag",
				type:'POST',
				data: {tour_id: tour_id},
				dataType: 'json',
				success:function(result)
				{
					list = $.map(result, function (obj) {
						obj.id = obj.id;
						obj.text = obj.text || obj.name; // replace name with the property used for the text
						return obj;
					});
					$('#tag_select').select2({
						maximumSelectionLength: 3,
						multiple: true,
						data: list,
						tags: "true",
						placeholder: "Select tags",
						allowClear: true,
						maximumInputLength: 20
					});
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
		}
	})
	$("#select-modal").modal('show');

	$("#select-modal").on('hide.bs.modal', function () {
		$('#tag_select').text('');
		clicked.length = 0;

	});
});
//this event when click to add new blank day tour on top list
$('#btn-add-tour').click(function(){
	var clicked_add = $(this);

	$('#edit-modal').on('show.bs.modal', function (event) {
		$(this).find('.modal-title').text('Add new day');
		$(this).find('.modal-body #title-tour').val('');
		$(this).find('.note-editable').text('');
	})
	$("#edit-modal").modal();
	$('#btnSave').on('click', function(){
		var title = $('.modal-body').find('#title-tour').val();
		var content = $('.modal-body').find('.note-editable').text();
		if(title !== '' && content !== '' && clicked_add.length > 0)
		{//console.log(clicked_add);return;
			$.ajax({
				url: "/ngaymau/add_new_daytour",
				type:'POST',
				data: {title: title, content: content, tour_id: tour_id,},
				success:function(response)
				{
					if(response == 'error') {
						alert(response);
					}else{
						var html = ' <li class="day-tour ui-sortable-handle"><div class="title-tour"><span class="badge i-count" >'+(i_count+1)+'</span><span class="date">20/5/2016</span><span class="title-text">'+title+'</span><input type="hidden" id="index" value="'+response+'"></div> <div class="content-tour"> <p class="content-text">'+content+'</p> </div> <div class="bottom-link text-right"> <ul class="wrap-links"> <li><a class="btn btn-default my-bottom-link add-day" data-popup="tooltip" title="Day after" > <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link add-blank-day" data-popup="tooltip" title="Day blank after"> <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link copy-day" data-popup="tooltip" title="Copy"> <i class="fa fa-copy"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="Move up"> <i class="fa fa-arrow-up"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="Move down"> <i class="fa fa-arrow-down"></i> </a></li> <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="edit"> <i class="fa fa-edit"></i></a></li> <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="delete"> <i class="fa fa-remove"></i></span> </a></li> <li><a class="btn btn-default my-bottom-link translate-item" data-popup="tooltip" title="Translate">T</a></li> <li><a class="btn btn-default my-bottom-link note-item" data-popup="tooltip" title="Note"><i class="fa fa-comment-o"></i></a></li></ul> </div> </li> <!-- end day-tour -->';
						if($(".list-day-tour").prepend(html)) {
							$('#edit-modal').modal('hide');
							clicked_add.length = 0;
							i_count++;
							sortItem();
							sortDay();
							updatePostion();

						}
		        		// updatePostion();
		        	}
		        },
		        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
		    });
		}
		else {
			if (clicked_add.length >0) {
				warningAlert('error', 'some field is empty!');
			}
		}
	});
	$("#edit-modal").on('hide.bs.modal', function () {
		clicked_add.length = 0;
	});

	//
});

// this event run when click to edit name of tour
$(document).on("click" , ".edit-title" ,function(e){
	var current_click = $(this);
	var name = $('.list-title').find('.name-tour').text();
	$('.wrap-content').hide();
	$('#edit-modal').on('show.bs.modal', function (event) {
		$(this).find('.modal-title').text('Edit name of tour');
		$(this).find('#title-tour').val(name);
	})
	$("#edit-modal").modal();
	$("#btnSave").click(function(){
		if ($(this).find('#title-tour').val() != '' && current_click.length > 0) {
			var title = $('#title-tour').val();
			$.ajax({
				url: "/tour/update_ajax",
				type:'POST',
				data: {title: title, id: tour_id},
				success:function(response)
				{
					if(response == 'error'){
						return;
					}
					$('.list-title').find('.name-tour').text(title);
					$('#edit-modal').modal('hide');
					PNotify.removeAll();
					new PNotify({
						title: 'Notice',
						text: 'Save success!',
						delay: 1000,
						history:{history:false},
						buttons: {
							sticker: false
						},
					});
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
		}
		else {
			if (current_click.length > 0) {
				warningAlert('error', 'some field is empty!');
			}
		}
	});
	$("#edit-modal").on('hide.bs.modal', function () {
		current_click.length = 0;
	});
	$("#edit-modal").on('hidden.bs.modal', function () {
		$(this).find('.wrap-content').show();
	});
});
// this event run when click to remove a day tour
$(document).on("click" , "a.delete-item" ,function(e){
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	var re_current = current;
	var index = current.find('.i-count').text();
	var status = true;
    //
    current.slideUp(function(){
    	if ($(this).remove()) {
    		$.ajax({
				url: "/ngaymau/delete_daytour",
				type:'POST',
				data: {id: current.find('#index').val(),},
				success:function(response)
				{
					re_current = current = null;
					i_count--;
		    		sortItem();
		    		sortDay();
		    		updatePostion();
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
    		new PNotify({
    			title: 'Notice',
    			text: 'Delete completed! <a class="under">undo</a>',
    			delay:2500,
    			buttons: {
    				closer: false,
    				sticker: false
    			},
    		});
    		Restore(re_current,index);
    	}
    });
});
function Restore(re_current,index) {
	$('.under').one('click', function(){
		var arr_in = $('.day-tour');
		var succ = false;
		var title = $(re_current).find('.title-text').text();
		var content = $(re_current).find('.content-text').text();
		$.ajax({
			url: "/ngaymau/add_new_daytour",
			type:'POST',
			data: {title: title, content: content, tour_id: tour_id,},
			success:function(response)
			{
				if(response == 'error') {
					alert(response);
				}else{
					$(re_current).find('#index').val(response);
					arr_in.each(function(){
						if ($(this).find('.i-count').text() == index) {
							if(re_current.insertBefore($(this))) {
								i_count++;
								sortItem();
								sortDay();
								updatePostion();
								succ = true;
								PNotify.removeAll();
								return false;
							}
						}
					});
					if (succ == false) {
						if($(".list-day-tour").append(re_current)) {
							i_count++;
							sortItem();
							sortDay();
							updatePostion();
							PNotify.removeAll();
						}
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
		});
	});
}
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
	if (current.index() < i_count -1){
		animateMove(current, next);
	}

});
// this event run when click to copy a day tour
$(document).on("click" , "a.copy-day" ,function(e){
	clicked_copy = $(this);
	var current;
	current = clicked_copy.closest('.day-tour');
	current.find('.tooltip').hide();
	var copy;
	copy = current.clone();
	var mymodal;
	$('#edit-modal').on('show.bs.modal', function (event) {
		var date = copy.find('span.date').text();
		var title = copy.find('span.title-text').text();
		var content = copy.find('p.content-text').text();
		$(this).find('.modal-title').text('Copy day');
		$(this).find('#date-tour').val(date);
		$(this).find('#title-tour').val(title);
		$(this).find('.note-editable').text(content);
	})
	$("#edit-modal").modal();
	$("#btnSave").on('click', function(){
		var date_m = $('.modal-body').find('#date-tour').val();
		var title_m = $('.modal-body').find('#title-tour').val();
		var content_m = $('.modal-body').find('.note-editable').text();
		if(title_m != '' && content_m != '' && current.length > 0){
			copy.find('span.date').text(date_m);
			copy.find('span.title-text').text(title_m);
			copy.find('p.content-text').text(content_m);
			var $id = 0;
			$.ajax({
				url: "/ngaymau/add_new_daytour",
				type:'POST',
				data: {title: title_m, content: content_m, tour_id: tour_id,},
				success:function(response)
				{
					copy.find('#index').val(response);
					if(copy.insertAfter(current)) {
						i_count++;
						$('#edit-modal').modal('hide');
						sortItem();
						sortDay();
						updatePostion();
						new PNotify({
							title: 'Notice',
							text: 'Save success!',
							delay: 500,
							history:{history:false},
							buttons: {
								sticker: false
							},
						});
						return;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
		}
		else {
			if (current.length > 0) {
				warningAlert('error', 'some field is empty!');
			}
		}
	});
	$("#edit-modal").on('hide.bs.modal', function () {
		current.length = 0;
	});
});
// this event run when click to edit a day tour
$(document).on("click" , "a.edit" ,function(e){
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	var title = $(current).find('span.title-text').text();
	var content = $(current).find('p.content-text').text();
	$('#edit-modal').on('show.bs.modal', function (event) {
		$(this).find('.modal-title').text('Update day');
		$(this).find('.modal-body #title-tour').val(title);
		$(this).find('.modal-body .note-editable').text(content);
	})
	$("#edit-modal").modal();
	$('#btnSave').on('click', function(){
		var title_m = $('.modal-body').find('#title-tour').val();
		var content_m = $('.modal-body').find('.note-editable').text();
		if(title_m !== '' && content_m !== '' && current.length > 0){

			$.ajax({
				url: "/ngaymau/edit_daytour",
				type:'POST',
				data: {title: title_m, content: content_m, id: current.find('#index').val(),},
				success:function(response)
				{
					$(current).find('span.title-text').text(title_m);
					$(current).find('p.content-text').text(content_m);
					$('#edit-modal').modal('hide');
					new PNotify({
						title: 'Notice',
						text: 'Save success!',
						delay: 500,
						history:{history:false},
						buttons: {
							sticker: false
						},
					});
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
		}
		else {
			if (current.length > 0) {
				warningAlert('error', 'some field is empty!');
			}
		}
	});
	$("#edit-modal").on('hidden.bs.modal', function () {
		current.length = 0;
	});
});
// this event run when click to add blank a day tour after another day tour
$(document).on("click" , "a.add-blank-day" ,function(e){
	clicked_add = $(this);
	var mymodal;
	var current = clicked_add.closest('.day-tour');
	var index = clicked_add.closest('.day-tour').find('#index').val();
	$('#edit-modal').on('show.bs.modal', function (event) {
		$(this).find('.modal-title').text('Add new day');
		$(this).find('.modal-body #title-tour').val('');
		$(this).find('.note-editable').text('');
	})
	$("#edit-modal").modal();
	$('#btnSave').on('click', function(){
		var title = $('.modal-body').find('#title-tour').val();
		var content = $('.modal-body').find('.note-editable').text();
		if(title !== '' && content !== '')
		{
			if (current.length > 0) {
				$.ajax({
					url: "/ngaymau/add_new_daytour",
					type:'POST',
					data: {title: title, content: content, tour_id: tour_id,},
					success:function(response)
					{
						if(response == 'error') {
							alert(response);
						}else{
							var html = ' <li class="ui-sortable-handle day-tour"><div class=" title-tour"><span class="badge i-count" >1</span><span class="date"></span><span class="title-text">'+ title +'</span><input type="hidden" id="index" value="'+response+'"></div> <div class="content-tour"> <p class="content-text">'+ content +'</p> </div> <div class="bottom-link text-right"> <ul class="wrap-links"> <li><a class="btn btn-default my-bottom-link add-day" data-popup="tooltip" title="Day after" > <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link add-blank-day" data-popup="tooltip" title="Day blank after"> <i class="fa fa-plus"></i> </a></li> <li><a class="btn btn-default my-bottom-link copy-day" data-popup="tooltip" title="Copy"> <i class="fa fa-copy"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-up" data-popup="tooltip" title="Move up"> <i class="fa fa-arrow-up"></i> </a></li> <li><a class="btn btn-default my-bottom-link move-down" data-popup="tooltip" title="Move down"> <i class="fa fa-arrow-down"></i> </a></li> <li><a class="btn btn-default my-bottom-link edit" data-popup="tooltip" title="edit"> <i class="fa fa-edit"></i></a></li> <li><a class="btn btn-default my-bottom-link delete-item" data-popup="tooltip" title="delete"> <i class="fa fa-remove"></i></span> </a></li> <li><a class="btn btn-default my-bottom-link translate-item" data-popup="tooltip" title="Translate">T</a></li> <li><a class="btn btn-default my-bottom-link note-item" data-popup="tooltip" title="Note"><i class="fa fa-comment-o"></i></a></li></ul> </div> </li> <!-- end day-tour -->';
							if ($(html).insertAfter(current)) {
								i_count = i_count+1;
								sortItem();
								sortDay();
								$('#edit-modal').modal('hide');
								updatePostion();
								return;
							}
						}
					},
					error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
				});
			}

		}
		else {
			if (current.length > 0) {warningAlert('error', 'some field is empty!');}
		}
	});
	$("#edit-modal").on('hide.bs.modal', function () {
		current.length = 0;
	});
});
//****this event run when click to add a source day tour after another day tour*****//
$(document).on("click", "a.add-day", function(e){
	addToTop = false;
	var clicked = $(this);
	var current = clicked.closest('.day-tour');
	current_id = $(current).find('#index').val();

	$('#select-modal').on('show.bs.modal', function (event) {
		if (current.length > 0) {
			var url ="/ngaymau/get_data";
			$.ajax({
				url: url,
				type:'POST',
				data: {tour_id: tour_id},
				dataType: 'json',
				success:function(response)
				{
					dataSource = response;
					displayPage(1,dataSource);
					showPaging(dataSource);
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
			$.ajax({
				url: "/ngaymau/get_tag",
				type:'POST',
				data: {tour_id: tour_id},
				dataType: 'json',
				success:function(result)
				{
					console.log(result)
					var data = $.map(result, function (obj) {
						obj.id = obj.id;
						  obj.text = obj.text || obj.name; // replace name with the property used for the text
						  return obj;
						});

					$('#tag_select').select2({
						maximumSelectionLength: 3,
						multiple: true,
						data: data,
						tags: "true",
						placeholder: "Select tags",
						allowClear: true,
						maximumInputLength: 20

					});
				},
				error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
			});
		}
	})
	$("#select-modal").modal('show');
	$("#select-modal").on('hide.bs.modal', function () {
		$('#tag_select').text('');
		current.length = 0;

	});
});
//***************this event run when search tags of source day tour****//
$('#tag_select').on('change', function(){
	var str = "";
	$( "#tag_select option:selected" ).each(function() {
		str += $( this ).text() + ", ";
	});
	$.ajax({
		url: "/ngaymau/search_tag",
		type:'POST',
		data: {name: str},
		dataType: 'json',
		success:function(response)
		{
			// console.log(response); return;
			$('table').find('#grid_source').empty();
			dataSource = response;
			displayPage(1,dataSource);
			showPaging(dataSource);
		},
		error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
	});
	$("#select-modal").on('hide.bs.modal', function () {
		$("#select-modal").removeData();

	});
});
//***************this event run when search title of source day tour****//
$('#txt-search').keyup( function(){
	$.ajax({
		url: "/ngaymau/search",
		type:'POST',
		data: {title: $('#txt-search').val()},
		dataType: 'json',
		success:function(response)
		{
			$('table').find('#grid_source').empty();
			dataSource = response;
			displayPage(1,dataSource);
			showPaging(dataSource);
		},
		error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
	});
});
//*******************pagination***************//
$(document).on("click", "a.page-link", function(e){
	var num = $("#pagingControls").pagination('getCurrentPage');
	displayPage(num,dataSource);
});
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
				sortDay();
				updatePostion();
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
	}, 400, function() {
		current.show('slow',function(){
			$(this).animate({
				width: "100%",
			},{
				duration: 1000,
				start: function(){
					$(this).css({
						'box-shadow': '0 1px 15px 6px green',
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
			sortDay();
			updatePostion();
		}
	});
}
//*********function sort************************//
function sortItem() {
	var c_i = 0;
	var c = $('.day-tour').find('.i-count');
	c.each(function(){
		c_i++;
		$(this).text(c_i);
	});
	return false;
}

function actionAjax(url,data)
{
	$.ajax({
		method: "POST",
		url: url,
		data: data,
	})
	.done(function( msg ) {
		url = null;
		alert(msg);
	})
	.fail(function(jqXHR, ajaxOptions, thrownError)
	{
		alert('No response from server');
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
function sortDay() {
	var c_i = 0;
	$('.day-tour:first').find('.move-up').hide();
	$('.day-tour:last').find('.move-down').hide();
	$('.day-tour:not(:first)').find('.move-up').show();
	$('.day-tour:not(:last)').find('.move-down').show();
	if(first_day != ''){
		var date = first_day;
		var arr = date.split("/");
		var strDate = arr[2] + '/' +arr[1]+ '/' + arr[0];
		var firstDay = new Date(strDate);

        // console.log(new_firstDay);
		c = $("li.day-tour").find('.date');
		c.each(function(){
            var new_firstDay = new Date(firstDay);
			new_firstDay.setYear(firstDay.getFullYear());
			new_firstDay.setMonth(firstDay.getMonth());
			new_firstDay.setDate(firstDay.getDate()+c_i);
			$(this).text(new_firstDay.getDate() + '/' + ( new_firstDay.getMonth() + 1 ) + '/' + new_firstDay.getFullYear());
			c_i++;
		});
		return false;
	}
}
function updatePostion(){
	var items = $('.day-tour');
	str_id ="";
	if (items.length > 0) {
		items.each(function(){
			str_id += $(this).find('#index').val()+',';
		});
	}
	$.ajax({
		url: "/tour/update_position",
		type:'POST',
		data: {id: tour_id, days_id: str_id},
		success:function(response)
		{
			return true;
		},
		error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
	});
	return false;
}
Date.prototype.yyyymmdd = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return yyyy+"-"+mm+"-"+dd;
};
Date.prototype.ddmmyyyy = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return dd+"-"+mm+"-"+yyyy;
};
function liGetContent(li) {
	li.html(content_li);
}
pagination.pager = function(){
	this.perPage = 10;
	this.currentPage = 1;
	this.pagingControlsContainer = '#pagingControls';
	this.pagingContainerPath = '#grid_source';
	this.numPages = function() {
		var numPages =0;
		if (this.data !=null && this.perPage != null) {
			numPages = Math.ceil(this.data.length /this.perPage);
		}
		return numPages;
	}
	this.showPage = function(page) {
		this.currentPage = page;
		var html = '';
		var start =(page-1) * this.perPage;
		var arr=[];
		for (var i = start; i < start+this.perPage; i++) {
			if (this.data[i] != undefined) {
				arr.push(this.data[i]);
			}
		}
		for (var i=0; i<arr.length; i++){
			html +="<tr class='record' data-id ='"+arr[i]['id']+"'><td><p class='_title'>"+arr[i]['ngaymau_title']+"</p></td> <td class='td-content'><p class='_content'>"+arr[i]['ngaymau_body']+"</p></td> <td><button type='button' class =\"btn btn-primary pull-right btnadd \">Add</button></td></tr>";
		}
		$(this.pagingContainerPath).html(html);
		// renderControls(this.pagingControlsContainer, this.currentPage, this.numPages());
	}
	var renderControls = function(container, currentPage, numPages) {
		var pagingControls = 'page: <ul class="pagination">';
		for (var i = 1; i <= numPages; i++) {
			if (i != currentPage) {
				pagingControls += '<li><a class ="link-num" href="#" onclick="showPage(' + i + '); return false;">' + i + '</a></li>';
			}
			else {
				pagingControls += '<li><a class="active">' + i + '</a></li>';
			}
		}
		pagingControls += '</ul>';
		$(container).html(pagingControls);
	}

}
function showPaging(data,perPage =10)
{
	$('#pagingControls').empty();
	$(function() {
		$('#pagingControls').pagination({
			items: data.length,
			itemsOnPage: perPage,
			cssStyle: 'light-theme'
		});
	});
}
function displayPage(i, data)
{
	var pager = new pagination.pager();
    pager.perPage = 10; // set amount elements per page
    pager.pagingContainer = $('#grid_source'); // set of main container
    pager.data = data; // set of required containers
    pager.showPage(i);
}