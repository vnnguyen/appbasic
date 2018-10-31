$(document).ready(function(){
    // Autocomplete();
    $(document).on('click', 'tr', function(){
        Autocomplete();
    });
    $('a.toggle-day-contents').on('click', function(){
        if ($('#tblCurrentProg .day-content:visible').length > 0){
            $('.day-content').hide();
        } else {
            $('#tblCurrentProg .day-content').toggle();
        }
        return false;
    });

    $('#tblCurrentProg').on('click', '.day-name', function(){
        $(this).closest('td').find('.day-content').toggle();
        return false;
    });

    $(document).on('focus', ':input', function(){
        var idx = $(this).closest('tr').find('[name="tt[]"]').val();
        var tr = $(this).closest('tr');
        var tt = $(tr).find('[name="tt[]"]');
        var tr_prev = $(tr).prevAll();
        jQuery.each(tr_prev, function(){
            if (tt.val() == $(this).find('[name="tt[]"]').val()) {
                idx = $(this).find('[name="tt[]"]').val();
                console.log(idx);
            }
        }).promise().done();
        idx--;
        $('#tblCurrentProg tbody .day-content:not(:eq('+idx+'))').hide();
        $('#tblCurrentProg tbody tr:eq('+idx+')').find('.day-content').show();
        return false;
    });


    // var submit = $('#lxForm').find('[type="submit"]');
    // var table = $('#lxForm').find('table');
    // var trs = $(table).find('tbody tr');
    // jQuery.each(trs, function(index, item){
    //     console.log($(index));
    // });
    // console.log(trs);
    // $(document).on('click', $(submit) ,function(){
    //     alert(1);
    // });
});

function Autocomplete(){
    $('.autocomplete').devbridgeAutocomplete({
        serviceUrl: '/appbasic/web/tour/list_lx',
        onSelect: function (suggestion) {
            var content;
            var arr_str = suggestion.value.split('|');
            var parrent = $(this).closest('tr');
            var sl = $(parrent).find('[name="sl[]"]');
            $(this).val(arr_str[0]);
            $(sl).val(suggestion.data);
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
}