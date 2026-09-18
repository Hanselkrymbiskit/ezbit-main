<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Rule    Parameter   Description Example
// required    No  Returns FALSE if the form element is empty.  
// matches Yes Returns FALSE if the form element does not match the one in the parameter.  matches[form_item]
// is_unique   Yes Returns FALSE if the form element is not unique to the table and field name in the parameter.   is_unique[table.field]
// min_length  Yes Returns FALSE if the form element is shorter then the parameter value.  min_length[6]
// max_length  Yes Returns FALSE if the form element is longer then the parameter value.   max_length[12]
// exact_length    Yes Returns FALSE if the form element is not exactly the parameter value.   exact_length[8]
// greater_than    Yes Returns FALSE if the form element is less than the parameter value or not numeric.  greater_than[8]
// less_than   Yes Returns FALSE if the form element is greater than the parameter value or not numeric.   less_than[8]
// alpha   No  Returns FALSE if the form element contains anything other than alphabetical characters.  
// alpha_numeric   No  Returns FALSE if the form element contains anything other than alpha-numeric characters.     
// alpha_dash  No  Returns FALSE if the form element contains anything other than alpha-numeric characters, underscores or dashes.  
// numeric No  Returns FALSE if the form element contains anything other than numeric characters.   
// integer No  Returns FALSE if the form element contains anything other than an integer.   
// decimal No  Returns FALSE if the form element contains anything other than a decimal number.     
// is_natural  No  Returns FALSE if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.    
// is_natural_no_zero  No  Returns FALSE if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.     
// valid_email No  Returns FALSE if the form element does not contain a valid email address.    
// valid_emails    No  Returns FALSE if any value provided in a comma separated list is not a valid email.  
// valid_ip    No  Returns FALSE if the supplied IP is not valid. Accepts an optional parameter of "IPv4" or "IPv6" to specify an IP format.    
// valid_base64    No  Returns FALSE if the supplied string contains anything other than valid Base64 characters.   
$CI =& get_instance();

// log_message("debug","TEST : ".$test);
$config = array();


$config['login/authenticate']   =  array(
                                          array(
                                            'field' => 'login',
                                            'label' => 'Username / Email Address',
                                             'rules' => 'trim|required|xss_clean'
                                          ),
                                          array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|xss_clean'
                                          ),
                                          array(
                                            'field' => 'authcode',
                                            'label' => 'Password',
                                            'rules' => 'trim|xss_clean'
                                          )

                                    );

$config['myaccount/changepassword']   =  array(
                                             array(
                                                    'field' => 'oldpassword',
                                                    'label' => 'Old Password',
                                                     'rules' => 'trim|required|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'newpassword',
                                                    'label' => 'New Password',
                                                    'rules' => 'trim|required|xss_clean|min_length['.$CI->config->item('password_min_length', 'tank_auth').']|max_length['.$CI->config->item('password_max_length', 'tank_auth').']'
                                                 ),
                                             array(
                                                    'field' => 'confirmnewpassword',
                                                    'label' => 'Confirm New Password',
                                                    'rules' => 'trim|required|xss_clean|matches[newpassword]'
                                                 )
                                            );

$config['myaccount/changesecurity']   =  array(
                                             array(
                                                    'field' => 'security_question',
                                                    'label' => 'Security Question',
                                                     'rules' => 'trim|required|xss_clean|numeric'
                                                 ),
                                             array(
                                                    'field' => 'security_question_custom',
                                                    'label' => 'Security Custom Question',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'security_answer',
                                                    'label' => 'Security Answer',
                                                    'rules' => 'trim|required|xss_clean'
                                                 )
                                            );

$config['utilities/passwordverification'] = array(
                                            array(
                                                    'field' => 'password_verification',
                                                    'label' => 'Password Verification',
                                                    'rules' => 'trim|required|xss_clean|min_length['.$CI->config->item('password_min_length', 'tank_auth').']|max_length['.$CI->config->item('password_max_length', 'tank_auth').']'
                                                )
                                            );
$config['myaccount/modifyContactDetails']   =  array(
                                             array(
                                                    'field' => 'streetno',
                                                    'label' => 'Street No/Name',
                                                     'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'region',
                                                    'label' => 'Region',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'province',
                                                    'label' => 'Province',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'city',
                                                    'label' => 'City / Municipalities',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'barangay',
                                                    'label' => 'Barangay',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'zipcode',
                                                    'label' => 'Zipcode',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'Landline_no',
                                                    'label' => 'Province',
                                                    'rules' => 'trim|xss_clean'
                                                 ),
                                             array(
                                                    'field' => 'Mobile_no',
                                                    'label' => 'Province',
                                                    'rules' => 'trim|xss_clean'
                                                 )
                                            );


$config['contactus/index/submitform'] = array(
                                array(
                                        'field' => 'Ticket_Category',
                                        'label' => 'Category',
                                        'rules' => 'trim|xss_clean|numeric'
                                     ),
                                array(
                                        'field' => 'Ticket_CompleteName',
                                        'label' => 'Complete Name',
                                        'rules' => 'trim|xss_clean|alpha_numeric_spaces'
                                     ),
                                array(
                                        'field' => 'Ticket_Subject',
                                        'label' => 'Subject',
                                        'rules' => 'trim|required|xss_clean|alpha_numeric_spaces'
                                     ),
                                array(
                                        'field' => 'Ticket_Message',
                                        'label' => 'EMR System',
                                        'rules' => 'trim|required|xss_clean|alpha_numeric_spaces|alpha_numeric_spaces'
                                     ),
                                array(
                                        'field' => 'Ticket_ContactEmail',
                                        'label' => 'Email Address',
                                        'rules' => 'trim|xss_clean|valid_email'
                                     ),
                                );
