module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            css: {
                files: [
                    'sass/*.scss', 'sass/common/*.scss', 
                    'sass/app/*.scss', 
                    'sass/app/home/*.scss', 
                    'sass/app/rets/*.scss',
                    'sass/app/schoolarea/*.scss',
                    'sass/app/news/*.scss',
                    'sass/app/yellowpage/*.scss'
                ],
                tasks: ['compass']
            },
            js: {
                files: [
                    'js/app.js',
                    'js/gmap.js',
                    'Gruntfile.js'
                ],
                tasks: ['uglify']
            }
        },
        compass: {
            dist: {
                options: {
                    sourcemap: true,
                    sassDir: 'sass',
                    cssDir: '../static/css',
                    outputStyle: 'compressed'
                }
            }
        },
        uglify: {
            options: {
                banner: '/*! 版权所有，Wesnail */\n'
            },
            build: {
                src: ['js/app.js', 'js/gmap.js'],
                dest: '../static/js/app.js'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    

    grunt.registerTask('default', ['watch']);

};