<?php
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
$baseUrl = Yii::$app->request->baseUrl;
$this->registerCssFile($baseUrl.'/css/pnotify.custom.min.css');
$this->registerJsFile($baseUrl.'/js/pnotify.custom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
// $this->registerCssFile($baseUrl.'/css/processBar.css');
$this->registerCss('
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
');
$this->registerCss('
    .chart {
      width: 100%;
      min-height: 450px;
      margin-top: 10px
    }

');
$this->registerJsFile('https://www.gstatic.com/charts/loader.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.autocomplete.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/report.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<div class="row wrap-search">
  <div class="col-md-12">
    <div id="search_div" class="search">
        <form class="form-inline panel-search" id="formx">
        <?= Html::textInput('txt_search', '', ['class'=>'form-control', 'placeholder'=>'Search']) ?>
        <?= Html::dropdownList('currency', '', [], ['class'=>'form-control', 'placeholder'=>'Select'])?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '#', ['id' => 'reset_btn', 'class' => 'btn btn-default']) ?>
        </form>
    </div>
  </div>
</div>
<div class="row wrap-chart">
  <div class="col-md-12">
    <div id="chart_div" class="chart"></div>
  </div>
</div>
<div class="panel panel-default">
    <div class="table-responsive">
        <table id="tbl-report" class="table table-xxs table-bordered table-condensed table-striped">
            <thead>
        		<tr>
        			<th>Status</th>
        			<th>Total</th>
        			<th>1</th>
        			<th>2</th>
        			<th>3</th>
        			<th>4</th>
        			<th>5</th>
        			<th>6</th>
        			<th>7</th>
        			<th>8</th>
        			<th>9</th>
        			<th>10</th>
        			<th>11</th>
        			<th>12</th>
        		</tr>
        	</thead>
        	<tbody>
        		<tr>
        			<td>Confirmed</td>
                    <?php
                    $total_ct = 0;
                    foreach ($cts as $ct) {
                        $total_ct += $ct['cnt'];
                    }
                    ?>
        			<td><?= $total_ct?></td>
        			<?php
                        for($i = 1; $i <= 12; $i++) {
                            $status = false;
                            foreach ($cts as $ct) {
                                if ($ct['m'] == $i) {
                                    $status = true;
                                    echo "<td>".$ct['cnt']."</td>";
                                }
                            }
                            if (!$status) {
                                echo "<td>0</td>";
                            }
                        }
                    ?>
        		</tr>
        		<tr>
        			<td>Pending</td>
        			<?php
                    $total_case = 0;
                    foreach ($cases as $case) {
                        $total_case += $cases['cnt'];
                    }
                    ?>
                    <td><?= $total_case?></td>
                    <?php
                        for($i = 1; $i <= 12; $i++) {
                            $status = false;
                            foreach ($cases as $case) {
                                if ($case['m'] == $i) {
                                    $status = true;
                                    echo "<td>".$case['cnt']."</td>";
                                }
                            }
                            if (!$status) {
                                echo "<td>0</td>";
                            }
                        }
                    ?>
        		</tr>
        	</tbody>
        </table>