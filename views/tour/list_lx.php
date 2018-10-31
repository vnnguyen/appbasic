
<div class="col-md-12 panel">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($lxs as $lx) {
			?>
				<tr>
					<td><?= $lx->name; ?></td>
					<td><a href="<?php echo Yii::$app->request->baseUrl.'/tour/create_lx'?>" title="">+ Add</a> | <a href="<?php echo Yii::$app->request->baseUrl.'/tour/in_lx/'?>" title="">Edit</a> | <a href="" title="">Delete</a></td>
				</tr>
			<?php
	        }
			?>
			</tbody>
		</table>
	</div>
</div>