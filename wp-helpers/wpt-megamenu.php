<?php return;

class WptMegamenu
{
	static function post_type($params=[])
	{
		$params = array_merge(['singular' => 'Item', 'plural' => 'Items', 'slug' => 'item'], $params);

	    $labels = array(
	        'name'                  => $params['plural'],
	        'singular_name'         => $params['singular'],
	        'menu_name'             => $params['plural'],
	        'name_admin_bar'        => $params['singular'],
	        'add_new'               => "Novo(a) {$params['singular']}",
	        'add_new_item'          => "Novo(a) {$params['singular']}",
	        'new_item'              => "Novo(a) {$params['singular']}",
	        'edit_item'             => "Editar {$params['singular']}",
	        'view_item'             => "Ver {$params['singular']}",
	        'all_items'             => "Todos os {$params['plural']}",
	        'search_items'          => "Pesquisar {$params['plural']}",
	        'parent_item_colon'     => "Pai {$params['plural']}:",
	        'not_found'             => "Nenhum {$params['plural']} encontrado.",
	        'not_found_in_trash'    => "Nenhum {$params['plural']} encontrado na lixeira.",
	        'featured_image'        => "Imagem de capa de {$params['singular']}",
	        'set_featured_image'    => "Alterar como imagem de capa",
	        'remove_featured_image' => "Remover imagem de capa",
	        'use_featured_image'    => "Usar como imagem de capa",
	        'archives'              => "Arquivos de {$params['singular']}",
	        'insert_into_item'      => "Inserir dentro de {$params['singular']}",
	        'uploaded_to_this_item' => "Enviado para {$params['singular']}",
	        'filter_items_list'     => "Filtrar lista de {$params['plural']}",
	        'items_list_navigation' => "Lista de navegação de {$params['plural']}",
	        'items_list'            => "Lista de {$params['plural']}",
	    );
	 

	    $params = array_merge([
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => $params['slug']),
			// 'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 1,
			'supports'           => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'elementor'],
	    ], $params);
	 
	    register_post_type($params['slug'], $params);
	}
}


// WptMegamenu::post_type([
// 	'slug' => 'megamenu',
// 	'singular' => 'Megamenu',
// 	'plural' => 'Megamenus',
// ]);

$menu_types = [
	(object) [
		'id' => 'wpt-megamenu-query-image',
		'title' => 'wpt-megamenu-query-image',
		'callback' => function() {
			echo 'wpt-megamenu-query-image';
		},
	],
	(object) [
		'id' => 'wpt-megamenu-query-posts',
		'title' => 'wpt-megamenu-query-posts',
		'callback' => function() {
			echo 'wpt-megamenu-query-posts';
		},
	],
	(object) [
		'id' => 'wpt-megamenu-shortcode',
		'title' => 'wpt-megamenu-shortcode',
		'callback' => function() {
			echo 'wpt-megamenu-shortcode';
		},
	],
	(object) [
		'id' => 'wpt-megamenu-auth-form',
		'title' => 'wpt-megamenu-auth-form',
		'callback' => function() {
			echo 'wpt-megamenu-auth-form';
		},
	],
];


add_action('admin_head-nav-menus.php', function() use($menu_types) {
	foreach($menu_types as $type) {
		add_meta_box($type->id, $type->title, function($object, $args) use($type) {
			global $_nav_menu_placeholder, $nav_menu_selected_id;
			?>
			<div class="<?php echo $type->id; ?>" id="<?php echo $type->id; ?>">
				<div id="tabs-panel-<?php echo $type->id; ?>" class="tabs-panel tabs-panel-active">
					<ul id ="<?php echo $type->id; ?>-checklist" class="categorychecklist form-no-clear">
						<li>
							<input type="checkbox" name="menu-item[-1][menu-item-object-id]" value="-1" checked id="<?php echo $type->id; ?>-recheck">
							<input type="text" name="menu-item[-1][menu-item-type]" value="<?php echo $type->id; ?>">
							<input type="text" name="menu-item[-1][menu-item-title]" value="Login">
							<input type="text" name="menu-item[-1][menu-item-url]" value="<?php bloginfo('wpurl'); ?>/wp-login.php">
							<input type="text" name="menu-item[-1][menu-item-classes]" value="wl-login-pop">
						</li>
					</ul>
				</div>

				<p class="button-controls wp-clearfix">
					<span class="add-to-menu">
						<input type="submit" class="button submit-add-to-menu right" value="Adicionar ao menu" name="add-custom-menu-item" id="submit-<?php echo $type->id; ?>">
						<span class="spinner"></span>
					</span>
				</p>
			</div>
			<?php
		}, 'nav-menus', 'side', 'default', $custom_param );
	}


	/*$custom_param = ['This param will be passed to my_render_menu_metabox'];
	add_meta_box('my-menu-test-metabox', 'Test Menu Metabox', function($object, $args) {
		?>
		<div id="my-plugin-div">
			<div id="tabs-panel-my-plugin-all" class="tabs-panel tabs-panel-active">
			<ul id="my-plugin-checklist-pop" class="categorychecklist form-no-clear" >
				<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $my_items ), 0, (object) array( 'walker' => $walker ) ); ?>
			</ul>

			<p class="button-controls">
				<span class="list-controls">
					<a href="<?php
						echo esc_url(add_query_arg(
							array(
								'my-plugin-all' => 'all',
								'selectall' => 1,
							),
							remove_query_arg( $removed_args )
						));
					?>#my-menu-test-metabox" class="select-all"><?php _e( 'Select All' ); ?></a>
				</span>
				<span class="add-to-menu">
					<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu' ); ?>" name="add-my-plugin-menu-item" id="submit-my-plugin-div" />
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}, 'nav-menus', 'side', 'default', $custom_param );*/
});


add_action('admin_init', function() {
	add_meta_box('wpt-megamenu-image', 'Image', function() {
		global $_nav_menu_placeholder, $nav_menu_selected_id;

		return; ?>
		<div id="wpt-megamenu-image">
			<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="wpt-megamenu-image">
			<input type="text" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="">
			<input type="text" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="">
			
			<div class="posttypediv">
				<div class="tabs-panel tabs-panel-active">
					
					<!-- <ul class="categorychecklist form-no-clear">
						<li>
							<label class="menu-item-title">
								<input type="radio" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="-1"> <?php _e( 'Column End', 'custom-menu-item-types' ); ?>
							</label>
						</li>
					</ul> -->
				</div>
			</div>

			<p class="button-controls wp-clearfix">
				<span class="add-to-menu">
					<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'custom-menu-item-types' ); ?>" name="add-custom-menu-item" id="submit-wpt-megamenu-image" />
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}, 'nav-menus', 'side');


	// add_meta_box('wpt-megamenu-query-posts', 'Query Posts', function() {
	// 	global $_nav_menu_placeholder, $nav_menu_selected_id;
	// 	$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
	// 	echo 'item';
	// }, 'nav-menus', 'side');

	// add_meta_box('wpt-megamenu-shortcode', 'Short code', function() {
	// 	global $_nav_menu_placeholder, $nav_menu_selected_id;
	// 	$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
	// 	echo 'item';
	// }, 'nav-menus', 'side');
});