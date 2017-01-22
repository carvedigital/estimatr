module.exports = function(grunt) {

	grunt.initConfig({

		/*
			Settings.
		*/

		public_html		: 'public',
		source_folder	: 'assets',

		js_src	 		: '<%= source_folder %>/javascript',
		scss_src		: '<%= source_folder %>/scss',
		assets_folder	: '<%= public_html %>/assets',

        css_folder		: '<%= public_html %>/css',
        css_file		: '<%= css_folder %>/style.css',

        js_folder		: '<%= public_html %>/js',
        js_file			: '<%= css_folder %>/main.js',

		uglify: {

			options: {
				compress: true,
				mangle: true
			},

			js: {

				files: [{

					src: [
						'<%= js_src %>/lib/jquery.min.js',
						'<%= js_src %>/lib/lodash.min.js',
						'<%= js_src %>/lib/what-input.min.js',
						'<%= js_src %>/lib/foundation.min.js',
						'<%= js_src %>/lib/vex.min.js',
						'<%= js_src %>/lib/accounting.min.js',
						'<%= js_src %>/lib/vue.js',
						'<%= js_src %>/lib/vue-resource.min.js',
						'<%= js_src %>/**/*.js'
					],

					dest: '<%= js_folder %>/scripts.js'

				}]

			}
		},

		sass:{

			dist:{

				options:{

					style 		: 'compressed',
                    lineNumber	: true,
                    compass		: false,
                    loadPath	: []

				},

				files:{
					'<%= css_folder %>/style.css':'<%= scss_src %>/style.scss',
				}

			}

		},

		watch: {

			// Live Reload Port
			options: {
				livereload: 1337
			},

			// Reloads the watch if you update this file.
			configFiles: {
				files: [ 'Gruntfile.js' ],
				options: {
					reload: true
				}
			},

			css: {
				files: ['<%= scss_src %>/**/*.scss'],
				tasks: ['sass'],
				options: {
					livereload: true,
				},
			},

			js: {
				files: ['<%= js_src %>/**/*.js'],
				tasks: ['uglify'],
				options: {
					livereload: true,
				},
			},

		},

	});


	/* Tasks */
	grunt.registerTask('js', 	['uglify']);
	grunt.registerTask('scss', 	['sass']);
	grunt.registerTask('css', 	['scss']);

	grunt.registerTask('update', ['uglify', 'sass']);
	grunt.registerTask('default', ['update', 'watch']);

	/* Required Modules */
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-modernizr');

};
