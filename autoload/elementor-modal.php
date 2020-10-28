<?php

/* Inspirations:
https://piano.io/
https://evernote.com/intl/pt-br/
https://www.columbiasportswear.com.br/
*/

\Basementor\Elementor::register([
    'class' => 'Elementor_Modal',
    'title' => 'Modal',
    'icon' => 'eicon-editor-code',
    'categories' => ['general'],
    'controls' => [
        'section_modal' => [
            'label' => 'Modal',
            'fields' => [
                'title' => [
                    'label' => 'Descrição do modal',
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Modal',
                ],

                'target_id' => [
                    'label' => 'ID do elemento',
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                ],

                'overlay_bg' => [
                    'label' => 'Background do overlay',
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#00000022',
                ],
            ],
        ],
    ],
    'render' => function($set=[]) {
        $style[] = "#{$set->target_id} {position:fixed; top:0px; left:0px; width:100%; height:100%; background:{$set->overlay_bg}; z-index:9; display:flex; align-items:center; justify-content:center;}";
        $style[] = "#{$set->target_id} .elementor-column-gap-default {background:#fff;}";

        echo '<style>'. implode(' ', $style) .'</style>';

        ?><div>
            Modal
        </div><?php
    },
]);