
$(document).ready(function() {
    var data_md;
    jQuery('#datetime1').timepicker({
        'minTime': '8:00',
        'maxTime': '17:30',
        'timeFormat': 'H:i'
    });
    $('#md_purpose').selectpicker();
    $('body').on('show.bs.modal', function () {
        $('#md_purpose').selectpicker('refresh');
    });
    $('#s_time').on('change', function(){
        var value = $(this).val();
        if (value != '') {
            if (value == 'detail') {
                $('#editForm').find('.detail_time').show();
                $('#editForm')
                            .find('#datetime1').val('').end();
                $('#editForm')
                        .find('[name="mins"]').val(0).end();
            }
            else
            {
                $('#editForm').find('.detail_time').hide();
                if (value == 'none') {
                    $('#editForm')
                            .find('#datetime1').val('00:00:00').end();
                    $('#editForm')
                            .find('[name="mins"]').val(0).end();
                }
                else{
                    var time = $('#editForm').find('[name="s_time"]').val();
                    $('#editForm')
                            .find('#datetime1').val(time).end();
                    $('#editForm')
                            .find('[name="mins"]').val(0).end();
                }
            }
        }
    });
    // $('#datetimePicker')
    //     .on('dp.change dp.show', function (e) {
    //         // Revalidate the date when user change it
    //         $('#datetimeForm').formValidation('revalidateField', 'datetimePicker');
    //     });

    $('#datetime1, #datetime2').on('change', function(){
        $('#editForm').formValidation('revalidateField', 'time');
    });
    $('#editForm').find('[name="note"]').on('change', function(){
        $('#editForm').formValidation('revalidateField', 'note');
    })
    $('#editForm').find('[name="purpose"]').on('change', function(){
        $('#editForm').formValidation('revalidateField', 'purpose');
    })
    $('#editForm').formValidation({
    framework: 'bootstrap',
    icon: false,
    fields: {
        s_time: {
            validators: {
                notEmpty: {
                    message: 'The time select is required'
                }
            }
        },
        'time': {
            validators: {
                notEmpty: {
                    message: 'The Time is required'
                },
                regexp: {
                    regexp: /^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/,
                    message: 'time incorrect format'
                }
            }
        },
        'mins': {
            validators: {
                notEmpty: {
                    message: 'The minutes is required'
                },
                regexp: {
                    regexp: /^\d+$/,
                    message: 'The minutes must is number'
                }
            }
        },
        purpose: {
            validators: {
                notEmpty: {
                    message: 'The purpose is required'
                }
            }
        },
        note: {
            validators: {
                notEmpty: {
                    message: 'The note is required'
                }
            }
        }
    }
})
.on('success.form.fv', function(e) {
    // Save the form data via an Ajax request
    e.preventDefault();

    var $form = $(e.target),
      id    = $form.find('[name="id"]').val();
    var data = {
        id: id,
        meet_place: $form.find('[name="meet_place"]').val(),
        time: $form.find('[name="time"]').val(),
        s_time: $form.find('[name="s_time"]').val(),
        mins: $form.find('[name="mins"]').val(),
        md: $form.find('[name="purpose"]').selectpicker().val(),
        note: $form.find('[name="note"]').val()
    };
      //console.log(data);return;
    // The url and method might be different in your application
    $.ajax({
        url: '/calen/update_info',
        method: 'POST',
        data: data,
        dataType: "json",
    }).success(function(response) {
        // Get the cells
        var $click = $('a[data-id="' + response.id + '"]'),
            $tr     = $click.closest('tr'),
            $cells  = $tr.find('td');

        // Update the cell data
                var html = '';
                for (var i = 0; i < response.md.length; i++) {
                    if(response.md[i] == 'a'){
                        html += '<i class="glyphicon fa fa-dollar text-pink"></i>';
                    }
                    if(response.md[i] == 'b'){
                        html += '<i class="glyphicon fa fa-refresh text-pink"></i>';
                    }
                    if(response.md[i] == 'c'){
                        html += '<i class="glyphicon fa fa-gift text-pink"></i>';
                    }
                    if(response.md[i] == 'd'){
                        html += '<i class="glyphicon fa fa-birthday-cake text-pink"></i>';
                    }
                }
        $cells
            .eq(4).html(html).end()
            .eq(5).html(response.note).end();
        
        $('#edit_modal').modal('hide');
        // Hide the dialog
        //$form.parents('.bootbox').modal('hide');

        // You can inform the user that the data is updated successfully
        // by highlighting the row or showing a message box
        //bootbox.alert('The user profile is updated');
    });
});

    $('.editable-click').on('click', function() {
        // Get the record's ID via attribute
        var id = $(this).attr('data-id');
        var baseUrl = $(this).attr('data-url');

        $.ajax({
            url: baseUrl,
            method: 'GET',
            dataType: "json",
        }).success(function(response) {
            // Populate the form fields with the data returned from server
            $('#editForm')
                .find('[name="id"]').val(response.id).end()
                .find('#meet_place').val(response.meet_place).end()
                .find('[name="note"]').val(response.note).end()
                .find('#md_purpose').selectpicker('val',response.md).end();
            // check to set time
            if (response.fuzzy == "time") {
                var arr_time = response.time.split(':');
                if (arr_time[2] == 59) {
                    $('#editForm').find('.detail_time').hide();
                    $('#editForm').find('[name="s_time"]').val(response.time).end();
                    $('#editForm')
                            .find('#datetime1').val(arr_time[0]+':'+arr_time[1]+':'+arr_time[2]).end();
                    $('#editForm')
                            .find('[name="mins"]').val(0).end();
                }
                else
                {
                    $('#editForm').find('[name="s_time"]').val('detail').end();
                    $('#editForm').find('.detail_time').show();
                    $('#editForm')
                        .find('#datetime1').val(arr_time[0]+':'+arr_time[1]).end();
                    $('#editForm')
                        .find('[name="mins"]').val(response.mins).end();
                }
            }
            else
            {
                $('#editForm').find('.detail_time').hide();
                $('#editForm').find('[name="s_time"]').val('none').end();
                $('#editForm')
                            .find('#datetime1').val("00:00:00").end();
                $('#editForm')
                        .find('[name="mins"]').val(0).end();
            }
            //////set icons on selectpicker
            var select = $('#editForm').find('#md_purpose');
            var parent = select.closest('.btn-group');
            var button = parent.find('button');
            var options = button.find('.filter-option');
            var values = select.val();
            if (values != null) {
                options.html('');
                var html = '';
                for (var i = 0; i < values.length; i++) {
                    if(values[i] == 'a'){
                        html += '<i class="glyphicon fa fa-dollar text-pink"></i>';
                    }
                    if(values[i] == 'b'){
                        html += '<i class="glyphicon fa fa-refresh text-pink"></i>';
                    }
                    if(values[i] == 'c'){
                        html += '<i class="glyphicon fa fa-gift text-pink"></i>';
                    }
                    if(values[i] == 'd'){
                        html += '<i class="glyphicon fa fa-birthday-cake text-pink"></i>';
                    }
                }
                options.html(html);
            }
            

            // Show the dialog
            // bootbox
            //     .dialog({
            //         title: 'Edit the user profile',
            //         message: $('#userForm'),
            //         show: false // We will show it manually later
            //     })
            //     .on('shown.bs.modal', function() {
            //         $('#userForm')
            //             .show()                             // Show the login form
            //             .formValidation('resetForm'); // Reset form
            //     })
            //     .on('hide.bs.modal', function(e) {
            //         // Bootbox will remove the modal (including the body which contains the login form)
            //         // after hiding the modal
            //         // Therefor, we need to backup the form
            //         $('#userForm').hide().appendTo('body');
            //     })
            //     .modal('show');
        });
    });
    $('#edit_modal').on('hide.bs.modal', function() {
        $('#editForm').formValidation('resetForm', true);
    });
    $(document).on('change', '#md_purpose', function(){
        var parent = $(this).closest('.btn-group');
        var button = parent.find('button');
        var options = button.find('.filter-option');
        // console.log();return;options.empty();
        var values = $(this).val();
        if (values != null) {
            options.html('');
            var html = '';
            for (var i = 0; i < values.length; i++) {
                if(values[i] == 'a'){
                    html += '<i class="glyphicon fa fa-dollar text-pink"></i>';
                }
                if(values[i] == 'b'){
                    html += '<i class="glyphicon fa fa-refresh text-pink"></i>';
                }
                if(values[i] == 'c'){
                    html += '<i class="glyphicon fa fa-gift text-pink"></i>';
                }
                if(values[i] == 'd'){
                    html += '<i class="glyphicon fa fa-birthday-cake text-pink"></i>';
                }
            }
            options.html(html);
        }

    });
});

