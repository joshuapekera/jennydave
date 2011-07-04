
<?php if (!empty($sites)): ?>
	<h3><?php echo lang('site.sites'); ?></h3>

	<table border="0" class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('site.descriptive_name'); ?></th>
				<th><?php echo lang('site.ref'); ?></th>
				<th><?php echo lang('site.domain'); ?></th>
				<th><?php echo lang('site.created_on'); ?></th>
				<th><?php echo lang('site.manage'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($sites as $site): ?>
			<tr>
				<td><?php echo $site->name; ?></td>
				<td><?php echo $site->ref; ?></td>
				<td><a target="_blank" href="http://<?php echo $site->domain; ?>"><?php echo $site->domain; ?></a></td>
				<td><?php echo format_date($site->created_on); ?></td>
				<td class="buttons">
					<?php echo anchor('sites/stats/'.$site->id, lang('site.stats'), 'class="button modal"'); ?>
					<?php echo anchor('sites/addons/index/'.$site->ref, lang('site.addons'), 'class="button"'); ?>
					<?php echo anchor('sites/edit/'.$site->id, 	lang('buttons.edit'), 'class="button"'); ?>
					<?php echo anchor('sites/delete/'.$site->id, 	lang('buttons.delete'), 'class="button modal"'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif;?>