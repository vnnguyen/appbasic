var condition_data;
var arr_ids_created = [];
showListDataCondition();
$('#btn-create-conditions').click(function(){
	var code = $('.wrap-setting').find('#txt-input-code').val();
	var category = $('.wrap-setting').find('#category_select').val();
	var description = $('.wrap-setting').find('.set_description').val();
	var con_value1 = [];
	$('#con_value1 option:selected').each(function() {
		con_value1.push($(this).val());
	});
	var con_value2 = [];
	$('#con_value2 option:selected').each(function() {
		con_value2.push($(this).val());
	});
	var operator = $('.wrap-setting').find('#operator_select').val();
	if (code == '') {
		notiAlert('waning', 'Code is empty'); return;
	}
	if (description == '') {
		notiAlert('waning', 'Description is empty'); return;
	}
	if (category == '') {
		notiAlert('waning', 'Category is empty'); return;
	}
	if (con_value1.length == 0) {
		notiAlert('waning', 'Condition Value 1 is empty'); return;
	}
	if (con_value2.length == 0) {
		notiAlert('waning', 'Condition Value 2 is empty'); return;
	}
	var data = {
		code: code,
		category: category,
		operator: operator,
		from: con_value1.toString(),
		to: con_value2.toString(),
		equal: '',
		description: description
	};
	var arr_data = [];
	arr_data.push(data);
	if (arr_data.length > 0 ) {
		$.ajax({
			url: "/appbasic/web/condition/add_condition",
			type: "POST",
			data: {arr_data: arr_data},
			success: function(response){ //console.log(response); return;
				var obj = JSON.parse(response);
	            if (obj.error) {
	                notiAlert('error', obj.error); return;
	            }
				arr_ids_created = response;
				showListDataCondition();
			},
			error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
		});
	}
});
$('#condition-modal').on('show.bs.modal', function () {
	var data = $.map(condition_data, function (obj) {
				obj.id = obj.code;
				obj.text = obj.text || obj.code; // replace name with the property used for the text
				return obj;
			});
	$('.code_select').select2({
						maximumSelectionLength: 3,
						multiple: true,
						data: data,
						tags: "true",
						maximumInputLength: 20

					});
});

$('#txt-search-code, #txt-search-category, #txt-search-description').on('keyup', function(){
	var code = $('#txt-search-code').val();
	var category = $('#txt-search-category').val();
	var description = $('#txt-search-description').val();
	$.ajax({
		url: "/appbasic/web/condition/search_condition",
		type: "POST",
		data: {code: code, category: category, description: description},
		success: function(response){
			var obj = JSON.parse(response);
            if (obj.error) {
                notiAlert('error', obj.error); return;
            }
			displayPage(1,obj);
			showPaging(obj);
		},
		error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
	});
});
$('#btn-add-conditions').click(function(){
	var rows = $('#list-condition').find('tr');
	var arr_code = [];
	rows.each(function(){
		if ($(this).find('.checkCondition').prop('checked') == true) {
			arr_code.push($(this).find('._code').text())
		}
	});
	if (arr_code.length > 0) {
		$('#service-conditions').val(arr_code);
		$('#condition-modal').modal('hide');
	} else {
		notiAlert('waning', 'none row is checked !');
		return;
	}

});
$('#btn-save-conditions').click(function(){
	var arr_data = [];
	$('.li-wrap-condition').each(function(){
		var current = $(this);
		if (current.find('.input-code-field').val() != '' && current.find('.select-category').val() != '' && current.find('.select-operator').val() != '') {
			var code = current.find('.input-code-field').val();
			var category = current.find('.select-category').val();
			var operator = current.find('.select-operator').val();
			var description = current.find('.description').val();
			var from = '';
			var to = '';
			var equal = '';
			var empty = false;
			switch(operator){
				case 'before':
					to = current.find('.to-field').val();
					if (to == '') { empty = true;}
					break;
				case 'after':
					from = current.find('.from-field').val();
					if (from == '') { empty = true;}
					break;
				case 'from':
					from = current.find('.from-field').val();
					if (from == '') { empty = true;}
					break;
				case 'to':
					to = current.find('.to-field').val();
					if (to == '') { empty = true;}
					break;
				case 'equal':
					equal = current.find('.equal-field').val();
					if (equal == '') { empty = true;}
					break;
				case 'not':
					equal = current.find('.equal-field').val();
					if (equal == '') { empty = true;}
					break;
				default:
					from = current.find('.from-field').val();
					to = current.find('.to-field').val();
					if (from == '' || to == '') { empty = true;}
					break;
			}
			if (empty) {
				alert('Text input is empty!');
				return;
			}
			if (description == '') { alert('Description is empty'); return;}
			var data = {
					code: code,
					category: category,
					operator: operator,
					from: from,
					to: to,
					equal: equal,
					description: description
				};
			arr_data.push(data);
		}
	});
	if (arr_data.length > 0 ) {
		$.ajax({
			url: "/appbasic/web/condition/add_condition",
			type: "POST",
			data: {arr_data: arr_data},
			success: function(response){
				var obj = JSON.parse(response);
	            if (obj.error) {
	                notiAlert('error', obj.error); return;
	            }
				arr_ids_created = response;
				console.log(arr_ids_created);
				$('#add-condition-modal').modal('hide');
				showListDataCondition();
			},
			error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
		});
	}
});

$(document).on('change', '.select-operator', function(){
	var operator = $(this).val();
	var current_parrent = $(this).closest('.li-wrap-condition');
	switch(operator){
		case 'after':
			$(current_parrent).find('.wrap-to, .wrap-equal').hide();
			$(current_parrent).find('.wrap-from').show();
			break;
		case 'before':
			$(current_parrent).find('.wrap-from, .wrap-equal').hide();
			$(current_parrent).find('.wrap-to').show();
			break;
		case 'from':
			$(current_parrent).find('.wrap-to, .wrap-equal').hide();
			$(current_parrent).find('.wrap-from').show();
			break;
		case 'to':
			$(current_parrent).find('.wrap-from, .wrap-equal').hide();
			$(current_parrent).find('.wrap-to').show();
			break;
		case 'equal':
			$(current_parrent).find('.wrap-from, .wrap-to').hide();
			$(current_parrent).find('.wrap-equal').show();
			break;
		case 'not':
			$(current_parrent).find('.wrap-from, .wrap-to').hide();
			$(current_parrent).find('.wrap-equal').show();
			break;
		default:
			$(current_parrent).find('.wrap-from, .wrap-to').show();
			$(current_parrent).find('.wrap-equal').hide();
			break;

	}
});

$(document).on({
		mouseover: function(){
			$(this).find('.bottom-link').stop().fadeIn();
		},
		mouseout: function(){
			$(this).find('.bottom-link').stop().fadeOut();
		},
	},'.li-wrap-condition');
$(document).on('click', '.btn-remove-condition', function(){
	var clicked = $(this);
	var current = clicked.closest('.li-wrap-condition');
    //
    current.slideUp(function(){
    	$(this).remove();
    });
});
$(document).on("click", "a.page-link", function(e){
	var num = $("#pagingControls").pagination('getCurrentPage');
	displayPage(num,condition_data);
});
$('#btn-add-more').click(function(){
	var html = '<li class="panel panel-flat col-md-12 li-wrap-condition"> <div class="col-md-12 select-wrap" > <div class="col-md-2 form-group wrap-code"> <label>Code</label> <input type="text" class="form-control input-code-field" value=""> </div><div class="col-md-2 form-group"> <label>Category</label> <select name="category[]" class="form-control select-category" placeholder="select option"> <option value="">select</option><option value="datebook">Ngày đặt</option> <option value="dateUse">Ngày sử dụng</option> <option value="datebook">Tuổi</option> </select> </div> <div class="col-md-1 form-group"> <label>Operator</label> <select name="operator[]" class="form-control select-operator"  placeholder="select option"><option value="">select</option> <option value="from">From</option> <option value="to">To</option> <option value="equal">Equal</option> <option value="beetween">Beetween</option> <option value="or">Or</option> <option value="and">And</option> <option value="not">Not</option> </select> </div> <div class="col-md-3 form-group wrap-from"> <label>From</label> <input type="text" class="form-control from-field" value=""> </div> <div class="col-md-3 form-group wrap-to"> <label>To</label> <input type="text" class="form-control to-field" value=""> </div> <div class="col-md-3 form-group wrap-equal"> <label>Equal</label> <input type="text" class="form-control equal-field" value=""> </div> <div class="col-md-5 form-group"> <label>Description</label> <textarea class="form-control description" ></textarea> </div> <div class="bottom-link"> <span class="btn-remove-condition"><i class="glyphicon glyphicon-remove"></i></span> </div> </div> </li>';
	$('#ul-condition').append(html);
});
$('#button-add-conditions').click(function(){
	$('#condition-modal').modal('show');
});
$('#add-new-conditions').click(function(){
	$('#add-condition-modal').modal('show');
});
function showListDataCondition()
{
	getListDataCondition();
}
// displayPage(1,dataSource);
// showPaging(dataSource);
function getListDataCondition(){
	$.ajax({
		url: "/appbasic/web/condition/list_condition",
		type: "GET",
		dataType: 'json',
		success: function(response){
			condition_data = response;
			displayPage(1,condition_data);
			showPaging(condition_data);
		},
		error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
	});
}
var pagination = {};
pagination.pager = function(){
	this.perPage = 10;
	this.currentPage = 1;
	this.pagingControlsContainer = '#pagingControls';
	this.pagingContainerPath = 'tbody';
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
			html +="<tr class='record' data-id ='"+arr[i]['id']+"'> <td class='td-checkCondition'><input type='checkbox' name='checkCondition[]' class ='checkCondition'></td><td class='td-code'><p class='_code'>"+arr[i]['code'] +"</p></td><td class='td-category'><p class='_category'>"+arr[i]['category'] +"</p></td><td class='td-description'><p class='_description'>"+arr[i]['description'] +"</p></td></tr>";
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
    pager.pagingContainer = $('tbody'); // set of main container
    pager.data = data; // set of required containers
    pager.showPage(i);
}
function notiAlert(type, text){
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
