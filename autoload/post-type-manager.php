<?php

add_action('init', function() {
	$post_types = get_option('basementor-post-types');
	$post_types = json_decode(json_encode($post_types), true);
	$post_types = is_array($post_types)? $post_types: [];
	foreach($post_types as $post_type) {
		register_post_type($post_type['slug'], $post_type);
	}
	// die;
});



\Basementor\Basementor::action('basementor-post-type-save', function($post) {
	$post->post_types = stripslashes($post->post_types);
	$post->post_types = json_decode($post->post_types);
	update_option('basementor-post-types', $post->post_types);
	wp_redirect($_SERVER['HTTP_REFERER']);
	return $post;
});


add_action('admin_menu', function() {
	add_submenu_page('tools.php', 'Post Types', 'Post Types', 'manage_options', 'basementor-post-type', function() {

	$data = new \stdClass;

	$data->post_type_new = ['singular' => '', 'plural' => '', 'slug' => ''];
	$data->post_type_edit = false;
	$data->post_types = get_option('basementor-post-types');
	$data->post_types = is_array($data->post_types)? $data->post_types: [];


	$data->taxonomy_new = ['singular' => '', 'plural' => '', 'slug' => ''];
	$data->taxonomy_edit = false;
	$data->taxonomies = get_option('basementor-taxonomies');
	$data->taxonomies = is_array($data->taxonomies)? $data->taxonomies: [];


	?>
	<br><div id="basementor-post-types">
		<div class="row">
			<div class="col-12 col-md-6">
				<form action="<?php echo \Basementor\Basementor::action('basementor-post-type-save'); ?>" method="post">
					<div class="card">
						<div class="card-header p-2">Post types</div>
						
						<div class="card-body p-2">
							<div class="row">
								<div class="col-4 form-group">
									<label>Singular</label>
									<input type="text" class="form-control" v-model="post_type_new.singular">
								</div>

								<div class="col-4 form-group">
									<label>Plural</label>
									<input type="text" class="form-control" v-model="post_type_new.plural">
								</div>

								<div class="col-4 form-group">
									<label>Slug</label>
									<div class="input-group">
										<input type="text" class="form-control" v-model="post_type_new.slug">
										<div class="input-group-btn">
											<button type="button" class="btn btn-primary" @click="posttypeAdd();">
												<i class="fa fa-fw fa-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</div>

							<ui-modal v-model="post_type_edit">
								<template #body><div style="max-width:500px;">
									<div class="row">
										<div class="col-6 form-group" v-for="l in ['name', 'singular_name', 'menu_name', 'name_admin_bar', 'add_new', 'add_new_item', 'new_item']">
											<labe>{{ l }}</labe>
											<input type="text" class="form-control" v-model="post_type_edit.labels[l]">
										</div>
									</div>

									<select class="form-control" v-model="post_type_edit.menu_position">
										<option value="5">Abaixo de Posts</option>
										<option value="10">Abaixo de Media</option>
										<option value="15">Abaixo de Links</option>
										<option value="20">Abaixo de Pages</option>
										<option value="25">Abaixo de comments</option>
										<option value="60">Abaixo de first separator</option>
										<option value="65">Abaixo de Plugins</option>
										<option value="70">Abaixo de Users</option>
										<option value="75">Abaixo de Tools</option>
										<option value="80">Abaixo de Settings</option>
										<option value="100">Abaixo de second separator</option>
									</select>

									<checkbox v-model="post_type_edit.public">public</checkbox>
									<checkbox v-model="post_type_edit.publicly_queryable">publicly_queryable</checkbox>
									<checkbox v-model="post_type_edit.show_ui">show_ui</checkbox>
									<checkbox v-model="post_type_edit.show_in_menu">show_in_menu</checkbox>
									<checkbox v-model="post_type_edit.query_var">query_var</checkbox>
									<checkbox v-model="post_type_edit.has_archive">has_archive</checkbox>
									<checkbox v-model="post_type_edit.hierarchical">hierarchical</checkbox>

									<div class="form-group">
										<label>Suporte:</label>
										
										<label class="input-group" v-for="s in ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'elementor']">
											<span class="input-group-prepend">
												<span class="input-group-text">
													<input type="checkbox" v-model="post_type_edit.supports" :value="s">
												</span>
											</span>
											<span class="form-control">{{ s }}</span>
										</label>
									</div>
								</div></template>
							</ui-modal>

							<table class="table table-bordered">
								<colgroup>
									<col width="*">
									<col width="50px">
									<col width="50px">
								</colgroup>
								<tbody>
									<tr v-for="pt in post_types" :key="pt">
										<td>{{ pt.labels.name }}</td>
										<td class="p-1"><a href="javascript:;" class="btn btn-primary" @click="post_type_edit=pt;"><i class="fa fa-fw fa-pencil"></i></a></td>
										<td class="p-1"><a href="javascript:;" class="btn btn-danger" @click="posttypeRemove(pt);"><i class="fa fa-fw fa-remove"></i></a></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="card-footer p-2 text-right">
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-fw fa-save"></i> Salvar
							</button>
						</div>
					</div>
					<textarea name="post_types" style="display:none;">{{ post_types }}</textarea>
				</form>
			</div>
			
			<div class="col-12 col-md-6">
				<form action="<?php echo \Basementor\Basementor::action('basementor-taxonomy-save'); ?>" method="post">
					<div class="card">
						<div class="card-header p-2">Taxonomias</div>
						<div class="card-body p-2">
							<div class="row">
								<div class="col-4 form-group">
									<label>Singular</label>
									<input type="text" class="form-control" v-model="taxonomy_new.singular">
								</div>

								<div class="col-4 form-group">
									<label>Plural</label>
									<input type="text" class="form-control" v-model="taxonomy_new.plural">
								</div>

								<div class="col-4 form-group">
									<label>Slug</label>
									<div class="input-group">
										<input type="text" class="form-control" v-model="taxonomy_new.slug">
										<div class="input-group-btn">
											<button type="button" class="btn btn-primary" @click="taxonomyAdd();">
												<i class="fa fa-fw fa-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</div>

							<ui-modal v-model="taxonomy_edit">
								<pre>taxonomy_edit: {{ taxonomy_edit }}</pre>
							</ui-modal>

							<table class="table table-bordered">
								<colgroup>
									<col width="*">
									<col width="50px">
									<col width="50px">
								</colgroup>
								<tbody>
									<tr v-for="t in taxonomies" :key="t">
										<td>{{ t.labels.name }}</td>
										<td class="p-1"><a href="javascript:;" class="btn btn-primary" @click="taxonomy_edit=t;"><i class="fa fa-fw fa-pencil"></i></a></td>
										<td class="p-1"><a href="javascript:;" class="btn btn-danger" @click="taxonomyRemove(pt);"><i class="fa fa-fw fa-remove"></i></a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="card-footer p-2 text-right">
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-fw fa-save"></i> Salvar
							</button>
						</div>
					</div>
					<textarea name="taxonomies" style="display:block;">{{ taxonomies }}</textarea>
				</form>
			</div>
		</div>
	</div>

	<script>
	Vue.component("ui-modal", {
		props: {
			value: {default:''},
		},

		methods: {
			valueEmit(value) {
				this.value = value;
				this.$emit('input', this.value);
				this.$emit('value', this.value);
				this.$emit('change', this.value);
			},
		},

		template: `<div>
			<transition name="ui-modal-transition"
				enter-active-class="animated fadeIn"
				leave-active-class="animated fadeOut"
			>
				<div v-if="value" style="position:fixed; top:0px; left:0px; width:100%; height:100%; background:#00000088; z-index:9; animation-diration:300ms !important; display:flex; align-items:center; justify-content:center;" @click.self="valueEmit(false);">
					<div class="card" style="min-width:400px;">
						<div class="card-header" v-if="$slots.header">
							<slot name="header">Header</slot>
						</div>

						<div class="card-body" style="max-height:80vh; overflow:auto;">
							<slot name="body">body</slot>
						</div>

						<div class="card-footer text-right">
							<slot name="footer">
								<button type="button" class="btn btn-secondary" @click="valueEmit('');">Fechar</button>
							</slot>
						</div>
					</div>
				</div>
			</transition>
		</div>`,
	});

	new Vue({
		el: "#basementor-post-types",
		data: <?php echo json_encode($data); ?>,
		methods: {
			posttypeAdd() {
				this.post_types.push({
					slug: this.post_type_new.slug,
					label: `${this.post_type_new.plural}`,
					labels: {
						name: `${this.post_type_new.plural}`,
						singular_name: `${this.post_type_new.singular}`,
						menu_name: `${this.post_type_new.plural}`,
						name_admin_bar: `${this.post_type_new.singular}`,
						add_new: `Novo(a) ${this.post_type_new.singular}`,
						add_new_item: `Novo(a) ${this.post_type_new.singular}`,
						new_item: `Novo(a) ${this.post_type_new.singular}`,
						edit_item: `Editar ${this.post_type_new.singular}`,
						view_item: `Ver ${this.post_type_new.singular}`,
						all_items: `Todos os ${this.post_type_new.plural}`,
						search_items: `Pesquisar ${this.post_type_new.plural}`,
						parent_item_colon: `Pai ${this.post_type_new.plural}:`,
						not_found: `Nenhum ${this.post_type_new.plural} encontrado.`,
						not_found_in_trash: `Nenhum ${this.post_type_new.plural} encontrado na lixeira.`,
						featured_image: `Imagem de capa de ${this.post_type_new.singular}`,
						set_featured_image: `Alterar como imagem de capa`,
						remove_featured_image: `Remover imagem de capa`,
						use_featured_image: `Usar como imagem de capa`,
						archives: `Arquivos de ${this.post_type_new.singular}`,
						insert_into_item: `Inserir dentro de ${this.post_type_new.singular}`,
						uploaded_to_this_item: `Enviado para ${this.post_type_new.singular}`,
						filter_items_list: `Filtrar lista de ${this.post_type_new.plural}`,
						items_list_navigation: `Lista de navegação de ${this.post_type_new.plural}`,
						items_list: `Lista de ${this.post_type_new.plural}`,
					},
					capability_type: 'post',
					public: true,
					publicly_queryable: true,
					show_ui: true,
					show_in_menu: true,
					query_var: true,
					rewrite: {slug: this.post_type_new.slug},
					has_archive: true,
					hierarchical: false,
					menu_position: 5,
					supports: ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'elementor'],
				});
				this.post_type_new.singular = "";
				this.post_type_new.plural = "";
				this.post_type_new.slug = "";
			},

			posttypeRemove(ptype) {
				if (! confirm('Confirmar a ação de deletar post type?')) return;
				var index = this.post_types.indexOf(ptype);
				this.post_types.splice(index, 1);
			},


			taxonomyAdd() {
				this.taxonomies.push({
					hierarchical: true,
					public: true,
					show_ui: true,
					show_admin_column: true,
					query_var: true,
					rewrite: {slug:this.taxonomy_new.slug},
					labels: {
						name: `Categorias de ${this.taxonomy_new.singular}`,
						singular_name: `${this.taxonomy_new.singular}`,
						search_items: `Procurar ${this.taxonomy_new.singular}`,
						all_items: `Todas as ${this.taxonomy_new.plural}`,
						parent_item: ``,
						parent_item_colon: ``,
						edit_item: `Editar ${this.taxonomy_new.singular}`,
						update_item: ``,
						add_new_item: `Adicionar ${this.taxonomy_new.singular}`,
						new_item_name: `Nova ${this.taxonomy_new.singular}`,
						menu_name: `Categorias`,
					},
				});
				this.taxonomy_new.singular = "";
				this.taxonomy_new.plural = "";
				this.taxonomy_new.slug = "";
			},
		},

		components: {
			checkbox: {
				props: {
					value: {default: false},
					trueValue: {default: true},
					falseValue: {default: false},
				},

				template: `<label class="input-group">
					<span class="input-group-prepend">
						<span class="input-group-text">
							<input type="checkbox" v-model="value" true-value="1" false-value="" @change="$emit('input', value);">
						</span>
					</span>
					<span class="form-control"><slot></slot></span>
				</label>`,
			},
		},
	});</script>
	<?php

	}, 'dashicons-admin-users', 10);
});