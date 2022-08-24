/**
 * 
 * @param gulp
 * @param {string} filesPathFrom - путь откуда переносить
 * @param {string} filesPathTo - путь куда переносить
 * @returns {Function} - возврат таска
 */
module.exports = function (gulp, plugins, filesPathFrom, filesPathTo) {
	return function () {
		return gulp.src(filesPathFrom)
			.pipe(plugins.plumber({errorHandler: plugins.notify.onError({
				message: "<%= error.message %>",
				title  : "TRANSFER Error!"
			})}))
			.pipe(gulp.dest(filesPathTo));
	}
};