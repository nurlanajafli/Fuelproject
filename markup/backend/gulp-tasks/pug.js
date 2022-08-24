/**
 *
 * @param {object} path - пути компиляции
 * @param gulp
 * @param {object} global - глобальные переменные
 * @param {object} plugins - gulp плагины
 * @param {boolean} dev - флаг
 * @returns {Function} - возврат таска
 */

module.exports = function (path, gulp, global, plugins, dev) {
	if(dev){
		return function () {
			// https://www.npmjs.com/package/emitty
			return new Promise(function(resolve, reject){
				plugins.emitty.scan(global.changedStyleFile).then(function(){
					gulp.src(path.src.pug)
						.pipe(plugins.plumber({errorHandler: plugins.notify.onError({
							message: "<%= error.message %>",
							title  : "PUG Error!"
						})}))
						.pipe(plugins.if(global.watch, plugins.emitty.filter(global.emittyChangedFile)))
						.pipe(plugins.pug({ pretty: true }))
						.pipe(gulp.dest(path.build.pug))
						.on('end', resolve)
						.on('error', reject);
				});
			});
		}
	}else{
		return function () {
			return gulp.src(path.src.pug)
				.pipe(plugins.plumber({errorHandler: plugins.notify.onError({
					message: "<%= error.message %>",
					title  : "PUG Error!"
				})}))
				.pipe(plugins.pug({ pretty: true })) // компиляция pug файлов
				.pipe(gulp.dest(path.build.pug));
		}
	}
};