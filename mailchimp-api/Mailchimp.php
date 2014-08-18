<?php

require_once 'Mailchimp/Folders.php';
require_once 'Mailchimp/Templates.php';
require_once 'Mailchimp/Users.php';
require_once 'Mailchimp/Helper.php';
require_once 'Mailchimp/Mobile.php';
require_once 'Mailchimp/Ecomm.php';
require_once 'Mailchimp/Neapolitan.php';
require_once 'Mailchimp/Lists.php';
require_once 'Mailchimp/Campaigns.php';
require_once 'Mailchimp/Vip.php';
require_once 'Mailchimp/Reports.php';
require_once 'Mailchimp/Gallery.php';
require_once 'Mailchimp/Exceptions.php';

class WPMUDEV_Mailchimp_Sync_API {

    /**
     * Placeholder attribute for Mailchimp_Folders class
     *
     * @var Mailchimp_Folders
     * @access public
     */
    var $folders;
    /**
     * Placeholder attribute for Mailchimp_Templates class
     *
     * @var Mailchimp_Templates
     * @access public
     */
    var $templates;
    /**
     * Placeholder attribute for Mailchimp_Users class
     *
     * @var Mailchimp_Users
     * @access public
     */
    var $users;
    /**
     * Placeholder attribute for Mailchimp_Helper class
     *
     * @var Mailchimp_Helper
     * @access public
     */
    var $helper;
    /**
     * Placeholder attribute for Mailchimp_Mobile class
     *
     * @var Mailchimp_Mobile
     * @access public
     */
    var $mobile;
    /**
     * Placeholder attribute for Mailchimp_Ecomm class
     *
     * @var Mailchimp_Ecomm
     * @access public
     */
    var $ecomm;
    /**
     * Placeholder attribute for Mailchimp_Neapolitan class
     *
     * @var Mailchimp_Neapolitan
     * @access public
     */
    var $neapolitan;
    /**
     * Placeholder attribute for Mailchimp_Lists class
     *
     * @var Mailchimp_Lists
     * @access public
     */
    var $lists;
    /**
     * Placeholder attribute for Mailchimp_Campaigns class
     *
     * @var Mailchimp_Campaigns
     * @access public
     */
    var $campaigns;
    /**
     * Placeholder attribute for Mailchimp_Vip class
     *
     * @var Mailchimp_Vip
     * @access public
     */
    var $vip;
    /**
     * Placeholder attribute for Mailchimp_Reports class
     *
     * @var Mailchimp_Reports
     * @access public
     */
    var $reports;
    /**
     * Placeholder attribute for Mailchimp_Gallery class
     *
     * @var Mailchimp_Gallery
     * @access public
     */
    var $gallery;

    /**
     * CURLOPT_SSL_VERIFYPEER setting
     * @var  bool
     */
    public $ssl_verifypeer = true;
    /**
     * CURLOPT_SSL_VERIFYHOST setting
     * @var  bool
     */
    public $ssl_verifyhost = 2;
    /**
     * CURLOPT_CAINFO
     * @var  string
     */
    public $ssl_cainfo = null;

    /**
     * Timeout setting
     * @var  bool
     */
    public $timeout = 600;

    /**
     * the api key in use
     * @var  string
     */
    public $apikey;

    public $root = 'https://api.mailchimp.com/2.0';
    /**
     * whether debug mode is enabled
     * @var  bool
     */
    public $debug = false;

    public static $error_map = array(
        "ValidationError" => "Mailchimp_ValidationError",
        "ServerError_MethodUnknown" => "Mailchimp_ServerError_MethodUnknown",
        "ServerError_InvalidParameters" => "Mailchimp_ServerError_InvalidParameters",
        "Unknown_Exception" => "Mailchimp_Unknown_Exception",
        "Request_TimedOut" => "Mailchimp_Request_TimedOut",
        "Zend_Uri_Exception" => "Mailchimp_Zend_Uri_Exception",
        "PDOException" => "Mailchimp_PDOException",
        "Avesta_Db_Exception" => "Mailchimp_Avesta_Db_Exception",
        "XML_RPC2_Exception" => "Mailchimp_XML_RPC2_Exception",
        "XML_RPC2_FaultException" => "Mailchimp_XML_RPC2_FaultException",
        "Too_Many_Connections" => "Mailchimp_Too_Many_Connections",
        "Parse_Exception" => "Mailchimp_Parse_Exception",
        "User_Unknown" => "Mailchimp_User_Unknown",
        "User_Disabled" => "Mailchimp_User_Disabled",
        "User_DoesNotExist" => "Mailchimp_User_DoesNotExist",
        "User_NotApproved" => "Mailchimp_User_NotApproved",
        "Invalid_ApiKey" => "Mailchimp_Invalid_ApiKey",
        "User_UnderMaintenance" => "Mailchimp_User_UnderMaintenance",
        "Invalid_AppKey" => "Mailchimp_Invalid_AppKey",
        "Invalid_IP" => "Mailchimp_Invalid_IP",
        "User_DoesExist" => "Mailchimp_User_DoesExist",
        "User_InvalidRole" => "Mailchimp_User_InvalidRole",
        "User_InvalidAction" => "Mailchimp_User_InvalidAction",
        "User_MissingEmail" => "Mailchimp_User_MissingEmail",
        "User_CannotSendCampaign" => "Mailchimp_User_CannotSendCampaign",
        "User_MissingModuleOutbox" => "Mailchimp_User_MissingModuleOutbox",
        "User_ModuleAlreadyPurchased" => "Mailchimp_User_ModuleAlreadyPurchased",
        "User_ModuleNotPurchased" => "Mailchimp_User_ModuleNotPurchased",
        "User_NotEnoughCredit" => "Mailchimp_User_NotEnoughCredit",
        "MC_InvalidPayment" => "Mailchimp_MC_InvalidPayment",
        "List_DoesNotExist" => "Mailchimp_List_DoesNotExist",
        "List_InvalidInterestFieldType" => "Mailchimp_List_InvalidInterestFieldType",
        "List_InvalidOption" => "Mailchimp_List_InvalidOption",
        "List_InvalidUnsubMember" => "Mailchimp_List_InvalidUnsubMember",
        "List_InvalidBounceMember" => "Mailchimp_List_InvalidBounceMember",
        "List_AlreadySubscribed" => "Mailchimp_List_AlreadySubscribed",
        "List_NotSubscribed" => "Mailchimp_List_NotSubscribed",
        "List_InvalidImport" => "Mailchimp_List_InvalidImport",
        "MC_PastedList_Duplicate" => "Mailchimp_MC_PastedList_Duplicate",
        "MC_PastedList_InvalidImport" => "Mailchimp_MC_PastedList_InvalidImport",
        "Email_AlreadySubscribed" => "Mailchimp_Email_AlreadySubscribed",
        "Email_AlreadyUnsubscribed" => "Mailchimp_Email_AlreadyUnsubscribed",
        "Email_NotExists" => "Mailchimp_Email_NotExists",
        "Email_NotSubscribed" => "Mailchimp_Email_NotSubscribed",
        "List_MergeFieldRequired" => "Mailchimp_List_MergeFieldRequired",
        "List_CannotRemoveEmailMerge" => "Mailchimp_List_CannotRemoveEmailMerge",
        "List_Merge_InvalidMergeID" => "Mailchimp_List_Merge_InvalidMergeID",
        "List_TooManyMergeFields" => "Mailchimp_List_TooManyMergeFields",
        "List_InvalidMergeField" => "Mailchimp_List_InvalidMergeField",
        "List_InvalidInterestGroup" => "Mailchimp_List_InvalidInterestGroup",
        "List_TooManyInterestGroups" => "Mailchimp_List_TooManyInterestGroups",
        "Campaign_DoesNotExist" => "Mailchimp_Campaign_DoesNotExist",
        "Campaign_StatsNotAvailable" => "Mailchimp_Campaign_StatsNotAvailable",
        "Campaign_InvalidAbsplit" => "Mailchimp_Campaign_InvalidAbsplit",
        "Campaign_InvalidContent" => "Mailchimp_Campaign_InvalidContent",
        "Campaign_InvalidOption" => "Mailchimp_Campaign_InvalidOption",
        "Campaign_InvalidStatus" => "Mailchimp_Campaign_InvalidStatus",
        "Campaign_NotSaved" => "Mailchimp_Campaign_NotSaved",
        "Campaign_InvalidSegment" => "Mailchimp_Campaign_InvalidSegment",
        "Campaign_InvalidRss" => "Mailchimp_Campaign_InvalidRss",
        "Campaign_InvalidAuto" => "Mailchimp_Campaign_InvalidAuto",
        "MC_ContentImport_InvalidArchive" => "Mailchimp_MC_ContentImport_InvalidArchive",
        "Campaign_BounceMissing" => "Mailchimp_Campaign_BounceMissing",
        "Campaign_InvalidTemplate" => "Mailchimp_Campaign_InvalidTemplate",
        "Invalid_EcommOrder" => "Mailchimp_Invalid_EcommOrder",
        "Absplit_UnknownError" => "Mailchimp_Absplit_UnknownError",
        "Absplit_UnknownSplitTest" => "Mailchimp_Absplit_UnknownSplitTest",
        "Absplit_UnknownTestType" => "Mailchimp_Absplit_UnknownTestType",
        "Absplit_UnknownWaitUnit" => "Mailchimp_Absplit_UnknownWaitUnit",
        "Absplit_UnknownWinnerType" => "Mailchimp_Absplit_UnknownWinnerType",
        "Absplit_WinnerNotSelected" => "Mailchimp_Absplit_WinnerNotSelected",
        "Invalid_Analytics" => "Mailchimp_Invalid_Analytics",
        "Invalid_DateTime" => "Mailchimp_Invalid_DateTime",
        "Invalid_Email" => "Mailchimp_Invalid_Email",
        "Invalid_SendType" => "Mailchimp_Invalid_SendType",
        "Invalid_Template" => "Mailchimp_Invalid_Template",
        "Invalid_TrackingOptions" => "Mailchimp_Invalid_TrackingOptions",
        "Invalid_Options" => "Mailchimp_Invalid_Options",
        "Invalid_Folder" => "Mailchimp_Invalid_Folder",
        "Invalid_URL" => "Mailchimp_Invalid_URL",
        "Module_Unknown" => "Mailchimp_Module_Unknown",
        "MonthlyPlan_Unknown" => "Mailchimp_MonthlyPlan_Unknown",
        "Order_TypeUnknown" => "Mailchimp_Order_TypeUnknown",
        "Invalid_PagingLimit" => "Mailchimp_Invalid_PagingLimit",
        "Invalid_PagingStart" => "Mailchimp_Invalid_PagingStart",
        "Max_Size_Reached" => "Mailchimp_Max_Size_Reached",
        "MC_SearchException" => "Mailchimp_MC_SearchException"
    );

    public function __construct($apikey=null, $opts=array()) {
        if(!$apikey) $apikey = getenv('MAILCHIMP_APIKEY');
        //if(!$apikey) $apikey = $this->readConfigs();
        if(!$apikey) throw new WPMUDEV_Mailchimp_Error_API('You must provide a MailChimp API key');
        $this->apikey = $apikey;
        $dc = "us1";
        if (strstr($this->apikey,"-")){
            list($key, $dc) = explode("-",$this->apikey,2);
            if (!$dc) $dc = "us1";
        }
        $this->root = str_replace('https://api', 'https://'.$dc.'.api', $this->root);
        $this->root = rtrim($this->root, '/') . '/';

        if ( isset( $opts['timeout'] ) && is_int( $opts['timeout'] ) )
            $this->timeout = absint( $opts['timeout'] );

        if ( isset( $opts['debug'] ) )
            $this->debug = true;

        if ( isset( $opts['ssl_verifypeer'] ) )
            $this->ssl_verifypeer = $opts['ssl_verifypeer'];

        if ( isset( $opts['ssl_verifyhost'] ) )
            $this->ssl_verifyhost = $opts['ssl_verifyhost'];

        if ( isset( $opts['ssl_cainfo'] ) )
            $this->ssl_cainfo = $opts['ssl_cainfo'];


        $this->folders = new WPMUDEV_Mailchimp_Folders_API($this);
        $this->templates = new WPMUDEV_Mailchimp_Templates_API($this);
        $this->users = new WPMUDEV_Mailchimp_Users_API($this);
        $this->helper = new WPMUDEV_Mailchimp_Helper_API($this);
        $this->mobile = new WPMUDEV_Mailchimp_Mobile_API($this);
        $this->ecomm = new WPMUDEV_Mailchimp_Ecomm_API($this);
        $this->neapolitan = new WPMUDEV_Mailchimp_Neapolitan_API($this);
        $this->lists = new WPMUDEV_Mailchimp_Lists_API($this);
        $this->campaigns = new WPMUDEV_Mailchimp_Campaigns_API($this);
        $this->vip = new WPMUDEV_Mailchimp_Vip_API($this);
        $this->reports = new WPMUDEV_Mailchimp_Reports_API($this);
        $this->gallery = new WPMUDEV_Mailchimp_Gallery_API($this);
    }

    public function call($url, $params) {

        $params['apikey'] = $this->apikey;
        $params = json_encode($params);

        $start = microtime(true);
        $this->log('Call to ' . $this->root . $url . '.json: ' . $params);

        $args = array(
            'timeout'     => $this->timeout,
            'user-agent'  => 'MailChimp-PHP/2.0.4',
            'blocking'    => true,
            'headers'     => array( 'Content-Type' => 'application/json' ),
            'body'        => $params,
            'sslverify'   => $this->ssl_verifypeer,
            'filename'    => null
        );

        $args = apply_filters( 'mailchimp_request_args', $args );

        if ( $this->ssl_cainfo )
            $args['sslcertificates'] = $this->ssl_cainfo;

        $response = wp_remote_post( $this->root . $url . '.json', $args );

        if ( is_wp_error( $response ) ) {
            $this->log_errors(
                array( array(  
                    'code' => $response->get_error_code(),
                    'message' => $response->get_error_message()
                ) )
            );
            return $response;
        }

        $response_body = wp_remote_retrieve_body( $response );

        $time = microtime(true) - $start;

        $this->log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->log('Got response: ' . $response_body);

        $result = json_decode($response_body, true);
        
        if( floor( $response['response']['code'] / 100 ) >= 4 ) {
            $error = $this->castError($result);
            $this->log_errors(
                array( array(  
                    'code' => $error->getCode(),
                    'message' => $error->getMessage()
                ) )
            );
            return new WP_Error( $error->getCode(), $error->getMessage() );
        }

        return $result;
    }

    public function readConfigs() {
        $paths = array('~/.mailchimp.key', '/etc/mailchimp.key');
        foreach($paths as $path) {
            if(file_exists($path)) {
                $apikey = trim(file_get_contents($path));
                if($apikey) return $apikey;
            }
        }
        return false;
    }

    public function castError($result) {
        if($result['status'] !== 'error' || !$result['name']) return new WPMUDEV_Mailchimp_Error_API('We received an unexpected error: ' . json_encode($result));

        $class = (isset(self::$error_map[$result['name']])) ? self::$error_map[$result['name']] : 'WPMUDEV_Mailchimp_Error_API';
        return new $class($result['error'], $result['code']);
    }

    public function log($msg) {
        if($this->debug) error_log($msg);
    }

    public function log_errors( $errors ) {
        if ( ! is_array( $errors ) )
            $errors = array( $errors );

        $current_log = get_site_option( 'mailchimp_error_log' );
        $new_log = array();


        foreach ( $errors as $error ) {

            $code = isset( $error['code'] ) ? $error['code'] : 0;
            $message = isset( $error['message'] ) ? $error['message'] : '';
            $email = isset( $error['email'] ) ? $error['email'] : '';
            $date = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), current_time( 'timestamp' ) );

            $new_log[] = compact( 'code', 'message', 'email', 'date' );
            
        }


        if ( $current_log ) {

            $new_log = array_merge( $current_log, $new_log );

            // We'll only saved the last X lines of the log
            $count = count( $new_log );
            if ( $count > MAILCHIMP_MAX_LOG_LINES ) {
                $new_log = array_slice( $new_log, $count - $offset - 1 );
            }
            
        }

        update_site_option( 'mailchimp_error_log', $new_log );
    }
}

