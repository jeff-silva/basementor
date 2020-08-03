<?php

add_filter('basementor-settings-menu', function($admin_bar) {
	$admin_bar->add_menu([
		'parent' => 'basementor',
		'id'    => 'basementor-settings-embeds',
		'title' => 'Embeds',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=embeds'),
    ]);
    
    return $admin_bar;
});


add_action('basementor-settings-embeds', function($data) {
    $data->plugins = [];
    $data->plugins[] = [
        'name' => 'Google Analytics',
        'active' => false,
    ];
    
    ?><div id="<?php echo $data->id; ?>">
        <form action="<?php echo \Basementor\Basementor::action('basementor-settings-save'); ?>" method="post">
            <div class="form-group">
                <label>Embed head</label>
                <input-code v-model="settings.basementor_head"></input-code>
            </div>

            <div class="form-group">
                <label>Embed body</label>
                <input-code v-model="settings.basementor_body"></input-code>
            </div>

            <div class="text-right">
                <textarea name="settings" style="display:none;">{{ settings }}</textarea>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>

    <?php do_action('vue'); ?>
    <script>new Vue({
        el: "#<?php echo $data->id; ?>",
        data: <?php echo json_encode($data); ?>,
    });</script><?php
});