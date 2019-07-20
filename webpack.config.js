const path = require("path");
const { VueLoaderPlugin } = require("vue-loader");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
var ManifestPlugin = require("webpack-manifest-plugin");

const mode =
  process.env.NODE_ENV && process.env.NODE_ENV !== "production"
    ? process.env.NODE_ENV
    : "production";

module.exports = {
  mode: mode,
  entry: {
    app: "./resources/js/app.js"
  },
  output: {
    filename:  mode === "production" ? "[name].[chunkhash].js" : "[name].js",
    chunkFilename:
      mode === "production" ? "chunk/[name].[chunkhash].js" : "chunk/[name].js",
    publicPath: "/build/",
    path: path.resolve(__dirname, "public/build")
  },
  devtool: mode === "production" ? "" : "eval-source-map",
  resolve: {
    alias: {
      "~": path.resolve(__dirname, "resources/js/")
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader"
      },
      {
        test: /\.m?js$/,
        exclude: file => /node_modules/.test(file) && !/\.vue\.js/.test(file),
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"]
          }
        }
      },
      {
        test: /\.css$/,
        use: [
          "vue-style-loader",
          {
            loader: "css-loader",
            options: { importLoaders: 1 }
          },
          "postcss-loader"
        ]
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin({
      dry: false,
      verbose: false,
      cleanStaleWebpackAssets: true,
      cleanOnceBeforeBuildPatterns: ["**/*"],
      cleanAfterEveryBuildPatterns: []
    }),
    new ManifestPlugin(),
    new VueLoaderPlugin()
  ]
};
