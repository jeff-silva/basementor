<?php

\Basementor\Elementor::register([
    'class' => 'Elementor_Css_Js',
    'title' => 'CSS & JS',
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
                'css' => [
                    'label' => 'CSS',
                    'type' => \Elementor\Controls_Manager::CODE,
                    'language' => 'css',
                    'default' => '',
                ],
                'js' => [
                    'label' => 'Javascript',
                    'type' => \Elementor\Controls_Manager::CODE,
                    'language' => 'javascript',
                    'default' => '',
                ],
            ],
        ],
    ],
    'render' => function($set=[]) {
        if ($set->is_edit) {
            echo '<div style="text-align:center; padding:10px; color:#aaa; background:repeating-linear-gradient(45deg, #eef, #eef 30px, #fff 30px, #fff 60px);">'. $set->title .'</div>';
        }

        echo "<style>{$set->css}</style>";
        echo "<script>{$set->js}</script>";
    },
]);