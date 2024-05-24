<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dev.ilyasine.com/
 * @since      1.0.0
 *
 * @package    Ikacom_Api
 * @subpackage Ikacom_Api/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ikacom_Api
 * @subpackage Ikacom_Api/public
 * @author     Yassine Idrissi <ydrissi9@gmail.com>
 */
class Ikacom_Api_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The SVA number.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sva    SVA number concerned by the access code to confirm.
	 */

	private $sva;

	/**
	 * The Formula code.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $code_formule    Formula code of the access code to verify.
	 */

	 private $code_formule;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//$this->validate_ikacom_access_code();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ikacom-api-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ikacom-api-public.js', array( 'jquery' ), $this->version, true );

		$ikacom_api_data = array(
			'nonce' => wp_create_nonce( $this->plugin_name . '-nonce' ),
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
		  );
		// Localize the script with ikacom_api_data
		wp_localize_script( $this->plugin_name, 'ikacom_api_data', $ikacom_api_data );  

	}

	/**
	 * Shortcode callback function that will generate the content
	 *
	 * @since    1.0.0
	 */
	public function ikacom_shortcode($atts) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            array(
                'sva' => '',
                'code' => '',
                'solde' => '',
                'region' => '',
            ),
            $atts,
            'ikacom'
        );

		$output = '';
        // Get the attribute values
        $sva = isset($atts['sva']) ? $atts['sva'] : '';
        $code = isset($atts['code']) ? $atts['code'] : '';
		$solde = $code - 1;
		$tarif = isset($atts['solde']) ? floatval($atts['solde']) : $solde;

		// Check if solde is set and non-empty
		if (isset($atts['solde']) && !empty($atts['solde'])) {
			$tarif = floatval($atts['solde']); // Convert solde to float
		} else {
			$tarif = $code - 1;
		}

        $code_formule = 'code_' . $code;
		$region = isset($atts['region']) ? $atts['region'] : '';
		$country_label = '';


		switch ($region) {
			case 'fr':
				$country_label = 'France';
				break;
			
			default:
				# code...
				break;
		}

		// Include the template file
        ob_start();
        include(plugin_dir_path(__FILE__) . 'partials/ikacom-api-public-display.php');
        $output .= ob_get_clean();

        return $output;
    }

	public function validate_ikacom_access_code(){

		if ( isset($_POST['payload']) && $_POST['payload'] == 'ikacom_post_request' && 
			isset($_POST['sva']) && isset($_POST['code_formule']) && isset($_POST['access_code']) &&
			isset($_POST['wallet_bal']) && isset($_POST['tarif']) ){

			check_ajax_referer($this->plugin_name . '-nonce', 'security');

			$current_user = wp_get_current_user();
			$user_id = get_current_user_id();
			$user_email = $current_user->user_email;
			$user_display_name = $current_user->display_name;
			$statut = '';
			$etat_code = '';
			$statut_etat = '';
			$notice = '';
			$new_solde = '';
			$sva = intval($_POST['sva']);
			$tarif = floatval($_POST['tarif']);
			$wallet_bal = floatval($_POST['wallet_bal']);			
			$code_formule = sanitize_text_field(($_POST['code_formule']));
			$access_code = intval($_POST['access_code']);

			$url = 'https://api.ikacom.fr/CodesMP/verification/code';

			$body = array(
				'id' => 'BARKANBADI',
				'cle' => '44etCwGRF27M5XFxapKqfd3pZ74fBXt2R59tUV6R3dH4n879pw',
				'sva' => $sva,
				'code_formule' => $code_formule, 
				'code' => $access_code,		
			);

			$args = array(
				'body' => $body,
			);

			$response = wp_remote_post( $url, $args );

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				$response_code = wp_remote_retrieve_response_code( $response );
				$response_body = wp_remote_retrieve_body( $response );

				$result = json_decode($response_body);

				$etat_code = $result->etat_code;
				$statut_etat = $result->statut_etat;

				$json_response = array(
					'response_code' => $response_code,
					'response_body' => $result,
				);

				// Successful connection
				if ($etat_code === '1') {
					// Fetch wallet balance as float
					$new_wallet_bal = floatval(get_user_meta($user_id, 'wps_wallet', true));
					
					// Handle cases where $new_wallet_bal is not set
					if (!$new_wallet_bal) {
						$new_wallet_bal = 0.0; // Set default value if not set
					}
										
					// Perform addition after ensuring $new_wallet_bal and $tarif are floats
					$new_solde = number_format($new_wallet_bal + $tarif, 2, '.', '');
					
					// Update user meta with the new balance
					update_user_meta($user_id, 'wps_wallet', $new_solde);


					$wallet_solde_updated = 'Félicitations ! Le nouveau solde a été ajouté avec succès à votre wallet.<br>';
					$wallet_solde_updated .= 'Le nouveau solde de votre wallet est de :  <strong>' .  wc_price( $new_solde ) . '</strong>';    
					$json_response['wallet_solde_updated'] = $wallet_solde_updated;
					$json_response['new_solde'] = $new_solde;
					$json_response['tarif'] = $tarif;

					// Logging to check if the code block is reached
					error_log('Code block executed: new solde added');
				} else {
					// Logging to check the value of $etat_code
					error_log('Etat code is not 1: ' . $etat_code);
				}
				
				// Send the JSON response
				wp_send_json_success($json_response);
			}
		}

		wp_die();

	}

}
