// set PATH=./node_modules/.bin;%PATH%
var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();
var browserSync = require('browser-sync');
plugins.emitty = require('emitty').setup('src', 'pug');
plugins.path = require('path');
plugins.merge = require('merge-stream');
plugins.pngquant = require('imagemin-pngquant');
plugins.del = require('del');
plugins.gpath = require('path');
plugins.eol = require('gulp-eol');

global.svgFileName = undefined;

var serverConfig = {
	server: {
		baseDir: "./dist"
	},
	ui: false,
	host: 'localhost',
	port: 9000,
	open: false,
	ghostMode: {
		clicks: false,
		forms: false,
		scroll: false
	}
};

var path = {
	svg: {
		svgFiles: './src/svg/**/*.svg',
		unnecessary: 'unnecessary-svg/svg-sprite.svg',
		template: './src/sass/templates/_svg-tmplt.sass',
		templateDest: 'sass/parts/_sprite.sass'
	},
	build: {
		pug: 'dist',
		// less:	'dist/css',
		sass: 'dist/css',
		js: 'dist/js',
		fonts: 'dist/fonts',
		images: 'dist/images',
		svgTransfer: 'dist/images/svg',
		jsonTransfer: 'dist/json'
	},
	src: {
		pug: 'src/*.pug',
		// less:	'src/less/*.less',
		sass: [
			'src/sass/*.sass',
			'src/sass/*.scss'
		],
		jsMinMap: [
			'./src/js/*.js',
			'!./src/js/_*.js'
		],
		js: './src/js/_*.js',
		fonts: 'src/fonts/**/*.*',
		images: 'src/images/**/*.*',
        favicon: 'src/favicon/**/*.*',
		svgTransfer: 'src/svg-transfer/**/*.*',
		jsonTransfer: 'src/json/**/*.*'
	},
	watch: {
		svgFiles: './src/svg/**/*.svg',
		pug: 'src/**/*.pug',
		less: 'src/less/**/*.*',
		sass: [
			'src/sass/**/*.sass',
			'src/sass/**/*.scss'
		],
		js: 'src/js/**/*.*',
		fonts: 'src/fonts/**/*.*',
		images: 'src/images/**/*.*',
		svgTransfer: 'src/svg-transfer/**/*.*',
        jsonTransfer: 'src/json/**/*.*'
	}
};


gulp.task('webserver', function () {
	browserSync(serverConfig);
});

gulp.task('reload', function (cb) {
	browserSync.reload();
	cb();
});

gulp.task('clean', function () {
	return plugins.del(['./dist', './src/unnecessary-svg']);
});

gulp.task('clean-svg', function () {
	return plugins.del(['./src/unnecessary-svg']);
});

gulp.task('fonts', require('./gulp-tasks/transfer')(gulp, plugins, path.src.fonts, path.build.fonts));
gulp.task('favicon', require('./gulp-tasks/transfer')(gulp, plugins, path.src.favicon, path.build.pug));
gulp.task('svgTransfer', require('./gulp-tasks/transfer')(gulp, plugins, path.src.svgTransfer, path.build.svgTransfer));
gulp.task('jsonTransfer', require('./gulp-tasks/transfer')(gulp, plugins, path.src.jsonTransfer, path.build.jsonTransfer));

gulp.task('pug:dev', require('./gulp-tasks/pug')(path, gulp, global, plugins, true));
gulp.task('pug:prod', require('./gulp-tasks/pug')(path, gulp, global, plugins, false));

// gulp.task('less:dev', require('./gulp-tasks/less')(path, gulp, plugins, true));
// gulp.task('less:prod', require('./gulp-tasks/less')(path, gulp, plugins, false));

gulp.task('sass:dev', require('./gulp-tasks/sass')(path, gulp, plugins, true));
gulp.task('sass:prod', require('./gulp-tasks/sass')(path, gulp, plugins, false));

gulp.task('js:dev', require('./gulp-tasks/js')(path, gulp, plugins, true));
gulp.task('js:prod', require('./gulp-tasks/js')(path, gulp, plugins, false));

gulp.task('images:dev', require('./gulp-tasks/transfer')(gulp, plugins, path.src.images, path.build.images));
gulp.task('images:prod', require('./gulp-tasks/images')(path, gulp, plugins));

gulp.task('svg-styles', require('./gulp-tasks/svg-styles')(path, gulp, plugins));
gulp.task('svg-store', require('./gulp-tasks/svg-store')(path, gulp, plugins, svgFileName));

gulp.task('watch', function () {
	// Shows that run "watch" mode
	global.watch = true;

	gulp.watch(path.watch.pug, gulp.series('pug:dev', 'reload'))
		.on('all', function (event, filepath) {
			global.emittyChangedFile = filepath;
		});

	// gulp.watch(path.watch.less, gulp.series('less:dev', 'reload'));
	gulp.watch(path.watch.sass, gulp.series('sass:dev', 'reload'));
	gulp.watch(path.watch.js, gulp.series('js:dev', 'reload'));
	gulp.watch(path.watch.images, gulp.series('images:dev', 'reload'));
    gulp.watch(path.watch.svgTransfer, gulp.series('svgTransfer', 'reload'));
    gulp.watch(path.watch.jsonTransfer, gulp.series('jsonTransfer', 'reload'));

	gulp.watch(path.watch.svgFiles, gulp.series('svg-styles', 'svg-store', 'sass:dev', 'reload'))
		.on('all', function (event, filepath) {
			console.log(filepath);
			var myregexp = /.*[\/\\]([^_]+)/;
			var match = myregexp.exec(filepath);
			global.svgFileName = match[1];

		});
});

gulp.task('build:dev', gulp.series(
	'clean',
	'pug:dev',
	'svg-styles',
	'svg-store',
	// 'less:dev',
	'sass:dev',
	gulp.parallel(
		'js:dev',
		'fonts',
        'favicon',
		'images:dev',
		'svgTransfer',
        'jsonTransfer'
	)
));

gulp.task('build:prod', gulp.series(
	'clean',
	'pug:prod',
	'svg-styles',
	'svg-store',
	// 'less:prod',
	'sass:prod',
	gulp.parallel(
		'js:prod',
		'fonts',
		'favicon',
		'images:prod',
		'svgTransfer',
        'jsonTransfer'
	),
	'clean-svg'
));

gulp.task('dev', gulp.series(
	'build:dev',
	gulp.parallel('watch', 'webserver')
));

gulp.task('build', gulp.series(
	'build:prod'
));
