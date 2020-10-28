<?php

/* Inspirations:
https://piano.io/
https://evernote.com/intl/pt-br/
https://www.columbiasportswear.com.br/
*/

\Basementor\Elementor::register([
    'class' => 'Elementor_Megamenu',
    'title' => 'Megamenu',
    'icon' => 'eicon-editor-code',
    'categories' => ['general'],
    'controls' => [
        'section_post' => [
            'label' => 'CSS & JS',
            'fields' => [
                'title' => [
                    'label' => 'Descrição do CSS',
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'CSS & JS',
                ],

                'type' => [
                    'label' => 'Tipo de menu',
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'default' => 'horizontal',
                    'options' => [
                        'horizontal' => 'Horizontal',
                        'vertical' => 'Vertical',
                        'vertical-dropdown' => 'Vertical dropdown',
                    ],
                ],

                'test_index' => [
                    'label' => 'Testar opção',
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 0,
                ],

                'nav_items' => [
                    'label' => 'Itens do menu',
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'title_field' => '{{{ title }}}',
                    'default' => [],
                    'fields' => [
                        'title' => [
                            'label' => 'Título',
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'label_block' => true,
                        ],

                        'section_id' => [
                            'label' => 'ID da seção (não use #)',
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'label_block' => true,
                        ],

                        'link' => [
                            'label' => 'Link',
                            'type' => \Elementor\Controls_Manager::URL,
                            'default' => ['url'=>'javascript:;'],
                            'label_block' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],
    'render' => function($set=[]) {
        $style = [];

        $style[] = "#{$set->id} {position:relative;}";
        $style[] = "#{$set->id} .elementor-megamenu-animated {transition: all 300ms ease;}";

        $style[] = "#{$set->id} ul.elementor-megamenu-nav {list-style-type:none; margin:0px; padding:0px;}";
        $style[] = "#{$set->id} ul.elementor-megamenu-nav > li {}";
        $style[] = "#{$set->id} ul.elementor-megamenu-nav > li > a {display:block; padding:5px;}";

        $style[] = "#{$set->id} ul.elementor-megamenu-nav-horizontal {display:flex;}";
        $style[] = "#{$set->id} ul.elementor-megamenu-nav-horizontal > li {}";

        $style[] = "#{$set->id} ul.elementor-megamenu-nav-vertical {}";
        $style[] = "#{$set->id} ul.elementor-megamenu-nav-vertical > li {}";

        $style[] = "#{$set->id} ul.elementor-megamenu-nav-vertical-dropdown {visibility:hidden; opacity:0; position:absolute; top:100%; left:0px;}";
        $style[] = "#{$set->id} ul.elementor-megamenu-nav-vertical-dropdown > li {}";

        foreach($set->nav_items as $item) {
            if ($item->section_id) {
                $style[] = "#{$item->section_id} {display:none;}";
            }
        }

        echo '<style>'. implode(' ', $style) .'</style>';

        ?>
        <div id="<?php echo $set->id; ?>_wrapper" data-elementor-megamenu-sets='<?php echo json_encode([
            'type' => $set->type,
        ]); ?>'>
            <?php if ($set->type=='vertical-dropdown'): ?>
            <div class="btn elementor-megamenu-nav-btn-vertical-dropdown" onmouseenter="jQuery('#<?php echo $set->id; ?>_nav').fadeIn(200);">
                <i class="fas fa-bars"></i> &nbsp; Exibir
            </div>
            <?php endif; ?>

            <ul class="elementor-megamenu-nav elementor-megamenu-nav-<?php echo $set->type; ?> elementor-megamenu-animated">
                <?php foreach($set->nav_items as $item): ?>
                <li><a href="javascript:;"
                    onmouseenter="elementorMegamenuToggleTarget('#<?php echo $set->id; ?>_wrapper', '#<?php echo $item->section_id; ?>', {show:true})"
                    onclick="elementorMegamenuToggleTarget('#<?php echo $set->id; ?>_wrapper', '#<?php echo $item->section_id; ?>', {show:true})"
                    ><?php echo $item->title; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <script>
        var elementorMegamenuToggleTarget = function(wrapper_id, target_id, params={}) {
            if (!wrapper_id || wrapper_id=='#' || !target_id || target_id=='#') return;
            var $ = jQuery;

            params = Object.assign({
                show: true,
                evtClose: true,
            }, params||{});
            
            var $target = $(target_id);
            var $wrapper = $(wrapper_id);
            var $container = $wrapper.closest('.elementor-container');
            var $section = $wrapper.closest('.elementor-section');

            var sectionRect = $section.get(0).getBoundingClientRect();
            var containerRect = $container.get(0).getBoundingClientRect();
            var wrapperRect = $wrapper.get(0).getBoundingClientRect();
            var css = {position:"fixed", zIndex:9};

            var sets = $wrapper.attr('data-elementor-megamenu-sets');
            try { eval('sets = '+sets); } catch(e) { sets = {}; }
            sets = Object.assign({type:false}, sets||{});

            if (sets.type=='horizontal') {
                css.top = sectionRect.y + sectionRect.heiht;
                // css.left = containerRect.x;
                // css.width = containerRect.width;
                css.left = 0;
                css.width = '100%';
            }

            else if (sets.type=='vertical') {
                css.top = sectionRect.y;
                css.left = containerRect.x + wrapperRect.width;
                css.width = containerRect.width - wrapperRect.width;
                css.minHeight = wrapperRect.height;
            }

            else if (sets.type=='vertical-dropdown') {
                css.top = sectionRect.y;
                css.left = containerRect.x + wrapperRect.width;
                css.width = containerRect.width - wrapperRect.width;
                css.minHeight = wrapperRect.height;
            }

            $target.css(css);
            params.show? $target.fadeIn(200): $target.fadeOut(200);
            params.evtClose?
                $target.attr('onmouseleave', 'elementorMegamenuToggleTarget("'+wrapper_id+'", "'+target_id+'", {show:false})'):
                $target.removeAttr('onmouseleave');
        };

        <?php if ($set->is_edit): ?>
        jQuery(document).ready(function($) {
            <?php foreach($set->nav_items as $i=>$item): ?>
            elementorMegamenuToggleTarget("#<?php echo $set->id; ?>_wrapper", "#<?php echo $item->section_id; ?>", <?php echo json_encode([
                'show' => ($i+1==$set->test_index),
                'evtClose' => false,
            ]); ?>);
            <?php endforeach; ?>
        });
        <?php endif; ?>
        </script>
        <?php
    },
]);