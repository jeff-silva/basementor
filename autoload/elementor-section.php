<?php

/* Custom CSS style */
add_action('elementor/element/section/section_typo/after_section_end', function($section, $args) {
	$section->start_controls_section('section_custom_style',[
		'label' => 'CSS',
		'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	]);

    $section->add_control('custom_style', [
		'label' => 'CSS',
        'description' => 'Use ":id" como seletor do wrapper.',
		'type' => Elementor\Controls_Manager::CODE,
        'language' => 'css',
		'value' => '',
	]);

	$section->end_controls_section();
}, 10, 2);


add_action('elementor/frontend/section/before_render',function($element) {
	if('section' !== $element->get_name()) return;
	if ($custom_style = $element->get_settings('custom_style')) {
        echo '<style>'. str_replace(':id', $element->get_unique_selector(), $custom_style) .'</style>';
	}
});


// add_action('elementor/frontend/section/after_render',function($element) {
// 	if ($sticky = $element->get_settings('sticky')) {
//         // echo "</div><!-- elementor-section-sticky -->";
// 	}
// });



/* Modal */
add_action('elementor/element/section/section_typo/after_section_end', function($section, $args) {
	$section->start_controls_section('section_modal',[
		'label' => 'Modal',
		'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	]);

    $section->add_control('modal_active', [
		'label' => 'Ativar/desativar modal',
		'type' => Elementor\Controls_Manager::SWITCHER,
		'value' => '',
	]);

    $section->add_control('modal_type', [
		'label' => 'Tipo',
		'type' => Elementor\Controls_Manager::SELECT2,
		'value' => 'modal',
        'options' => [
            'modal' => 'Modal',
            'drawer-left' => 'Drawer esquerdo',
            'drawer-right' => 'Drawer direito',
        ],
	]);

    $section->add_control('modal_bg_color', [
		'label' => 'Cor do backdrop',
		'type' => Elementor\Controls_Manager::COLOR,
		'value' => '#ffffff',
	]);

    $section->add_control('modal_backdrop_color', [
		'label' => 'Cor do backdrop',
		'type' => Elementor\Controls_Manager::COLOR,
		'value' => '#00000022',
	]);

	$section->end_controls_section();
}, 10, 2);


// add_action('elementor/frontend/section/before_render',function($element) {
// 	if('section' !== $element->get_name()) return;
//     $set = (object) $element->get_settings();
//     if (!$set->modal_active) return;

//     echo "<div style='position:fixed; top:0px; left:0px; width:100%; height:100%; background:{$set->modal_backdrop_color}; z-index:8; display:flex; align-items:center; justify-content:center;'><div style='max-height:90vh; overflow:auto; background:{$set->modal_bg_color};'>";
// });


// add_action('elementor/frontend/section/after_render',function($element) {
// 	if('section' !== $element->get_name()) return;
//     $set = (object) $element->get_settings();
//     if (!$set->modal_active) return;
//     echo '</div></div>';
// });


