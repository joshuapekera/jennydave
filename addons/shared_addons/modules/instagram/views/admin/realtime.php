<section class="title">
	<!-- We'll use $this->method to switch between sample.create & sample.edit -->
	<h4><?php echo lang('instagram:realtime'); ?></h4>
</section>

<section class="item">

	<div class="content">

		<p>Please be patient with the data on this page. It currently reads live Instagram API data without a cache.</p>

		<table id="forms" style="margin:0 0 20px 0;">
			<thead>
				<tr>
					<th>ID</th>
					<th>Object</th>
					<th>Object ID</th>
					<th>Aspect</th>
					<th>Type</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if($subscriptions): foreach($subscriptions as $sub): ?>
				<tr>
					<td><?php echo $sub['id'];?></td>
					<td><?php echo $sub['object'];?></td>
					<td><?php echo $sub['object_id'];?></td>
					<td><?php echo $sub['aspect'];?></td>
					<td><?php echo $sub['type'];?></td>
					<td class="actions">
						<?php echo anchor('admin/'.$this->module_details['slug'].'/realtime/delete/'.$sub['id'].'/'.$sub['object'].'/'.$sub['object_id'], 'Delete', 'class="button"');?>
					</td>
				</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>		

		<?php echo anchor('admin/instagram/realtime/add_subscription', 'Add Subscription', 'class="btn blue"'); ?>

	</div>

</section>