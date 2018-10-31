var a_clicked;
var cpt_mau;
var ids_selected = '';
var ids_copy = '';
var nm_ids;
var count_check = 0;
var count_ids;
var data_source;
$('.add_cpt').on('click', function(){
    a_clicked = $(this);
    var day_id = $(this).data('id');
    var day_title = $(this).data('title');
    var cpt_nm_ids = $(this).data('cps');
    $('#list_cpt_nm_modal').find('.modal-title').data('day-id', day_id);
    $('#list_cpt_nm_modal').find('.modal-title').text(day_title);
    $('#select-all').prop('checked', false);
    $('#copy, #delete').hide();
    $('#save_paste').hide();
    $('#body-cpts').empty();
    $('#search').find('.search_txt').val('');
    if (cpt_nm_ids != '') {
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/list_cpt",
            data: { arr: cpt_nm_ids },
            dataType: 'json'
        })
        .done(function(result) {
            if (result != 0) {
                data_source = result;
                jQuery.each(result, function(index, item){
                    var html = '<tr class="cptour-nm" data-dv-id="' +item.dv_id+ '" data-vid="' +item.venue.id+ '" data-day-id="'+ day_id +'" data-cpt-id="'+ item.id +'"> <td> <input class="chk" name="chk" value="" data-id="'+ item.id +'" data-dv-id="' +item.dv_id+ '" type="checkbox">                                </td> <td> <p class="cpt-name">' +item.dv.name+ '</p> </td> <td> <p class="cpt-ncc">' +item.venue.name+ '</p> </td> <td> <p class="cpt-note">' +item.note+ '</p> </td> <td> <a class="update" data-id="' + item.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delete_cpt"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
                    $('#body-cpts').append(html);
                });
            } else {
                return;
            }
        })
        .fail(function() {
            alert( "Error" );
        });
    }
    if (ids_copy != '') {
        $('#paste').show();
    } else { $('#paste').hide(); }
    $('#list_cpt_nm_modal').modal('show');
    return false;
});
$(document).on('focus', '.ncc', function(){
    var txt_ncc = $(this);
    $(this).devbridgeAutocomplete({
        serviceUrl: '/appbasic/web/cpt/list_ncc',
        lookupFilter: function (suggestion, query, queryLowerCase) {
            console.log(1);
        },
        onSelect: function (suggestion) {
            $(txt_ncc).val(suggestion.value);
            $(txt_ncc).data('ncc-id', suggestion.data);
            var venue_id = suggestion.data;
            $.ajax({
                url: '/appbasic/web/nm/list_dv?vid='+venue_id,
                method: 'POST',
                dataType: "json",
            }).success(function(response) {
                list = $.map(response, function (obj) {
                            obj.id = obj.id;
                            obj.text = obj.text || obj.name; // replace name with the property used for the text
                            return obj;
                        });
                $('.dv_id').html('');
                $('.dv_id').select2({
                    // maximumSelectionLength: 3,
                    data: list,
                    placeholder: "Select services",
                    minimumResultsForSearch: Infinity,
                    maximumInputLength: 20
                });
            });
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
});
$(document).on('focus', '.dv_id', function(){
    var txt_dv = $(this);
    var venue_id = $(this).data('ncc-id');
    $(this).devbridgeAutocomplete({
        serviceUrl: '/appbasic/web/cpt/list_dv?vid='+venue_id,
        lookupFilter: function (suggestion, query, queryLowerCase) {
            console.log(1);
        },
        onSelect: function (suggestion) {
            console.log(suggestion.data);
            $(txt_dv).val(suggestion.value);

            $(txt_dv).next().val(suggestion.data);
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
});
$(document).on('click', '.add_cpt_new', function(){
    var cpt_nm = $(this).closest('.wrap_cpt_nm').clone();
    $(cpt_nm).find('.cpt_id').val('');
    $(cpt_nm).find('.dv_ids').val('');
    $(cpt_nm).find('.dv_id').val('');
    $(cpt_nm).find('.ncc').val('');
    $(cpt_nm).find('.note').val('');
    $(cpt_nm).find('[name="chk"]').data('id','');
    $(cpt_nm).insertAfter($(this).closest('.wrap_cpt_nm'));
});
$(document).on('click', '.delete_cpt', function(){
        var cfirm = confirm('Confirm Delete');
        if (cfirm) {
            var cpt_nm = $(this).closest('tr');
            $(cpt_nm).fadeOut(500, function(){
                var ids = $(cpt_nm).data('cpt-id');
                if (ids > 0) {
                    var day_id = $(cpt_nm).data('day-id');
                    $.ajax({
                        method: "GET",
                        url: "/appbasic/web/nm/delete_cpt",
                        data: { ids: ids, day_id: day_id}
                    })
                    .done(function(result) {

                        if (result.error) {
                            alert(result.error); return;
                        }
                        if (result == 'empty') { 
                            $(a_clicked).data('cps', '');
                        }
                        else {
                            $(a_clicked).data('cps', result);
                        }
                        new PNotify({
                            title: 'Info',
                            text: 'Deleted!',
                            delay:2500,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
                        });
                        $(cpt_nm).remove();
                    })
                    .fail(function() {
                        alert( "Error adding NoUse tag " );
                    });
                }
            });
        }
});

$('#select-all').on('click', function(){
    var cnt = 0;
   if ($(this).prop('checked')) {
        ids_selected = '';
        $('#copy, #delete').show();
        $('#body-cpts').find('tr').each(function(index, item){
                $(item).find('[name="chk"]').prop('checked', true);
                cnt++;
                ids_selected += ',' + $(item).data('cpt-id');
        });
   } else {
        $('#copy, #delete').hide();
        $('#body-cpts').find('tr').each(function(index, item){
            $(item).find('[name="chk"]').prop('checked', false);
        });
        cnt = 0;
        ids_selected = '';
   }
   count_check = cnt;
});
$(document).on('click', '.chk', function(){
    if ($(this).prop('checked')) {
        $('#copy, #delete').show();
        count_check ++;
   } else {
        count_check --;
        if (count_check == 0) {
            $('#copy, #delete').hide();
        }
   }
   ids_selected = '';
   $('#body-cpts').find('tr').each(function(index, item){
        if ($(item).find('.chk').prop('checked')) {
            ids_selected = ids_selected +','+$(item).find('[name="chk"]').data('id');
       }
    });
});
$('#delete').on('click', function(){
    var cdfirm = confirm('Confirm Delete');
    if (cdfirm) {
        if (ids_selected == '') {
            return;
        }
        var day_id = $('#list_cpt_nm_modal').find('.modal-title').data('day-id');
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/delete_cpt",
            data: { ids: ids_selected, day_id: day_id}
        })
        .done(function(result) {

            if (result.error) {
                alert(result.error); return;
            }
            if (result == 'empty') { $(a_clicked).data('cps', '');}
            else $(a_clicked).data('cps', result);
            new PNotify({
                title: 'Info',
                text: 'Deleted!',
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
        $('#body-cpts').find('tr').each(function(index, item){
            if ($(item).find('.chk').prop('checked')) {
                if ( $(item).find('[name="chk"]').data('id') != '' ) {
                    $(item).fadeOut(500, function(){
                        $(item).remove();
                    });
                }
            }
        });
        $('#select-all').prop('checked', false);
    }
});
$('#copy').on('click', function(){
    if (ids_selected != '') {
        ids_copy = ids_selected;
        new PNotify({
            title: 'Copy info',
            text: 'Copy ok!',
            delay:2500,
            buttons: {
                closer: false,
                sticker: false
            },
        });
    }
});

$('#paste').on('click', function(){
    // alert(ids_copy);return;
    var day_id = $('#list_cpt_nm_modal').find('.modal-title').data('day-id');
    if (ids_copy != '') {
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/list_cpt",
            data: { arr: ids_copy },
            dataType: 'json'
        })
        .done(function(result) {
            if (result != 0) {
                jQuery.each(result, function(index, item){
                    var tr = '<tr class="cptour-nm" data-dv-id="' +item.dv_id+ '" data-vid="' +item.venue.id+ '" data-day-id="'+ day_id +'" data-cpt-id=""> <td> <input class="chk" name="chk" value="" data-id="" data-dv-id="' +item.dv_id+ '" type="checkbox"></td> <td> <p class="cpt-name">' +item.dv.name+ '</p> </td> <td> <p class="cpt-ncc">' +item.venue.name+ '</p> </td> <td> <p class="cpt-note">' +item.note+ '</p> </td> <td> <a class="update" data-id=""><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delete_cpt"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
                    $('#body-cpts').append(tr);
                });
                $('#save_paste').show();
                new PNotify({
                    title: 'Info',
                    text: 'Pasted!',
                    delay:2500,
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                });
            } else {
                return;
            }
        })
        .fail(function() {
            alert( "Error adding NoUse tag " );
        });
    }
    if (ids_copy != '') {
        $('#paste').show();
    } else { $('#paste').hide(); }
    return false;
});

$('#save_paste').on('click', function(){
    var objs = [];
    $('#body-cpts').find('tr').each(function(index, item){
        var obj = {};
        obj.cpt_id = $(item).data('cpt-id');
        obj.dv_id = $(item).data('dv-id');
        obj.note = $(item).find('.cpt-note').text();
        objs.push(obj);
    });
    var day_id =  $('#list_cpt_nm_modal').find('.modal-title').data('day-id');
    $.ajax({
        method: "post",
        url: "/appbasic/web/nm/cpt_save_paste",
        data: { objs: objs, day_id: day_id},
        dataType: 'json'
    })
    .done(function(result) {

        if (result.error) {
            alert(result.error);
        }
        if (result == 0) { return;}
        $(a_clicked).data('cps', result.toString());
        nm_ids = result.toString();
        $('#body-cpts').empty();
        var day_id = $('#list_cpt_nm_modal').find('.modal-title').data('day-id');

        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/list_cpt",
            data: { arr: nm_ids },
            dataType: 'json'
        })
        .done(function(result) {
            if (result != 0) {
                data_source = result;
                jQuery.each(result, function(index, item){
                    var html = '<tr class="cptour-nm" data-dv-id="' +item.dv_id+ '" data-vid="' +item.venue.id+ '" data-day-id="'+ day_id +'" data-cpt-id="'+ item.id +'"> <td> <input class="chk" name="chk" value="" data-id="'+ item.id +'" data-dv-id="' +item.dv_id+ '" type="checkbox">                                </td> <td> <p class="cpt-name">' +item.dv.name+ '</p> </td> <td> <p class="cpt-ncc">' +item.venue.name+ '</p> </td> <td> <p class="cpt-note">' +item.note+ '</p> </td> <td> <a class="update" data-id="' + item.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delete_cpt"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
                    $('#body-cpts').append(html);
                });
            } else {
                return;
            }
        })
        .fail(function() {
            alert( "Error" );
        });
        new PNotify({
            title: 'Info',
            text: 'saved!',
            delay:2500,
            buttons: {
                closer: false,
                sticker: false
            },
        });
    })
    .fail(function() {
        alert( "Error adding NoUse tag " );
    });
});

$(document).on('click', '.update', function(){
    var cpt_nm = $(this).closest('tr');
    $('#cptForm').find('[name="ncc"]').val($(cpt_nm).find('.cpt-ncc').text());
    $('#cptForm').find('[name="note"]').val($(cpt_nm).find('.cpt-note').text());
    $('#cptForm').find('[name="day_id"]').val($(cpt_nm).data('day-id'));
    $('#cptForm').find('[name="cpt_id"]').val($(cpt_nm).data('cpt-id'));
    var venue_id = $(cpt_nm).data('vid');

    $.ajax({
        url: '/appbasic/web/nm/list_dv?vid='+venue_id,
        method: 'POST',
        dataType: "json",
    }).success(function(response) {
        var list = $.map(response, function (obj) {
                    obj.id = obj.id;
                    obj.text = obj.text || obj.name; // replace name with the property used for the text
                    return obj;
                });
                $('.dv_id').select2({
                    // maximumSelectionLength: 3,
                    data: list,
                    placeholder: "Select services",
                    minimumResultsForSearch: Infinity,
                    minimumResultsForSearch: 10
                });

    });
    $('#cpt_nm_modal').modal('show');
});
$('#add_new_cpt').on('click', function(){
    $('#cptForm').find('[name="ncc"]').val('');
    $('#cptForm').find('[name="note"]').val('');
    $('#cptForm').find('[name="day_id"]').val($('#list_cpt_nm_modal').find('.modal-title').data('day-id'));
    $('#cptForm').find('[name="cpt_id"]').val('');
    $('#cpt_nm_modal').modal('show');
});
$('#search_btn').on('click', function(){
    var search_txt = $('#search').find('.search_txt').val();
    var day_id = $('#list_cpt_nm_modal').find('.modal-title').data('day-id');
    // alert(day_id);return;
    if (search_txt != '') {
        $('#body-cpts').empty();
        $.ajax({
            url: '/appbasic/web/nm/search_cpt',
            method: 'GET',
            data: {day_id: day_id, s_txt: search_txt},
            dataType: 'json'
        }).success(function(response) {
            if (response != 0) {
                $('#body-cpts').addClass('result_search');
                jQuery.each(response, function(index, item){
                    var html = '<tr class="cptour-nm" data-dv-id="' +item.dv_id+ '" data-vid="' +item.venue.id+ '" data-day-id="'+ day_id +'" data-cpt-id="'+ item.id +'"> <td> <input class="chk" name="chk" value="" data-id="'+ item.id +'" data-dv-id="' +item.dv_id+ '" type="checkbox"></td> <td> <p class="cpt-name">' +item.dv.name+ '</p> </td> <td> <p class="cpt-ncc">' +item.venue.name+ '</p> </td> <td> <p class="cpt-note">' +item.note+ '</p> </td> <td> <a class="update" data-id="' + item.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delete_cpt"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
                    $('#body-cpts').append(html);
                });
            } else {
                return;
            }

        });
    }
});
$('#reset_btn').on('click', function(){
    if ($(a_clicked).data('cps') != '') {
        $('#body-cpts').empty();
        var day_id = $('#list_cpt_nm_modal').find('.modal-title').data('day-id');
        $.ajax({
            method: "GET",
            url: "/appbasic/web/nm/list_cpt",
            data: { arr: $(a_clicked).data('cps') },
            dataType: 'json'
        })
        .done(function(result) {
            if (result != 0) {
                data_source = result;
                jQuery.each(result, function(index, item){
                    var html = '<tr class="cptour-nm" data-dv-id="' +item.dv_id+ '" data-vid="' +item.venue.id+ '" data-day-id="'+ day_id +'" data-cpt-id="'+ item.id +'"> <td> <input class="chk" name="chk" value="" data-id="'+ item.id +'" data-dv-id="' +item.dv_id+ '" type="checkbox">                                </td> <td> <p class="cpt-name">' +item.dv.name+ '</p> </td> <td> <p class="cpt-ncc">' +item.venue.name+ '</p> </td> <td> <p class="cpt-note">' +item.note+ '</p> </td> <td> <a class="update" data-id="' + item.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delete_cpt"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
                    $('#body-cpts').append(html);
                });
            } else {
                return;
            }
        })
        .fail(function() {
            alert( "Error" );
        });
    }
});
$(document).ready(function(){
    $('#cptForm').formValidation({
        framework: 'bootstrap',
        icon: false,
        fields: {
            'dv_id': {
                validators: {
                    notEmpty: {
                        message: 'The Services is required'
                    }
                }
            },
        }
    })
    .on('success.form.fv', function(e) {
        e.preventDefault();

        var form = $(e.target),
            id    = form.find('[name="cpt_id"]').val();
        var dv_id = form.find('[name="dv_id"]').val();
        var name_dv = form.find('[name="dv_id"]').find("option:selected").text();
        var note = form.find('[name="note"]').val();
        var name_ncc = form.find('[name="ncc"]').val();
        var venue_id = form.find('[name="ncc"]').data('ncc-id');
        var data = {
            cpt_id: id,
            day_id: form.find('[name="day_id"]').val(),
            dv_id: dv_id,
            note: note
        };
        // ajax save
        $.ajax({
            url: '/appbasic/web/nm/cpt_nm',
            method: 'POST',
            data: data,
            dataType: "json",
        }).success(function(response) {
            if (response.error) {
                console.log(response.error);return;
            } else {
                var nm_ids = response.nm_ids;
                response = response.cpt;
                var click = $('a[data-id="' + id + '"]'),
                tr     = click.closest('tr'),
                cells  = tr.find('td');
                if (click.length > 0) {
                    tr.data('vid',venue_id);
                    tr.data('cpt-id', id);
                    tr.find('.chk').data('id', id);
                    tr.find('.chk').data('dv-id', dv_id);
                    $(cells)
                    .eq(1).html('<p class="cpt-name">'+name_dv+'</p>').end()
                    .eq(2).html('<p class="cpt-ncc">'+name_ncc+'</p>').end()
                    .eq(3).html('<p class="cpt-note">'+note+'</p>').end();
                    new PNotify({
                        title: 'Info',
                        text: 'Updated!',
                        delay:2500,
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                    });
                }
                else {
                    var tr = '<tr class="cptour-nm" data-dv-id="' +response.dv_id+ '" data-vid="' +venue_id+ '" data-day-id="'+ response.day_id +'" data-cpt-id="' + response.id + '"> <td> <input class="chk" name="chk" value="" data-id="' +response.id+ '" data-dv-id="' +response.dv_id+ '" type="checkbox"></td> <td> <p class="cpt-name">' +name_dv+ '</p> </td> <td> <p class="cpt-ncc">' +name_ncc+ '</p> </td> <td> <p class="cpt-note">' +response.note+ '</p> </td> <td> <a class="update" data-id="' + response.id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delete_cpt"><i class="fa fa-trash" aria-hidden="true"></i></a> </td> </tr>';
                    $('#body-cpts').append(tr);
                    new PNotify({
                        title: 'Info',
                        text: 'Created!',
                        delay:2500,
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                    });
                }
                $(a_clicked).data('cps', nm_ids);
            }
        });
    });
});

