const baseConfig = require('./webpack.base.config');
const path = require('path');

// Go up one level since this file is in webpack/ directory
const rootPath = path.resolve(__dirname, '..');

module.exports = {
	...baseConfig,
	name: 'spa',
	entry: {
		backend: path.resolve(rootPath, 'src/backend/dashboard.js'),
	},
};
