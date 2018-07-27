const path = require("path");

const HtmlWebpackPlugin = require('html-webpack-plugin');

const webpack = require('webpack');

module.exports = function (env, args) {
    return {
        mode: env || 'development',
        entry: ["react-hot-loader/patch", "./src/app.js"],
        output: {
            filename: "app.js",
            path: path.resolve(__dirname, env === 'development' ? 'dist': 'public'),
            publicPath: '/',
        },
        module: {
            rules: [
                { test: /\.(js)$/, use: {
                    'loader': 'babel-loader',
                    'query': {
                        compact: false
                    }
                }
                },
                { test: /\.css$/, use: ['style-loader', 'css-loader']},
                { test: /\.html$/, use: ['html-loader']}
            ],
        },
        devServer: {
            contentBase: './src',
            inline: true,
            hot: true,
            host: '0.0.0.0',
            public: '192.168.56.101:8080'
        },
        plugins: [
            new webpack.ProvidePlugin({
                "React": "react",
            }),
            new HtmlWebpackPlugin({
                template: './src/index.html'
            }),
            new webpack.HotModuleReplacementPlugin()
        ]
    }
};