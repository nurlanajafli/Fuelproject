/**
 *
 * @param gulp
 * @param {object} plugins - gulp плагины
 * @param {string} flag - флаг таска
 * @returns {Function} - возврат таска
 */

module.exports = function (gulp, plugins, flag) {
	// Очистка дирректории dist
	return function () {
		return plugins.del('./dist/**');
	};
};