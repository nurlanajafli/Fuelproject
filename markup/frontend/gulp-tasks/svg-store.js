module.exports = function (path, gulp, plugins, svgFileName) {
	return function () {
		var svgSpritesName = ['sprite', 'challenge'];
		if(global.svgFileName !== undefined){
			svgSpritesName = [global.svgFileName];
		}

		var svgStoreMerge = plugins.merge();

		svgSpritesName.forEach(function(item, i, arr){
			svgStoreMerge.add(gulp.src('./src/svg/**/' + item + '_*.svg')
				.pipe(plugins.svgmin(function (file) {
					var prefix = plugins.gpath.basename(file.relative, plugins.gpath.extname(file.relative));
					return {
						plugins: [{
							cleanupIDs: {
								prefix: prefix + '-',
								minify: true
							}
						}]
					}
				}))
				.pipe(plugins.svgstore({ inlineSvg: true }))
				.pipe(plugins.rename({
					basename: item,
					suffix: '_icons',
					extname: '.svg'
				}))
				.pipe(gulp.dest('./dist/svgmin')));
		});

		return svgStoreMerge;
	}
};