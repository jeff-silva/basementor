<?php

class BasementorDatabase
{
	static function tables() {
		global $wpdb;

		$tables = [];
		foreach($wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}%'") as $row) {
			$table = ['name' => reset(array_values((array) $row))];
			
			$table['fields'] = [];
			foreach($wpdb->get_results("DESCRIBE {$table['name']}") as $field) {
				$table['fields'][] = [
					'name' => $field->Field,
					'type' => $field->Type,
					'null' => $field->Null,
				];
			}

			$table['data'] = [];

			$tables[] = $table;
		}

		return $tables;
	}
}


\Basementor\Basementor::action('basementor-database-table-search', function($post) {
	global $wpdb;
	return $wpdb->get_results("select * from {$post->table}");
});



add_action('admin_menu', function() {
	add_submenu_page('tools.php', 'Database', 'Database', 'manage_options', 'basementor-database', function() {

	$data = new \stdClass;
	$data->table = false;
	$data->tables = \BasementorDatabase::tables();

	?>
	<a href="">refresh</a>
	<br><div id="basementor-database">
		<div class="row no-gutters">
			<div class="col-3" style="overflow-x:hidden; overflow-y:auto; max-height:400px;">
				<table class="table table-bordered" style="min-width:none !important;">
					<tbody>
						<tr v-for="t in tables" :key="t" @click="tableActive(t);">
							<td>{{ t.name }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="col-9">
				<div style="overflow:auto;" v-if="table">
					<table class="table table-bordered m-0">
						<thead>
							<tr>
								<th :colspan="table.fields.length">{{ table.name }}</th>
							</tr>
							<tr>
								<th v-for="f in table.fields">{{ f.name }}</th>
							</tr>
						</thead>

						<tbody>
							<tr v-for="fields in table.data">
								<td v-for="value in fields">{{ value }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<!-- <pre>$data: {{ $data }}</pre> -->
	</div>

	<script>new Vue({
		el: "#basementor-database",
		data: <?php echo json_encode($data); ?>,
		methods: {
			tableActive(table) {
				this.table = table;
				if (this.table.data.length==0) {
					this.tableDataSearch();
				}
			},

			tableDataSearch() {
				var post = {
					table: this.table.name,
				};
				jQuery.post("<?php echo \Basementor\Basementor::action('basementor-database-table-search'); ?>", post, (resp) => {
					this.table.data = resp;
				}, "json");
			},
		},
	});</script>
	<?php

	}, 'dashicons-admin-users', 10);
});
