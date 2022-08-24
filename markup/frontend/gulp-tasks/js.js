/**
 *
 * @param {object} path - пути компиляции
 * @param gulp
 * @param {object} global - глобальные переменные
 * @param {object} plugins - gulp плагины
 * @param {boolean} dev - флаг
 * @returns {Function} - возврат таска
 */

module.exports = function (path, gulp, plugins, dev) {
	return function () {
		// Такс компиляции js файлов
		var jsMinMap = gulp.src(path.src.jsMinMap)
			.pipe(plugins.plumber({errorHandler: plugins.notify.onError({
				message: "<%= error.message %>",
				title  : "JS Error!"
			})}))
			.pipe(plugins.if(dev, plugins.sourcemaps.init())) // Если dev - инициализируем запись sourcemaps
			.pipe(plugins.include()) // Обрабатываем подключения файлов
			// .pipe(plugins.if(dev, plugins.sourcemaps.write('../maps'))) // Если dev - пишем sourcemaps в отдельный файл
			.pipe(plugins.eol("\r\n")) // Меняем формат конца строки
            .pipe(gulp.dest(path.build.js)) // Выкладываем файлы в dist
			// .pipe(plugins.if(!dev, plugins.uglify())) // Если не dev - минифицируем файлы
			// .pipe(plugins.if(!dev, plugins.rename({
			// 	suffix: '.min' // Если не dev - добавляем суффикс .min
			// })))
			// .pipe(plugins.if(!dev, gulp.dest(path.build.js))); // Если не dev - выкладываем минифицированные файлы в dist

		// Такс компиляции статичных js файлов с префиксом
		var js = gulp.src(path.src.js)
			.pipe(plugins.plumber({errorHandler: plugins.notify.onError({
				message: "<%= error.message %>",
				title  : "JS Error!"
			})}))
			.pipe(plugins.rename(function(path) {
				path.basename = path.basename.replace(/_/, ''); // Удаляем префикс _
				return path;
			}))
            .pipe(plugins.eol("\r\n")) // Меняем формат конца строки
			.pipe(gulp.dest(path.build.js));

		return plugins.merge(jsMinMap, js); // Мержим оба таска в один
	};
};