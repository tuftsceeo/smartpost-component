<?php
if (!class_exists("sp_catMyComponent")) {

    /**
     * The first steps in creating a SmartPost component are to define your "sp_cat" and
     * "sp_post" classes. Each class serves a different purpose. The classes prefixed with "sp_cat"
     * are associated with the administrative dashboard interface, the classes prefixed with "sp_post"
     * correspond to rendering components on the posts themselves, i.e. on the "front-end" of the site.
     *
     * Note that both the "sp_cat" and "sp_post" classes inherit from abstract classes: "sp_catComponent" and
     * "sp_postComponent" respectively. I highly recommend to go into these abstract classes and look at what they do;
     * a lot of the "behind scenes" of SmartPost happens inside these classes.
     *
     * @see sp_catComponent
     */
    class sp_catMyComponent extends sp_catComponent{

        /**
         * The constructor is used both for creating a new component and loading an existing one. If a
         * component ID is passed in to the constructor, then it will attempt to load that component.
         *
         * @param int $compID  - The component ID, if a value > 0 is given, then it will attempt to load that component)
         * @param int $catID   - The category associated with the component
         * @param string $name - The component name
         * @param string $description - The component description
         * @param int $typeID    - The type ID of the component
         * @param int $order     - The order at which the component appears in the template
         * @param null $options  - Options that can be saved and retrieved from the database
         * @param bool $default  - This flag decides if the component is rendered by default or not when the template is
         *                         generated on the front-end.
         * @param bool $required - Whether the component is required to be filled in by the user prior to submitting.
         *                         Also prohibits the user from deleting the component.
         */
        function __construct($compID = 0, $catID = 0, $name = '',
                             $description = '', $typeID = 0, $order = 0,
                             $options = null, $default = false, $required = false){

            $compInfo = compact("compID", "catID", "name", "description", "typeID",
                "options",	"order", "default", "required");

            $this->initComponent($compInfo);
        }

        /**
         * The method gets called when SmartPost is first activated and makes SmartPost aware of the name of the
         * component, its description, and where it resides. This method is inherited from the abstract
         * "sp_catComponent" class.
         *
         * Note: The name of the component should not have any spaces in it.
         *
         * @see parent::installComponent()
         */
        function install(){
            self::installComponent("Content", "Rich text editor.", __FILE__);
        }

        /**
         * In future versions, this method will be called when SmartPost is uninstalled. Right now it doesn't do
         * anything.
         * @see sp_uninstall class
         * @see parent::uninstall()
         */
        function uninstall(){}

        /**
         * Used to initialize the component, I use this method to enqueue any styles or JavaScript libraries that
         * the component depends on. I split up these actions into the "equeueCSS" and "enqueueJS" methods, but that
         * is optional.
         *
         * This is also where I initialize my AJAX classes that handle AJAX calls. The AJAX class should have its own
         * init() method that registers AJAX handlers. That way when this component is initialized, it will also be
         * ready to process any incoming AJAX calls from the client's browser.
         */
        static function init(){

            // Include the AJAX class and initialize it
            require_once( dirname( __FILE__ ) . '/ajax/sp_catMyComponentAJAX.php');
            sp_catAttachmentsAJAX::init();

            // Include any CSS + JS
            self::enqueueCSS();
            self::enqueueJS();
        }

        /**
         * Add content component JS
         */
        static function enqueueJS(){

            /**
             * Here is where you can register any JS libraries associated with the "admin" portion of your component.
             * Note that I prefixed the JS file with "sp_cat" to denote that it is associated with administrative
             * actions.
             *
             * The third parameter in 'wp_register_script' defines JS library dependencies. SmartPost has three JS
             * dependencies that every JS library should depend on:
             *  1) jquery
             *  2) sp_admin_globals - Global SmartPost JS vars
             *  3) sp_admin_js - Admin-side vars and methods
             *  4) sp_catComponentJS - JS vars and methods that are available to be used for new components
             */
            wp_register_script(
                'sp_catMyComponentJS',
                plugins_url('/js/sp_catMyComponent.js', __FILE__),
                array('jquery', 'sp_admin_globals', 'sp_admin_js', 'sp_catComponentJS')
            );
            wp_enqueue_script( 'sp_catMyComponentJS' );
        }

        /**
         * Add CSS files
         */
        static function enqueueCSS(){
            wp_register_style( 'sp_catMyComponentCSS', plugins_url('/css/sp_catMyComponent.css', __FILE__));
            wp_enqueue_style( 'sp_catMyComponentCSS' );
        }

        /**
         * Renders the HTML of the component in the template view. If there is some configurable settings that need
         * to be set, i.e. form input elements, then they should be rendered here.
         * @see parent::componentOptions()
         */
        function componentOptions(){
            $options = $this->options;
            ?>
            <p>No options exist for this component.</p>
        <?php
        }

        /**
         * Setter method for settings options for this component. I recommend to use the sp_core::updateVar
         * method to save any data to the database. The table associated with 'sp_cat' options is called
         * 'sp_catComponents'. Before saving the data, I would recommend to serialize it, especially if you have
         * a datastructure such as a PHP object or an array.
         *
         * @see parent::setOptions()
         */
        function setOptions($data = null){
            $data = maybe_serialize( $data );
            return sp_core::updateVar('sp_postComponents', $this->ID, 'value', $data, '%s');
        }

        /**
         * Accessor method that returns the options for this component.
         * @see parent::getOptions()
         */
        function getOptions(){
            return $this->options;
        }

        /**
         * This method is responsible for the HTML that will be rendered in the SmartPost->Settings section in the
         * Dashboard. If there are any global component settings that need to be configured, then this is where you
         * should render them. If no global options exist, return false.
         *
         * @return bool|string
         */
        public static function globalOptions(){
            return false;
        }
    }
}
?>