$(document).on('click', '.ltt-code, .span-status', function(){
	var ltt = $(this).closest('tr'),
		ltt_id = ltt.data('ltt-id');
	$.ajax({
	        method: "GET",
	        url: "/appbasic/web/cpt/list_detail_ltt",
	        data: {id: ltt_id},
	        dataType: 'json'
	    })
	    .done(function(result) {
	    	if (!result.error) {
	    		$('#body-detail-ltt-cpts').empty();
	        	jQuery.each(result, function(index, item) {
	        		var tr = '<tr data-mtt-id="'+item.id+ '"> <td> <p class="name-dntt"><a href="#">' +item.cpt.unit+ '</a></p> </td> <td> <p class="dntt-ncc">' +item.venue.name+ '</p> </td> <td class="text-right"> <p class="rem-amount">' +new Number(item.rem_amount).format(2)+ ' <span class="text-muted dntt-currency">' +item.currency+ '</span></p> </td> <td class="text-right"> <p class="dntt-amount">' +new Number(item.amount).format(2)+ ' <span class="text-muted dntt-currency">' +item.currency+ '</span></p> </td> <td class="td-note"> <p class="dntt-note">' +item.note+ '</p> </td> <td> <a class="delete_ldntt" title="remove"><i class="fa fa-ban" aria-hidden="true"></i></a> </td> </tr>';
	        		$('#body-detail-ltt-cpts').append(tr);
	        	});
	        	var href = '/appbasic/web/cpt/pdf/' + ltt_id;
	        	$('#view_pdf').prop('href', href);
	        	$('#list-detail-ltt-modal').modal('show');
	        } else {
	        	alert('error');
	        }
	        return false;
	    })
	    .fail(function() {
	        alert( "Error" );
	    });
});
$(document).on('click', '.delete_ldntt', function(){
	var clicked = $(this),
		mtt = clicked.closest('tr'),
		mtt_id = mtt.data('mtt-id');

	if (mtt_id == null) {
		return;
	}
	(new PNotify({
	    title: 'Confirmation',
	    text: 'Are you sure delete this item?',
	    icon: 'glyphicon glyphicon-question-sign',
	    hide: false,
	    confirm: {
	        confirm: true
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
	    }
	})).get().on('pnotify.confirm', function() {
	    $.ajax({
	        method: "GET",
	        url: "/appbasic/web/cpt/stop_mtt",
	        data: {id: mtt_id},
	        dataType: 'json'
	    })
	    .done(function(result) {
	    	if (!result.err) {
	        	$(mtt).fadeOut(500, function(){
	        		var trs = $('#body-ltt-cpts').find('tr');
	        		var tr;
	        		jQuery.each(trs, function(index, item){
	        			if ($(item).data('ltt-id') == result.ltt.id) {
	        				tr = $(item);
	        			}
	        		});
	        		var ltt_currency = $(tr).find('.ltt-currency');
	        		$(ltt_currency).empty();
	        		jQuery.each(result.m, function(index, item){
	        			var it = item.split('-');
	        			if (it.length == 2) {
	        				var html = '<b>'+new Number(it[0]).format(2)+'</b> <span class="text-muted">'+it[1]+'</span> <br>';
	        				$(ltt_currency).append(html);
	        			}
	        		});
	        		$(mtt).remove();
	        	});
	        } else {
	        	if (result.warn != '')
	        		alert(result.warn);
	        	if (result.err != '')
	        		alert(result.err);
	        }
	        return false;
	    })
	    $('.ui-pnotify-modal-overlay').remove();
	}).on('pnotify.cancel', function() {
	    $('.ui-pnotify-modal-overlay').remove();
	});
});
 $(document).on('click', '.confirm_ok', function(){
//////////////////////////////''''''// 	var ltt_id = $(this).data('ltt_id'); // new PNotify({// 	type: 'error', //     title: 'info', //     text: 'Stop this ltt ?\n <div class="text-right"><a class="btn btn-success confirm_ok" data-ltt_id="' +ltt_id+ '">Yes</a> <a class="btn btn-default confirm_cancel">Cancel</a></div>', //        buttons: {//            closer: false, //            sticker: false //        } // }); // 		if (ltt_id == null) {// 			return; // 		} // 		$.ajax({// 	        method: "GET", // 	        url: "/appbasic/web/cpt/stop_ltt", // 	        data: {id: ltt_id}, // 	        dataType: 'json'// 	    }) // 	    .done(function(result) {// 	    	if (!result.err) {// 	        	$(ltt).fadeOut(500, function(){// 	        		$(ltt).remove(); // 	        	}); // 	        } else {// 	        	if (result.warn != '') // 	        		alert(result.warn); // 	        	if (result.err != '') // 	        		alert(result.err); // 	        } // 	        return false; // 	    }) ////////////////////////////////////
 });
$(document).ready(function(){
	$('.checked_ldntt').on('click', function(){
		// var animate_in = $('#animate_in').val(),
  //   	animate_out = $('#animate_out').val();
		new PNotify({
			type: 'success',
		    title: 'info',
		    text: 'Status confirmed',
		    delay:1000,
            buttons: {
                closer: false,
                sticker: false
            }
		});
		var clicked = $(this),
			ltt = clicked.closest('tr'),
			ltt_id = ltt.data('ltt-id');
		if (ltt_id == null) {
			return;
		}
		$.ajax({
	        method: "GET",
	        url: "/appbasic/web/cpt/check_ltt",
	        data: {id: ltt_id},
	        dataType: 'json'
	    })
	    .done(function(result) {
	    	if (!result.err) {
	    		var actions = result.actions.split(';');
	    		var action = actions.pop().split(',');
	        	if (result.status == 'check1') {
	        		$(clicked).css('color', 'blue');
	        		$(ltt).find('.span-status').addClass('span-status-check1')
	        	}
	     		if (result.status == 'check2') {
	        		$(clicked).css('color', 'red');
	        		$(ltt).find('.span-status').addClass('span-status-check2')
	        	}
	        	if (result.status == 'check3'){
	        		$(clicked).css('color', 'green');
	        		$(ltt).find('.span-status').addClass('span-status-check3')
	        	}
	        	$(ltt).find('.span-status').text(result.status);
        		$(ltt).find('.ltt-update').html('<b>'+action[0]+'</b> confirm <b>'+action[1]+'</b>');
        		var date = convertUTCDateToLocalDate(new Date(action[2]));
        		$(ltt).find('.ltt-update-dt').html('<b>'+date+'</b>');
	        } else {
	        	if (result.warn != '')
	        		alert(result.warn);
	        	if (result.err != '')
	        		alert(result.err);
	        }
	        return false;
	    })
	});
	$('.cancel_ldntt').on('click', function(){
		var ltt = $(this).closest('tr'),
		ltt_id = ltt.data('ltt-id');

		(new PNotify({
		    title: 'Confirmation',
		    text: 'Are you sure delete this item?',
		    icon: 'glyphicon glyphicon-question-sign',
		    hide: false,
		    confirm: {
		        confirm: true
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
		    }
		})).get().on('pnotify.confirm', function() {
		    if (ltt_id == null) {
				return;
			}
			$.ajax({
		        method: "GET",
		        url: "/appbasic/web/cpt/stop_ltt",
		        data: {id: ltt_id},
		        dataType: 'json'
		    })
		    .done(function(result) {
		    	if (!result.err) {
		        	$(ltt).fadeOut(500, function(){
		        		$(ltt).remove();
		        	});
		        } else {
		        	if (result.warn != '')
		        		alert(result.warn);
		        	if (result.err != '')
		        		alert(result.err);
		        }
		        return false;
		    })
		    $('.ui-pnotify-modal-overlay').remove();
		}).on('pnotify.cancel', function() {
		    $('.ui-pnotify-modal-overlay').remove();
		});
	});
});

Date.prototype.ddmmyyyy = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	return dd+"/"+mm+"/"+yyyy;
};
Date.prototype.hhiiddmmyyyy = function() {
	var yyyy = this.getFullYear();
	var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
	var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
	var hh  = this.getHours() < 10 ? "0" + this.getHours() : this.getHours();
	var ii  = this.getMinutes() < 10 ? "0" + this.getMinutes() : this.getMinutes();
	return dd+"/"+mm+"/"+yyyy+" "+hh+":"+ii;
};
function convertUTCDateToLocalDate(date) {
    var newDate = new Date(date.getTime());

    var offset = date.getTimezoneOffset() / 60;
    var hours = date.getHours();

    newDate.setHours(hours - offset);

    return newDate.hhiiddmmyyyy();
}
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};