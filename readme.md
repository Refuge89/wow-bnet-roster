**Required changes to run this WordPress plugin:**

1) Change to root directory 'wp-template' name and use your own plugin name
2) Refactor all files named 'wp-template.*' to use the same name as the root directory set in #1
    - wp-template.php
    - css/wp-template.css
    - languages/wp-template.pot
    - js/wp-template.js
    - Don't forget to search for references of the files above and update them too
3) Refactor the prefix of all classes and functions to a new unique prefix which is associated with your plugin name (simply use the first 2 or 3 letters of your plugins name)
    - We recommend to use a prefix written in all uppercase
4) Replace 'WP_Template' in the php/classes/Ajax.php file with your plugin name (without whitespaces)
5) Replace 'wp_template_textdomain' in all files with your plugins name (without whitespaces)
6) Update all defines set in the config.php
7) Refactor all defines using the same prefix as you used for classes and functions in #3
8) Update the project name in the *.pot file of the plugin
9) Add the keyword used for translations in the *.pot file using the *_trans function
10) Add your own styling the in the *.css file included in the css/ directory
11) Add your own scripts the in the *.js file included in the js/ directory
12) Create a language file (*.po and *.mo) for all languages you like to support
    - We recommend to use POEdit for translations