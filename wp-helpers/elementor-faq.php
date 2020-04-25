<?php

if (! BASEMENTOR_ELEMENTOR) { return; }

add_action('elementor/widgets/widgets_registered', function($manager) {
	class Elementor_Faq extends \Elementor\Widget_Base {

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

			$repeater = new \Elementor\Repeater();

			$repeater->add_control('question', [
				'label' => 'Pergunta',
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
				'label_block' => true,
			]);

			$repeater->add_control('answer', [
				'label' => 'Pergunta',
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
				'label_block' => true,
			]);

			$this->add_control('questions', [
				'label' => 'Perguntas',
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [],
				'title_field' => '{{{ question }}}',
			]);

			$this->add_control('css', [
				'label' => 'CSS',
				'type' => \Elementor\Controls_Manager::CODE,
				'default' => '$root {}',
			]);

			$this->end_controls_section();
		}

		protected function render() {
			$set = json_decode(json_encode($this->get_settings()));
			
			$data = new \stdClass;
			$data->id = uniqid('elementor-faq-');
			$data->questions = $set->questions;
			$data->quest = isset($data->questions[0])? $data->questions[0]: false;

			?>
			<style><?php echo str_replace('$root', ".{$data->id}", $set->css); ?></style>
			<div class="<?php echo $data->id; ?>" id="<?php echo $data->id; ?>">
				<div v-for="q in questions" :key="q._id">
					<div class="card">
						<div class="card-header">
							<div class="row no-gutters">
								<div class="col-10 pr-2" v-html="q.question"></div>
								<div class="col-2 text-right">
									<a href="javascript:;" @click="quest = quest==q? false: q;" v-if="questions.length>1">
										<i class="fa fa-fw fa-chevron-left" :class="{'fa-rotate-270':q==quest}"></i>
									</a>
								</div>
							</div>
						</div>
						<transition
							name="elementor-faq-transition"
							enter-active-class="animated fadeIn"
							leave-active-class="animated fadeOut"
						>
							<div class="card-body"
								v-html="q.answer"
								v-if="quest && quest._id==q._id"
								style="animation-duration:300ms;"
							></div>
						</transition>
					</div>
					<br>
				</div>
			</div>
			<script>new Vue({
				el: "#<?php echo $data->id; ?>",
				data: <?php echo json_encode($data); ?>,
			});</script>
			<?php
		}

		protected function content_template() {}
	}

	$manager->register_widget_type(new \Elementor_Faq());
});
