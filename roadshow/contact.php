<?php

/**
 * Class file to handle user requests for contact form
 * 
 * LICENSE:
 *
 * This source file is subject to the licensing terms that
 * is available through the world-wide-web at the following URI:
 * http://codecanyon.net/wiki/support/legal-terms/licensing-terms/.
 * The buyers have extended license are hence certified to use or
 * extend the functionality of this file or re-sell after modification.
 * 
 * This file is licensed to be used with Eventify theme and the
 * same can be resold/redistributed with the terms subject to those
 * are specified for extended license at Envato marketplace in the above link
 * 
 * PHP version >= 5.3
 *
 * @category  ContactForm
 * @package   ContactForm
 * @author    Kirti Kumar Nayak, India <thebestfreelancer.in@gmail.com>
 * @copyright 2013 TheBestFreelancer,
 * @license   http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ CodeCanyon
 * @version   Release 1.9
 * @link      http://demos.thebestfreelancer.in/phpcontact/
 * @tutorial  http://demos.thebestfreelancer.in/phpcontact/documentation/
 */

/**
 * Check if Contact class exists or not and define if not
 */
if (!class_exists('Contact')) {
    
    /**
     * Class to handle contact form requests handled via url
     * 
     * This is a singleton pattern class and can be called via static methods
     * 
     * @category  ContactForm
     * @package   ContactForm
     * @author    Kirti Kumar Nayak, India <thebestfreelancer.in@gmail.com>
     * @copyright 2013 TheBestFreelancer,
     * @license   http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ CodeCanyon
     * @version   Release 1.9
     * @link      http://demos.thebestfreelancer.in/phpcontact/
     * @tutorial  http://demos.thebestfreelancer.in/phpcontact/documentation/
     */
    class Contact
    {
        // {{{ properties
        
        /**
         * private variable to store any string
         * mainly used to store user submitted data
         * 
         * @access private
         * @var string  The string submitted by user/visitor
         */
        private $_str;
        
        /**
         * private variable to store a string of allowable HTML tags
         * to be used against code / CSFR / XSS attacks by visitors/hackers
         * 
         * @access private
         * @var string  The allowable html tags for user
         */
        private $_allowedHTML;
        /**
         * private variable to store
         * an array of restricted HTML/special characters
         * to be used against code / CSFR / XSS attacks by visitors/hackers
         * 
         * @access private
         * @var string  The restricted special characters/strings for user
         */
        private $_restrictedChars;
        /**
         * private property to store mail receivers/admin
         * 
         * @access private
         * @var string The e-mail id(s) who will receive the mail
         */
        private $_receiver;
        /**
         * private property to hold the lowercase letter set
         * to be used to generate random string
         * 
         * @access private
         * @var string  The lowercase character set string
         */
        private $_lowerCaseChars;
        
        /**
         * private property to hold the uppercase letter set
         * to be used to generate random string
         * 
         * @access private
         * @var string  The uppercase character set string
         */
        private $_upperCaseChars;
        
        /**
         * private property to hold the numeric character set
         * to be used to generate random string
         * 
         * @access private
         * @var string  The numeric character set string
         */
        private $_numericChars;
        
        /**
         * private property to hold the special character set
         * to be used to generate random string
         * 
         * @access private
         * @var string  The special character set string
         */
        private $_specialChars;
        
        /**
         * private variable for random characters used by captcha method
         * 
         * @access private
         * @var string  The character set from which captcha should be generated
         */
        private $_charSet;
        
        /**
         * Private variable to store captcha type
         * if you have no GD library installed you may switch over to
         * Javascript captcha
         * 
         * @access private
         * @var string  The type of the captcha wanted
         */
        private $_captchaType;
        
        /**
         * Private variable to store captcha width
         * 
         * @access private
         * @var int  The height of the captcha image
         */
        private $_captchaWidth;
        
        /**
         * Private variable to store the captcha height
         * 
         * @access private
         * @var int  The height of the captcha image
         */
        private $_captchaHeight;
        
        /**
         * Private variable to store the location of the font to be used in captcha image
         * 
         * @access private
         * @var string  The path string of the ttf font file
         */
        private $_captchaFontLocation;
        
        /**
         * Private variable to store the captcha image font size
         * 
         * @access private
         * @var float  The font size for the captcha image
         */
        private $_captchaFontSize;
        
        /**
         * Private variable to store the captcha string angle
         * 
         * @access private
         * @var float  The angle of the captcha string
         */
        private $_captchaCharAngle;
        
        /**
         * private variable to hold mail type
         * expected values 'plain' / 'html'
         * 
         * @access private
         * @var string  The string describing mail type
         */
        private $_mailType;
        
        /**
         * private variable to store plain template
         * 
         * @access private
         * @var string  The plain mail template string
         */
        private $_plainMailTemplate;
        
        /**
         * private variable to store html template
         * 
         * @access private
         * @var string  The html mail template string
         */
        private $_htmlMailTemplate;

        /**
         * private variable to store html reply template
         * 
         * @access private
         * @var string  The html reply mail template string
         */
        private $_replyHtmlMailTemplate;
        
        /**
         * private variable to contain response and to be converted into json
         * 
         * @access private
         * @var mixed  Json data object for page responses
         */
        private $_response;
        
        /**
         * private variable to store system auto response mail id
         * 
         * @access private
         * @var string  The name and e-mail string of the system
         */
        private $_autoResponder;
        
        /**
         * private static variable to hold class object
         * 
         * @access private
         * @staticvar
         * @var object  The current class object
         */
        private static $_classObject;
        
        // }}}
        // {{{ __construct()
        
        /**
         * Default constructor class to initialize variables and page data.
         * Accoring to singleton class costructor must be private
         * 
         * @return void
         * @access  private
         */
        private function __construct()
        {
            
            /*
             * set Error Reporting to all
             */
            error_reporting(E_ALL | E_STRICT);
            /*
             * Initialize a session if not started yet
             * YOU MUST ENABLE THIS IF WANT TO USE CAPTCHA !!!!!!!!!!!!
             */
            /*if (session_id() === '') {
                session_start();
            }
            /*
             * Set the receiver e-mail of the mail
             * CHANGE IT ACCORDING TO NEEDS
             */
            $this->_receiver        = 'yourname@yourdomain.com';
            /*
             * initialize the allowed html tags which user/visitor can
             * use to send a html formatted message
             * define more if you want
             */
            $this->_allowedHTML     =   '<a><br><div><p><span><strong>';
            $this->_allowedHTML     .=  '<h1><h2><h3><h4><h5><h6><hr>';
            $this->_allowedHTML     .=  '<table><tr><td><th><thead><tfoot>';
            
            /*
             * initialize the allowed html tags which user/visitor can
             * use to send a html formatted message
             * define more if you want
             */
            $this->_restrictedChars  =   array('"', 'javascript', '()', '\\');
            /*
             * Main user configurations start
             * You may edit as per your need from here
             * Please refer to documentation if you face problems
             * Or you may ask me in the support section
             */
            
            // captcha configurations
            
            /*
             * define captcha type as php
             * you may use javascript captcha too
             * possible values: php, js
             */
            
            $this->_captchaType     =   'php';
            
            /*
             * initialize captcha image width
             * it is defined as per the html design
             */
            $this->_captchaWidth     =   70;            
            /*
             * initialize the captcha image height
             * defined optimum for the design
             */
            $this->_captchaHeight    =   30;            
            /*
             * initialize the font file location to be used for captcha characters
             * it must be a valid ttf font file at the specified location
             */
            $this->_captchaFontLocation =   './MONOFONT.TTF';
            /*
             * initialize the font size of the captcha string
             * by default the maximum defined i.e. 80% of the image height
             */
            $this->_captchaFontSize  = $this->_captchaHeight * 0.8;
            /*
             * initialize the characters angle for the captcha
             * it is randomly set between -2 and 2
             * as the image height and font size are set
             */
            $this->_captchaCharAngle = rand(-2, 2);
            /*
             * initialize the lowercase character set from a-z
             */
            $this->_lowerCaseChars  =    'abcdefghijklmopqrstuvwxyz';
            /*
             * initialize the uppercase character set from A-Z
             */
            $this->_upperCaseChars  =    'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            /*
             * initalize the numeric character set from 0-9
             */
            $this->_numericChars    =    '0123456789';
            /*
             * initialize the special character set (add more or delete if you like)
             */
            $this->_specialChars    =    '!$%^&*+#~/|';
            /*
             * initialize the captcha characters as null
             */
            $this->_charSet		    =	 '';
            
            // mail configurations
            
            /*
             * Initialize mail type to catch user preferences
             * change it if you want plain mail witout any formatting
             */
            $this->_mailType        =    'html';
            
            /*
             * initiate auto reply system name and e-mail
             * Change it accodting to your needs
             */
            $this->_autoResponder   =   'noreply@yourdomain.com';
            
            /*
             * Initialize plain mail content template
             */
            $this->_plainMailTemplate = '{userMessage}';
            $this->_plainMailTemplate .= "\r\n\r\n\r\n" . 'From';
            $this->_plainMailTemplate .= "\r\n" . 'Name : {userFullName}';
            $this->_plainMailTemplate .= "\r\n" . 'E-Mail : {userEmail}';
            // check for optional fields and add them if they are set
            /*if (isset($_POST['phone']) and (trim($_POST['phone']) !== '')) {
                $this->_plainMailTemplate .= "\r\n" . 'Phone : {userPhone}';
            }
            if (isset($_POST['url']) and (trim($_POST['url']) !== '')) {
                $this->_plainMailTemplate .= "\r\n" . 'URL : {userUrl}';
            }
            /*
             * define html mail content template
             */
            $this->_htmlMailTemplate = '<html><body>';
            $this->_htmlMailTemplate .= '<table style="margin:0 auto;padding:20px;width:90%;display:block;color:#000;background-color:#dddfea;font-family:Tahoma;border-radius:10px"><tr>';
            $this->_htmlMailTemplate .= '<td style="padding:5px;width:100%;display:block">';
            $this->_htmlMailTemplate .= '<p>{userMessage}</p>';
            $this->_htmlMailTemplate .= '</td></tr>';
            $this->_htmlMailTemplate .= '<tr><td style="margin-top:10px;padding-left:10px;font-weight:700;font-family:seriff;font-style:italic">';
            $this->_htmlMailTemplate .= '<h4>From</h4></td></tr>';
            $this->_htmlMailTemplate .= '<tr><td><hr />Name : {userFullName}</td></tr>';
            $this->_htmlMailTemplate .= '<tr><td>E-Mail : {userEmail}</td></tr>';
            // check for optional fields and add them if they are set
            /*if (isset($_POST['phone']) and (trim($_POST['phone']) !== '')) {
                $this->_htmlMailTemplate .= '<tr><td>Phone : {userPhone}</td></tr>';
            }
            if (isset($_POST['url']) and (trim($_POST['url']) !== '')) {
                $this->_htmlMailTemplate .= '<tr><td>URL : {userUrl}</td></tr>';
            }*/
            $this->_htmlMailTemplate .= '</table></body></html>';
            /*
             * define reply html mail content template
             */
            $this->_replyHtmlMailTemplate = '<html><body>';
            $this->_replyHtmlMailTemplate .= '<table style="margin:0 auto;padding:20px;width:100%;display:block;color:#000;font-family:Tahoma">';
            $this->_replyHtmlMailTemplate .= '<tr><td style="padding:10px;width:90%;display:block">';
            $this->_replyHtmlMailTemplate .= 'Dear&nbsp;{userName}</td></tr>';
            $this->_replyHtmlMailTemplate .= '<tr><td><p>We just received your following mail.<br />We\'ll reach you as soon as possible.</p><br /></td></tr>'; 
            $this->_replyHtmlMailTemplate .= '<tr><td><i style="font-size:10px">This is an auto generated reply. Please <strong>do not reply to this e-mail</strong></i></td></tr>';
            $this->_replyHtmlMailTemplate .= '<tr><td style="padding:15px;font-weight:normal;font-size:11px;border:#ccc 1px solid;border-radius:8px">';
            $this->_replyHtmlMailTemplate .= '<p>{userMessage}</p></td></tr>';
            $this->_replyHtmlMailTemplate .= '<tr><td style="margin-top:10px;padding-left:10px;font-weight:700;font-family:seriff;font-style:italic">';
            $this->_replyHtmlMailTemplate .= '<h4>From</h4></td></tr><tr><td>';
            $this->_replyHtmlMailTemplate .= $this->_autoResponder;
            $this->_replyHtmlMailTemplate .= '</td></tr>';
            $this->_replyHtmlMailTemplate .= '</body></html>';
            
            // output configurations
            
            /*
             * initialize page contents as null
             */
            $this->_pageContents	= 	 '';
            /*
             * set page compression to true
             * you may set this false
             * if you don't like to compress the page contents
             */
            $this->_pageCompression =    true;
            /*
             * Initialize the response variable as null
             */
            $this->_response = array(
                'status'            =>   '',
                'message'           =>   '',
                'control'           =>   ''
            );
            
        }
        
        // }}}
        // {{{ getObject()
        
        /**
         * Method to return singleton class object.
         * returns current class object if already present
         * else creates one
         * 
         * @return object  The current class object
         * @access public
         * @static
         * 
         */
        public static function getObject()
        {
            /*
             *  check if class not instantiated
             */
            if (self::$_classObject === null) {
                /*
                 *  then create a new instance
                 */
                self::$_classObject = new self();
            }
            /*
             *  return the class object to be used
             */
            return self::$_classObject;
        }
        
        // }}}
        // {{{ _getRandomChars
        
        /**
         * Generate string of random characters
         *
         * @param int  $length         Length of the string to generate
         * @param bool $lowerCaseChars Include lower case characters
         * @param bool $upperCaseChars Include uppercase characters
         * @param bool $numericChars   Include numbers
         * @param bool $specialChars   Include special characters
         * 
         * @access private
         * @return string  The random character string
         */
        private function _getRandomChars (
            $length = 5,
            $lowerCaseChars = true,
            $upperCaseChars = true,
            $numericChars = true,
            $specialChars = false
        ) {
           
            /**
             * variable to store a random character index every time
             * @access private
             * @var int  The random character index out of character set
             */
            $charIndex               =    '';
           
            /**
             * variable to store a random character every time
             * @access private
             * @var char  The random character out of character set
             */
            $char                    =    '';
           
            /**
             * variable to store a random character set every time
             * @access private
             * @var int  The random character setof length 5 out of character set
             */
            $resultChars             =    '';
           
            /*
             * check if user has opted for lowercase characters
             * if true, then add it to the character set
             */
            if ($lowerCaseChars === true) {
                $this->_charSet      .=   $this->_lowerCaseChars;
            }
            /*
             * Check if user has opted for uppercase characters
             * If true, add it to the character set
             */
            if ($upperCaseChars === true) {
                $this->_charSet      .=   $this->_upperCaseChars;
            }
            /*
             * Check if user has opted for numeric characters
             * If true, add it to the character set
             */
            if ($numericChars === true) {
                $this->_charSet      .=   $this->_numericChars;
            }
            /*
             * Check if user has opted for uppercase characters
             * If true, add it to the character set
             */
            if ($specialChars === true) {
                $this->_charSet      .=   $this->_specialChars;
            }

            /*
             * Check if length has given greater than 0 else return null
             */
            if (($length < 0) || ($length == 0)) {
                return $resultChars;
            }

            /*
             * create a loop to get random 5 characters from the character set
             * 
             */
            for ($i = 0; $i < $length; $i++) {
                /*
                 * get the character randomly
                 * by selecting between 0 to length of the charSet
                 */
                $charIndex           =    rand(0, strlen($this->_charSet));
                $char                =    substr($this->_charSet, $charIndex, 1);
                $resultChars         .=   $char;
            }

            return $resultChars;
        }
        
        // }}}
        // {{{ respondRequest()
                
        /**
         * Method to respond to the requests via url
         * 
         * @param bool   $pageCompression Option for the output data to be compressed or not
         * @param mixed  $mapOptions      The option to show/hide, logitude, latitude for Google Map
         * @param string $companyName     The name of your company
         * @param string $address         The address of your company
         * @param string $captchaType     The type of captcha wanted, possible values: php, js
         * @param string $mailType        The type of mail to be sent, possible values: html, text
         * @param string $emails          The address of mail to send, possible values: as specified
         * @param string $autoResponder   The address of reply mail to be sent back to the visitor
         * 
         * @return mixed  May output page HTML string or JSON validation data
         * @access public
         */
        public function respondRequest(
            $captchaType,
            $mailType,
            $emails,
            $autoResponder
        ) {
            
            /*
             * assign captcha type as user has defined
             * you may use javascript captcha too
             * possible values: php, js
             */
            $this->_captchaType     =   $captchaType;
            
            /*
             * assign mail type to catch user preferences
             * change it if you want plain mail witout any formatting
             */
            $this->_mailType        =    $mailType;
            
            /*
             * set page compression to user defined
             * you may set this false
             * if you don't like to compress the page contents
             */
            $this->_receiver          =    $emails;
            
            /*
             * set page compression to user defined
             * you may set this false
             * if you don't like to compress the page contents
             */
            $this->_autoResponder   =    $autoResponder;
            
            /*
             * catch the get variable
             * if set then act accordingly
             */
            if (isset($_GET['req'])) {
                /*
                 * switch over the request and respond accordingly
                 */
                switch ($_GET['req']) {
                case 'captcha':
                    /*
                     * call the method to create the captcha image
                     */
                    $this->createCaptcha();
                    break;
                case 'captchaimg':
                    /*
                     * check if user has opted php captcha and GD library present
                     * if user has them both then
                     * call the method to create the captcha image
                     * else just return the captcha characters
                     */
                    
                    if (extension_loaded('gd') and ($this->_captchaType==='php')) {
                        /*
                         * return an image tag
                         */
                        echo '<img src="' . $_SERVER['PHP_SELF'] . '?req=captcha&tm='.time().'" alt="Captcha Image" title="Click to get new challenge" />';
                    } else {
                        /*
                         * take two variables with random integer values,
                         * then add them and save in session to verify
                         * @var int  Integers to work as captcha
                         */
                        $a          =   rand(1, 9);
                        $b          =   rand(1, 9);
                        /*
                         * Assign the characters to a session variable
                         */
                        $_SESSION['CaptchaChars']	=	$a+$b;
                        /*
                         * Close the session write buffer to avoid overwriting
                         */
                        session_write_close();
                        /*
                         * simply output the characters only
                         */
                        echo '<h5>'.$a.' + '.$b.' =</h5>';
                    }
                    break;
                
                default:
                     /*
                     * call the method to validate and send the message
                     */
                    $this->sendMail();
                    break;
                }
                /*
                 * validate the name must not be empty
                 * and send json data
                 */
            } else {
                /*
                 * call the method to validate and send the message
                 */
                $this->sendMail();
            }
        }
        
        // }}}
        // {{{ createCaptcha()
        
        /**
         * Method to create captcha image for bot verification
         *
         * @return mixed  Image for captcha
         * @throws Exception  GD or general exceptions
         * @access public
         */
        public function createCaptcha()
        {
            try {
                /*
                 * Assign the characters to a session variable
                 */
                $_SESSION['CaptchaChars']	=	$this->_getRandomChars(5, false, true, true, false);
                /*
                 * Close the session write buffer to avoid overwriting
                 */
                session_write_close();
                
                /*
                 * Create a 100 X 30 image and assign it to a var
                 */
                $img				=	 imagecreatetruecolor($this->_captchaWidth, $this->_captchaHeight);
                /*
                 * create a white color
                 */
                $white              =    imagecolorallocate($img, 255, 255, 255);
                
                /*
                 * Create a black color to write the characters prominently
                 */
                $black              =    imagecolorallocate($img, 0, 0, 0);
                /*
                 * fill the rectangular image with white background
                 */
                imagefilledrectangle($img, 0, 0, 399, 30, $white);
                
                /*
                 * Write the string inside the image
                 * with black color
                 */
                imagettftext(
                    $img,
                    $this->_captchaFontSize,
                    $this->_captchaCharAngle,
                    2,
                    25,
                    $black,
                    $this->_captchaFontLocation,
                    $_SESSION['CaptchaChars']
                );
                /*
                 * generating dots randomly in background
                 * to make an image noise
                 * if you want more noise replace the argument 5
                 * as per your requirement
                 */ 
                for ( $i=0; $i<5; $i++ ) {
                    imagefilledellipse(
                        $img,
                        mt_rand(0,  $this->_captchaWidth),
                        mt_rand(0,  $this->_captchaHeight),
                        2,
                        3,
                        0
                    );
                }
                
                /*
                 * generating lines randomly in background of image
                 * for more noise
                 * if you want more noise replace the argument 10
                 * as per your requirement
                 */ 
                for ( $i=0; $i<10; $i++ ) {
                    imageline(
                        $img,
                        mt_rand(0, $this->_captchaWidth),
                        mt_rand(0, $this->_captchaHeight),
                        mt_rand(0, $this->_captchaWidth),
                        mt_rand(0, $this->_captchaHeight),
                        0
                    );
                }
                /*
                 * Output the image
                 */
                header('Content-Type: image/gif');
                /*
                 * output a gif image
                 */
                imagegif($img);
                /*
                 * destroy the image to save server space
                 */
                imagedestroy($img);
            }
            catch(Exception $ex) {
                die('Oh no.. Something gone wrong... Details: ' . $ex->getMessage());
            }
        }
        
        // }}}
        // {{{ _cleanSubmittedData()
        
        /**
         * The following method makes a variable safe
         * as that may contain unacceptable formats or data
         * to prevent security holes those may be a threat
         * 
         * @param mixed $submittedData The data submitted by the user to be filtered
         * 
         * @return mixed  Cleaned data submitted by the user
         * @access protected
         */

        protected function _cleanSubmittedData($submittedData)
        {
            try {
                /*
                 * check if the data is an array or not
                 */
                if (!is_array($submittedData)) {
                    /*
                     * if that is not an array, treat that as a string
                     */
                    $this->_str       =   $submittedData;
                     /*
                      * trim the spaces if any
                      */
                    $this->_str       =   trim($this->_str);

                    /*
                     * check if magic quotes are on or not
                     * if on then it must have inserted slashes before quotes and slashes
                     */
                    if (get_magic_quotes_gpc()) {
                        /*
                         * if magic quotes are on, it inserts a slash before any quotes, hence remove them
                         */
                        $this->_str   =   stripslashes($this->_str);
                    }
                    
                    /*
                     * escape the data and insert null where restricted characters found
                     */
                    $this->_str       =   str_ireplace($this->_restrictedChars, "", $this->_str);

                    /*
                     * allow the tags for user and strip off rest of them
                     */
                    $this->_str     =   strip_tags($this->_str, $this->_allowedHTML);
                    /*
                     * now return the cleaned data
                     */
                    return $this->_str;

                } else {
                    /**
                     * var to keep cleaned data array for a temporary period
                     * so that they can be returned in cleaned state
                     * and acceptable format
                     * 
                     * @var mixed  The injection cleaned data array
                     * @access private
                     */
                    $cleanArr       =   array();
                    /*
                     * if the data is an array
                     * fetch the array values one by one by the loop
                     */
                    foreach ($submittedData as $pointer=>$str) {
                        /*
                         * Recursively call clean function if the data is array
                         */
                        $cleanArr[$pointer]=$this->_cleanSubmittedData($str);
                    }
                    /*
                     * return the cleaned data array
                     */
                    return $cleanArr;
                }
            }
            catch(Exception $ex) {
                /*
                 * Catch any Exceptions occured
                 */
                die('There seems an error while cleaning user submitted data. Description: '. $ex->getMessage());
            }
        }
        
        // }}}
        // {{{ sendMail()
        
        /**
         * Method to send normal mail
         * 
         * @return string  JSON data object for success or failure
         * @access public
         */
        public function sendMail()
        {
            /*
             * clean up the user submitted data with the defined method
             */
            $submittedData          =   $this->_cleanSubmittedData($_POST);
            /*
             * validate the form data
             */
            if (!isset($submittedData['contact_name']) or ($submittedData['contact_name'] === '')) {
                /*
                 * fill out the response array variable
                 * with alert/error message having bootstrap styles
                 */
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $this->_response['message']	.=	'Please Enter your Name'.$submittedData['contact_name'];
                $this->_response['message']	.=	'</div>';
                
                /*
                 * output the json data by converting the response array into a json format
                 */
                echo json_encode($this->_response);
                /*
                 * exit the script
                 */
                exit(0);
            }
            
            /*
             * validate email via php predefined (inbuilt) function
             */
            if (!isset($submittedData['contact_email']) or (strlen(filter_var($submittedData['contact_email'], FILTER_VALIDATE_EMAIL)) < 1)) {
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $this->_response['message']	.=	'Please Enter your Valid E-Mail';
                $this->_response['message']	.=	'</div>';
                echo json_encode($this->_response);
                exit(0);
            }
            
            /*
             * validate the phone number correct or not if entered
             * enable if you want to validate phone number
             */
            /*if (isset($submittedData['phone']) and !empty($submittedData['phone']) and (!preg_match('/^(\+[1-9][0-9]*(\([0-9]*\)|-[0-9]*-))?[0]?[1-9][0-9\- ]*$/', $submittedData['phone']))) {
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable">';
                $this->_response['message']	.=	'Please Enter a correct phone number';
                $this->_response['message']	.=	'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $this->_response['control']	=	'phone';
                echo json_encode($this->_response);
                exit(0);
            }
            /*
             * validate the url correct or not if entered
             * enable this if you want to validate url
             */
            /*if (isset($submittedData['url']) and !empty($submittedData['url']) and (!preg_match('/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/', $submittedData['url']))) {
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable">';
                $this->_response['message']	.=	'Please Enter a correct URL';
                $this->_response['message']	.=	'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $this->_response['control']	=	'url';
                header("Content-Type: application/json");
                echo json_encode($this->_response);
                exit(0);
            }
            /*
             * validate subject for empty value
             */
            if (!isset($submittedData['contact_subject']) or ($submittedData['contact_subject']==='')) {
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $this->_response['message']	.=	'Please Enter a Subject';
                $this->_response['message']	.=	'</div>';
                echo json_encode($this->_response);
                exit(0);
            }
            /*
             * validate the message empty or blank
             */
            if (!isset($submittedData['contact_message']) or ($submittedData['contact_message'] === '')) {
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $this->_response['message']	.=	'Please Enter your Message';
                $this->_response['message']	.=	'</div>';
                echo json_encode($this->_response);
                exit(0);
            }
            /*
             * Enable this if you want to validate captcha
             */
            /*if (!isset($submittedData['captcha']) or !isset($_SESSION['CaptchaChars']) or ($submittedData['captcha'] !== (string)$_SESSION['CaptchaChars'])) {
                $this->_response['status']	=	'error';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable">';
                $this->_response['message']	.=	'Please Enter The Captcha Correctly';
                $this->_response['message']	.=	'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                $this->_response['control']	=	'captcha';
                echo json_encode($this->_response);
                exit(0);
            }
            
            /*
             * Build up the mail and
             * Set headers first, else the mail may be caught as spam
             */
            $headers			=	array();
            $headers[]			=	"MIME-Version: 1.0";
            // set content type according to the option supplied
            if ($this->_mailType === 'html') {
                $headers[]			=	"Content-type: text/html; charset=iso-8859-1";
            } else {
                $headers[]			=	"Content-type: text/plain; charset=iso-8859-1";
            }
            $headers[]			=	"From: {$submittedData['contact_name']} <{$submittedData['contact_email']}>";
            $headers[]			=	"Reply-To: {$submittedData['contact_name']} <{$submittedData['contact_email']}>";
            $headers[]			=	"Subject: {$submittedData['contact_subject']}";
            /*
             * Set final headers by separating them by newlines into a single string
             */
            $finalHeaderString		=	implode("\r\n", $headers);
            /*
             * Set up the message according to the template
             */
            if ($this->_mailType === 'html') {
                // set up the html template and add contents
                $message			= str_replace(
                    array(
                        '{userMessage}',
                        '{userFullName}',
                        '{userEmail}',
                        //'{userPhone}',
                        //'{userUrl}'
                        ),
                    array(
                        nl2br($submittedData['contact_message']),
                        $submittedData['contact_name'],
                        $submittedData['contact_email'],
                        //$submittedData['phone'],
                        //$submittedData['url']
                        ),
                    "{$this->_htmlMailTemplate}"
                );
                
            } else {
                // set up the plain mail template
                $message			= str_replace(
                    array(
                        '{userMessage}',
                        '{userFullName}',
                        '{userEmail}',
                        //'{userPhone}',
                        //'{userUrl}'
                        ),
                    array(
                        $submittedData['contact_message'],
                        $submittedData['contact_name'],
                        $submittedData['contact_email'],
                        //$submittedData['phone'],
                        //$submittedData['url']
                        ),
                    "{$this->_plainMailTemplate}"
                );
            }
            
            /*
             * Mail it and catch the result to further check if mail has sent or not
             */
            $mailed			=	mail($this->_receiver, $submittedData['contact_subject'], $message, $finalHeaderString);
            /*
             * Now check if the mailing was successful
             */
            if ($mailed) {
                /*
                 * if sender has checked the checkbox to get acknowledgement,
                 * then send a confirmation mail
                 * You dont have any checkbox hence commented the if condition
                 */
                //if (isset($submittedData['acknowledge']) and ($submittedData['acknowledge']==='1')) {
                    
                    // Build up the mail and
                    $headers			=	array();
                    $headers[]		=	"MIME-Version: 1.0";
                    $headers[]		=	"Content-type: text/html; charset=iso-8859-1";
                    $headers[]		=	"From: ".$this->_autoResponder;
                    $headers[]		=	"Reply-To: {$this->_receiver}";
                    $headers[]		=	"Subject: reply: {$submittedData['contact_subject']}";
                    $finalHeaderString	=	implode("\r\n", $headers);
                    // set up the html reply mail template
                    $message		= str_replace(
                        array(
                            '{userName}',
                            '{userMessage}'
                            ),
                        array(
                            $submittedData['contact_name'],
                            nl2br($submittedData['contact_message'])
                            ),
                        $this->_replyHtmlMailTemplate
                    );
                    /*
                     * Mail it and catch the result to further check if mail has sent or not
                     */
                    $mail			=	mail($submittedData['contact_email'], 'reply: ' . $submittedData['contact_subject'], $message, $finalHeaderString);
                //}
                
                $this->_response['status']	=	'success';
                $this->_response['message']	=	'<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $this->_response['message']	.=	'Your message has been sent!';
                // if acknowledgement opted, then output a message to view inbox  ## Commented as you need not
                //if (isset($submittedData['acknowledge']) and ($submittedData['acknowledge']==='1')) {
                $this->_response['message']	.=	' Please check your acknowledgement in your email (inbox/spam folder).';
                //}
                $this->_response['message']	.=	'</div>';
                echo json_encode($this->_response);
                exit(0);
            } else {
                /*
                 * Else give Out an error message and reset
                 */
                $this->_response['status']	=	'success';
                $this->_response['message']	=	'<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $this->_response['message']	.=	'Some Error Occured!';
                $this->_response['message']	.=	'</div>';
                echo json_encode($this->_response);
                exit(0);
            }
        }
        
        // }}}
        // {{{ __clone()
        
        /**
         * According to singleton pattern instance, cloning is prihibited
         *
         * @return string  A message that states, cloning is prohibited
         * @access public
         */
        private function __clone()
        {
            /*
             * only set an error message
             */
            die('Cloning is prohibited for singleton instance.');
        }
        
        // }}}
        
    }
}
//echo 'error:'.implode(', '$_POST);exit();
/*
 * Call Setup for the class
 * you must set these options
 * before using the class
 * This configuration should be present
 * when you call the class object
 * Otherwise you may not get your desired results
 * 
 * please contact the author in case
 * any difficulties
 */
/**
 * Set the type of the captcha needed
 * @var string The type of captcha needed
 */
$captchaType                        = null;
/**
 * Set the reciever email(s) to receive the mail(s)
 * 
 * @var string The e-mails of contact mail receiver
 */
$emails                             = 'yourname@yourdomain.com';
/**
 * Set auto reply system name and e-mail
 * from where the acknowledgement to be sent.
 * Generally it is set to be the id where no reply to be sent
 * Change it according to your needs
 * 
 * @var string  The e-mail for auto reply system
 */
$autoResponder                      =   'noreply@yourdomain.com';

/**
 * Set desired type of mail to send
 * Possible values: html/text
 * Change according to need
 * 
 * @var string  The type of mail to be sent
 */
$mailType                           =   'html';

/*
 * Now call the class method  to implement all the options
 * and get the response as per need
 */
Contact::getObject()->respondRequest(
    $captchaType,
    $mailType,
    $emails,
    $autoResponder
);