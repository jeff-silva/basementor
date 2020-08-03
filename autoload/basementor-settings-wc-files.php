<?php

add_filter('basementor-settings-menu', function($admin_bar) {
	$admin_bar->add_menu([
		'parent' => 'basementor',
		'id'    => 'basementor-settings-wc-files',
		'title' => 'Woocommerce files',
		'href'  => admin_url('admin.php?page=basementor-settings&tab=wc-files'),
    ]);
    
    return $admin_bar;
});


add_action('basementor-settings-wc-files', function($data) {
    if (! function_exists('basementor_settings_woocommerce_files')) {
        function basementor_settings_woocommerce_files($dir=null, $files=[], $level=0) {
            if ($dir===null) {
                $dir = get_template_directory() .'/woocommerce';
            }

            foreach(glob($dir .'/*') as $file) {
                if (is_dir($file)) {
                    $files = basementor_settings_woocommerce_files($file, $files, $level+1);
                    continue;
                }
                $info = pathinfo($file);
                $info['woo_file'] = str_replace(realpath(__DIR__ .'/..').'/', '', $file);
                $info['id'] = md5($info['woo_file']);
                $info['parent_file'] = $file;
                $info['theme_file'] = get_stylesheet_directory() ."/{$info['woo_file']}";
                $info['theme_file_exists'] = file_exists($info['theme_file']);
                $info['edit'] = '?'. http_build_query(array_merge($_GET, ['file'=>$info['id']]));
                $info['content'] = '';
                $info['content_edit'] = false;

                if (isset($_GET['file']) AND $_GET['file']==$info['id']) {
                    $info['content_edit'] = true;
                    if ($info['theme_file_exists']) {
                        $info['content'] = file_get_contents($info['theme_file']);
                    }
                    else {
                        $info['content'] = file_get_contents($info['parent_file']);
                    }
                }

                $files[ $info['id'] ] = $info;
            }

            return $files;
        };
    }

    $data->files = basementor_settings_woocommerce_files();

    ?><br>
    <div id="basementor-woocommerce-templates">
        <div class="row">
            <div class="col-4">
                <div class="list-group">
                    <a :href="f.edit" class="list-group-item" v-for="f in files" :key="f.id">
                        {{ f.woo_file }}
                    </a>
                </div>
                <!-- <pre>$data: {{ $data }}</pre> -->
            </div>
            <div class="col-8">
                <div v-for="f in files" :key="f.id" v-if="f.content_edit">
                    <form action="<?php echo \Basementor\Basementor::action('basementor-settings-woocommerce-template-save'); ?>" method="post">
                        <div class="card m-0">
                            <div class="card-header">
                                <div class="text-uppercase font-weight-bold">{{ f.basename }}</div>
                                <div class="text-muted">{{ f.woo_file }}</div>
                            </div>
                            <div class="card-body p-0">
                                <input-code v-model="f.content" name="content"></input-code>
                            </div>
                            <div class="card-footer text-right p-2">
                                <input type="hidden" name="file" v-model="f.theme_file">

                                <button type="submit" class="btn btn-danger pull-left" name="delete" value="1">
                                    <i class="fa fa-fw fa-remove"></i> Resetar
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-fw fa-save"></i> Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php do_action('vue'); ?>
    <script>new Vue({
        el: "#basementor-woocommerce-templates",
        data: <?php echo json_encode($data); ?>,
    });</script>
    <?php
});