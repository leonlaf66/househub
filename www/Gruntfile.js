// http://www.cnblogs.com/wymbk/p/5766064.html
module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      output: {
        options: {
          style: 'compressed'
        },
        files: {
          './static/css/main.css': './src/sass/main.scss'
        }
      }
    },
    copy: {
      main: {
        expand: true,
        cwd: './src/js/',
        src: ['**'],
        dest: './static/js/',
      },
    },
    jshint: {
      all: ['./src/js/*']
    },
    watch: {
      sass: {
        files: [
          './src/sass/**/*.scss',
          './src/sass/**/**/*.scss',
          './src/sass/**/**/**/*.scss',
          './src/sass/**/**/**/**/*.scss'
        ],
        tasks: ['sass']
      },
      js: {
        files: [
          'src/js/*.js',
          'src/js/**/*.js',
          'src/js/**/**/*.js',
        ],
        tasks: ['jshint', 'copy']
      }
    },
  });

  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['sass', 'jshint', 'copy', 'watch']);
};