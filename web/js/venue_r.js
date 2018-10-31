var date_select = '';
var ncc_id = $('#table_dv').data('venue-id');
var tday = new Date();
var id_dvc = 0;
var current_dvd = [];;
var dvc_current;

if (tday) {
    $.ajax({
        method: "GET",
        url: "/appbasic/web/venue/list_dv",
        data: { venue_id: ncc_id, date_selected: tday.getDate()+'/'+(tday.getMonth()+1)+'/'+tday.getFullYear()},
        dataType: 'json'
    })
    .done(function(result) {
        console.log(result);//return;
        if (result.err && result.err != undefined) { $('#list_dv').empty(); return;}
        var dvc = dvc_current = result['dvc'];
        var venue = dvc['venue'];
        current_dvd = dvc.dvd.def.split(';');
        // $('#wrap_dvd').empty();
        // $('#wrap_dvd').append('<a class="masterTooltip dvd-a" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</a>');
        if (id_dvc != dvc.id) {
            $('.note_display').html(dvc.body);
            id_dvc = dvc.id
        }
        $('#list_dv').empty();
        jQuery.each(venue['dv'], function(i, dv){
            var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
            $('#list_dv').append(html);
            jQuery.each(dv['cp'], function(k, cp){
                if (cp.period == dvc.dvd.code) {
                    var curren = new Number(cp.price).format(2);
                    var curren_arr = curren.split('.');
                    if (parseInt(curren_arr[1]) == 0 ) {
                        curren = curren_arr[0];
                    }
                    var td_html = '<div><span class="pull-left text-muted">'+cp.conds+'</span> <a>'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></div>';
                    $('#list_dv').find('tr:last td.content_price').append(td_html);
                }
            });
        });

    })
    .fail(function() {
        alert( "Error" );
    });
}

$('#selme').datepicker({
    firstDay: 1,
    todayButton: true,
    clearButton: true,
    autoClose: true,
    // range: true,
    // multipleDatesSeparator: ' - ',
    language: 'en',
    dateFormat: 'd/m/yyyy',
    position: 'top left',
    onSelect: function(fd, d, picker) {
        if (!d) return;
        date_selected = fd;
        var venue_id = $('#table_dv').data('venue-id');
        var exist = false;
        jQuery.each(current_dvd, function(index, item){
            var dt_items = item.split('-');
            if (dt_items.length != 2) { return false;}
            var dt1 = dt_items[0].split('/'),
                dt_f = dt1[2]+'/'+dt1[1]+'/'+dt1[0];
            var dt2 = dt_items[1].split('/'),
                dt_s = dt2[2]+'/'+dt2[1]+'/'+dt2[0];
            if (new Date(dt_f).valueOf() <= d.valueOf() && d.valueOf() <= new Date(dt_s).valueOf()) {
                exist = true;
            }

        });
        if (!exist) {
            $.ajax({
                method: "GET",
                url: "/appbasic/web/venue/list_dv",
                data: { venue_id: venue_id, date_selected:date_selected},
                dataType: 'json'
            })
            .done(function(result) {
                if (result.err && result.err != undefined) { $('#list_dv').empty(); return;}
                var dvc = dvc_current = result['dvc'];
                var venue = dvc['venue'];
                current_dvd = dvc.dvd.def.split(';');
                if (id_dvc != dvc.id) {
                    $('.note_display').html(dvc.body);
                    id_dvc = dvc.id
                }
                $('.note_display').html(dvc.body);
                $('#list_dv').empty();
                jQuery.each(venue['dv'], function(i, dv){
                    var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
                    $('#list_dv').append(html);
                    jQuery.each(dv['cp'], function(k, cp){
                        if (cp.period == dvc.dvd.code) {
                            var curren = new Number(cp.price).format(2);
                            var curren_arr = curren.split('.');
                            if (parseInt(curren_arr[1]) == 0 ) {
                                curren = curren_arr[0];
                            }
                            var td_html = '<p><span class="pull-left text-muted">'+cp.conds+'</span> <a href="/cp/u/57">'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></p>';
                            $('#list_dv').find('tr:last td.content_price').append(td_html);
                        }
                    });
                });

            })
            .fail(function() {
                alert( "Error" );
            });
        } else {
            var dvc = dvc_current;
            var venue = dvc['venue'];
            current_dvd = dvc.dvd.def.split(';');
            if (id_dvc != dvc.id) {
                $('.note_display').html(dvc.body);
                id_dvc = dvc.id
            }
            $('.note_display').html(dvc.body);
            $('#list_dv').empty();
            jQuery.each(venue['dv'], function(i, dv){
                var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
                $('#list_dv').append(html);
                jQuery.each(dv['cp'], function(k, cp){
                    if (cp.period == dvc.dvd.code) {
                        var curren = new Number(cp.price).format(2);
                        var curren_arr = curren.split('.');
                        if (parseInt(curren_arr[1]) == 0 ) {
                            curren = curren_arr[0];
                        }
                        var td_html = '<p><span class="pull-left text-muted">'+cp.conds+'</span> <a href="/cp/u/57">'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></p>';
                        $('#list_dv').find('tr:last td.content_price').append(td_html);
                    }
                });
            });
        }
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

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};