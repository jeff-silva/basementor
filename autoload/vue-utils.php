<?php

add_action('vue', function() { ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/theme/ambiance.min.css">
<style>.CodeMirror {min-height:500px;}</style>
<script>Vue.component("input-code", (resolve, reject) => {
	head.load([
		'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/codemirror.min.js',
		'https://cdn.jsdelivr.net/npm/vue-codemirror@4.0.6/dist/vue-codemirror.js',
		'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/htmlmixed/htmlmixed.js',
		'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/xml/xml.js',
		'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/javascript/javascript.js',
		'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.50.2/mode/css/css.js',
	], () => {
		Vue.use(window.VueCodemirror);
		resolve({
			props: {
				value: {default:''},
				settings: {default:() => ({})},
			},

			methods: {
			    emit(value) {
			        this.value = value;
			        this.$emit('input', this.value);
			        this.$emit('value', this.value);
			        this.$emit('change', this.value);
			    },
			},

			computed: {
			    computedSettings() {
			        return Object.assign({}, {
			            tabSize: 4,
			            mode: 'application/x-httpd-php',
			            theme: 'ambiance',
			            lineNumbers: true,
			            line: true,
			            indentUnit: 4,
			            indentWithTabs: true,
			        }, this.options);
			    },
			},

			template: `<div>
			    <codemirror v-model="value" :options="computedSettings" @input="emit($event);"></codemirror>
			</div>`,
		});
	});
});</script>







<!-- input-color -->
<script>Vue.component("input-color", (resolve, reject) => {
	head.load(['https://cdnjs.cloudflare.com/ajax/libs/vue-color/2.7.1/vue-color.min.js'], () => {
		resolve({
		    components: {color:VueColor.Chrome},

		    props: {
		        value: {default:''},
		        pickerShow: {default:''},
		    },

		    methods: {
		    	emit(value) {
		    		this.value = value;
		    		this.$emit('input', this.value);
		    	},
		    },

		    mounted() {
		        document.addEventListener('click', (ev) => {
		            this.pickerShow = this.$el.contains(ev.target) && ev.target.classList.contains('form-control');
		        });
		    },

		    template: `<div class="input-color">
		        <div class="input-group form-control p-0" style="padding:0px !important;">
		        	<slot name="prepend" :value="value"></slot>
		            <input type="text" class="form-control border-0 bg-transparent"
		            	v-model="value" @focus="pickerShow=true;"
		            	:style="'border:none; border-left:solid 35px '+value+' !important;'"
		            />
		            <slot name="append" :value="value"></slot>
		        </div>
		        <div v-if="pickerShow" style="position:absolute; z-index:3;">
		            <color :value="value" @input="emit($event.hex8);"></color>
		        </div>
		    </div>`,
		});
	});
});</script>

<!--
<input-select v-model="opt" :options='[]' option-id="id" option-label="name">
	<template #option="{option, value}">{{ option.name }}</template>
</input-select>
-->
<script>Vue.component('input-select', {
	props: {
		value: {default: false},
		optionId: {default: ''},
		optionLabel: {default: ''},
		options: {default: () => ([])},
		search: {default: ''},
		shown: {default: false},
		placeholder: {default: ''},
	},

	watch: {
		value(nvalue, ovalue) {
			for(var i in this.options) {
				var opt = this.options[i];
				var value = this.optionId? (opt[this.optionId]||false): opt;
				if (value==nvalue) {
					this.setValue(opt);
					break;
				}
			}
		},
	},

	methods: {
		setValue(option) {
			this.value = this.optionId?
				(option[this.optionId] || false):
				option;

			this.placeholder = this.optionLabel?
				(option[this.optionLabel] || false):
				option;

			this.$emit('input', this.value);
			this.shown = false;
			this.search = '';
		},

		selected(opt) {
			var value = this.optionId? (opt[this.optionId]||false): opt;
			return this.value==value;
		},
	},

	computed: {
		compOptions() {
			var search = this.search.toLowerCase();
			return this.options.filter((opt) => {
				var line = Object.values(opt).join('').toLowerCase().replace(/\s/g, '');
				return line.includes(search);
			});
		},

		compPlaceholder() {
			var placeholder = this.placeholder;

			if (this.optionId && this.optionLabel) {
				for(var i in this.options) {
					var option = this.options[i];

					if (option[this.optionId]==this.value && option[this.optionLabel]) {
						placeholder = option[this.optionLabel];
						break;
					}
				}
			}

			return placeholder;
		},
	},

	mounted() {
		document.addEventListener('click', (ev) => {
			this.shown = this.$el.contains(ev.target);
		});

		document.addEventListener('keyup', (ev) => {
			if (ev.keyCode != 9) return;
			setTimeout(() => {
				this.shown = this.$el.contains(document.activeElement);
			}, 10);
		});
	},

	template: `<div class="input-group" style="position:relative;">
		<input type="search"
			class="form-control"
			v-model="search"
			:placeholder="compPlaceholder"
		/>
		<div class="bg-white shadow" style="position:absolute; top:100%; left:0px; width:100%; max-height:300px; overflow:auto; z-index:3;" v-if="shown">
			<div v-for="o in compOptions" @click="setValue(o);" class="p-2" :class="{'bg-primary text-light':selected(o)}">
				<slot name="option" :option="o" :value="value">
					<div>{{ o }}</div>
				</slot>
			</div>
		</div>
	</div>`,
});</script>



<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"> -->
<script>Vue.component("vue-slider", function(resolve, reject) {
	jQuery.getScript('//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', function() {
		resolve({
			props: {
				items: {default: () => ([])},
				options: {default: () => ({})},
				optionsXs: {default: () => ({})},
			},

			computed: {
				compOptions() {
					let options = Object.assign({
						infinite: true,
						slidesToShow: 1,
						slidesToScroll: 1,
						arrows: true,
						asNavFor: null,
						autoplay: false,
						autoplaySpeed: true,
						centerMode: false,
						cssEase: 'ease',
						dots: false,
						lazyLoad: 'ondemand',
						prevArrow: this.$refs.prevArrow,
						nextArrow: this.$refs.nextArrow,
						speed: 300,
						vertical: false,
						responsive: [],
					}, this.options||{});

					if (options.asNavFor) {
						options.asNavFor = document.querySelector(options.asNavFor).querySelector('.vue-slider-slider');
						console.log(options.asNavFor);
					}

					return options;
				},
			},

			methods: {
				slickCurrentSlide() {},
				slickGoTo(index, animate) { this.$slider.slick('slickGoTo', index, animate); },
				slickNext() { this.$slider.slick('slickNext'); },
				slickPrev() { this.$slider.slick('slickPrev'); },
				slickPause() { this.$slider.slick('slickPause'); },
				slickPlay() { this.$slider.slick('slickPlay'); },
				slickAdd(dom, index, addBefore) { this.$slider.slick('slickAdd', dom, index, addBefore); },
				slickRemove(index, removeBefore) { this.$slider.slick('slickRemove', index, removeBefore); },
			},

			mounted() {
				this.$slider = jQuery(this.$refs.slider);
				this.$slider.slick(this.compOptions);
				// ['swipe', 'edge', 'afterChange', 'beforeChange'].forEach((evt) => {
				// 	$slider.on(evt, () => {
				// 		this.$emit.apply(this, [evt].concat(Array.from(arguments)));
				// 	});
				// });
			},

			template: `<div class="vue-slider">
				<button type="button" ref="prevArrow" class="slick-arrow slick-prev">
					<slot name="prev">
						<i class="fa fa-fw fa-chevron-left" style="font-size:30px;"></i>
					</slot>
				</button>

				<button type="button" ref="nextArrow" class="slick-arrow slick-next">
					<slot name="next">
						<i class="fa fa-fw fa-chevron-right" style="font-size:30px;"></i>
					</slot>
				</button>

				<div ref="slider" class="vue-slider-slider">
					<div v-for="(it, index) in items">
						<slot name="slide" v-bind="{slide:it, index:index}"></slot>
					</div>
				</div>
			</div>`,
		});
	});
});</script>

<script>Vue.component('vue-slider2', {
	props: {
		items: {default: () => ([])},
		options: {default: () => ({})},
		optionsXs: {default: () => ({})},
	},

	computed: {
		compOptions() {
			return Object.assign({
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				autoplay: false,
				autoplaySpeed: true,
				centerMode: false,
				cssEase: 'ease',
				dots: false,
				lazyLoad: 'ondemand',
				prevArrow: this.$refs.prevArrow,
				nextArrow: this.$refs.nextArrow,
				speed: 300,
				vertical: false,
				responsive: [],
			}, this.options||{});
		},
	},

	mounted() {
		console.log('window.Slick', window.Slick);
		jQuery(this.$refs.slider).slick(this.compOptions);
	},

	template: `<div class="vue-slider">
		<button type="button" ref="prevArrow" class="slick-arrow slick-prev">
			<slot name="prev">
				<i class="fa fa-fw fa-chevron-left" style="font-size:30px;"></i>
			</slot>
		</button>

		<button type="button" ref="nextArrow" class="slick-arrow slick-next">
			<slot name="next">
				<i class="fa fa-fw fa-chevron-right" style="font-size:30px;"></i>
			</slot>
		</button>

		<div ref="slider">
			<div v-for="(it, index) in items">
				<slot name="slide" v-bind="{slide:it, index:index}"></slot>
			</div>
		</div>
	</div>`,
});</script>

<style>
.vue-slider {position:relative;}
.vue-slider .slick-arrow {position:absolute; top:0px; height:100%; border:none; background:none; cursor:pointer; z-index:3; min-width:50px; outline:none!important;}
.vue-slider .slick-prev {left:0px;}
.vue-slider .slick-next {right:0px;}
</style>


<script>Vue.component("vue-draggable", function(resolve, reject) {
	head.load([
		'//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js',
	], function() { resolve(vuedraggable); });
});</script>
<?php });