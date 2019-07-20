const purgecss = require("@fullhuman/postcss-purgecss")({
  // Specify the paths to all of the template files in your project
  content: [
    "./public/**/*.html",
    "./resources/js/components/**/*.vue",
    "./resources/js/**/*.jsx",
    "./resources/view/**/*.twig",
    "./resources/view/**/*.blade.php"
    // etc.
  ],

  // Include any special characters you're using in this regular expression
  defaultExtractor: content => content.match(/[A-Za-z0-9-_:/]+/g) || []
});

module.exports = {
  plugins: [
    // ...
    require("postcss-easy-import"),
    require("tailwindcss"),
    require("postcss-nested"),
    require("postcss-css-variables"),
    require("autoprefixer"),
    ...(process.env.NODE_ENV === "production" ? [purgecss] : [])
    // ...
  ]
};
