const path              = require("path");
const argv              = require("yargs")["argv"];
const webpack           = require("webpack");
const ExtractTextPlugin = require("extract-text-webpack-plugin");

let scriptSources = require("./tasks/script-sources");
let styleSources  = require("./tasks/style-sources");

let compress = (typeof argv.p !== "undefined" && argv.p);

let extractStyles = new ExtractTextPlugin({filename: "[name].css", allChunks: true,});

let config = [
	{
		context: path.resolve(__dirname, "assets/src/scripts"),
		entry: scriptSources,
		output: {
			path: path.resolve(__dirname, "assets/dist/js"),
			publicPath: "assets/dist/js/",
			filename: "[name].bundle.js",
		},
		resolve: {
			extensions: [".webpack.js", ".web.js", ".ts", ".tsx", ".js",],
			alias: {
				"vue$": "vue/dist/vue" + (compress ? ".min" : "") + ".js",
				"vue-resource": "vue-resource/dist/vue-resource.js",
			},
		},
		module: {
			rules: [
				{enforce: "pre", test: /\.js$/, use: "source-map-loader", exclude: /node_modules/,},
				{test: /\.js$/, loader: "babel-loader", exclude: [/node_modules/,], query: {presets: ["latest",],}},
				{test: /\.tsx?$/, loader: "ts-loader", exclude: [/node_modules/,],},
				{test: /\.vue$/, loader: "vue-loader", exclude: [/node_modules/,],},
				{test: /\.html?$/, loader: "html-loader", exclude: [/node_modules/,],},
				{test: /\.pug$/, loaders: ["raw-loader", "pug-html-loader"], exclude: [/node_modules/,],},
				{test: /\.scss$/, loaders: ["style-loader", "css-loader", "sass-loader",]},
			]
		},
		externals: {},
		plugins: [
			new webpack.optimize.CommonsChunkPlugin({
				name: "vendor",
				filename: "vendor.bundle.js",
				minChunks: Infinity,
			}),
		],
		devtool: "source-map",
	},
	{
		context: path.resolve(__dirname, "assets/src/styles"),
		entry: styleSources,
		output: {
			path: path.resolve(__dirname, "assets/dist/css"),
			filename: "[name].bundle.js",
		},
		resolve: {
			extensions: [".s[ac]ss", ".css",]
		},
		module: {
			rules: [
				{
					test: /\.css$/,
					loader: extractStyles.extract(["css-loader?sourceMap",]),
				},
				{
					test: /\.s[ac]ss$/,
					loader: extractStyles.extract(["css-loader?sourceMap", "sass-loader?sourceMap",]),
				},
			]
		},
		plugins: [
			extractStyles,
		],
		devtool: "source-map",
	},
];

module.exports = config;
