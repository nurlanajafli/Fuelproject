module.exports = function (path, gulp, plugins) {
	return function () {
		return gulp.src(path.src.images)
			.pipe(plugins.imagemin([
				plugins.imagemin.gifsicle(),
				plugins.imagemin.jpegtran(),
				plugins.pngquant(),
				plugins.imagemin.svgo()
			]))
			.pipe(gulp.dest(path.build.images));
	}
};