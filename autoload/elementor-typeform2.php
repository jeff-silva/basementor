<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

function elementor_typeform_validate($field, $value, $form_action_type) {
	$error = [];
	if (isset($field->validate) AND is_array($field->validate)) {
		foreach(elementor_typeform_validations() as $rule=>$valid) {
			if (in_array($rule, $field->validate)) {
				if ($err = call_user_func($valid['error'], $value, $field->name, $form_action_type)) {
					$error[] = $err;
				}
			}
		}
	}
	return empty($error)? null: implode('<br>', $error);
}

function elementor_typeform_validations() {
	return [
		'required' => [
			'name' => 'Obrigatório',
			'error' => function($value, $name, $form_action_type) {
				return $value? false: 'Este campo é obrigatório';
			},
		],

		'email' => [
			'name' => 'E-mail',
			'error' => function($value, $name, $form_action_type) {
				if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
					return 'E-mail inválido';
				}
			},
		],

		'exists' => [
			'name' => 'Dado já existente',
			'error' => function($value, $name, $form_action_type) {
				global $wpdb;

				if ($form_action_type=='user-register') {
					$sql = "select * from {$wpdb->usermeta} where meta_key='{$name}' and meta_value='{$value}' ";

					if ($name=='user_email') {
						$sql = "select * from {$wpdb->users} where user_email='{$value}' ";
					}
				}

				return sizeof($wpdb->get_results($sql))? 'Registro já encontrado no sistema': null;
			},
		],

		'cpf' => [
			'name' => 'CPF',
			'error' => function($value, $name, $form_action_type) {
				$erro = 'CPF inválido';
				$value = preg_replace('/[^0-9]/is', '', $value);
				if (strlen($value) != 11) { return $erro; }
				if (preg_match('/(\d)\1{10}/', $value)) { return $erro; }
				for ($t = 9; $t < 11; $t++) {
				    for ($d = 0, $c = 0; $c < $t; $c++) { $d += $value{$c} * (($t + 1) - $c); }
				    $d = ((10 * $d) % 11) % 10;
				    if ($value{$c} != $d) { return $erro; }
				}
				return null;
			},
		],

		'cnpj' => [
			'name' => 'CNPJ',
			'error' => function($value, $name, $form_action_type) {
				$erro = 'CNPJ inválido';
				$value = preg_replace("/[^0-9]/", '', $value);
				$value = str_pad($value, 14, '0', STR_PAD_LEFT);
				if (strlen($value) != 14) { return $erro; }
				else if ($value == '00000000000000' || 
					$value == '11111111111111' || 
					$value == '22222222222222' || 
					$value == '33333333333333' || 
					$value == '44444444444444' || 
					$value == '55555555555555' || 
					$value == '66666666666666' || 
					$value == '77777777777777' || 
					$value == '88888888888888' || 
					$value == '99999999999999') { return $erro; }
				else {   
					$j = 5; $k = 6; $soma1 = ''; $soma2 = '';
					for ($i = 0; $i < 13; $i++) {

						$j = $j == 1 ? 9 : $j;
						$k = $k == 1 ? 9 : $k;

						$soma2 += ($value{$i} * $k);

						if ($i < 12) {
							$soma1 += ($value{$i} * $j);
						}

						$k--;
						$j--;

					}

					$digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
					$digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
					return (($value{12} == $digito1) and ($value{13} == $digito2))? null: $erro;
				 
				}
			},
		],

		'alphanumeric' => [
			'name' => 'Alphanumérico',
			'error' => function($value, $name, $form_action_type) {
				// 
			},
		],

		'date' => [
			'name' => 'Data válida',
			'error' => function($value, $name, $form_action_type) {
				// 
			},
		],
	];
}



function elementor_typeform_inputs() {
	return [
		'content' => [
			'name' => 'Conteúdo HTML',
			'content' => function() { return '&nbsp;'; },
		],

		'text' => [
			'name' => 'Texto linha única',
			'content' => function() { ob_start(); ?>
			<input :type="f.type" class="form-control" v-model="output.input[f.name]" :placeholder="f.placeholder" v-if="f.mask" v-mask="f.mask">
			<input :type="f.type" class="form-control" v-model="output.input[f.name]" :placeholder="f.placeholder" v-else>
			<?php return ob_get_clean(); },
		],

		'password' => [
			'name' => 'Senha',
			'content' => function() { ob_start(); ?>
			<input :type="f.type" class="form-control" v-model="output.input[f.name]" :placeholder="f.placeholder">
			<?php return ob_get_clean(); },
		],

		'phone' => [
			'name' => 'Telefone',
			'content' => function() { ob_start(); ?>
			<input :type="f.type" class="form-control" v-model="output.input[f.name]" :placeholder="f.placeholder" v-mask="['(##) ####-####', '(##) #####-####']">
			<?php return ob_get_clean(); },
		],

		'select' => [
			'name' => 'Caixa de seleção',
			'content' => function() { ob_start(); ?>
			<select class="form-control" v-model="output.input[f.name]">
				<option value="">{{ f.placeholder || 'Selecione' }}</option>
				<option :value="opt.value" v-for="opt in f.options||[]">{{ opt.text }}</option>
			</select>
			<?php return ob_get_clean(); },
		],

		'textarea' => [
			'name' => 'Texto multilinha',
			'content' => function() { ob_start(); ?>
			<textarea class="form-control" :placeholder="f.placeholder" v-model="output.input[f.name]"></textarea>
			<?php return ob_get_clean(); },
		],

		'br-states' => [
			'name' => 'Estados brasileiros',
			'content' => function() { ob_start(); ?>
			<select class="form-control" v-model="output.input[f.name]">
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
			<?php return ob_get_clean(); },
		],

		'radio' => [
			'name' => 'Radio',
			'content' => function() { ob_start(); ?>
			<label v-for="opt in f.options||[]" class="d-block py-1">
				<input type="radio" :name="f._id" :value="opt.value" v-model="output.input[f.name]"> {{ opt.text }}
			</label>
			<?php return ob_get_clean(); },
		],

		'date' => [
			'name' => 'Data',
			'content' => function() { ob_start(); ?>
			<div class="input-group">
				<input type="number" class="form-control" v-model="f.d" @keyup="output.input[f.name]=(f.y||'2000')+'-'+(f.m||'01')+'-'+(f.d||'01')">
				<select class="form-control" v-model="f.m" @change="output.input[f.name]=(f.y||'2000')+'-'+(f.m||'01')+'-'+(f.d||'01')">
					<option value="01">Janeiro</option>
					<option value="02">Fevereiro</option>
					<option value="03">Março</option>
					<option value="04">Abril</option>
					<option value="05">Maio</option>
					<option value="06">Junho</option>
					<option value="07">Julho</option>
					<option value="08">Agosto</option>
					<option value="09">Setembro</option>
					<option value="10">Outubro</option>
					<option value="11">Novembro</option>
					<option value="12">Dezembri</option>
				</select>
				<input type="number" class="form-control" v-model="f.y" @keyup="output.input[f.name]=(f.y||'2000')+'-'+(f.m||'01')+'-'+(f.d||'01')">
			</div>

			<!-- <flatpickr class="form-control"
				v-model="output.input[f.name]"
				:placeholder="f.placeholder"
				:config='{
					altInput: true,
					altFormat: "d/m/Y",
					locale: "pt",
				}'
			></flatpickr> -->
			<?php return ob_get_clean(); },
		],

		'image' => [
			'name' => 'Imagem',
			'content' => function() { ob_start(); ?>
			<input :type="f.type" class="form-control" v-model="output.input[f.name]" :placeholder="f.placeholder" v-mask="['(##) ####-####', '(##) #####-####']">
			<?php return ob_get_clean(); },
		],

		'attachment' => [
			'name' => 'Anexo',
			'content' => function() { ob_start(); ?>
			<input :type="f.type" class="form-control" v-model="output.input[f.name]" :placeholder="f.placeholder" v-mask="['(##) ####-####', '(##) #####-####']">
			<?php return ob_get_clean(); },
		],
	];
}



function elementor_typeform_fields_process($output, $fields=[]) {
	$fields = json_decode(json_encode($fields));

	$output = [
		'form_action_type' => (isset($output['form_action_type'])? $output['form_action_type']: 0),
		'percent' => 0,
		'index' => (isset($output['index'])? $output['index']: 0),
		'goto' => (isset($output['goto'])? $output['goto']: ''),
		'input' => (isset($output['input'])? $output['input']: []),
		'fields' => [],
	];

	$output['index'] = intval($output['index']);

	$save = [];
	if ($output['form_action_type']=='user-register') {
		$save = [
			'user_login' => '',
			'user_pass' => '',
			'user_nicename' => '',
			'user_email' => '',
			'display_name' => '',
			'role' => '',
			'meta_input' => [],
		];
	}

	// prepare $output.fields
	foreach($fields as $findex => $field) {
		if ($field->name) {
			if (!isset($output['input'][ $field->name ])) {
				$output['input'][ $field->name ] = '';
			}

			if (isset($save[ $field->name ])) {
				$save[ $field->name ] = $output['input'][ $field->name ];
			}
			else {
				$save['meta_input'][ $field->name ] = $output['input'][ $field->name ];
			}

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


			$can_show = '1';

			if ($field->condition_show) {
				$field->condition_show = stripslashes($field->condition_show);
				$can_show = call_user_func(function($field, $input) {
					try {
						extract($input);
						$eval = "return {$field->condition_show} ;";
						$eval = @eval($eval);
						return $eval? '1': '';
					}
					catch(\ParseError $e) {
						return $e->getMessage();
					}
					return true;
				}, $field, $output['input']);
			}

			if ($can_show) {
				$output['fields'][] = $field;
			}
		}

		else {
			if ($field->type=='content') {
				$output['fields'][] = $field;
			}
		}
	}


	foreach($output['fields'] as $i=>$field) {
		foreach($output['input'] as $key=>$value) {
			$field->description = str_replace("\${$key}", $value, $field->description);
		}
		$output['fields'][$i] = $field;
	}


	$field = $output['fields'][ $output['index'] ];
	if ($output['goto'] !== '') {
		if ($output['goto']=='+') {
			$output['goto'] = $output['index'] + 1;
		}
		else if ($output['goto']=='-') {
			$output['goto'] = $output['index'] - 1;
		}

		$output['goto'] = max(0, $output['goto']);
		$output['goto'] = min(sizeof($output['fields'])-1, $output['goto']);

		if ($output['goto'] > $output['index']) {
			$field->error = elementor_typeform_validate($field, $output['input'][ $field->name ], $output['form_action_type']);
			if (! $field->error) {
				$output['index'] = $output['goto'];
			}
		}
		else {
			$output['index'] = $output['goto'];
		}
	}

	$output['percent'] = number_format(($output['index'] * 100) / (sizeof($output['fields'])-1), 1, '.', '');

	if (isset($field->final_step) OR 100==intval($output['percent'])) {
		if ($output['form_action_type']=='user-register') {
			if (! isset($save['user_login']) OR empty($save['user_login'])) {
				$save['user_login'] = isset($save['user_email'])? $save['user_email']: uniqid();
			}

			$id = wp_insert_user($save);
			if (! is_wp_error($id)) {
				foreach($save['meta_input'] as $key=>$value) {
					update_user_meta($id, $key, $value);
				}
			}
			$output['success'] = $id;
		}
	}

	return $output;
}



if (isset($_GET['elementor-typeform-submit-2'])) {
	add_action('init', function() {
		$fields = $_POST['fields'];
		$output = $_POST;
		unset($output['fields']);
		echo json_encode(elementor_typeform_fields_process($output, $fields)); die;
	});
}



add_action('elementor/widgets/widgets_registered', function($manager) {
	if (class_exists('Elementor_Typeform2')) return;
	
	class Elementor_Typeform2 extends \Elementor\Widget_Base {

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

			$this->start_controls_section('section_heading', [
				'label' => 'Configurações',
			]);

			$this->add_control('form_action_type', [
				'label' => 'Tipo de ação',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'label_block' => true,
				'options' => [
					'user-register' => 'Cadastro de usuário',
					'post-insert' => 'Post',
				],
			]);

			$repeater = new \Elementor\Repeater();

			$input_types = [];
			foreach(elementor_typeform_inputs() as $type=>$input) {
				$input_types[ $type ] = $input['name'];
			}

			$repeater->add_control('type', [
				'label' => 'Tipo de campo',
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'text',
				'options' => $input_types,
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
			foreach(elementor_typeform_validations() as $id=>$valid) {
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

			$this->add_control('success', [
				'label' => 'Mensagem de sucesso',
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => 'Obrigado!',
				'label_block' => true,
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
			$data->fields = $set->fields;
			$data->success = $set->success;
			
			$data->output = [
				'index' => 0,
				'form_action_type' => $set->form_action_type,
				'percent' => 0,
				'goto' => '',
				'input' => [],
				'fields' => [],
			];

			if (\Elementor\Plugin::$instance->editor->is_edit_mode() AND $set->fields_test) {
				$data->output['index'] = $set->fields_index;
			}

			$data->output = elementor_typeform_fields_process($data->output, $set->fields);

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
			.elementor-typeform-field-each {}
			<?php echo str_replace('$root', ".{$data->id}", $set->css); ?>
			</style>

			<div class="<?php echo $data->id; ?> elementor-typeform" id="<?php echo $data->id; ?>">
				<form action="" @submit.prevent="submit('+');">
					<div class="elementor-typeform-center elementor-typeform-field-each" v-for="(f, findex) in output.fields" :key="f" v-if="findex==output.index">
						<div>
							<div class="mb-3" v-html="f.description"></div>

							<div v-if="f.name">
								<?php foreach(elementor_typeform_inputs() as $type=>$input): ?> 
								<div v-if="f.type=='<?php echo $type; ?>'">
									<?php echo call_user_func($input['content']); ?>
								</div>
								<?php endforeach; ?> 

								<div v-if="f.error" v-html="f.error" class="text-danger"></div>
							</div>

							<div v-if="output.percent<100">
								<br>
								<div class="row no-gutters">
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
							</div>

						</div>
					</div>
					<br>
					<div class="text-center">
						<div class="progress"><div
							class="progress-bar progress-bar-striped progress-bar-animated bg-success"
							:style="{width:output.percent+'%'}"
						></div></div>
					</div>
				</form>
				
				<!-- <pre>output: {{ output }}</pre> -->
			</div>

			<script>new Vue({
				el: "#<?php echo $data->id; ?>",
				data: <?php echo json_encode($data); ?>,

				methods: {
					submit(goto) {
						var $=jQuery, $el=$(this.$el);

						var post = Object.assign({}, this.output);
						post.fields = Object.values(Object.assign({}, this.fields));
						post.fields.push({type:"content", description:this.success, final_step:true});
						post.goto = goto;

						$el.css({opacity:.7});
						this.loading = true;
						$.post('?elementor-typeform-submit-2', post, (resp) => {
							this.loading = false;
							this.$set(this, 'output', resp);
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

	$manager->register_widget_type(new \Elementor_Typeform2());
});
