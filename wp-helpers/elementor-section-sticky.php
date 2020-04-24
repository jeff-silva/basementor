<?php

if (! BASEMENTOR_ELEMENTOR) return;

add_action('elementor/frontend/section/before_render',function($element) {
	// if('section' !== $element->get_name()) { return; }
	$set = (object) $element->get_settings();
	if (is_integer($set->sticky)) {
		// $element->add_render_attribute('_wrapper', 'style', "position:sticky; top:{$set->sticky_top}px; z-index:9999 !important;");
		$element->add_render_attribute('_wrapper', 'class', $set->sticky);
	}
});


add_action('elementor/frontend/section/after_render',function($element) {
	// if('section' !== $element->get_name()) { return; }
	$set = (object) $element->get_settings();
	if ($set->sticky): ?>
	<?php endif;
});


add_action('wp_footer', function() { ?><script>
var elementorSectionStickyResizeHandle = function() {
	var $=jQuery, stickyTop=0, spacerHeight=0, mobileWidth=600;

	// 32px
	if (window.innerWidth>mobileWidth && $('#wpadminbar').length==1) {
		stickyTop += parseFloat($('#wpadminbar').outerHeight());
	}


	$('.elementor-section-sticky-top').each(function() {
		var $element = $(this);
		if ($element.is(':visible')) {
			$element.css({position:"fixed", width:"100%", zIndex:99, top:stickyTop});
			var height = this.getBoundingClientRect().height;
			stickyTop += height;
			spacerHeight += height;
		}
	});

	$('.elementor-section-sticky-top-spacer').remove();
	$('body').prepend(`<div class="elementor-section-sticky-top-spacer" style="height:${spacerHeight-1}px;"></div>`);
};

jQuery(document).ready(elementorSectionStickyResizeHandle);
window.addEventListener('resize', elementorSectionStickyResizeHandle);
</script><?php });


add_action('elementor/element/section/section_typo/after_section_end', function($section, $args) {
	$section->start_controls_section('section_custom_sticky',[
		'label' => 'Sticky',
		'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	]);

	$section->add_control('sticky', [
		'label'        => 'Sticky',
		'type'         => Elementor\Controls_Manager::SELECT,
		'return_value' => '',
		'prefix_class' => '',
		'options' => [
			'' => 'Nenhum',
			'elementor-section-sticky-top' => 'Em Cima',
			'elementor-section-sticky-bottom' => 'Em Baixo',
		],
	]);

	$section->end_controls_section();
}, 10, 2);
