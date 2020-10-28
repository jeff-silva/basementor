<?php

/* Hide #wpadminbar and show on hover */
add_theme_support('admin-bar', ['callback' => function() { ?><style>
body:not(.wp-admin) #wpadminbar {top:-25px; transition: all 300ms ease; opacity:0;}
body:not(.wp-admin) #wpadminbar:hover {opacity:1; top:0px;}
@media (min-width: 0px) and (max-width: 767px) { #wpadminbar {display:none;} }
</style><?php }]);

foreach(['admin_head', 'wp_head'] as $action) {
    add_action($action, function() {
        $style[] = '.form-control {box-shadow:none!important;}';
        echo '<style>'. implode(' ', $style) .'</style>';
    });
}