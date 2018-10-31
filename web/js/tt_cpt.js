var ids_selected = '';
var count_check = 0;



$(document).on('click', '.dntt-amount', function(){
	if ($(this).find('[name="txt_amount"]').length != 1) {
		var clicked = $(this),
			txt_this = $(clicked).closest('td').data('rem_amount'),
			mtt = clicked.closest('tr'),
			mtt_id = mtt.data('mtt-id');
		$(clicked).text('');
		$(clicked).append($('<input>', {
						        type: 'text',
						        name: 'txt_amount',
						        val: txt_this
						    }));
		$(clicked).find('[name="txt_amount"]').focus();
	}
});

$(document).on('blur', '.dntt-amount', function(){
	var clicked = $(this);
	var txt_amount = $(this).find('[name="txt_amount"]');
	if (txt_amount.length > 0) {
		if (txt_amount.val() == '') {
			txt_amount.val(0);
		}
		var mtt = $(this).closest('tr'),
			mtt_id = mtt.data('mtt-id'),
			mtt_amount = $(mtt).find('[name="txt_amount"]').val(),
			mtt_note = $(mtt).find('.dntt-note').text();
			// alert(mtt_amount); return;

		$.ajax({
	        method: "POST",
	        url: "/appbasic/web/cpt/edit_dntt",
	        data: { mtt_id: mtt_id, amount:mtt_amount, note: mtt_note },
	        dataType: 'json'
	    })
	    .done(function(result) {
	        if (!result.err) {
	        	$(clicked).closest('td').data('rem_amount', result.amount);
				$(clicked).text(new Number(result.amount).format(2));
				$(txt_amount).fadeOut(500, function(){
					$(txt_amount).remove(); 
				});
	        } else {
	        	new PNotify({
	                type: 'error',
	                title: 'warning',
	                text: result.err,
	                delay:3500,
	                // styling: 'bootstrap3',//fontawesome, bootstrap3, jqueryui, brighttheme
	                buttons: {
	                    closer: false,
	                    sticker: false
	                },
	            });
	            return;
	        }
	    })
	    .fail(function() {
	        alert( "Error" );
	    });
	}
});

$(document).on('click', '.td-note', function(){
	if ($(this).find('[name="txt_note"]').length != 1) {
		var clicked = $(this),
			txt_this = $(clicked).text(),
			mtt = clicked.closest('tr'),
			mtt_id = mtt.data('mtt-id');
		$(clicked).find('.dntt-note').text('');
		$(clicked).find('.dntt-note').append($('<textarea>', {
						        name: 'txt_note',
						        val: txt_this
						    }));
		$(clicked).find('[name="txt_note"]').focus();
	}
});
$(document).on('blur', '.dntt-note', function(){
	var txt_note = $(this).find('[name="txt_note"]');
	if (txt_note.length > 0) {
		var mtt = $(this).closest('tr'),
			mtt_id = mtt.data('mtt-id'),
			mtt_amount = $(mtt).find('.dntt-amount').text(),
			mtt_note = $(mtt).find('[name="txt_note"]').val();
			// alert(mtt_id +','+mtt_amount+','+mtt_note); return;

		$.ajax({
	        method: "POST",
	        url: "/appbasic/web/cpt/edit_dntt",
	        data: { mtt_id: mtt_id, amount:mtt_amount, note: mtt_note },
	        dataType: 'json'
	    })
	    .done(function(result) {

	    })
	    .fail(function() {
	        alert( "Error" );
	    });
		$(txt_note).fadeOut(500, function(){
			$(txt_note).remove(); 
			$(mtt).find('.dntt-note').text(mtt_note);
		});
	}
});
$(document).on('click', '.delete_dntt', function(){
	var cfirm = confirm('Delete this item?');
	if (cfirm) {
		var mtt = $(this).closest('tr'),
			mtt_id = mtt.data('mtt-id');
		$.ajax({
	        method: "POST",
	        url: "/appbasic/web/cpt/remove_dntt?id="+mtt_id,
	        dataType: 'json'
	    })
	    .done(function(result) {
	    	$(mtt).fadeOut(500, function(){
		    	$(mtt).remove();
		    });
		    $('span.add-dntt').each(function(index, item){
		    	if ($(item).data('dvtour_id') == result.cpt_id) {
		    		$(item).removeClass('green-color');
		    	}
		    });
		    $('#cnt_mtt').text($('#body-dntt-cpts').find('.dntt').length-1);
		    new PNotify({
	                type: 'success',
	                title: 'info',
	                text: 'Item is removed',
	                styling: 'bootstrap3',
	                delay:2500,
	                buttons: {
	                    closer: false,
	                    sticker: false
	                },
	            });
	    })
	    .fail(function() {
	        alert( "Error" );
	    });
	}
});
$(document).on('click', '.chk', function(){
    if ($(this).prop('checked')) {
        count_check ++;
   } else {
        count_check --;
        if (count_check == 0) {
            $('#copy, #delete').hide();
        }
   }
   ids_selected = '';
   $('#body-dntt-cpts').find('tr').each(function(index, item){
        if ($(item).find('.chk').prop('checked')) {
            ids_selected = ids_selected +','+$(item).find('[name="chk"]').data('mtt-id');
       }
    });
});
////////tooltip//////////////
$(document).on({
	mouseenter: function(){// Hover over code
	    var title = $(this).attr('title');
	    $(this).data('tipText', title).removeAttr('title');
	    $('<p class="my_tooltip"></p>')
	    .text(title)
	    .appendTo('body')
	    .fadeIn('slow');
	},
	mouseout: function(){
		// Hover out code
	    $(this).attr('title', $(this).data('tipText'));
	    $('.my_tooltip').remove();
	},
	mousemove: function(e){
		var mousex = e.pageX + 20; //Get X coordinates
	    var mousey = e.pageY + 10; //Get Y coordinates
	    $('.my_tooltip')
	    .css({ top: mousey, left: mousex , zIndex: 999999})
	}

},'.masterTooltip');

$(document).ready(function(){
	$('.add-dntt').on('click', function(){
	    var span = $(this);
	    var tour_id = $(this).data('tour_id');
	    var dvtour_id = $(this).data('dvtour_id');
	    $.ajax({
	        method: "POST",
	        url: "/appbasic/web/cpt/cpt_dntt",
	        data: { dvtour_id: dvtour_id, tour_id:tour_id },
	        dataType: 'json'
	    })
	    .done(function(result) {
	        if (result.limit) {
	        	new PNotify({
                            title: 'warning',
                            text: result.limit,
                            delay:2500,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
                        });
	        	return false;
	        }
	        if (result.err == 'error') {
	        	new PNotify({
	    					type:'error',
                            title: 'warning',
                            text: result.err,
                            delay:2500,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
                        });
	        	return false;
	        } else {
	            $(span).toggleClass('green-color');
	            var cnt_mtt = $('#cnt_mtt').text();
	            if (result.success == 'Saved') {
	            	cnt_mtt++;
	            } else {
	            	cnt_mtt--;
	            }
	            $('#cnt_mtt').text(cnt_mtt);
	        }
	    })
	    .fail(function() {
	        alert( "Error" );
	    });
	});
	$('#viewDntt').on('click', function(){
		$('#select-all').prop('checked', false);
		$('#body-dntt-cpts').empty();
		$.ajax({
	        method: "GET",
	        url: "/appbasic/web/cpt/list_dntt",
	        dataType: 'json'
	    })
	    .done(function(result) {//console.log(result); return;
	        if (result != 0) {
	        	jQuery.each(result, function(index, item) {
	        		var content = (item.cpt.unit)?item.cpt.unit:item.cpt.dvtour_name;
	        		var venue = (item.venue)?(item.venue.name)?item.venue.name:'':'';
	        		var tr = '<tr class="dntt" data-mtt-id="'+item.id+ '"> <td> <input class="chk" name="chk" data-mtt-id="'+item.id+ '" type="checkbox"><td> <p class="name-dntt">' +content+ '</p> </td> <td> <p class="dntt-ncc">' +venue+ '</p> </td> <td class="text-right"> <p class="dntt-rem_amount">' +new Number(item.rem_amount).format(2)+ ' <span class="text-muted dntt-currency">' +item.currency+ '</span></p> </td > <td class="text-right" data-rem_amount="'+item.amount+ '"> <span class="dntt-amount" >' +new Number(item.amount).format(2)+ '</span> <span class="text-muted dntt-currency">' +item.currency+ '</span></td><td class="td-note"> <p class="dntt-note">' +item.note+ '</p> </td> <td> <a class="delete_dntt" title="delete"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
	        		$('#body-dntt-cpts').append(tr);
	        	});
	        	$('#list-dntt-modal').modal('show');
	        } else {
	            alert('chọn cpt để DNTT');
	        }
	    })
	    .fail(function() {
	        alert( "Error" );
	    });
	});
	$('#dnttSave').on('click', function(){
		var note_ltt = $('#note_ltt').val();
		var ids = '';
		$('.dntt').each(function(index, item){
			ids += $(item).data('mtt-id')+',';
		});
		if (ids == '') return;

		$.ajax({
	        method: "POST",
	        url: "/appbasic/web/cpt/create_ltt",
	        data: {ids: ids, note_ltt: note_ltt},
	        dataType: 'json'
	    })
	    .done(function(result) {
	    	if (result.error) {
	    		new PNotify({
	    					type:'warning',
                            title: 'warning',
                            text: 'error DNTT!',
                            delay:2500,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
                        });
	    	} else {
	    		$('#cnt_mtt').text(0);
	    		new PNotify({
	    					type:'success',
                            title: 'info',
                            text: 'DNTT Created!',
                            delay:2500,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
                        });
	    	}
	    	//thay doi trang thai
	    	$('#tbl-cpt').find('tr').each(function(idx, itm){
	    		var cpt_id = $(itm).find('.cpt_status').data('cpt-id');
	    		var bar_status = $(itm).find('.progress-bar');
	    		if (!bar_status.length > 0) {
	    			$(itm).find('.cpt_status').append('<div class="progress-bar"></div>');
	    			bar_status = $(itm).find('.progress-bar');
	    		}
	    		jQuery.each(result, function(index, item){
	    			if (cpt_id == index) {
	    				$(itm).find('span.add-dntt').toggleClass('green-color');
	    				var span_request = $(bar_status).find('span.request');
	    				if (span_request.length > 0) {
	    					$(span_request).data('amount', item['request']['amount'])
	    								   .prop('title', item['request']['amount']+'('+item['request']['percent']+'%')
	    								   .text(item['request']['percent'] + "%")
	    								   .css('width', item['request']['percent'] + "%");
	    				} else {
	    					var new_span_request = '<span class="masterTooltip request" title="'+item['request']['amount']+'('+item['request']['percent']+'%)" data-amount="'+item['request']['amount']+'" style="width: '+item['request']['percent']+'%">'+item['request']['percent']+'%</span>';
	    					$(bar_status).append(new_span_request);
	    				}
	    			}
	    		});

	    	});
	    	///////
	    	$('#list-dntt-modal').modal('hide');
	    })
	    .fail(function() {
	        alert( "Error" );
	    });
	});
	$('#select-all').on('click', function(){
		var cnt = 0;
		if ($(this).prop('checked')) {
		    ids_selected = '';
		    $('#body-dntt-cpts').find('tr').each(function(index, item){
		            $(item).find('[name="chk"]').prop('checked', true);
		            cnt++;
		            ids_selected += ',' + $(item).data('mtt-id');
		    });
		} else {
		    $('#body-dntt-cpts').find('tr').each(function(index, item){
		        $(item).find('[name="chk"]').prop('checked', false);
		    });
		    cnt = 0;
		    ids_selected = '';
		}
		count_check = cnt;
	});
	$('#percenForm').formValidation({
        framework: 'bootstrap',
        icon: false,
        fields: {
            'percen': {
                validators: {
                    notEmpty: {
                        message: 'The Percentage is required'
                    }
                }
            },
        }
    })
    .on('success.form.fv', function(e) {
        e.preventDefault();

        var form = $(e.target),
            id    = form.find('[name="cpt_id"]').val();
        var percen = form.find('[name="percen"]').val();
        form.find('[name="percen"]').val('');
        var ids = ids_selected.split(',');
        if (count_check <= 0) {
        	new PNotify({
                type: 'error',
                title: 'warning',
                text: 'Item is not selected',
                delay:2500,
                // styling: 'bootstrap3',//fontawesome, bootstrap3, jqueryui, brighttheme
                buttons: {
                    closer: false,
                    sticker: false
                },
            });
        	return false;
        }
        // ajax save
        $.ajax({
            url: '/appbasic/web/cpt/cpt_percen',
            method: 'GET',
            data: {percen: percen, ids: ids_selected},
            dataType: "json",
        }).success(function(response) {
            if (!response.err) {
            	$('#select-all').prop('checked', false);
            	count_check = 0;
            	$('#body-dntt-cpts').empty();
            	jQuery.each(response, function(index, item) {
	        		var tr = '<tr class="dntt" data-mtt-id="'+item.id+ '"> <td> <input class="chk" name="chk" data-mtt-id="'+item.id+ '" type="checkbox"><td> <p class="name-dntt">' +item.cpt.unit+ '</p> </td> <td> <p class="dntt-ncc">' +item.venue.name+ '</p> </td> <td class="text-right"> <p class="dntt-rem_amount">' +new Number(item.rem_amount).format(2)+ ' <span class="text-muted dntt-currency">' +item.currency+ '</span></p> </td > <td class="text-right" data-rem_amount="'+item.amount+ '"> <span class="dntt-amount" >' +new Number(item.amount).format(2)+ '</span> <span class="text-muted dntt-currency">' +item.currency+ '</span></td><td class="td-note"> <p class="dntt-note">' +item.note+ '</p> </td> <td> <a class="delete_dntt" title="delete"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
	        		$('#body-dntt-cpts').append(tr);
	        	});
	        	$('#percenForm').formValidation('resetForm');
	        	new PNotify({
	                type: 'success',
	                title: 'info',
	                text: 'Amount of items is changed',
	                delay:2500,
	                buttons: {
	                    closer: false,
	                    sticker: false
	                },
	            });
            } else {
            	new PNotify({
	                type: 'error',
	                title: 'warning',
	                text: response.err,
	                delay:3500,
	                buttons: {
	                    closer: false,
	                    sticker: false
	                },
	            });
            }
        });
    });
});
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
 //new 
 Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};