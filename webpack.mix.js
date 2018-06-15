let mix = require('laravel-mix');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.webpackConfig({
module: {
    rules: [
    {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract({
        fallback: 'style-loader',
        use: 'css-loader'
        })
    }
    ]
}
});