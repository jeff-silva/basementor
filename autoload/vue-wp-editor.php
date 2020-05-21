<?php

add_action('vue', function() { ?>
	<?php wp_enqueue_editor(); ?>
	<script>Vue.component("wp-editor", {
		props: {
			value: {default: ''},
		},

		data() {
			return {
				id: ("wp-editor-"+ Math.round(Math.random()*9999)),
			};
		},

		mounted() {
			jQuery(document).ready(($) => {
				wp.editor.initialize(this.id, {
					quicktags: true,
					mediaButtons: true,
					tinymce: {
						wpautop: true,
						plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
						toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
						toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
					},
				});
			});
		},

		template: `<div>
			<textarea :id="id" v-model="value"></textarea>
			<pre>{{ value }}</pre>
		</div>`,
	});</script>
<?php });
