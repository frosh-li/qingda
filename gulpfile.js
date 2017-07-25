var gulp = require('gulp');
var concat = require('gulp-concat');                            //- 多个文件合并为一个；
var minifyCss = require('gulp-minify-css');                     //- 压缩CSS为一行；

gulp.task('concat', function() {                                //- 创建一个名为 concat 的 task
    gulp.src([
    		'css/jquery.dataTables.css', 
    		'libs/jqueryui/jquery-ui.css',
    		'css/jqtransform.css',
    		'css/jquery.mCustomScrollbar.css',
    		'css/zTreeStyle.css',
    		'css/pnotify.css',
    		'css/ext.css',
    		'css/main.css'
    		])    //- 需要处理的css文件，放到一个字符串数组里
        .pipe(concat('main.min.css'))                            //- 合并后的文件名
        .pipe(minifyCss())                                      //- 压缩处理成一行
        .pipe(gulp.dest('./dist/'))                               //- 输出文件本地
});

gulp.task('default', ['concat']);
