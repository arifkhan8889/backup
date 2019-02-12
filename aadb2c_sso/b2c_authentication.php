<?php

/**
 * Plugin Name: Microsoft Azure Active Directory B2C Authentication
 * Plugin URI: https://github.com/AzureAD/active-directory-b2c-wordpress-plugin-openidconnect
 * Description: A plugin that allows users to log in using B2C policies
 * Version: 1.0
 * Author: Microsoft and Arif Khan
 * Author URI: https://azure.microsoft.com/en-us/documentation/services/active-directory-b2c/
 * License: TBD
 */

 
//*****************************************************************************************


/** 
 * Requires the autoloaders.
 */
require 'autoload.php';
require 'vendor/autoload.php';
require_once 'graph_api/GraphServiceAccessHelper.php';

/**
 * Defines the response string posted by B2C.
 */
define('B2C_RESPONSE_MODE', 'id_token');

// Adds the B2C Options page to the Admin dashboard, under 'Settings'.
if (is_admin()) 
	$b2c_settings_page = new B2C_Settings_Page();

$b2c_settings = new B2C_Settings();


//*****************************************************************************************

/*
* migrate all wp users to AAD
*/

function b2c_migrateUser(){
	if(isset($_GET['usermigrate'])){
		@ini_set('max_execution_time', 300);
		//require_once 'graph_api/GraphServiceAccessHelper.php';
		$blogusers = get_users( array('role__not_in'=>array('administrator')) );
		$countries = array
	                (
	                    'AF' => 'Afghanistan',
	                    'AX' => 'Aland Islands',
	                    'AL' => 'Albania',
	                    'DZ' => 'Algeria',
	                    'AS' => 'American Samoa',
	                    'AD' => 'Andorra',
	                    'AO' => 'Angola',
	                    'AI' => 'Anguilla',
	                    'AQ' => 'Antarctica',
	                    'AG' => 'Antigua And Barbuda',
	                    'AR' => 'Argentina',
	                    'AM' => 'Armenia',
	                    'AW' => 'Aruba',
	                    'AU' => 'Australia',
	                    'AT' => 'Austria',
	                    'AZ' => 'Azerbaijan',
	                    'BS' => 'Bahamas',
	                    'BH' => 'Bahrain',
	                    'BD' => 'Bangladesh',
	                    'BB' => 'Barbados',
	                    'BY' => 'Belarus',
	                    'BE' => 'Belgium',
	                    'BZ' => 'Belize',
	                    'BJ' => 'Benin',
	                    'BM' => 'Bermuda',
	                    'BT' => 'Bhutan',
	                    'BO' => 'Bolivia',
	                    'BA' => 'Bosnia And Herzegovina',
	                    'BW' => 'Botswana',
	                    'BV' => 'Bouvet Island',
	                    'BR' => 'Brazil',
	                    'IO' => 'British Indian Ocean Territory',
	                    'BN' => 'Brunei Darussalam',
	                    'BG' => 'Bulgaria',
	                    'BF' => 'Burkina Faso',
	                    'BI' => 'Burundi',
	                    'KH' => 'Cambodia',
	                    'CM' => 'Cameroon',
	                    'CA' => 'Canada',
	                    'CV' => 'Cape Verde',
	                    'KY' => 'Cayman Islands',
	                    'CF' => 'Central African Republic',
	                    'TD' => 'Chad',
	                    'CL' => 'Chile',
	                    'CN' => 'China',
	                    'CX' => 'Christmas Island',
	                    'CC' => 'Cocos (Keeling) Islands',
	                    'CO' => 'Colombia',
	                    'KM' => 'Comoros',
	                    'CG' => 'Congo',
	                    'CD' => 'Congo, Democratic Republic',
	                    'CK' => 'Cook Islands',
	                    'CR' => 'Costa Rica',
	                    'CI' => 'Cote D\'Ivoire',
	                    'HR' => 'Croatia',
	                    'CU' => 'Cuba',
	                    'CY' => 'Cyprus',
	                    'CZ' => 'Czech Republic',
	                    'DK' => 'Denmark',
	                    'DJ' => 'Djibouti',
	                    'DM' => 'Dominica',
	                    'DO' => 'Dominican Republic',
	                    'EC' => 'Ecuador',
	                    'EG' => 'Egypt',
	                    'SV' => 'El Salvador',
	                    'GQ' => 'Equatorial Guinea',
	                    'ER' => 'Eritrea',
	                    'EE' => 'Estonia',
	                    'ET' => 'Ethiopia',
	                    'FK' => 'Falkland Islands (Malvinas)',
	                    'FO' => 'Faroe Islands',
	                    'FJ' => 'Fiji',
	                    'FI' => 'Finland',
	                    'FR' => 'France',
	                    'GF' => 'French Guiana',
	                    'PF' => 'French Polynesia',
	                    'TF' => 'French Southern Territories',
	                    'GA' => 'Gabon',
	                    'GM' => 'Gambia',
	                    'GE' => 'Georgia',
	                    'DE' => 'Germany',
	                    'GH' => 'Ghana',
	                    'GI' => 'Gibraltar',
	                    'GR' => 'Greece',
	                    'GL' => 'Greenland',
	                    'GD' => 'Grenada',
	                    'GP' => 'Guadeloupe',
	                    'GU' => 'Guam',
	                    'GT' => 'Guatemala',
	                    'GG' => 'Guernsey',
	                    'GN' => 'Guinea',
	                    'GW' => 'Guinea-Bissau',
	                    'GY' => 'Guyana',
	                    'HT' => 'Haiti',
	                    'HM' => 'Heard Island & Mcdonald Islands',
	                    'VA' => 'Holy See (Vatican City State)',
	                    'HN' => 'Honduras',
	                    'HK' => 'Hong Kong',
	                    'HU' => 'Hungary',
	                    'IS' => 'Iceland',
	                    'IN' => 'India',
	                    'ID' => 'Indonesia',
	                    'IR' => 'Iran, Islamic Republic Of',
	                    'IQ' => 'Iraq',
	                    'IE' => 'Ireland',
	                    'IM' => 'Isle Of Man',
	                    'IL' => 'Israel',
	                    'IT' => 'Italy',
	                    'JM' => 'Jamaica',
	                    'JP' => 'Japan',
	                    'JE' => 'Jersey',
	                    'JO' => 'Jordan',
	                    'KZ' => 'Kazakhstan',
	                    'KE' => 'Kenya',
	                    'KI' => 'Kiribati',
	                    'KR' => 'Korea',
	                    'KW' => 'Kuwait',
	                    'KG' => 'Kyrgyzstan',
	                    'LA' => 'Lao People\'s Democratic Republic',
	                    'LV' => 'Latvia',
	                    'LB' => 'Lebanon',
	                    'LS' => 'Lesotho',
	                    'LR' => 'Liberia',
	                    'LY' => 'Libyan Arab Jamahiriya',
	                    'LI' => 'Liechtenstein',
	                    'LT' => 'Lithuania',
	                    'LU' => 'Luxembourg',
	                    'MO' => 'Macao',
	                    'MK' => 'Macedonia',
	                    'MG' => 'Madagascar',
	                    'MW' => 'Malawi',
	                    'MY' => 'Malaysia',
	                    'MV' => 'Maldives',
	                    'ML' => 'Mali',
	                    'MT' => 'Malta',
	                    'MH' => 'Marshall Islands',
	                    'MQ' => 'Martinique',
	                    'MR' => 'Mauritania',
	                    'MU' => 'Mauritius',
	                    'YT' => 'Mayotte',
	                    'MX' => 'Mexico',
	                    'FM' => 'Micronesia, Federated States Of',
	                    'MD' => 'Moldova',
	                    'MC' => 'Monaco',
	                    'MN' => 'Mongolia',
	                    'ME' => 'Montenegro',
	                    'MS' => 'Montserrat',
	                    'MA' => 'Morocco',
	                    'MZ' => 'Mozambique',
	                    'MM' => 'Myanmar',
	                    'NA' => 'Namibia',
	                    'NR' => 'Nauru',
	                    'NP' => 'Nepal',
	                    'NL' => 'Netherlands',
	                    'AN' => 'Netherlands Antilles',
	                    'NC' => 'New Caledonia',
	                    'NZ' => 'New Zealand',
	                    'NI' => 'Nicaragua',
	                    'NE' => 'Niger',
	                    'NG' => 'Nigeria',
	                    'NU' => 'Niue',
	                    'NF' => 'Norfolk Island',
	                    'MP' => 'Northern Mariana Islands',
	                    'NO' => 'Norway',
	                    'OM' => 'Oman',
	                    'PK' => 'Pakistan',
	                    'PW' => 'Palau',
	                    'PS' => 'Palestinian Territory, Occupied',
	                    'PA' => 'Panama',
	                    'PG' => 'Papua New Guinea',
	                    'PY' => 'Paraguay',
	                    'PE' => 'Peru',
	                    'PH' => 'Philippines',
	                    'PN' => 'Pitcairn',
	                    'PL' => 'Poland',
	                    'PT' => 'Portugal',
	                    'PR' => 'Puerto Rico',
	                    'QA' => 'Qatar',
	                    'RE' => 'Reunion',
	                    'RO' => 'Romania',
	                    'RU' => 'Russian Federation',
	                    'RW' => 'Rwanda',
	                    'BL' => 'Saint Barthelemy',
	                    'SH' => 'Saint Helena',
	                    'KN' => 'Saint Kitts And Nevis',
	                    'LC' => 'Saint Lucia',
	                    'MF' => 'Saint Martin',
	                    'PM' => 'Saint Pierre And Miquelon',
	                    'VC' => 'Saint Vincent And Grenadines',
	                    'WS' => 'Samoa',
	                    'SM' => 'San Marino',
	                    'ST' => 'Sao Tome And Principe',
	                    'SA' => 'Saudi Arabia',
	                    'SN' => 'Senegal',
	                    'RS' => 'Serbia',
	                    'SC' => 'Seychelles',
	                    'SL' => 'Sierra Leone',
	                    'SG' => 'Singapore',
	                    'SK' => 'Slovakia',
	                    'SI' => 'Slovenia',
	                    'SB' => 'Solomon Islands',
	                    'SO' => 'Somalia',
	                    'ZA' => 'South Africa',
	                    'GS' => 'South Georgia And Sandwich Isl.',
	                    'ES' => 'Spain',
	                    'LK' => 'Sri Lanka',
	                    'SD' => 'Sudan',
	                    'SR' => 'Suriname',
	                    'SJ' => 'Svalbard And Jan Mayen',
	                    'SZ' => 'Swaziland',
	                    'SE' => 'Sweden',
	                    'CH' => 'Switzerland',
	                    'SY' => 'Syrian Arab Republic',
	                    'TW' => 'Taiwan',
	                    'TJ' => 'Tajikistan',
	                    'TZ' => 'Tanzania',
	                    'TH' => 'Thailand',
	                    'TL' => 'Timor-Leste',
	                    'TG' => 'Togo',
	                    'TK' => 'Tokelau',
	                    'TO' => 'Tonga',
	                    'TT' => 'Trinidad And Tobago',
	                    'TN' => 'Tunisia',
	                    'TR' => 'Turkey',
	                    'TM' => 'Turkmenistan',
	                    'TC' => 'Turks And Caicos Islands',
	                    'TV' => 'Tuvalu',
	                    'UG' => 'Uganda',
	                    'UA' => 'Ukraine',
	                    'AE' => 'United Arab Emirates',
	                    'GB' => 'United Kingdom',
	                    'US' => 'United States',
	                    'UM' => 'United States Outlying Islands',
	                    'UY' => 'Uruguay',
	                    'UZ' => 'Uzbekistan',
	                    'VU' => 'Vanuatu',
	                    'VE' => 'Venezuela',
	                    'VN' => 'Viet Nam',
	                    'VG' => 'Virgin Islands, British',
	                    'VI' => 'Virgin Islands, U.S.',
	                    'WF' => 'Wallis And Futuna',
	                    'EH' => 'Western Sahara',
	                    'YE' => 'Yemen',
	                    'ZM' => 'Zambia',
	                    'ZW' => 'Zimbabwe',
	                );
		$i = 1;
		$arrFailed = $arrSuccess = array();

		// Array of WP_User objects.
		foreach ( $blogusers as $user ) {
			$user_info  = get_userdata($user->ID);
			$first_name = $user_info->first_name;
			$last_name  = $user_info->last_name;
			//_pre($user_info);
			$accountEnabled = True;
	        $passwordProfile = array(
	                            'password' =>  'p@ssw0rd',
	                            // 'forceChangePasswordNextLogin' => $_POST["forcePasswordChangeOnNextLogin"],
	                    	);
	         $signin = array(
	         				array(
	                            'type' => 'emailAddress',
	                            'value' => $user->user_email,
	                            // 'value' => 'arif.khan@wwindia.com',
	                            )
	         				);

	        $userEntryInput = array(
							'displayName'                                            => $first_name ? $first_name : 'NULL',
							'creationType'                                           => 'LocalAccount',
							// 'userPrincipalName'                                   => $alias.'@'.Settings::$appTenantDomainName ,
							// 'mailNickname'                                        => $alias,
							'otherMails'                                             => (array)  $user->user_email,
							'extension_0cc0a2fdbf904c4a977bff84fd4a3ec9_wp_roles'    => (string) $user_info->roles[0],
							"extension_0cc0a2fdbf904c4a977bff84fd4a3ec9_UserType"    => (string) get_user_meta($user->ID,'user_type',true) ? get_user_meta($user->ID,'user_type',true) : 'NULL',
							'extension_0cc0a2fdbf904c4a977bff84fd4a3ec9_CompanyName' => (string) get_user_meta($user->ID,'company_name',true) ? get_user_meta($user->ID,'company_name',true) : 'NULL',
							"extension_0cc0a2fdbf904c4a977bff84fd4a3ec9_Website"     => (string) $user_info->user_url ?  $user_info->user_url : 'NULL',
							"city"                                                   => (string) get_user_meta($user->ID,'city',true) ? get_user_meta($user->ID,'city',true) : 'NULL',
							"streetAddress"                                          => (string) get_user_meta($user->ID,'street',true) ? get_user_meta($user->ID,'street',true) : 'NULL',
							"postalCode"                                             => (string) get_user_meta($user->ID,'postal_code',true) ? get_user_meta($user->ID,'postal_code',true) : 'NULL',
							"country"                                                => (string) $countries[get_user_meta($user->ID,'country',true)] ? $countries[get_user_meta($user->ID,'country',true)] : 'NULL',
							"mobile"                                                 => (string) get_user_meta($user->ID,'telephone',true) ? (string) get_user_meta($user->ID,'telephone',true) : 'NULL',
							'givenName'                                              => $first_name,
							'surname'                                                => $last_name,
							'signInNames'                                            => $signin,                    
							'passwordProfile'                                        => $passwordProfile,                    
							'accountEnabled'                                         => $accountEnabled
	                    );

			// _pre( $userEntryInput );
	        // Create the user and display a message
	       	$aadUser = GraphServiceAccessHelper::addEntryToFeed('users',$userEntryInput);
	        if(!empty($aadUser->{'odata.error'})){
	           $message = $aadUser->{'odata.error'}->{'message'};
	            $arrFailed[$user->user_email] = $message->{'value'};
	            // echo  $message->{'value'};
	            // die('fail');
	        }else {
	        	$arrSuccess[] = $user->user_email;
	        // die('done');
	        }

	       /* if($i == 2){
	        	// get all failed and successfull created users
	        	echo "Failed users List";
				_pre($arrFailed);

				echo "Successfull users List";
			    _pre($arrSuccess);
		        die;
	        }
	        $i++;*/
		}

		echo "Failed users List";
		_pre($arrFailed);

		echo "Successfull users List";
	    _pre($arrSuccess);

		// $groups = GraphServiceAccessHelper::getFeed('groups');  
	 //    echo "<pre>";print_r($groups);  die;
	}
}

/**
 * Redirects to B2C on a user login request.
 */
function b2c_login() {
	try {
		$b2c_endpoint_handler = new B2C_Endpoint_Handler(B2C_Settings::$generic_policy);
		$authorization_endpoint = $b2c_endpoint_handler->get_authorization_endpoint()."&state=generic";
		wp_redirect($authorization_endpoint);
	}catch (Exception $e) {
		echo $e->getMessage();
	}
	exit;
}


/** 
 * Redirects to B2C on user logout.
 */
function b2c_logout() {
	try {
		$signout_endpoint_handler = new B2C_Endpoint_Handler(B2C_Settings::$generic_policy);
		$signout_uri = $signout_endpoint_handler->get_end_session_endpoint();
		wp_redirect($signout_uri);
		exit;
	}catch (Exception $e) {
		echo $e->getMessage();
	}
}

/** 
 * Verifies the id_token that is POSTed back to the web app from the 
 * B2C authorization endpoint. 
 */
function b2c_verify_token() {
	try {
		if (isset($_POST['error'])) {
			// echo 'Unable to log in';
			// echo '<br/>error:' . $_POST['error'];
			// echo '<br/>error_description:' . $_POST['error_description'];
			return new WP_Error(
				$_POST['error'],
				sprintf( __( 'ERROR: Unable to log in. %s', 'aad-sso-wordpress' ), $_POST['error_description'])
			);
		}else{

			if (isset($_POST[B2C_RESPONSE_MODE])) {	
				// _pre($_POST);die;
				// Check which authorization policy was used
				$poster = explode('@',$_POST['state']);
				if(!empty($poster)){
					$result_url = $poster[0];
				}else{
					$result_url = $_POST['state'];
				}
				switch ($result_url) {
				// switch ($_POST['state']) {
					case 'generic': 
						$policy = B2C_Settings::$generic_policy;
						break;
					case 'reset_password': 
						$policy = B2C_Settings::$password_reset_policy;
						break;
					case 'sign_up': 
						$policy = B2C_Settings::$sign_up_policy;
						break;
					case 'admin':
						$policy = B2C_Settings::$admin_policy;
						break;
					case 'edit_profile':
						$policy = B2C_Settings::$edit_profile_policy;
						break;
					default:
						// Not a B2C request, ignore.
						return;
				}
				
				
				// Verifies token only if the checkbox "Verify tokens" is checked on the settings page
				$token_checker = new B2C_Token_Checker($_POST[B2C_RESPONSE_MODE], B2C_Settings::$clientID, $policy);

				if (B2C_Settings::$verify_tokens) {
					$verified = $token_checker->authenticate();
					if ($verified == false) wp_die('Token validation error');
				}
				
				// Use the email claim to fetch the user object from the WP database
				$email       = $token_checker->get_claim('emails');
				$email       = $email[0];
				$user        = WP_User::get_data_by('email', $email);
				$displayName = $token_checker->get_claim('name');
				$first_name  = $token_checker->get_claim('given_name');
				$last_name   = $token_checker->get_claim('family_name');
				$oid         = $token_checker->get_claim('oid');
				
				// Get the userID for the user
				if ($user == false) { // User doesn't exist yet, create new userID
					$our_userdata = array (
							'ID'              => 0,
							'user_login'      => $email,
							'user_pass'       => NULL,
							'user_registered' => true,
							'user_status'     => 0,
							'user_email'      => $email,
							'display_name'    => $displayName,
							'first_name'      => $displayName,
							'last_name'       => $last_name
							);

					$userID = wp_insert_user( $our_userdata ); 
					$userEntryInput = array(
						'otherMails'=> (array) $email
						);
					// Create the user and display a message
					GraphServiceAccessHelper::updateEntry('users',$oid,$userEntryInput);

				} else if ($policy == B2C_Settings::$edit_profile_policy) { // Update the existing user w/ new attritubtes
					$our_userdata = array (
										'ID'           => $user->ID,
										'display_name' => $displayName,
										'first_name'   => $displayName,
										'last_name'    => $last_name
									);
														
					$userID = wp_update_user( $our_userdata );
				} else {
					$our_userdata = array (
											'ID'           => $user->ID,
											'display_name' => $displayName,
											'first_name'   => $displayName,
											'last_name'    => $last_name
											);
														
					$userID = wp_update_user( $our_userdata );
					// $userID = $user->ID;
				}
				
				// Check if the user is an admin and needs MFA
				$wp_user = new WP_User($userID); 
				if (in_array('administrator', $wp_user->roles)) {
					// If user did not authenticate with admin_policy, redirect to admin policy
					if (mb_strtolower($token_checker->get_claim('tfp')) != mb_strtolower(B2C_Settings::$admin_policy)) {
						$b2c_endpoint_handler = new B2C_Endpoint_Handler(B2C_Settings::$admin_policy);
						$authorization_endpoint = $b2c_endpoint_handler->get_authorization_endpoint().'&state=admin';
						wp_redirect($authorization_endpoint);
						// exit;
					}
				}
				// Set cookies to authenticate on WP side
				wp_set_auth_cookie($userID);
				if(!empty($poster[1])){
					wp_safe_redirect($poster[1]);
				}else{
					wp_safe_redirect(site_url() . '/');
				}
				
				exit;
			}
		}

	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}
}

/** 
 * Redirects to B2C's edit profile policy when user edits their profile.
 */
function b2c_edit_profile() {
	
	// Check to see if user was requesting the edit_profile page, if so redirect to B2C
	$pagename = $_SERVER['REQUEST_URI'];
	$parts    = explode('/', $pagename);
	$len      = count($parts);
	if ($len > 1 && $parts[$len-2] == "wp-admin" && $parts[$len-1] == "profile.php") {
		
		// Return URL for edit_profile endpoint
		try {
			$b2c_endpoint_handler = new B2C_Endpoint_Handler(B2C_Settings::$edit_profile_policy);
			$authorization_endpoint = $b2c_endpoint_handler->get_authorization_endpoint().'&state=edit_profile';
			wp_redirect($authorization_endpoint);
		}catch (Exception $e) {
			echo $e->getMessage();
		}
		exit;
	}
}

/**
	 * Renders the link used to initiate the login to Azure AD.
	 */
function print_login_link() {
	// echo  $_SERVER['REQUEST_URI'];
	// 
	$signout_endpoint_handler = new B2C_Endpoint_Handler(B2C_Settings::$generic_policy);
	$signout_uri = $signout_endpoint_handler->get_end_session_endpoint();
	$authorization_endpoint = $signout_endpoint_handler->get_authorization_endpoint()."&state=generic";

	// reset password with AAD
	$sign_up_handler = new B2C_Endpoint_Handler(B2C_Settings::$sign_up_policy);
	$sign_up = $sign_up_handler->get_authorization_endpoint()."&state=sign_up";

	// sign with AAD
	$reset_password_handler = new B2C_Endpoint_Handler(B2C_Settings::$password_reset_policy);
	$reset_password = $reset_password_handler->get_authorization_endpoint()."&state=reset_password";
	$html = '<p class="aadsso-login-form-text">';
	$html .= '<a href="%s">';
	$html .= sprintf( __( 'Sign in with your %s account', 'aad-sso-wordpress' ),
	                  htmlentities('AAD') );
	$html .= '<br /><a href="%s">'.__('Sign Up With AAD','aad-sso-wordpress').'</a>';
	$html .= '<br /><a href="%s">'.__('Reset Password','aad-sso-wordpress').'</a>';
	$html .= '</a><br /><a class="dim" href="%s">'
	         . __( 'Sign out', 'aad-sso-wordpress' ) . '</a></p>';
	printf(
		$html,
		$authorization_endpoint ,
		$sign_up ,
		$reset_password ,
		$signout_uri
	);
}

function redirect_to_login(){
    if(!is_admin()){
        wp_reset_query();
		if(is_page('login') && !is_user_logged_in()){
			try {
				die('herr');
				$b2c_endpoint_handler = new B2C_Endpoint_Handler(B2C_Settings::$generic_policy);
				$authorization_endpoint = $b2c_endpoint_handler->get_authorization_endpoint()."&state=generic@".$_GET['redirect_to'];
				wp_redirect($authorization_endpoint);
				die();
			}catch (Exception $e) {
				echo $e->getMessage();
			}
		}
    }
}



function updateAlternateEmail(){
	if(isset($_GET['updateuser'])){
		@ini_set('max_execution_time', 500);
		//require_once 'graph_api/GraphServiceAccessHelper.php';
		// $users = GraphServiceAccessHelper::getFeed('users');
		// $blogusers = get_users( array('role__not_in'=>array('administrator')) );
		$blogusers = get_users( array('include'=>array('1547','1552')) );
		_pre($users);
		// $users = GraphServiceAccessHelper::getEntry('users','0009f43e-2f29-4257-9344-235c984a3481');      
		// echo $users->signInNames[0]->value;
		// Array of WP_User objects.
		foreach ( $users as $user ) {
			// echo $user->signInNames[0]->value;
			if(empty($user->otherMails)){
			_pre($user);

	        $userEntryInput = array(
							'otherMails' => (array) $user->mail
						);
						// _pre($userEntryInput);

	        // Create the user and display a message
			$aadUser = GraphServiceAccessHelper::updateEntry('users',$user->objectId,$userEntryInput);
	        if(!empty($aadUser->{'odata.error'})){
	           $message = $aadUser->{'odata.error'}->{'message'};
	            $arrFailed[$user->signInNames[0]->value] = $message->{'value'};
	            // echo  $message->{'value'};
	            // die('fail');
	        }else {
	        	$arrSuccess[] = $user->signInNames[0]->value;
	        // die('done');
	        }
die('done');
	       /* if($i == 2){
	        	// get all failed and successfull created users
	        	echo "Failed users List";
				_pre($arrFailed);

				echo "Successfull users List";
			    _pre($arrSuccess);
		        die;
	        }
	        $i++;*/
		    }
	    }

		// echo "Failed users List";
		// _pre($arrFailed);

		// echo "Successfull users List";
	    // _pre($arrSuccess);

		// $groups = GraphServiceAccessHelper::getFeed('groups');  
	 //    echo "<pre>";print_r($groups);  die;
	}
}

/** 
 * Redirect user to AAD login page if user clicks on login page.
 */
add_action("wp_loaded", "redirect_to_login");
// add_action("init", "setSession");

/*
Print all links on wp login form
*/

 //add_action( 'login_form', 'print_login_link' ) ;

/** 
 * Hooks onto the WP login action, so when user logs in on WordPress, user is redirected
 * to B2C's authorization endpoint. 
 */
// add_action('init', 'b2c_login');
// add_action('wp_authenticate', 'b2c_login');

/**
 * Hooks onto the WP page load action, so when user request to edit their profile, 
 * they are redirected to B2C's edit profile endpoint.
 */
// add_action('wp_loaded', 'b2c_edit_profile');
// add_action('wp_loaded', 'b2c_reset_password');
add_action('wp_loaded', 'b2c_migrateUser');
add_action('wp_loaded', 'updateAlternateEmail');

/** 
 * Hooks onto the WP page load action. When B2C redirects back to WordPress site,
 * if an ID token is POSTed to a special path, b2c-token-verification, this verifies 
 * the ID token and authenticates the user.
 */
 add_action('wp_loaded', 'b2c_verify_token');
//  add_action('wp', 'b2c_verify_token');

/**
 * Hooks onto the WP logout action, so when a user logs out of WordPress, 
 * they are redirected to B2C's logout endpoint.
 */
 add_action('wp_logout', 'b2c_logout');



