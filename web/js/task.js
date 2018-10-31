var status_action;
var data_source_task;
var data_source_task_user;
getDataSource();

$(document).on('click', '.span-check-success', function(){
    var task_id = $(this).closest('.li-wrap-task').find('.task_desc').data('id');
    var user_id = $(this).closest('.li-wrap-task').find('.id_user').val();
    var icon = $(this).find("i");
    var status;
    icon.toggleClass("fa-square-o fa-check-square-o");
    if (icon.hasClass('fa-square-o')) {
        status = 'on';
    }
    else{
        status = 'off';
    }
    $.ajax({
        url: "/appbasic/web/task/update_status/"+task_id,
        type: "POST",
        data: {status: status, user_id: user_id},
        success:function(response)
        {
            var obj = JSON.parse(response);
            if (obj.error) {
                notiAlert('error', obj.error);
            }
            else {
                data_source_task = obj.task;
                data_source_task_user = obj.taskUser;
                RefreshTask(data_source_task,data_source_task_user);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
    });
});
var elems = document.querySelector('#is_priority');
$('#anytime-both').AnyTime_picker({ format: "%Y-%m-%d %H:%i"});
var switchery = new Switchery(elems,{
            size:"small",
            color: '#0D74E9'
        });
$('#add_new_task').click(function(){
    status_action = 'create';
    $('#modal_tasks').on('show.bs.modal', function() {
        $('.option-detail-task').hide();
        $('#task-form').formValidation('resetForm', true);
        var $progControl = $("#assignee").select2({
              placeholder: "select option" //placeholder
            });
        $progControl.val(null).trigger("change"); 
        $('#input_action').val(status_action);
        var modal_tasks = $(this);
        for (var i = 0; i < modal_tasks.find('[name="description[]"]').length; i++) {
            modal_tasks.find('[name="description[]"]').val('');
            if (i>0) {
                var remove_modal = modal_tasks.find('[name="description[]"]')[i].closest('.form-group ');
                remove_modal.remove();
            }
        }
        setSwitchery(switchery, false);
        modal_tasks.find('#set_is_all').prop('checked',true);
    });
    $('#modal_tasks').modal('show');

});
$('#modal_tasks').on('show.bs.modal', function() {
    $('#task-form')
    .formValidation('enableFieldValidators','description[]', true)
    .formValidation('enableFieldValidators','deadlines', true)
    .formValidation('enableFieldValidators','who[]', true);

});
$('#modal_tasks').on('hide.bs.modal', function() {
    $('#task-form')
    .formValidation('enableFieldValidators','description[]', false)
    .formValidation('enableFieldValidators','deadlines', false)
    .formValidation('enableFieldValidators','who[]', false);

});
$('#deadline').on('change', function(){
     var deadline = $(this).val();
    if (deadline !== 'detail') {
        $('.option-detail-task').hide();
        $('#task-form')
            .formValidation('enableFieldValidators', 'due_date', false);
        return;
    }
    if (deadline == 'detail') {
        $('.option-detail-task').show();
        $('#task-form')
            .formValidation('enableFieldValidators', 'due_date', true);
            // .formValidation('enableFieldValidators', 'due_time', true);
    }
});

$(document).ready(function() {
    $('#task-form')
    // IMPORTANT: You must declare .on('init.field.fv')
    // before calling .formValidation(options)
    .on('init.field.fv', function(e, data) {
        // data.fv      --> The FormValidation instance
        // data.field   --> The field name
        // data.element --> The field element

        var $parent = data.element.parents('.form-group'),
            $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');

        // You can retrieve the icon element by
        // $icon = data.element.data('fv.icon');

        $icon.on('click.clearing', function() {
            // Check if the field is valid or not via the icon class
            if ($icon.hasClass('glyphicon-remove')) {
                // Clear the field
                data.fv.resetField(data.element);
            }
        });
    })
    .formValidation({
        autoFocus: true,
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'description[]': {
                validators: {
                    notEmpty: {
                        message: 'The description is required'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 255,
                        message: 'The description must be less than 40 characters long'
                    },
                }
            },
            deadlines: {
                validators: {
                    notEmpty: {
                        message: 'The deadline is required'
                    }
                }
            },
            due_date: {
                enabled:false,
                validators: {
                    notEmpty: {
                        message: 'The due date is required'
                    },
                    date: {
                            format: 'YYYY-MM-DD hh:mm',
                            message: 'The due date is not valid'
                        }
                }
            },
            'who[]': {
                validators: {
                    notEmpty: {
                        message: 'The assigned is required'
                    },
                }
            }
            // 'due_time': {
            //     enabled:false,
            //     validators: {
            //         notEmpty: {
            //             message: 'The first dateline is required'
            //         }
            //     }
            // }
        }
    })
    .on('click', '#task-group-add', function() {
        var html = '<div class="form-group wrap-priority"> <label for="description" class="col-md-2 control-label">Việc cần làm và thời hạn (ngày, giờ)</label> <div class="col-md-10"> <input type="text" value="" name="description[]" class="form-control" placeholder="Miêu tả việc cần làm, tối đa 255 chữ"> </div> </div>';
        var position = $('.wrap-priority:last');
        $(html).insertAfter(position);
        $('#taskForm')
            .formValidation('addField', $(html).find('[name="description[]"]'));
    })

    // Remove button click handler
    .on('click', '#task-group-delete', function() {
        var $row = $('.wrap-priority:last');
        if ($('.wrap-priority').length > 1) {
            // Remove fields
            $('#taskForm')
                .formValidation('removeField', $row.find('[name="description[]"]'));
            // Remove element containing the fields
            $row.remove();
        }
    })
    .on('change', '#anytime-both', function() {
             $('#task-form')
            .formValidation('enableFieldValidators', 'due_date', true);
        })
        .on('err.field.fv', function(e, data) {
            if (data.fv.getSubmitButton()) {
                data.fv.disableSubmitButtons(false);
            }
        })
        .on('success.field.fv', function(e, data) {
            if (data.fv.getSubmitButton()) {
                data.fv.disableSubmitButtons(false);
            }
        });
});
// $("#datepicker").datetimepicker({
//         showTodayButton: true,
//         showClear: true,
//         showClose: true,
// });
// $('#task-group-add').on('click', function(){
//     var html = '<div class="form-group wrap-priority"> <label for="description" class="col-md-2 control-label">Việc cần làm và thời hạn (ngày, giờ)</label> <div class="col-md-10"> <input type="text" value="" name="description[]" class="form-control" placeholder="Miêu tả việc cần làm, tối đa 255 chữ"> </div> </div>';
//     var position = $('.wrap-priority:last');
//     $(html).insertAfter(position);
// });
// $('#task-group-delete').on('click', function(){
//     var position = $('.wrap-priority:last');
//     if ($('.wrap-priority').length > 1) {
//         $(position).remove();
//     }
// });
$('#assignee').select2({
                data: [
                    {
                        id: 1,
                        text: 'Nguyennv@amicatravel.com'
                    },
                    {
                        id: 2,
                        text: 'Nguyennv-2@amicatravel.com'
                    }
                ],
                multiple: true,
                placeholder: "Select option",
                tag: true
            });
////////////////this event to view and update task////////////////////
$(document).on('click', '.task_desc', function(){
    status_action = 'update';
    $('#input_action').val(status_action);
    var clicked = $(this);
    var parent = clicked.closest('.li-wrap-task');
    var curr_id = clicked.data('id');
    var due_dt = parent.find('.due_t').val();
    var text_due_dt = parent.find('.task-date').text();
    var desc = parent.find('.task_desc').text();
    var is_priority = parent.find('.is_priority').val();
    var is_all = parent.find('.is_all').val();
    var name_u = parent.find('.name-user').text();
    var id_users = [];
    $('.li-wrap-task').each(function(){
        var wrap_task = $(this);
        if (wrap_task.find('.task_desc').data('id') == curr_id) {
            id_user = wrap_task.find('.id_user').val();
            id_users.push(id_user);
        }
    });
    $('#modal_tasks').on('show.bs.modal', function() {
        var modal_tasks = $(this);
        modal_tasks.find('.option-detail-task').hide();
        modal_tasks.find('#anytime-both').val('');
        var $progControl = $("#assignee").select2({
          placeholder: "select option" //placeholder
        });
        $progControl.val(id_users).trigger("change");
        // modal_tasks.find('#assignee').select2('val', id_users);
        modal_tasks.find('.description').val(desc);
        if (is_priority == 'yes') {
            setSwitchery(switchery, true);
        } else {
            setSwitchery(switchery, false);
        }
        if (is_all == 'yes') {
            modal_tasks.find('#set_is_all').prop('checked',true);
        } else{
            modal_tasks.find('#set_is_not_all').prop('checked',true);
        }
        var date = new Date(due_dt);
        if (date.getSeconds() == 59) {
            switch(text_due_dt) {
                case 'Today':
                    var deadline = 'today';
                    break;
                case 'Tomorrow':
                    var deadline = 'tomorrow';
                    break;
                case 'The day after tomorrow':
                    var deadline = 'after_tomorrow';
                    break;
                case 'This week':
                    var deadline = 'this_week';
                    break;
                case 'Next week':
                    var deadline = 'next_week';
                    break;
                case 'This month':
                    var deadline = 'this_month';
                    break;
                case 'Next month':
                    var deadline = 'next_month';
                    break;
                case 'Any time':
                    var deadline = 'any_time';
                    break;
                default:
                    var deadline = 'detail';
            }
            modal_tasks.find('#deadline').val(deadline);
            if (deadline == 'detail') {
                modal_tasks.find('.option-detail-task').show();
                modal_tasks.find('#anytime-both').val(due_dt);
            }
        }
        if (date.getSeconds() == 0) 
        {
            modal_tasks.find('#deadline').val('detail');
            modal_tasks.find('.option-detail-task').show();
            modal_tasks.find('#anytime-both').val(due_dt);
        }
        modal_tasks.find('#task_id').val(curr_id);
    });
    $('#modal_tasks').modal('show');
});
////////////////this event to submit to save task////////////////////
$(document).on('click', '#btn-save-task', function(){
    var action = $('#input_action').val();
    var state_validate = true;
    var task_id = $('#task_id').val();
    var description = [];
    var due_date = '';
    var deadlines = '';
    var who = [];
    var is_priority = 'no';
    var is_all;
    $('[name="description[]"]').each(function(){
        if ($(this).val() != '' && $(this).val().length < 255) {
            description.push($(this).val());
        }
    })
    is_priority = (elems.checked)?'yes':'no';
    if ($('[name="deadlines"]').val() != '') {
        deadlines = $('[name="deadlines"]').val();
        if (deadlines == 'detail') {
            if ($('#anytime-both').val() != '') {
                due_date = $('#anytime-both').val();
            }
        }
        else{
            due_date = '';
        }
    }
    if ($('#assignee').val() != '') {
        who = $('#assignee').val();
    }

    is_all = ($('#set_is_all').prop('checked'))?'yes':'no';
    $('#task-form')
        .formValidation('validateField', 'description[]')
        .formValidation('validateField', 'deadlines')
        .formValidation('validateField', 'who[]');

    state_validate = $('#task-form').data('formValidation').validate().isValid();
        // .on('err.field.fv', function(e, data) {//     // data.fv --> The FormValidation instance //     state_validate = false; //     // Get the first invalid field //     var $invalidFields = data.fv.getInvalidFields().eq(0); //     // Get the tab that contains the first invalid field //     var $tabPane     = $invalidFields.parents('.tab-pane'), //         invalidTabId = $tabPane.attr('id'); //     // If the tab is not active //     if (!$tabPane.hasClass('active')) {//         // Then activate it //         $tabPane.parents('.tab-content') //                 .find('.tab-pane') //                 .each(function(index, tab) {//                     var tabId = $(tab).attr('id'), //                         $li   = $('a[href="#' + tabId + '"][data-toggle="tab"]').parent(); //                     if (tabId === invalidTabId) {//                         // activate the tab pane //                         $(tab).addClass('active'); //                         // and the associated <li> element //                         $li.addClass('active'); //                     } else {//                         $(tab).removeClass('active'); //                         $li.removeClass('active'); //                     } //                 }); //         // Focus on the field //         $invalidFields.focus(); //     } // }) // .on('success.field.fv', function(e, data) {//     // data.fv      --> The FormValidation instance //     // data.element --> The field element //     state_validate = true; //     var $tabPane = data.element.parents('.tab-pane'), //         tabId    = $tabPane.attr('id'), //         $icon    = $('a[href="#' + tabId + '"][data-toggle="tab"]') //                     .parent() //                     .find('i') //                     .removeClass('fa-check fa-times'); //     // Check if all fields in tab are valid //     var isValidTab = data.fv.isValidContainer($tabPane); //     if (isValidTab !== null) {//         $icon.addClass(isValidTab ? 'fa-check' : 'fa-times'); //     } // });
    if (deadlines == 'detail') {
        $('#task-form')
        .formValidation('validateField', 'due_date')
        .formValidation('revalidateField', 'due_date');
        state_validate = $('#task-form').data('formValidation').validate().isValid();
        // .on('err.field.fv', function(e, data) {state_validate = false; }) .on('success.field.fv', function(e, data) {state_validate = true; });
    }
    if (action == 'create') {
        
        if (state_validate) {
            var data = {
                action: action,
                description: description,
                priority: is_priority,
                deadlines: deadlines,
                due_date: due_date,
                who: who,
                is_all: is_all
            };
            $.ajax({
                url: "/appbasic/web/task/list",
                type:'POST',
                data: data,
                success:function(response)
                {
                    var obj = JSON.parse(response);
                    if (obj.error) {
                        notiAlert('error', obj.error);
                    }
                    else {
                        notiAlert('success', 'Save success');
                        data_source_task = obj.task;
                        data_source_task_user = obj.taskUser;
                        RefreshTask(data_source_task,data_source_task_user);
                        $('#modal_tasks').modal('hide');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
            });
        }
    }else {
        if (state_validate) {
            var data = {
                action: action,
                task_id: task_id,
                description: description,
                priority: is_priority,
                deadlines: deadlines,
                due_date: due_date,
                who: who,
                is_all: is_all
            };
            $.ajax({
                url: "/appbasic/web/task/list",
                type:'POST',
                data: data,
                success:function(response)
                {
                    var obj = JSON.parse(response);
                    if (obj.error) {
                        notiAlert('error', obj.error);
                    }
                    else {
                        notiAlert('success', 'Save success');
                        data_source_task = obj.task;
                        data_source_task_user = obj.taskUser;
                        RefreshTask(data_source_task,data_source_task_user);
                        $('#modal_tasks').modal('hide');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
            });
        }
    }
});
////////////////this event to refresh list task////////////////////
$(document).on('click', '#span-refresh-task', function(){
    getDataSource();
    RefreshTask(data_source_task, data_source_task_user);
});
///////////////this event to delete task from list/////////////////
$(document).on('click', '.remove-task', function(){
    var clicked = $(this);
    var current = clicked.closest('.li-wrap-task');
    var task_id = current.find('.task_desc').data('id');
    //
    $.ajax({
        url: "/appbasic/web/task/remove/"+task_id,
        type:'POST',
        data: {id: task_id,},
        success:function(response)
        {
            new PNotify({
                title: 'Notice',
                text: 'Delete completed! <a class="under">undo</a>',
                delay:2500,
                buttons: {
                    closer: false,
                    sticker: false
                },
            });
            var obj = JSON.parse(response);
            data_source_task = obj.task;
            data_source_task_user = obj.taskUser;
            RefreshTask(data_source_task,data_source_task_user);

        },
        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
    });
});
$(document).on({
    mouseover: function(){
        $(this).find('.remove-task').stop().fadeIn();
    },
    mouseout: function(){
        $(this).find('.remove-task').stop().fadeOut();
    },
},'.li-wrap-task');

function setSwitchery(switchElement, checkedBool) {
    if((checkedBool && !switchElement.isChecked()) || (!checkedBool && switchElement.isChecked())) {
        switchElement.setPosition(true);
        switchElement.handleOnchange(true);
    }
}
// if ($(document).find('.save_success').text() != '') {
//     notiAlert('success', $(document).find('.save_success').text());
// }
// if ($(document).find('.save_unsuccess').text() != '') {
//     notiAlert('error', $(document).find('.save_unsuccess').text());
// }
// if ($(document).find('.delete_success').text() != '') {
//     notiAlert('success', $(document).find('.delete_success').text());
// }
// if ($(document).find('.delete_unsuccess').text() != '') {
//     notiAlert('error', $(document).find('.delete_unsuccess').text());
// }
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

function RefreshTask(data_task,data_task_user)
{

    var d = new Date();
    $(document).find('#ul-list-task').empty();
    jQuery.each(data_task, function(index, task) {
        var date_task = new Date(task.due_dt);
        var list_user = getListUserInTask(task.id, data_task_user).toString();
        var icon_checked = (task.status == "on")?"fa-square-o":"fa-check-square-o";
        date_task.setHours(date_task.getHours()-(d.getTimezoneOffset()/60));
        if (date_task.getSeconds() == 59) {
            var text = getTextDate(date_task.toString());
            if (text != 'detail') {
                var li = '<li class="li-wrap-task"> <span class="span-check-success"><i class="task-check fa '+icon_checked+'"></i></span> <input type="hidden" class="is_priority" value="'+task.is_priority+'"> <input type="hidden" class="due_t" value="'+date_task.yyyymmddhhmmss()+'"> <input type="hidden" class="is_all" value="'+task.is_all+'"> <input type="hidden" class="id_user" value="1"> <span class="task-date">'+text+'</span> <span> <a class="task_desc" data-id="'+task.id+'">'+task.description+'</a> </span> <span class="name-user">'+list_user+'</span> <span class="remove-task" title="remove" data-popup="tooltip"><i class="fa fa-remove"></i></span> </li>';
                $('#ul-list-task').append(li);
            }
            else
            {
                var text_other = date_task.getDate()+'-'+(date_task.getMonth()+1);
                var li = '<li class="li-wrap-task"> <span class="span-check-success"><i class="task-check fa '+icon_checked+'"></i></span> <input type="hidden" class="is_priority" value="'+task.is_priority+'"> <input type="hidden" class="due_t" value="'+date_task.yyyymmddhhmmss()+'"> <input type="hidden" class="is_all" value="'+task.is_all+'"> <input type="hidden" class="id_user" value="1"> <span class="task-date">'+text_other+'</span> <span> <a class="task_desc" data-id="'+task.id+'">'+task.description+'</a> </span> <span class="name-user">'+list_user+'</span> <span class="remove-task" title="remove" data-popup="tooltip"><i class="fa fa-remove"></i></span> </li>';
                $('#ul-list-task').append(li);
            }
        }
        else{
            var d_date = date_task.getDate()+'-'+(date_task.getMonth()+1);
            var li = '<li class="li-wrap-task"> <span class="span-check-success"><i class="task-check fa '+icon_checked+'"></i></span> <input type="hidden" class="is_priority" value="'+task.is_priority+'"> <input type="hidden" class="due_t" value="'+date_task.yyyymmddhhmmss()+'"> <input type="hidden" class="is_all" value="'+task.is_all+'"> <input type="hidden" class="id_user" value="1"> <span class="task-date">'+d_date+'</span> <span> <a class="task_desc" data-id="'+task.id+'">'+task.description+'</a> </span> <span class="name-user">'+list_user+'</span> <span class="remove-task" title="remove" data-popup="tooltip"><i class="fa fa-remove"></i></span> </li>';
            $('#ul-list-task').append(li);
        }
    });
}
function getListUserInTask(task_id, data)
{
    var str_user =[];
    jQuery.each(data, function(i, task_user) {
        if (task_user.task_id == task_id) {
            if (task_user.completed_dt != '0000-00-00 00:00:00') {
                str_user.push('<del>'+task_user.user_id+'</del>');
            }
            else{
                str_user.push(task_user.user_id);
            }
        }
    });
    return str_user;
}
function getTextDate(date)
{
    var date_c = new Date(), y_c = date_c.getFullYear(), m_c = date_c.getMonth(), d_c= date_c.getDate();
    var day_index = date_c.getDay();
    var numday = 7 - day_index;
    var d = new Date(date);
    var date_task = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    // return date_task.toString('yyyy/dd/MM');
    switch(date_task.yyyymmdd()){
        case (new Date(y_c, m_c, d_c)).yyyymmdd():
            return 'Today';
            break;
        case (new Date(y_c, m_c, d_c+1)).yyyymmdd():
            return 'Tomorrow';
            break;
        case (new Date(y_c, m_c, d_c-1)).yyyymmdd():
            return 'Yesterday'
            break;
        case (new Date(y_c, m_c, d_c+2)).yyyymmdd():
            return 'The day after tomorrow'
            break;
        case (new Date(y_c, m_c, d_c+numday)).yyyymmdd():
            return 'This week'
            break;
        case (new Date(y_c, m_c, d_c+numday+7)).yyyymmdd():
            return 'Next week'
            break;
        case (new Date(y_c, m_c+1, 0)).yyyymmdd():
            return 'This month'
            break;
        case (new Date(y_c, m_c+2, 0)).yyyymmdd():
            return 'Next month'
            break;
        case (new Date(y_c+10, 11, 0)).yyyymmdd():
            return 'Any time'
            break;
        default:
            return 'detail';
    }
}
Date.prototype.yyyymmdd = function() {
   var yyyy = this.getFullYear();
   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
   return yyyy+"-"+mm+"-"+dd;
  };
Date.prototype.yyyymmddhhmmss = function() {
   var yyyy = this.getFullYear();
   var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
   var dd  = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
   var hh = this.getHours() < 10 ? "0" + this.getHours() : this.getHours();
   var min = this.getMinutes() < 10 ? "0" + this.getMinutes() : this.getMinutes();
   var ss = this.getSeconds() < 10 ? "0" + this.getSeconds() : this.getSeconds();
   return yyyy+"-"+mm+"-"+dd+" "+hh+":"+min+":"+ss;
  };
function getDataSource()
{
    $.ajax({
        url: "/appbasic/web/task/data_task",
        type:'GET',
        data: {id:1},
        success:function(response)
        {
            // alert(response);
            var obj = JSON.parse(response);
            data_source_task = obj.task;
            data_source_task_user = obj.taskUser;
        },
        error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
    });
}