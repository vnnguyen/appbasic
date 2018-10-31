
$(document).ready(function() {
	var current_id;
	var data_cal = [];
	var confirmed = '<a class="btn btn-primary cicle"><i class="fa fa-check"></i></a>';
	var canceled = '<a class="btn btn-default cicle"><i class="fa fa-thumbs-o-down"></i></a>';
	var draft = '<a class="btn btn-default cicle"><i class="fa fa-exclamation"></i></a>';
	var commited = '<a class="btn btn-success cicle"><i class="fa fa-spinner"></i></a>';
	var btn_group = '<div class="wrap_btn">'
		+'<a class="btn btn-success wrap_confirm"><i class="fa fa-thumbs-o-up"></i></a>'
		+'<a class="btn btn-primary wrap_cancel"><i class="fa fa-thumbs-o-down"></i></a>'
		+'<a class="btn btn-info wrap_edit"><i class="fa fa-edit"></i></a>'
		+'<a class="btn btn-default wrap_close"><i class="fa fa-remove"></i></a>'
		+'</div>';
	var position;
// 	$('[name="time_to_meet"]').datetimepicker({
//     timeFormat: ''
// });
	fillData(position);
	$(document).on('change', '#filter [name="position"]', function(){
		position = $('#filter [name="position"]').val();
		fillData(position);
	});
	$(document).on('click','.fc-corner-left, .fc-list-heading-main, .fc-corner-right, .fc-left .fc-today-button, .fc-listDay-button, .fc-listWeek-button, .fc-month-button', function(){
		setIcons();
	});
	$(document).on('click','.fc-left', function(){
		setIcons();
	});
	$(document).on('change', '#filter [name="time_to_meet"]', function(){
		// console.log();
		$('#calendar').fullCalendar( 'changeView', 'listDay' )
		$('#calendar').fullCalendar('gotoDate', new Date($(this).val()).yyyymmdd());
	});
	$(document).on({
		mouseover: function(){
			if ($(this).find('td:last .wrap_btn').length < 1) {
				$(this).find('td:last').prepend(btn_group).css({position: 'relative'});
				$(this).find('.wrap_btn').css({
					position: 'absolute',
					top: '0',
					right: '15px',
				});
				//console.log($(this).hasClass('confirmed'));
				if ($(this).hasClass('confirmed')) {
					$(this).find('.wrap_confirm').hide();
					$(this).find('.wrap_cancel').show();
				}
				if ($(this).hasClass('canceled')) {
					$(this).find('.wrap_confirm').hide();
					$(this).find('.wrap_cancel').hide();
				}
				if ($(this).hasClass('draft')) {
					$(this).find('.wrap_confirm').hide();
				}
			}
		},
		mouseleave: function(){
			$(this).find('.wrap_btn').remove();
			// /$(this).find('.bottom-link').stop().fadeOut();
		},
	},'.fc-list-item');//$('#calendar').find('.fc-list-item td:last').append(btn_delete).css({

		//});
	$(document).on('click', '.wrap_edit', function(){
		$(this).attr('href', "/appbasic/web/reserv/update/"+current_id);
	});
	$(document).on('click', '.wrap_close', function(){
		$(this).attr('href', "/appbasic/web/reserv/delete/"+current_id);

	});
	$(document).on('click', '.wrap_confirm, .wrap_cancel', function(){
		//$(this).attr('href', "/appbasic/web/reserv/update_status/"+current_id);
		var parrent = $(this).closest('.fc-list-item');
	    $(this).hide();
		$.ajax({
	        url: "/appbasic/web/reserv/update_status/"+current_id,
	        type:'POST',
	        dataType: 'json',
	        success:function(response){
	        	parrent.removeClass('confirmed canceled commited draft');
	        	parrent.addClass(response.status);
				setIcons();
	        },
	        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
    	});
	});
	Date.prototype.yyyymmddhhmmss = function() {
	   var yyyy = this.getFullYear();
	   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	   var hh = this.getHours() < 10 ? "0" + this.getHours() : this.getHours();
	   var min = this.getMinutes() < 10 ? "0" + this.getMinutes() : this.getMinutes();
	   var ss = this.getSeconds() < 10 ? "0" + this.getSeconds() : this.getSeconds();
	   return yyyy+"-"+mm+"-"+dd+" "+hh+":"+min+":"+ss;
	  };
	  Date.prototype.yyyymmdd = function() {
	   var yyyy = this.getFullYear();
	   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	   return yyyy+"-"+mm+"-"+dd;
	  };
	 function fillData(position = ''){
	 	//console.log(position);
		$.ajax({
	        url: "/appbasic/web/reserv/data_cal",
	        type:'GET',
	        dataType: 'json',
	        data: {position: position},
	        success:function(response){
	        	$('#calendar').fullCalendar( 'removeEvents');
	        	data_cal = [];
	        	var data = $.map(response, function (obj) {
	        		var s = {};
					s.id = obj.id ;
					s.title = obj.title = 'B'+obj.pos_id + ' - ' + obj.created_by + ' - [' + obj.content + ']';
					s.start = obj.start = obj.book_dt;
					var date_start = new Date(obj.book_dt);
					date_start.setMinutes(date_start.getMinutes()+parseInt(obj.mins));
					s.end = obj.end = date_start.yyyymmddhhmmss();
					obj.className ="";
					if (obj.pos_id) {
						s.className = obj.className = 'pos_meet_'+obj.pos_id + ' ';
						obj.editable = false;
						obj.startEditable = true;
					}
					if (obj.status == 'confirmed') {
						obj.className += 'confirmed' + ' ';
					}
					if (obj.status == 'commited') {
						obj.className += 'commited' + ' ';
					}
					if (obj.status == 'draft') {
						obj.className += 'draft' + ' ';
					}
					if (obj.status == 'canceled') {
						obj.className += 'canceled' + ' ';
					}
					data_cal.push(obj);
					return obj;
				});
	            $('#calendar').fullCalendar( 'addEventSource', data_cal);
	        	$('#calendar').fullCalendar({
	        		customButtons: {
				        addNew: {
				            text: 'Add new +',
				            click: function() {
				            	var url = window.location.href;
				            	var segs = url.split('/');
				            	if (segs[segs.length - 1] != 'reserv') {
				            		segs[segs.length - 1] = 'create';
				            		url = segs.join('/');
				            	}
				            	else
				            	{
				            		url += '/create';
				            	}
				                window.location.href = url;
				            }
				        }
				    },
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'listDay,listWeek,month,addNew'
					},

					// customize the button names,
					// otherwise they'd all just say "list"
					views: {
						listDay: { buttonText: 'list day' },
						listWeek: { buttonText: 'list week' }
					},

					defaultView: 'listWeek',
					defaultDate: new Date(),
					navLinks: true, // can click day/week names to navigate views
					editable: true,
					eventLimit: true, // allow "more" link when too many events
					events: data_cal,
					eventMouseover: function(calEvent, jsEvent, view) {
						current_id = calEvent.id;
					},
					eventClick: function(calEvent, jsEvent, view) {}
				});
				var i = 0;
				$('#calendar').find('.fc-list-item').each(function(){
					$(this).attr('data-id',data_cal[i].id);
					i++;
				});
				setIcons();
	        },
	        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
    	});
	}

	
	function setIcons(){
		var i = 0;
		
		var confirm_class = $('#calendar').find('.confirmed');
		var commit_class = $('#calendar').find('.commited');
		var cancel_class = $('#calendar').find('.canceled');
		var draft_class = $('#calendar').find('.draft');
		jQuery.each(confirm_class, function(index, c_c){
			var icon = $(this).find('td:eq(1)');
			icon.empty();
			icon.html(confirmed +' '+ 'OK');
		});
		jQuery.each(commit_class, function(index, c_c){
			var icon = $(this).find('td:eq(1)');
			icon.empty();
			icon.html(commited +' '+ 'Waiting');
		});
		jQuery.each(cancel_class, function(index, c_c){
			var icon = $(this).find('td:eq(1)');
			icon.empty();
			icon.html(canceled +' '+ 'Canceled');
		});
		jQuery.each(draft_class, function(index, c_c){
			var icon = $(this).find('td:eq(1)');
			icon.empty();
			icon.html(draft +' '+ 'draft');
		});
		$('#calendar').find('.fc-list-item').each(function(){
			$(this).attr('data-id',data_cal[i].id);
			i++;
		});
	}
});