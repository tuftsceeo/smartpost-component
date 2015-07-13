This is meant as a tutorial on how to extend SmartPost with your own custom component. The target audience
are WordPress developers. You should have proficient knowledge on how WordPress works, MySQL databases, OOP, AJAX, JS,
and of course PHP. If you find yourself lacking in knowledge in any of these topics, I'd recommend to do some basic
research in them before attempting at this tutorial!

The first steps in understanding how to create your own SmartPost (SP) component are to understand SmartPost itself.
Let's look at the directory of SmartPost:

smartpost-templates
 |_ajax
 |_components <-- This is where components live
 |_core
 |_css
 |_images
 |_js
 |_widgets

Let's look at the structure of a component:

mycomponent <- the directory name of your component, should not have any whitespace
 |_ajax
 |_css
 |_images
 |_js
 README.md
 sp_catMyComponent.php   <- The "administrative" side of your component
 sp_postMyComponent.php  <- The "front-end" side of your component

 * ajax   -> This dir has all the AJAX classes that register any AJAX handler using the `wp_ajax` wordpress action
 * css    -> Your component's CSS for styling
 * images -> This is where you should throw your `icon.png`, it should be 16px x 16px
 * js     -> JS corresponding to your component

This is the basic skeletal convention that all components use in SmartPost.
