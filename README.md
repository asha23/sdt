AKA CORE WORDPRESS INSTALLATION
===============================

This is the core Wordpress installation for AKA websites


AKA BOOTSTRAP BASE THEME - COMPILER INSTRUCTIONS
------------------------------------------------

Below are some instructions to get started. This theme uses nodejs building systems.
You will need nodejs and npm installed on your computer before starting: https://nodejs.org/en/
You will also need to install the builder you wish to use globally - if not done already (grunt or gulp).

To install grunt:

    npm install -g grunt


To install gulp:

    npm install -g gulp

On some machines you may need to sudo

    sudo npm install -g gulp

Step by step:
------------------------------------------------

This base theme uses Gulp by default. If you would like to use Grunt (and want your workflow to be really slow!) then simply rename package.json to package-gulp.json and change package-grunt.json to package.json.

In your terminal, cd into your theme directory and execute

    npm install

On some machines you may need to:

    sudo npm install

Once everything is finished, execute the name of the builder you choose, either grunt or gulp
   The default task should execute and generate everything in the build folder.
   The Grunt file automatically triggers a watch task. The gulp file requires you to trigger "gulp watch"

It should be all good now! Any problems ask a back-end developer. (or Ash)


Gulp commands
------------

    gulp watch
    gulp images
    gulp

Gulp watch will start the watch task and gulp images will run an image optimisation on the images folder (useful before deployment) - Run gulp on it's own to do the tasks once.

BOOTSTRAP SHOWS BASE THEME
------------------------------------------------

There is a now a shows base theme. This contains all the pages and custom fields required to get a show website up and running quickly. It is meant as a good starting point for show sites and can be extended and customised as you like.

It is based on the Bootstrap Base theme, so the basic setup is exactly the same as this.

You will need to activate the theme's specific custom fields when you enable it. You can do this by going to the Custom Fields overview page. You will see a *Sync Available* tab at the top, click on this. Select all the fields and they will auto populate the themes custom fields. This will import all the fields from acf-json and set them up for use in the theme.

There are custom post types for Staff and Galleries. The reviews page is also auto populated with the correct fields to create quotes. The correct templates for these pages now exist in the theme as well.

There is a page template for single gallery pages. The default is multiple galleries, but you can easily set up a single page. (The template is Single Gallery)

Notes and best practices
-----------

* You will find the Gulp compiler a lot faster than the Grunt compiler. The structure of the gulpfile is similar to the gruntfile when it comes to including your javascript dependencies.
* The Bootstrap config.scss file is now streamlined considerably. Only the grid and core elements are activated by default. You can turn on or off features within the Bootstrap library by enabling them as you require them, rather than them being enabled by default. Please be sure you need a set of styles before enabling them.
* Only activate what you require. Try and avoid going over the selector limit.
* Think about your code in terms of modules, blocks and elements. Keep your scss modular and make elements as re-usable as possible. If possible, create a global style guide containing all the elements of your site. This will help you identify elements that can be reused and extended. Utilise mixins where possible for reusable elements such as buttons
* Please inline your media queries where possible. When you use the gulp compiler, this automatically sorts your media queries and places them after the main styles, so you really don't need to have media queries separated from their modules.
* Do not use camelCase for css selectors and id's. use a dash notation, ie "class-style" - CamelCase should be utilised for javascript variables. Having one style for css and another style for javascript makes it easier to identify what is a css classname and what is a script variable/function etc.
* Structure and format your scss in a neat, formal and organised way. Remember, other people may need to work on your code and you may not be here to talk them through what you have done. Code should be as clear and easy to work on as possible - Don't be afraid to use comments throughout. It's better to have loads of comments than loads of obfuscation. If you think something might be tricky for someone else to work on. Comment it all like crazy!
* When picking a third party JavaScript library. Please make sure it is supported from IE8 and above (Yes. We still support IE8 at the moment - So don't use technologies which only work in modern browsers - No matter how snazzy they might be) - Progressively enhance from IE8 upwards.
* Work from mobile first where possible. This is where style-guides come in. Prototype for mobile first, then add features for larger resolutions.
* Prototype before design on items with complicated functionality. It is far easier to work up a design from something that works, than make something work from a design. Form follows function.
* When using Advanced Custom Fields, think carefully about how you are going to structure your cms frontend. Avoid too much nesting.
* Use custom post types where possible. (Ask a backend developer about how to set this up)
* Organise your functionality into clearly named template parts. Try and treat template parts as modular content. Reuse them where you can.


Bootstrap
-----------

Use the Bootstrap include methods for grid styles and media queries, eg:

    @media (max-width:$screen-sm-max)

And for the Bootstrap grid:

    @include make-md-column(6)

Not, eg:

    col-md-6

In your HTML - Try and make your css classes descriptive. This will mean that when someone else comes to edit your code, it will be easy for them to work out what something does.

This is a really good article about using BootStrap with SASS

http://www.hongkiat.com/blog/bootstrap-and-sass/

Notes about JavaScript libraries
-----------

There are a number of useful JavaScript libraries included in the base theme. You can find these in library/js/vendor-libs. These libraries have been arranged into folders by type. If you require any further libraries then save them out into this folder (in the correct category for the type of library you are using).

There are some useful libraries pre-installed. They come with all their dependencies in the right place. You simply need to include them in your gulp/grunt file and refer to the scss file for the library you need in the vendor-styles folder.

* js/vendor_libs/sliders/lightgallery-all.js (Lightgallery popup)
* js/vendor_libs/sliders/slick.js (nice carousel slider - Like BXSlider but works properly in IE8)
* js/vendor_libs/modals/magnific.js (modal window)
