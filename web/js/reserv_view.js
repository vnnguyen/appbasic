var data_cal = [];
var source = [];
// tooltip show on hover source day
tooltip = new PNotify({
	title: "Informations",
	text: "",
	addclass: "stack-custom",
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
$(document).ready(function() {
	$.ajax({
        url: "/appbasic/web/reserv/data_cal",
        type:'GET',
        dataType: 'json',
        success:function(response)
        {
            var data = $.map(response, function (obj) {
            		var s = {};
					s.id = obj.id = obj.id;
					s.title = obj.title = obj.content;
					s.start = obj.start = obj.book_dt;
					var date_start = new Date(obj.book_dt);
					date_start.setMinutes(date_start.getMinutes()+parseInt(obj.mins));
					s.end = obj.end = date_start.yyyymmddhhmmss();
					
					if (obj.pos_id) {
						s.className = obj.className = ['pos_meet_'+obj.pos_id];
						obj.editable = false;
						obj.startEditable = true;
					}
					if (obj.status == 'commited') {
						obj.className.push('commited');
					}
					if (obj.status == 'draft') {
						obj.className.push('draft');
					}
					if (obj.status == 'canceled') {
						obj.className.push('canceled');
					}
					data_cal.push(obj);
					console.log(obj );
					return obj;
				});
            source = data;



             $('#calendar').fullCalendar({
				customButtons: {
			        myCustomButton: {
			            text: 'custom!',
			            click: function() {
			                alert('clicked the custom button!');
			            }
			        }
			    },
			    // header: {
			    //     left: 'prev,next today myCustomButton',
			    //     center: 'title',
			    //     right: 'month,agendaWeek,agendaDay'
			    // }
			    // resourceAreaWidth: 200,
			    header: {
					left: 'prev,next today',
					center: 'title',
					right: 'listDay,agendaWeek,month'//'month,basicWeek,listDay'//'listDay,listWeek,month'
				},
				
				// customButtons: {
				// 	promptResource: {
				// 		text: '+ room',
				// 		click: function() {
				// 			var title = prompt('Room name');
				// 			if (title) {
				// 				$('#calendar').fullCalendar(
				// 					'addResource',
				// 					{ title: title },
				// 					true // scroll to the new resource?
				// 				);
				// 			}
				// 		}
				// 	}
				// },
				 eventClick: function(calEvent, jsEvent, view) {

			        // alert('Event: ' + calEvent.title);
			        // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
			        // alert('View: ' + view.name);

			        // // change the border color just for fun
			        // $(this).css('border-color', 'red');

			    },
			    eventMouseover: function(calEvent, jsEvent, view) {
			    	if ($(this).find('.remove').length < 1) {
			    		var html ='<span class="remove">x</span>'
			    		$(this).append(html);
			    	}
			    	
			    	var text ="";
			    	 text += "Position: " + calEvent.pos_id + '<br />'
			    		  + "Time: " + calEvent.start._i + ' - ' + calEvent.end._i + '<br />'
			    		  + "Number of people: " + calEvent.num_people + '<br />'
			    		  + "Content: " + calEvent.content + '<br />'
			    		  + "Note: " + calEvent.note + '<br />'
			    		  + "Status: " + calEvent.status + '<br />';
					tooltip.update({
						text: text
					});

					tooltip.get().css({
						'top': jsEvent.clientY + 12,
						'left': jsEvent.clientX + 12
					});
					tooltip.open();
			    	
			        // alert('Event: ' + calEvent.id);
			        // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
			        // alert('View: ' + view.name);

			        // // change the border color just for fun
			        // $(this).css('border-color', 'red');

			    },
			    eventMouseout: function(calEvent, jsEvent, view) {
			    	tooltip.remove();
			    	if ($(this).find('.remove').length > 0) {
			    		$(this).find('.remove').remove();
			    	}
			        //alert('Event: ' + calEvent.title);
			        // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
			        // alert('View: ' + view.name);

			        // // change the border color just for fun
			        // $(this).css('border-color', 'red');

			    },
				displayEventTime: true,
				defaultView: 'agendaWeek',
				// views: {
				// 	agendaWeek: { buttonText: 'agenda' },
				// 	basicWeek: { buttonText: 'basic' }
				// },
				defaultDate: new Date(),
				navLinks: true, // can click day/week names to navigate views
				editable: true,
				eventLimit: true, // allow "more" link when too many events
				hiddenDays: [ 0 ],
				resourceAreaWidth: '100',
				resourceColumns: [
					{
						labelText: 'Occupancy',
						field: 'occupancy'
					}
				],
				resources: [
					{ id: 'p', title: 'Auditorium P' },
					{ id: 'q', title: 'Auditorium Q' },
					{ id: 'r', title: 'Auditorium R'},
					{ id: 's', title: 'Auditorium S' },
					{ id: 't', title: 'Auditorium T'},
					{ id: 'u', title: 'Auditorium U'},
					{ id: 'v', title: 'Auditorium V'},
					{ id: 'w', title: 'Auditorium W' },
					{ id: 'x', title: 'Auditorium X' },
					{ id: 'y', title: 'Auditorium Y' },
					{ id: 'z', title: 'Auditorium Z'}
				],
					businessHours: [ // specify an array instead
					    {
					        dow: [ 1, 2, 3, 4, 5], // Monday, Tuesday, Wednesday
					        start: '08:00', // 8am
					        end: '17:30' // 5:30pm
					    },
					    {
					        dow: [ 6], // Monday, Tuesday, Wednesday
					        start: '08:00', // 8am
					        end: '12:00' // 5:30pm
					    }
					],
					events: data_cal

				});
   /*        $(function() { // dom ready

			// 	var todayDate = moment().startOf('day');
			// 	var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
			// 	var TODAY = todayDate.format('YYYY-MM-DD');
			// 	var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

			// 	$('#calendar').fullCalendar({
			// 		resourceAreaWidth: 230,
			// 		editable: true,
			// 		aspectRatio: 1.5,
			// 		scrollTime: '00:00',
			// 		header: {
			// 			left: 'promptResource today prev,next',
			// 			center: 'title',
			// 			right: 'timelineDay,timelineThreeDays,agendaWeek,month'
			// 		},
			// 		customButtons: {
			// 			promptResource: {
			// 				text: '+ room',
			// 				click: function() {
			// 					var title = prompt('Room name');
			// 					if (title) {
			// 						$('#calendar').fullCalendar(
			// 							'addResource',
			// 							{ title: title },
			// 							true // scroll to the new resource?
			// 						);
			// 					}
			// 				}
			// 			}
			// 		},
			// 		defaultView: 'timelineDay',
			// 		views: {
			// 			timelineThreeDays: {
			// 				type: 'timeline',
			// 				duration: { days: 3 }
			// 			}
			// 		},
			// 		resourceLabelText: 'Rooms',
			// 		resources: [
			// 			{ id: 'a', title: 'Auditorium A' },
			// 			{ id: 'b', title: 'Auditorium B', eventColor: 'green' },
			// 			{ id: 'c', title: 'Auditorium C', eventColor: 'orange' },
			// 			{ id: 'd', title: 'Auditorium D', children: [
			// 				{ id: 'd1', title: 'Room D1' },
			// 				{ id: 'd2', title: 'Room D2' }
			// 			] },
			// 			{ id: 'e', title: 'Auditorium E' },
			// 			{ id: 'f', title: 'Auditorium F', eventColor: 'red' },
			// 			{ id: 'g', title: 'Auditorium G' },
			// 			{ id: 'h', title: 'Auditorium H' },
			// 			{ id: 'i', title: 'Auditorium I' },
			// 			{ id: 'j', title: 'Auditorium J' },
			// 			{ id: 'k', title: 'Auditorium K' },
			// 			{ id: 'l', title: 'Auditorium L' },
			// 			{ id: 'm', title: 'Auditorium M' },
			// 			{ id: 'n', title: 'Auditorium N' },
			// 			{ id: 'o', title: 'Auditorium O' },
			// 			{ id: 'p', title: 'Auditorium P' },
			// 			{ id: 'q', title: 'Auditorium Q' },
			// 			{ id: 'r', title: 'Auditorium R' },
			// 			{ id: 's', title: 'Auditorium S' },
			// 			{ id: 't', title: 'Auditorium T' },
			// 			{ id: 'u', title: 'Auditorium U' },
			// 			{ id: 'v', title: 'Auditorium V' },
			// 			{ id: 'w', title: 'Auditorium W' },
			// 			{ id: 'x', title: 'Auditorium X' },
			// 			{ id: 'y', title: 'Auditorium Y' },
			// 			{ id: 'z', title: 'Auditorium Z' }
			// 		],
			// 		events: [
			// 			{ id: '1', resourceId: 'b', start: TODAY + 'T02:00:00', end: TODAY + 'T07:00:00', title: 'event 1' },
			// 			{ id: '2', resourceId: 'c', start: TODAY + 'T05:00:00', end: TODAY + 'T22:00:00', title: 'event 2' },
			// 			{ id: '3', resourceId: 'd', start: YESTERDAY, end: TOMORROW, title: 'event 3' },
			// 			{ id: '4', resourceId: 'e', start: TODAY + 'T03:00:00', end: TODAY + 'T08:00:00', title: 'event 4' },
			// 			{ id: '5', resourceId: 'f', start: TODAY + 'T00:30:00', end: TODAY + 'T02:30:00', title: 'event 5' }
			// 		]
			// 	});*/

			// });

			// readjust sizing after font load
			// $(window).on('load', function() {
			// 	$('#calendar').fullCalendar('render');
			// });
			$('.fc-event-container').find('.pos_meet_1').prepend('<div class="turn_right"><span class="badge badge-default">B1</span></div>');
			$('.fc-event-container').find('.pos_meet_2').prepend('<div class="turn_right"><span class="badge badge-default">B2</span></div>');

        },
        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
    });
$(document).on({
		mouseover: function(){
			$(this).find('.remove').css({
				position: 'absoluted',
				top: 0,
				right: 0
			});
		}
	},'.fc-event');
Date.prototype.yyyymmddhhmmss = function() {
   var yyyy = this.getFullYear();
   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
   var hh = this.getHours() < 10 ? "0" + this.getHours() : this.getHours();
   var min = this.getMinutes() < 10 ? "0" + this.getMinutes() : this.getMinutes();
   var ss = this.getSeconds() < 10 ? "0" + this.getSeconds() : this.getSeconds();
   return yyyy+"-"+mm+"-"+dd+" "+hh+":"+min+":"+ss;
  };
	

});

