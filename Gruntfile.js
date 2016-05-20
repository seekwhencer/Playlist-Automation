/**
 * Gruntfile for Playlist Automation
 * 
 * Matthias Kallenbach
 * 2016
 * 
 * Installation:
 * 
 * npm install grunt --save-dev
 * npm install grunt-contrib-jshint --save-dev
 * npm install grunt-contrib-less --save-dev
 * npm install grunt-contrib-watch --save-dev
 * npm install grunt-bower-task --save-dev
 * npm install grunt-bowercopy --save-dev
 * npm install grunt-contrib-csslint --save-dev
 * npm install grunt-sync --save
 * 
 */
module.exports = function(grunt) {

    grunt.initConfig({
        jshint : {
            files : ['Gruntfile.js', 'src/**/*.js'],
            options : {
                globals : {
                    jQuery : true
                }
            }
        },

        csslint : {
            lax : {
                options : {
                    import : false,
                    quiet: false
                },
                src : [
                    'src/**/*.css',
                    '!src/css/radio.bootstrap.css'
                ]
            }
        },

        less : {
            build : {
                files : {
                    'build/css/page.css' : 'src/css/page.less'
                }
            }
        },

        bowercopy : {
            options : {
                srcPrefix : 'bower_components'
            },

            scripts : {
                options : {
                    destPrefix : 'build/js/assets'
                },
                files : {
                    'jquery/jquery.js' : 'jquery/dist/jquery.js',
                    'jquery/jquery.min.js' : 'jquery/dist/jquery.min.js',
                    'bootstrap/bootstrap.js' : 'bootstrap/dist/js/bootstrap.js',
                    'bootstrap/bootstrap.min.js' : 'bootstrap/dist/js/bootstrap.min.js'
                }
            },

            fonts : {
                options : {
                    destPrefix : 'build/css'
                },
                files : {
                    'fonts' : 'fontawesome/fonts/**/*',
                }
            },

            styles : {
                options : {
                    destPrefix : 'build/css/assets'
                },
                files : {
                    'font-awesome.min.css' : 'fontawesome/css/font-awesome.min.css'
                }
            },

        },

        watch : {

            scripts : {
                files : ['src/**'],
                tasks : ['jshint','sync'],
                options : {
                    spawn : false,
                }
            },

            less : {
                files : ['src/css/**/*.less'],
                tasks : ['less','sync'],
                options : {
                    spawn : false,
                }
            },

            css : {
                files : ['src/css/**/*.css', '!src/css/radio.bootstrap.css'],
                tasks : ['csslint','sync'],
                options : {
                    spawn : false,
                }
            }

        },

        sync : {
            /*
             * Called on every watch 
             */
            build : {
                files : [{
                    expand: true,
                    cwd: 'src/',
                    src: [ 
                        '**',
                        '!**/*.less',
                        '!data/**/*.json',
                        '!data/**/*.txt',
                        '!data/**/*.m3u',
                        '!.htaccess'
                    ],
                    dest: 'build/'
                }],
                updateAndDelete: false
            },
            
            
            /**
             * Called in the console as "grunt sync:htdocs" or "grunt sync"
             */
            htdocs : {
                files : [{
                    expand: true,
                    cwd: 'build/',
                    src: [
                        '**',
                        '!data/**/*.json',
                        '!data/**/*.txt',
                        '!data/**/*.m3u',
                        '!.htaccess'
                    ],
                    dest: 'htdocs/'
                }],
                updateAndDelete: true,
                ignoreInDest: [
                    'data/**/*.json',
                    'data/**/*.txt',
                    'data/**/*.m3u',
                    '.htaccess'
                ],
            },
        }

    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-csslint');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-sync');

    grunt.registerTask('default', ['jshint', 'watch', 'bowercopy', 'less', 'csslint', 'sync']);
    //grunt.registerTask('default', ['jshint', 'watch', 'bowercopy', 'less', 'csslint', 'sync']);
    
    
    /**
     * Export the "build" folder in the "htdocs" folder
     * 
     * call "grunt export"
     * 
     * before downloading all dependencies
     * create the less css files
     * and sync the source with the "build" folder
     * after that, sync the "build" folder with the "htdocs" folder
     * 
     */
    grunt.registerTask('export', ['bowercopy', 'less', 'sync']);

};

