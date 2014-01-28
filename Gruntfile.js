module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      build: {
        files: {
        'build/public/js/app.min.js': ['build/public/js/app.js', 'build/public/vendor/dsinmessagebox/dsinMessageBox.js'],
        'build/public/vendor/nProgress/nProgress.min.js': ['build/public/vendor/nProgress/nProgress.js'],
        'build/public/vendor/parsley/parsley.extend.min.js': ['build/public/vendor/parsley/parsley.extend.js'],
        'build/public/vendor/dynaTable/jquery.dynatable.min.js': ['build/public/vendor/dynaTable/jquery.dynatable.js'],
        }
      }
    },
    jshint: {
      all: ['Gruntfile.js', 'public/js/**/*.js', 'public/vendor/dsinmessagebox/**/*.js']
    },
    jslint: { // configure the task
          // lint your project's client code
          client: {
            src: [
              'public/js/**/*.js',
              'public/vendor/dsinmessagebox/**/*.js'
            ],
            directives: {
              browser: true,
              plusplus: true,
              unparam: true,
              bitwise: true,
              predef: [
                'jQuery', '$', 'NProgress', 'dsinMsgBox'
              ]
            }
          }
    },

    inlinelint: {
      html: ['application/views/scripts/**/*.html'],
      php: ['application/views/scripts/**/*.phtml']
    },

    copy: {

      buildAll: {
        files: [
              // Application Files - includes files within path
              {expand: true, src: ['application/**'], dest: 'build/'},

              {expand: true, src: ['public/**'], dest: 'build/'},
              {src: ['public/.htaccess'], dest: 'build/public/.htaccess'},

              // Libraries
              // ---------

              // TCPDF: 
              {expand: true, src: ['vendor/tcpdf/*.php', 'vendor/tcpdf/config/**', 'vendor/tcpdf/fonts/**', 'vendor/tcpdf/include/**'], dest: 'build/'},

              // Zend: 
              {expand: true, src: ['vendor/Zend/**'], dest: 'build/'},
              {expand: true, src: ['vendor/ZendX/**'], dest: 'build/'},

        ]
      },

      build: {
        files: [
              // Application Files - includes files within path
              {expand: true, src: ['application/**'], dest: 'build/'},

              {expand: true, src: ['public/**'], dest: 'build/'},
              {src: ['public/.htaccess'], dest: 'build/public/.htaccess'},

        ]
      },

    },

    clean: {
      build: ["build/application", "build/public"],
      buildAll: ["build"],
    },

    csslint: {
      strict: {
        options: {
          "box-sizing": false,
          import: 2
        },
        src: ['public/css/*.css']
      },
      lax: {
        options: {
          "box-sizing": false,
          import: false
        },
        src: ['public/css/*.css']
      }
    },

    useminPrepare: {
      html: ['build/application/views/scripts/index/index.phtml']
    },
    usemin: {
      html: ['build/application/views/scripts/index/index.phtml', 'build/application/views/scripts/web-apps/index.phtml']
    },

    cssmin: {
      minify: {
        files: {
          'build/public/css/app.min.css': ['build/public/css/app.css', 
                                           'build/public/vendor/yamm3/yamm.css', 
                                           'build/public/vendor/nProgress/nProgress.css',
                                           'build/public/vendor/dynaTable/jquery.dynatable.css']
        }
      }
    }


  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.loadNpmTasks('grunt-contrib-jshint');

  grunt.loadNpmTasks('grunt-jslint');

  grunt.loadNpmTasks('grunt-contrib-csslint');

  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.loadNpmTasks('grunt-contrib-clean');

  grunt.loadNpmTasks('grunt-lint-inline');

  grunt.loadNpmTasks('grunt-usemin');

  grunt.loadNpmTasks('grunt-contrib-cssmin');


  grunt.registerTask('default', ['jshint','jslint', 'inlinelint', 'csslint']);

  grunt.registerTask('build', ['clean:build', 'default', 'copy:build', 'uglify:build', 'usemin', 'cssmin']);

  grunt.registerTask('buildAll', ['clean:buildAll', 'copy:buildAll']);

};