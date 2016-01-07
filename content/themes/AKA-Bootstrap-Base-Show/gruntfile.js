/*

Grunt config file for AKA Bootstrap theme
Author: Ash Whiting @ AKA
Version: 0.3.4
----------------------------------------------------------------------------------------------------------

How to use
==========

Open your console, navigate to the theme folder you are using.

1. Install Node.js on your machine

2. Install Grunt - You'll only ever need to do this once

	sudo npm install -g grunt-cli

3. Run the install in your theme folder to set up dependencies on your machine

	npm install

4. Update the package.json file with the new project name and version.

5. Run the grunt watch command - This will watch for file changes and build out the project files to build.
If you use LiveReload, it will also automatically load the page.

	grunt watch

Alternatively, you can just do a build of anything in here by doing something like

	grunt concat

Or fire a single build instance by doing

	grunt

See AKA Documentation for more information

To do
==========

Add bourbon throughput
Unit testing (if needed)
Command based versioning with Bitbucket (if needed)

*/

module.exports = function(grunt) {

	// Timer
	// ===================================================================

	require('time-grunt')(grunt);


	// Setup some common folder paths
	// ===================================================================

	var globalConfig = {
    	js: 'library/js',
    	temp: 'library/temp',
    	scss: 'library/scss',
    	imagesrc: 'library/images',
    	img: 'build/images',
		dest: 'build'
	};

	// Set up the concatenation ordering
	// ===================================================================

	// Pull in individual bootstrap elements and concatenate. (Saves on file size if not needed)
	// Careful about dependencies here though

	var jsCustomBootstrap = [
		'<%= globalConfig.js %>/vendor-libs/bootstrap/affix.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/alert.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/button.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/carousel.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/collapse.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/dropdown.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/modal.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/tooltip.js', // this is dependent
		'<%= globalConfig.js %>/vendor-libs/bootstrap/popover.js', // on this
		'<%= globalConfig.js %>/vendor-libs/bootstrap/scrollspy.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/tab.js',
		'<%= globalConfig.js %>/vendor-libs/bootstrap/transition.js'
	]

	// Just add the basic scripts

	var jsFileList = [
		'<%= globalConfig.js %>/vendor-libs/modernizr.js',
		'<%= globalConfig.js %>/vendor-libs/respond.min.js',
		'<%= globalConfig.js %>/vendor-libs/placeholdr.min.js',
		'<%= globalConfig.js %>/scripts.js',
		// '<%= globalConfig.js %>/vendor-libs/bootstrap.min.js'
	];


	// Configure the tasks
	// ===================================================================

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		globalConfig: globalConfig,

		// Create a custom Modernizr file for the theme
		// ===================================================================

		// modernizr: {
		// 	dist: {
		// 	    "devFile" : "<%= globalConfig.js %>/vendor-libs/modernizr.js",
		// 	    "outputFile" : "<%= globalConfig.temp %>/modernizr-custom.js",

		// 	    // Based on default settings on http://modernizr.com/download/
		// 	    "extra" : {
		// 	        "shiv" : true,
		// 	        "printshiv" : false,
		// 	        "load" : true,
		// 	        "mq" : false,
		// 	        "cssclasses" : true
		// 	    },
		// 	    "extensibility" : {
		// 	        "addtest" : true,
		// 	        "prefixed" : true,
		// 	        "teststyles" : true,
		// 	        "testprops" : true,
		// 	        "testallprops" : true,
		// 	        "hasevents" : true,
		// 	        "prefixes" : true,
		// 	        "domprefixes" : true
		// 	    },

		// 	    // Define any tests you want to implicitly include.
		// 	    "tests" : [],
		// 	    "parseFiles" : true,

		// 	    "matchCommunityTests" : true,

		// 	    // Have custom Modernizr tests? Add paths to their location here.
		// 	    "customTests" : []
		// 	}
		// },

		// Test the javascript
		// ===================================================================
		// This only checks the scripts.js file you edit - 3rd party scripts are
		// assumed to work. If you add another js file (which you shouldn't have to)
		// Then either add it to the vendor folder, or add it to the array below
		// If you do want to bugfix other js files simply add them to this function IE
		// all: ['<%= globalConfig.js %>/scripts.js', 'some other file.js']

		jshint: {
			options: {
            	reporter: require('jshint-stylish'),
			},
			all: ['<%= globalConfig.js %>/scripts.js']
		},

		// Concatenate the JS
		// ===================================================================
		// If you add any other Javascript files to the vendor-libs folder
		// Be sure to add them to the jsFileList array so they compile together
		// Be careful about ordering as files are concatenated in order

		concat: {
			options: {
				stripBanners: true,
			},
			dist: {
				src: [jsFileList],
				// src: ['<%= globalConfig.temp %>/modernizr-custom.js', jsCustomBootstrap],
				dest: '<%= globalConfig.temp %>/min/scripts.js'
			}
		},

		// Minify the javascript
		// ===================================================================

		uglify: {
			options: {
				mangle: false,
				sourceMap: false,
				beautify: true,
				sourceMapName: '<%= globalConfig.build %>/js/min/sourcemap.map',
				compress: {
					dead_code: true,
					conditionals:true,
					properties:true
				},
			},
			build: {
				src: '<%= globalConfig.temp %>/min/scripts.js',
				dest: '<%= globalConfig.dest %>/js/min/scripts.min.js'
			}
		},

		// Optimise all the images
		// ===================================================================

		imagemin: {
			dynamic: {
				options: {
					// Optimisation level from 1-7
					optimizationlevel: 2,
					cache: false
				},
				files: [{
					expand: true,
					cwd:'<%= globalConfig.imagesrc %>/',
					src: ['**/*.{png,jpg,gif}'],
					dest: '<%= globalConfig.img %>/'
				}]
			}
		},

		// Compile Sass
		// ===================================================================
		// Add extra files for compilation as need be
		// This automatically compiles the config.css and outputs style.css

		sass: {
			dist: {
				options: {
					outputStyle: 'compressed'
				},
				files: {
					'<%= globalConfig.temp %>/style.css' : '<%= globalConfig.scss %>/config.scss' // Build the core styles
				}
			}
		},

		// Automate vendor css vendor prefixes
		// ===================================================================

		autoprefixer: {
			options: {
				browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
			},
			single_file: {
				src:'<%= globalConfig.temp %>/style.css',
				dest:'<%= globalConfig.dest %>/css/min/styles.min.css'
			}
		},

		bless: {
			css: {
				options: {
					 logCount: false
				},
				files: {
					'<%= globalConfig.dest %>/css/min/styles.min.css' : '<%= globalConfig.temp %>/style.css'
				}
			}
		},

		// Remove the temp folder where all the work was done
		// ===================================================================

		clean: {
		 	temp: ['<%= globalConfig.temp %>/'],
			images: ['<%= globalConfig.imagesrc %>/**/*','<%= globalConfig.imagesrc %>/**/*.gif','<%= globalConfig.imagesrc %>/**/*.png','<%= globalConfig.imagesrc %>/**/*.jpg', '<%= globalConfig.imagesrc %>/**/*.gif']
		},

		// Watch the folders (with grunt-newer)
		// ===================================================================
		// Minify the cleaned CSS

		cssmin: {
			add_banner: {
				options: {
					banner: '/* Cleaned up stylesheet */'
				},
				files: {
					'<%= globalConfig.temp %>/style.css' : '<%= globalConfig.dest %>/css/min/styles.min.css'
				}
			}
		},

	    // Shows how many selectors there are

	    cssmetrics: {
		    dev: {
		        src: [
		            '<%= globalConfig.dest %>/css/min/styles.min.css'
		        ],
		        options: {
		            quiet: true,
                    maxSelectors: 4096,
                    maxFileSize: 10240000
		        }
		    }
		},

		// Add cache-busting versions to the CSS and main script files

	    version: {
	      default: {
	        options: {
	          format: true,
	          length: 32,
	          manifest: 'core/manifest.json',
	          querystring: {
	            style: 'AKA-stylesheet',
	            script: 'scripts'
	          }
	        },
	        files: {
	          'core/AKA.php': ['build/css/min/styles.min.css', 'build/js/min/scripts.min.js']
	        }
	      }
	    },

	   criticalcss: {
	        custom: {
	            options: {
	                url: "http://ash.local/AKA/", // Your local path
	                width: 1200,
	                height: 900,
	                outputfile: "build/css/critical/critical.css",
	                filename: "build/css/min/styles.min.css", // Using path.resolve( path.join( ... ) ) is a good idea here
	                buffer: 800*1024,
	                ignoreConsole: true
	            }
	        }
	    },


		watch: {
		  options: {
		    dateFormat: function(time) {
		      grunt.log.writeln('\n★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ \nAll tasks completed. \nEverything took: ' + time + ' seconds. \n★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ ★ \n');
			  grunt.log.writeln('Waiting for more changes...');
		    },
		  },
		  scripts: {
		    files: ['<%= globalConfig.js %>/*.js', '<%= globalConfig.scss %>/*.scss'],
			tasks: ['jshint', 'concat', 'uglify', 'imagemin', 'sass', 'autoprefixer', 'bless', 'version', 'cssmetrics', 'clean'],
		  },
		},
	});

	// Load up all the tasks
	// ===================================================================

	grunt.loadNpmTasks('grunt-contrib-concat'); // Concatenation
	grunt.loadNpmTasks('grunt-contrib-watch'); // Watch
	grunt.loadNpmTasks('grunt-contrib-uglify'); // JS Minify
	grunt.loadNpmTasks('grunt-contrib-imagemin'); // Optimise images
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-jshint'); // Error check JS
	grunt.loadNpmTasks('grunt-autoprefixer'); // Add vendor prefixes to the CSS
	grunt.loadNpmTasks('grunt-newer'); // Checks for newer files
	grunt.loadNpmTasks('grunt-contrib-clean'); // Clean up temporary files
	// grunt.loadNpmTasks("grunt-modernizr"); // Create a Modernizr file
	grunt.loadNpmTasks('grunt-exec'); // Execute commands
	grunt.loadNpmTasks('grunt-contrib-cssmin'); // Minify css
	grunt.loadNpmTasks('grunt-wp-assets'); // Minify css
	grunt.loadNpmTasks('grunt-concurrent'); // Run some tasks concurrently.
	grunt.loadNpmTasks('grunt-css-metrics'); // return some metrics
	grunt.loadNpmTasks('grunt-bless');
	grunt.loadNpmTasks('grunt-criticalcss');

	require('time-grunt')(grunt);

	// Force run the tasks
	// ===================================================================

	grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'imagemin', 'sass', 'autoprefixer', 'bless', 'version', 'cssmetrics', 'clean', 'watch']);
	grunt.registerTask('critical', ['criticalcss']);
};
