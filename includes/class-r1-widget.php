<?php
/**
 * The R1_Widget class.
 *
 * @package Resolution_Widget
 * @author  flooidCX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
	 * R1_Widget.
	 *
	 * @since 1.0.0
	 */
class Resolution1WP_Widget extends WP_Widget {

        private $options;
        private $styles = array();

		/**
		 * The constructor
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			$args = array(
				'classname'   => 'Resolution1WP_widget',
				'description' => __( 'Displays Resolution1 (R1) Contact Widget', 'resolution-widget' ),
			);

            parent::__construct( 'Resolution1WP_widget', __( 'Resolution1 (R1) Contact Widget', 'resolution-widget' ), $args );
            add_action( 'admin_menu', array( $this, 'Resolution1WP_add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'Resolution1WP_plugin_register_settings' ) );
        }

        /**
        * Add options page
        */
        public function Resolution1WP_add_plugin_page()
        {
            // This page will be under "Settings"
            add_options_page(
                'Settings',
                'Resolution1 (R1) Contact Widget',
                'manage_options',
                'my-setting-admin',
                array( $this, 'Resolution1WP_get_started_page' )
            );
        }

        /**
        *
        * get started page call back
        */
        public function Resolution1WP_get_started_page(){
            // Set class property
            $this->options = get_option( 'my_option_name');
            ?>
            <style>
                @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600');
                .wrap{
                    display: flex;
                    justify-content: center;
                    margin: 3% 20px 0 0;
                }

                .wrap .custom-container{
                    font-family: 'Source Sans Pro', sans-serif;
                    width: 60%;
                    text-align: center;
                    font-size: 14px;
                    background-color: #ffffff;
                    padding: 30px;
                    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
                    border-radius: 6px;
                }

                .wrap .custom-container .heading{
                    font-family: 'Source Sans Pro', sans-serif;
                    display: flex;
                    justify-content: center;
                    margin-bottom: 6px;
                    align-items: center;
                }

                .wrap .custom-container .heading p{
                    margin: 0 0 10px 0;
                    padding: 0;
                    font-weight: 300;
                    font-size: 35px;
                    color: #bd1f1f;
                }

                .wrap .custom-container .heading img{
                    width: 45px;
                    margin-right: 5px;
                }

                .wrap .custom-container .panel {
                    padding: 0 25px;
                }

                .wrap .custom-container p{
                    font-size: 16px;
                    color: #000000;
                }

                .wrap .custom-container .panel .subheading{
                    margin: 0 0 20px;
                }

                .wrap .custom-container .panel .paragraph{
                    margin: 20px 0px;
                    font-size: 13px;
                }

                .wrap .custom-container .panel .paragraph-2{
                    margin: 20px 0px 8px 0px;
                    font-size: 13px;
                }

                .wrap .custom-container .panel .paragraph-3{
                    text-decoration: underline;
                    margin: 0px 0px 8px 0px;
                    font-size: 15px;
                    color: #4a4a4a;
                }

                .wrap .custom-container .panel a{
                    text-decoration: underline !important;
                }

                .wrap .custom-container .panel form{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .wrap .custom-container .panel form .button{
                   margin-left: 17px;
                }

                .wrap .custom-container .panel .form-table{
                    width: auto;
                    margin: 0px;
                }

                .wrap .custom-container .panel .form-table tbody tr{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .wrap .custom-container .panel .form-table tbody tr th{
                    text-align: right;
                    line-height: 2;
                    width: auto;
                    margin: 0 20px 0 0;
                    padding: 0px;
                    font-size: 16px;
                }

                .wrap .custom-container .panel .form-table tbody tr td{
                    padding: 0px;
                    margin: 0px;
                }

                .wrap .custom-container .panel .form-table tbody tr td .api-token{
                    width: 100%;
                    padding: 7px 9px;
                    font-size: 14px;
                    color: #949494;
                }

                .wrap .custom-container .panel .token__button{
                    color: #0073aa;
                    background-color: #ffffff;
                    border: none;
                    font-size: 13px;
                    text-decoration: underline;
                    cursor: pointer;
                    padding: 0;
                    margin: 0;
                }

                .wrap .custom-container .panel .token-content{
                    display: flex;
                    flex-direction: column;
                    width: 60%;
                }

                .wrap .custom-container .panel ul{
                    margin: 0px;
                    text-align: left;
                }

                .wrap .custom-container .panel ul li{
                    color: #000000;
                    margin-bottom: 10px;
                    font-size: 13px;
                }
            </style>

            <div class="wrap">
                <div class="custom-container">
                    <div class="heading">
                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'includes/favicon.png'; ?>" alt="logo" />

                        <p>Resolution1<sup>®</sup>(R1) Contact Widget</p>
                    </div>
                    <div class="panel">
                        <p class="subheading">Converts any static contact us page into a dynamic call-center like experience, by allowing your website visitors to conveniently connect with your business from any page using the pop-up contact us button.</p>
                        <a style="font-size:15px;" href="https://dashboard.resolution1.com/" target="_blank">Click here to access your Resolution1 (R1) Dashboard</a>
                        <p class="paragraph">Learn more about our products: <a href="https://flooidcx.com/resolution1/" target="_blank">flooidCX</a></p>

                        <?php $r1Connected = isset( $this->options['token']) && !empty($this->options['token']) ?>
                        <?php if (!$r1Connected) :?>
                            <a id="resolution1ConnectButton" href="javascript:void(0)" target="_blank" rel="opener">
                                <button class="button button-primary">
                                    Connect with Resolution1
                                </button>
                            </a>
                        <?php endif ?>
                        <?php if ($r1Connected) :?>
                            <div class="heading">
                                <p>Widget has been installed!</p>
                            </div>
                        <?php endif ?>
                        <form id="resolution1TokenForm" method="post" action="options.php" <?php echo (!$r1Connected ? 'style="display:none"' : '') ?>>
                            <?php
                                settings_fields( 'my_option_group' );
                                do_settings_sections( 'my-setting-admin' );
                            ?>
                            <input name="submit" class="button button-primary" type="submit"
                                   value="<?php esc_attr_e( 'Save' ) ?>" style="display: none"/>
                        </form>
                        <?php if ($r1Connected) :?>
                            <p class="paragraph-2">The <strong style="color:#4a4a4a;">API Token</strong> is used to connect you to your Resolution1 (R1) Dashboard to ensure conversations between you and your customers are private.</p>
                            <p class="paragraph">
                                If the R1 Contact Widget associated with your Wordpress is incorrect, please
                                <a id="resolution1ReconnectButton" href="javascript:void(0)" target="_blank" rel="opener">click here</a>
                                to reconnect with Resolution1.
                            </p>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    let nonce;
                    const form = document.getElementById('resolution1TokenForm');

                    const connectButton = document.getElementById('resolution1ConnectButton');
                    const reconnectButton = document.getElementById('resolution1ReconnectButton');

                    function generateLink() {
                        nonce = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                        const params = new URLSearchParams({
                            nonce: nonce,
                            origin: window.location.origin,
                        });
                        return `https://dashboard.resolution1.com/connect?${params.toString()}`;
                    }

                    const url = generateLink()
                    if (connectButton) connectButton.href = url;
                    if (reconnectButton) reconnectButton.href = url;

                    window.addEventListener('message', function(event) {
                        if (event.origin === 'https://dashboard.resolution1.com') {
                            if (event.data.nonce && event.data.nonce === nonce) {
                                if (event.data.r1Event === 'connect' && event.data.token) {
                                    const tokenInput = form.querySelector('input[name="my_option_name[token]"]');
                                    const submitButton = form.querySelector('input[type="submit"]');
                                    tokenInput.value = event.data.token;
                                    submitButton.click();
                                }
                            }
                        }
                    }, false);
                })();
            </script>
            <?php
        }

        /**
         *
         * Register and add settings
         */
        public function Resolution1WP_plugin_register_settings()
        {
            register_setting(
                'my_option_group', // Option group
                'my_option_name', // Option name
                array( $this, 'Resolution1WP_sanitize' ) // Sanitize
            );

            add_settings_section(
                'setting_section_id', // ID
                '', // Title
                array( $this, 'Resolution1WP_print_section_info' ), // Callback
                'my-setting-admin' // Page
            );

            add_settings_field(
                'token', // ID
                'API Token:', // Title
                array( $this, 'Resolution1WP_token_callback' ), // Callback
                'my-setting-admin', // Page
                'setting_section_id' // Section
            );
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function Resolution1WP_sanitize( $input )
        {
            $new_input = array();
            if( isset( $input['token'] ) )
                $new_input['token'] = sanitize_text_field( $input['token'] );

            return $new_input;
        }

        /**
         * Print the Section text
         */
        public function Resolution1WP_print_section_info()
        {
            // print 'Enter your settings below:';
        }

        /**
         * Get the settings option array and print one of its values
         */
        public function Resolution1WP_token_callback()
        {
            printf(
                '<input class="api-token" type="text" id="token" name="my_option_name[token]" placeholder="icclJ2agGMG5R" value="%s" readonly/>',
                isset( $this->options['token'] ) ? esc_attr( $this->options['token']) : ''
            );
        }

		/**
		 * Displays widget for the frontend.
		 *
		 * @param array  $args     The widget args
		 * @param string $instance The widget instance
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function widget( $args, $instance ) {
            $this->options = get_option( 'my_option_name');

			/*
			 * Validate token not empty.
			 */
			if ( !empty( $this->options['token']) ) :
			?>
				 <div id="resolution1"></div> <script> (function() { window.r1WidgetToken = "<?php echo esc_attr( $this->options['token']); ?>"; window.rSource = 0; const resolution = document.createElement('script'); resolution.src = "https://r1prod.azureedge.net/The_Script_widget_prod.js"; resolution.async = true; const one = document.createElement('link'); one.rel = "stylesheet"; one.type = "text/css"; one.href = "https://r1prod.azureedge.net/BasicStyle.css"; document.body.appendChild(resolution); document.head.appendChild(one); })() </script>

			<?php
			endif;
		}

		// /**
		//  * Handles widget updates in admin
		//  *
		//  * @param  array $new_instance
		//  * @param  array $old_instance
		//  *
		//  * @since 1.0.0
		//  *
		//  * @return array $instance
		//  */
		// public function update( $new_instance, $old_instance ) {
		// 	/* Updates widget title value */
		// 	$instance = $old_insatnce;

		// 	$instance['token'] = strip_tags( $new_instance['token'] );
		// 	return $instance;
		// }

		/**
		 * Display widget form in admin.
		 *
		 * @param  array $instance widget instance
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function form( $instance ) {
            $this->options = get_option( 'my_option_name');
            if ( empty( $this->options['token']) ):
            ?>
            <div>
                <h3>Resolution1 (R1) Contact Widget is not setup!</h3>
                <p style="font-size: 14px;">Resolution1<sup>®</sup> account must be connected in order to setup your Resolution1 (R1) Contact Widget</p>
                <p style="font-size: 14px;">To connect your Resolution1<sup>®</sup> account,
                        navigate to <i>Settings > Resolution1 (R1) Contact Widget</i></p>
            </div>
            <?php
            endif;
            if(!empty( $this->options['token'])):
            ?>
            <div>
                <h3>Resolution1 (R1) Contact Widget has been setup successfully!</h3>
                <p style="font-size: 14px;">To connect or reconnect with your Resolution1<sup>®</sup> account,
                        navigate to <i>Settings > Resolution1 (R1) Contact Widget</i></p>
            </div>

            <?php
            endif;
		}
}

