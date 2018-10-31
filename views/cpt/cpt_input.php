<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css', ['depends' => ['app\assets\MainAsset']]);
$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css', ['depends' => ['app\assets\MainAsset']]);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$currency = [
		'VND' => 'VND',
		'USD' => 'USD',
	];

if (Yii::$app->session->getFlash('success') != '') {
   echo '<div class="alert alert-success no-border">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
    <span class="text-semibold">' . Yii::$app->session->getFlash('success') . '!</span></div>';
}
if (Yii::$app->session->getFlash('err') != '') {
   echo '<div class="alert alert-danger no-border">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <span class="text-semibold">' . Yii::$app->session->getFlash('err') .']</span></div>';
}
?>

<div class="">
	<form id="cptForm" method="post" action="" class="form-horizontal">
	    <div class="form-group">
	        <!-- <label class="col-xs-1 control-label">Task(s)</label> -->
	        <div class="col-xs-2 dateContainer">
	            <div class="input-group input-append date" id="payDatePicker">
	                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
	                <input type="text" class="form-control" name="payDate[]" placeholder="Pay date" />
	            </div>
	        </div>
	        <div class="col-md-3">
				<div class="form-group">
					<input type="text" name="amount[]" class="form-control" placeholder="Amount">
				</div>
			</div>
			<div class="col-md-1">
				<div class="form-group">
					<?= Html::dropDownList('currency[]', '', $currency, ['class' => 'form-control'])?>
				</div>
			</div>
	        <div class="col-xs-5">
	            <input type="text" class="form-control" name="content[]" placeholder="Content" />
	        </div>
	        <div class="col-xs-1">
	            <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
	        </div>
	    </div>

	    <!-- The template for adding new field -->
	    <div class="form-group hide" id="taskTemplate">
	        <!-- <label class="col-xs-1 control-label">Task(s)</label> -->
	        <div class="col-xs-2 dateContainer">
	            <div class="input-group input-append date" id="payDatePicker">
	                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
	                <input type="text" class="form-control" name="payDate[]" placeholder="Pay date" />
	            </div>
	        </div>
	        <div class="col-md-3">
				<div class="form-group">
					<input type="text" name="amount[]" class="form-control" placeholder="Amount">
				</div>
			</div>
			<div class="col-md-1">
				<div class="form-group">
					<?= Html::dropDownList('currency[]', '', $currency, ['class' => 'form-control'])?>
				</div>
			</div>
	        <div class="col-xs-5">
	            <input type="text" class="form-control" name="content[]" placeholder="Content" />
	        </div>
	        <div class="col-xs-1">
	            <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
	        </div>
	    </div>

	    <div class="form-group">
	        <div class="col-xs-5 col-xs-offset-5">
	            <button type="submit" class="btn btn-primary" name="save">Submit</button>
	        </div>
	    </div>
	</form>
</div>

<?
$js = <<<TXT
$(document).on('keyup', 'input[name="amount[]"]', function(){
		var number = $(this).val();
		$(this).val(format(number));
});
$(document).on('focus', 'input[name="amount[]"]', function(){
	if ($(this).val() != '') {
		var number = $(this).val();
		$(this).val(format(number));
	}
});
$(document).on('blur', 'input[name="amount[]"]', function(){
		var clicked = $(this);
		var number = $(clicked).val().replace(/,/g, '').replace('$', '');
		$(clicked).val(number);
});
$(document).ready(function() {
    $('#payDatePicker')
        .datepicker({
            format: 'dd/mm/yyyy'
        })
        .on('changeDate', function(evt) {
            // Revalidate the date field
            $('#cptForm').formValidation('revalidateField', $('#payDatePicker').find('[name="payDate[]"]'));
        });

    $('#cptForm')
        .formValidation({
            framework: 'bootstrap',
            fields: {
                'payDate[]': {
                    // row: '.col-xs-3',
                    validators: {
                        notEmpty: {
                            message: 'The pay date is required'
                        },
                        date: {
                            format: 'DD/MM/YYYY',
                            min: new Date(),
                            message: 'The pay date is not valid'
                        }
                    }
                },
                'amount[]': {
                    // row: '.col-md-3',
                    validators: {
                        notEmpty: {
                            message: 'The amount is required'
                        },
                        numeric: {
	                        message: 'The amount must be a number',
	                        transformer: function(field, validatorName, validator) {
	                            var value = field.val();
	                            value = value.replace('$', '');
	                            return value.replace(/,/g, '');
	                        }
	                    }
                    }
                },
                'currency[]': {
                    // row: '.col-md-1',
                    validators: {
                        notEmpty: {
                            message: 'The currency is required'
                        }
                    }
                },
                'content[]': {
                    // row: '.col-xs-4',
                    validators: {
                        notEmpty: {
                            message: 'The content is required'
                        }
                    }
                }
            }
        })

        .on('added.field.fv', function(e, data) {
            if (data.field === 'payDate[]') {
                // The new due date field is just added
                // Create a new date picker
                data.element
                    .parent()
                    .datepicker({
                        format: 'dd/mm/yyyy'
                    })
                    .on('changeDate', function(evt) {
                        // Revalidate the date field
                        $('#cptForm').formValidation('revalidateField', data.element);
                    });
            }
        })

        // Add button click handler
        .on('click', '.addButton', function() {
            var template = $('#taskTemplate'),
                clone    = template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .insertBefore(template);

            // Add new fields
            $('#cptForm')
                .formValidation('addField', clone.find('[name="amount[]"]'))
                .formValidation('addField', clone.find('[name="payDate[]"]'))
                .formValidation('addField', clone.find('[name="currency[]"]'))
                .formValidation('addField', clone.find('[name="content[]"]'))
        })

        // Remove button click handler
        .on('click', '.removeButton', function() {
            var row = $(this).closest('.form-group');

            // Remove fields
            $('#cptForm')
                .formValidation('removeField', row.find('[name="amount[]"]'))
                .formValidation('removeField', row.find('[name="payDate[]"]'))
                .formValidation('removeField', row.find('[name="currency[]"]'))
                .formValidation('removeField', row.find('[name="content[]"]'));

            // Remove element containing the fields
            row.remove();
        });
});
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
var format = function(num){
    var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
    if(str.indexOf(".") > 0) {
        parts = str.split(".");
        str = parts[0];
    }
    str = str.split("").reverse();
    for(var j = 0, len = str.length; j < len; j++) {
        if(str[j] != ",") {
            output.push(str[j]);
            if(i%3 == 0 && j < (len - 1)) {
                output.push(",");
            }
            i++;
        }
    }
    formatted = output.reverse().join("");
    return("$"+formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};
TXT;
$this->registerJs($js);
