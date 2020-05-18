<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

global $elementor_typeform_validations;


$elementor_typeform_validations = [
	'required' => [
		'name' => 'Obrigatório',
		'error' => function($field) {
			return $field->value? false: 'Este campo é obrigatório';
		},
	],

	'email' => [
		'name' => 'E-mail',
		'error' => function($field) {
			if (! filter_var($field->value, FILTER_VALIDATE_EMAIL)) {
				return 'E-mail inválido';
			}
		},
	],

	'exists' => [
		'name' => 'Dado já existente',
		'error' => function($field) {
			// 
		},
	],

	'cpf' => [
		'name' => 'CPF',
		'error' => function($field) {
			// 
		},
	],

	'cnpj' => [
		'name' => 'CNPJ',
		'error' => function($field) {
			// 
		},
	],

	'alphanumeric' => [
		'name' => 'Alphanumérico',
		'error' => function($field) {
			// 
		},
	],

	'date' => [
		'name' => 'Data válida',
		'error' => function($field) {
			// 
		},
	],
];


function elementor_typeform_post($post=[]) {
	// dd($post); die;
	global $elementor_typeform_validations;

	$post = (object) array_merge([
		'action' => '',
		'index' => 0,
		'new_index' => 0,
		'fields' => [],
	], $post);

	$post->fields = is_array($post->fields)? $post->fields: [];
	$post->save = [];
	$post->input = [];

	if ($post->action=='user-register') {
		$post->save = [
			'user_pass' => '',
			'user_login' => '',
			'user_nicename' => '',
			'user_url' => '',
			'user_email' => '',
			'display_name' => '',
			'nickname' => '',
			'first_name' => '',
			'last_name' => '',
			'description' => '',
			'meta' => [],
		];
	}



	// prepare $inputs and $save
	foreach($post->fields as $i=>$field) {
		$field = (object) $field;
		if (! $field->name) continue;

		$post->input[ $field->name ] = $field->value;

		if (isset($post->save[ $field->name ])) {
			$post->save[ $field->name ] = $field->value;
		}
		else {
			$post->save['meta'][ $field->name ] = $field->value;
		}
	}


	$_parse = function($model) use($post) {
		$return = $model;
		foreach($post->input as $key=>$value) {
			$return = str_replace("\${$key}", $value, $return);
		}
		return $return;
	};
	



	// prebuilt fields data
	foreach($post->fields as $i=>$field) {
		$field = (object) $field;
		$field->error = '';
		$field->description_parse = $_parse($field->description);

		$field->options = isset($field->options)? $field->options: [];
		if (! is_array($field->options)) {
			$field->options = array_values(array_filter(explode("\n", $field->options), 'strlen'));
			$field->options = array_map(function($opt) {
				list($value, $text) = explode('|', $opt);
				return [
					'value' => ($value),
					'text' => ($text? $text: $value),
				];
			}, $field->options);
		}


		$field->hidden = '';
		if ($field->condition_show = stripslashes($field->condition_show)) {
			$field->hidden = call_user_func(function($field, $input) {
				try {
					extract($input);
					$eval = "return {$field->condition_show} ;";
					$eval = @eval($eval);
					return $eval? '': '1';
				}
				catch(\ParseError $e) {
					return $e->getMessage();
				}
				return true;
			}, $field, $post->input);
		}

		$post->fields[$i] = $field;
	}


	$field = isset($post->fields[$post->index])? $post->fields[$post->index]: false;
	if ($field) {
		if ($field->hidden==1) {
			$post->index = $post->index+1;
			return elementor_typeform_post((array) $post);
		}
	}


	if ($field) {
		$field = (object) $field;

		if ($post->try) {
			$field->error = [];
			foreach($field->validate as $rule) {
				$field->test = isset($elementor_typeform_validations[$rule]);
				if (isset($elementor_typeform_validations[$rule]) AND $err = call_user_func($elementor_typeform_validations[$rule]['error'], $field)) {
					$field->error[] = $err;
				}
			}
			$field->error = implode('<br>', $field->error);
		}

		$post->fields[ $post->index ] = $field;

		if (! $field->error AND $post->try) {
			$post->index = $post->new_index;
		}
	}


	$post->try = $post->try? $post->try: 0;
	$post->try++;
	return $post;
}


if (isset($_GET['elementor-typeform-submit'])) {
	add_action('init', function() {
		$post = elementor_typeform_post($_POST);
		echo json_encode($post); die;
	});
}


add_action('elementor/widgets/widgets_registered', function($manager) {
	if (class_exists('Elementor_Typeform')) return;
	
	class Elementor_Typeform extends \Elementor\Widget_Base {

		public function get_name() {
			return __CLASS__;
		}

		public function get_title() {
			return preg_replace('/[^a-zA-Z0-9]/', ' ', __CLASS__);
		}

		// https://ecomfe.github.io/eicons/demo/demo.html
		public function get_icon() {
			return 'eicon-editor-code';
		}

		public function get_categories() {
			return [ 'general' ];
		}

		public function get_script_depends() {
			return [];
		}

		public function get_style_depends() {
			return [];
		}

		protected function _register_controls() {
			global $elementor_typeform_validations;

			$this->start_controls_section('section_heading', [
				'label' => 'Configurações',
			]);

			$this->add_control('action', [
				'label' => 'Tipo de ação',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'label_block' => true,
				'options' => [
					'user-register' => 'Cadastro de usuário',
					'post-insert' => 'Post',
				],
			]);

			// $this->add_control('intro_text', [
			// 	'label' => 'Apresentação',
			// 	'type' => \Elementor\Controls_Manager::WYSIWYG,
			// 	'default' => '',
			// 	'label_block' => true,
			// ]);

			// $this->add_control('intro_btn_label', [
			// 	'label' => 'Texto do botão da introdução',
			// 	'type' => \Elementor\Controls_Manager::TEXT,
			// 	'default' => 'Prosseguir',
			// 	'label_block' => true,
			// ]);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control('type', [
				'label' => 'Tipo de campo',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'text',
				'options' => [
					'text' => 'Texto',
					'textarea' => 'Texto multilinha',
					'password' => 'Senha',
					'select' => 'Seleção',
					'radio' => 'Radio',
					'date' => 'Data',
					'br-states' => 'Estados brasileiros',
					'content' => 'Conteúdo (sem input)',
				],
			]);

			$repeater->add_control('name', [
				'label' => 'Atributo "NAME" da input',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'condition' => [
					'type!' => ['content'],
				],
			]);

			$repeater->add_control('description', [
				'label' => 'Texto',
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('value', [
				'label' => 'Valor inicial',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'type!' => ['content'],
				],
			]);

			$repeater->add_control('placeholder', [
				'label' => 'Placeholder',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'condition' => [
					'type!' => ['content'],
				],
			]);

			$repeater->add_control('options', [
				'label' => 'Opções (para select e radio, um item por linha)',
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'condition' => [
					'type' => ['select', 'radio'],
				],
			]);

			$validate_opts = [];
			foreach($elementor_typeform_validations as $id=>$valid) {
				$validate_opts[ $id ] = $valid['name'];
			}

			$repeater->add_control('validate', [
				'label' => 'Validação',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'default' => [],
				'options' => $validate_opts,
				'condition' => [
					'type!' => ['content'],
				],
			]);

			$repeater->add_control('mask', [
				'label' => 'Máscara',
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'condition' => [
					'type' => ['text'],
				],
			]);

			$repeater->add_control('condition_show', [
				'label' => 'Condition',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('btn_label', [
				'label' => 'Texto do botão "Prosseguir"',
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Prosseguir',
				'label_block' => true,
			]);

			$this->add_control('fields', [
				'label' => 'Campos',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [],
				'title_field' => '{{{ name? ("$"+name+" | "): "" }}} {{{ condition_show }}} {{{ description }}}',
			]);

			$this->add_control('css', [
				'label' => 'CSS',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '$root {}',
			]);

			$this->add_control('fields_test', [
				'label' => 'Ativar teste',
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			]);

			$this->add_control('fields_index', [
				'label' => 'Índice (apenas para teste)',
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => -1,
				'label_block' => true,
			]);

			$this->end_controls_section();
		}

		protected function render() {
			$set = json_decode(json_encode($this->get_settings()));
			
			$data = new \stdClass;
			$data->id = uniqid('elementor-typeform-');
			$data->loading = false;

			$data->post = [
				'index' => 0,
				'action' => $set->action,
				'fields' => $set->fields,
			];

			if (\Elementor\Plugin::$instance->editor->is_edit_mode() AND $set->fields_test) {
				$data->post['index'] = $set->fields_index;
			}

			$data->post = elementor_typeform_post($data->post);

			?>
			<!-- vue-the-mask -->
			<script src="https://cdn.jsdelivr.net/npm/vue-the-mask@0.11.1"></script>
			<!-- <script>Vue.directive("mask", VueTheMask);</script> -->

			<!-- vue-slider -->
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/theme/default.css">
			<script src="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/dist/vue-slider-component.umd.min.js"></script>
			<script>Vue.component("vue-slider",  window['vue-slider-component']);</script>

			<!-- flatpickr -->
			<link href="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/flatpickr.min.css" rel="stylesheet">
			<script src="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/flatpickr.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/vue-flatpickr-component@8"></script>
			<script src="https://npmcdn.com/flatpickr@4.6.3/dist/l10n/pt.js"></script>
			<script>Vue.component("flatpickr", window.VueFlatpickr);</script>

			<style>
			.elementor-typeform {position:relative; max-width:400px; margin:0 auto;}
			/*.elementor-typeform-center {display: flex; align-items: center; justify-content: center; min-height:400px;}*/
			.elementor-typeform-field-each {}
			<?php echo str_replace('$root', ".{$data->id}", $set->css); ?>
			</style>

			<div class="<?php echo $data->id; ?> elementor-typeform" id="<?php echo $data->id; ?>">
				<form action="" @submit.prevent="submit('+');">
					<div class="elementor-typeform-center elementor-typeform-field-each" v-for="(f, findex) in post.fields" :key="f" v-if="findex==post.index">
						<div>
							<div class="mb-3" v-html="f.description_parse"></div>

							<div v-if="f.name">
								<div v-if="f.type=='text' || f.type=='password'">
									<input :type="f.type" class="form-control" v-model="f.value" :placeholder="f.placeholder" v-if="f.mask" v-mask="f.mask">
									<input :type="f.type" class="form-control" v-model="f.value" :placeholder="f.placeholder" v-else>
								</div>

								<div v-else-if="f.type=='select'">
									<select class="form-control" v-model="f.value">
										<option value="">{{ f.placeholder || 'Selecione' }}</option>
										<option :value="opt.value" v-for="opt in f.options||[]">{{ opt.text }}</option>
									</select>
								</div>

								<div v-else-if="f.type=='textarea'">
									<textarea class="form-control" :placeholder="f.placeholder" v-model="f.value"></textarea>
								</div>

								<div v-else-if="f.type=='br-states'">
									<select class="form-control" v-model="f.value">
										<option value="">{{ f.placeholder || 'Selecione' }}</option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espírito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MT">Mato Grosso</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
									</select>
								</div>

								<div v-else-if="f.type=='radio'">
									<label v-for="opt in f.options||[]" class="d-block py-1">
										<input type="radio" :name="f._id" :value="opt.value" v-model="f.value"> {{ opt.text }}
									</label>
								</div>

								<div v-else-if="f.type=='date'">
									<flatpickr class="form-control"
										v-model="f.value"
										:placeholder="f.placeholder"
										:config='{
											altInput: true,
											altFormat: "d/m/Y",
											locale: "pt",
										}'
									></flatpickr>
								</div>

								<div v-else-if="f.type=='content'">
									&nbsp;
								</div>

								<div v-else>
									<component
										:is="f.type"
										v-model="f.value"
										:placeholder="f.placeholder"
									></component>
								</div>

								<div v-if="f.error" v-html="f.error" class="text-danger"></div>
							</div>

							<br><br>

							<div class="row no-gutters" style="max-width:400px; margin:0 auto;">
								<div class="col-6">
									<a href="javascript:;" class="btn btn-link" @click="submit('-');" v-if="findex>0">Voltar</a>
								</div>
								<div class="col-6 text-right">
									<a href="javascript:;" class="btn btn-primary" @click="submit('+');">
										<i class="fa fa-fw fa-spin fa-spinner" v-if="loading"></i>
										<span v-html="f.btn_label"></span>
									</a>
								</div>
							</div>

							<!-- <pre>field: {{ f }}</pre> -->
						</div>
					</div>
				</form>

				<div v-for="(f, findex) in post.fields" class="border border-primary mt-2 p-2" :class="{'bg-primary text-light':(findex==post.index)}">
					<div>type: {{ f.type }}</div>
					<div>${{ f.name }} = '{{ f.value }}';</div>
					<div>condition_show: {{ f.condition_show }}</div>
					<div>hidden: {{ f.hidden }}</div>
				</div>

				<pre>post: {{ post }}</pre>
			</div>

			<script>new Vue({
				el: "#<?php echo $data->id; ?>",
				data: <?php echo json_encode($data); ?>,

				filters: {
					varparse: function(value, items) {
						for(var i in items) {
							var item = items[i];
							if (!item.name) continue;
							value = value.replace(`\{\{ ${item.name} \}\}`, item.value);
						}
						return value;
					},
				},

				methods: {
					submit(index) {
						var $=jQuery, $el=$(this.$el);

						if (index=='+') { this.post.new_index = 1+parseInt(this.post.index); }
						else if (index=='-') { this.post.new_index = 1-parseInt(this.post.index); }
						else { this.post.new_index = index; }


						$el.css({opacity:.7});
						this.loading = true;
						$.post('?elementor-typeform-submit', this.post, (resp) => {
							this.loading = false;
							this.$set(this, 'post', resp);
							$el.css({opacity:1});
							setTimeout(() => {
								$el.find(":input:visible").focus();
							}, 100);
						}, "json");
					},
				},
			});</script>
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Typeform());
});
