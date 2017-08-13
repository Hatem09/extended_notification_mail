#!/usr/bin/php -q
<?php
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Extended Notification Mail
 *
 * Version 1.00
 *
 * ------------------
 * License:
 * ------------------
 *
 * Copyright (c) 2009, Otto Berger
 * Copyright (c) 2017, Yannick Charton
 * 
 * This file is part of Extended Notification Mail (or Extended Nagios Notification Mail).
 * 
 * Extended Notification Mail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Extended Notification Mail is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with Extended Notification Mail.  If not, see <http://www.gnu.org/licenses/>.
 *
*/

/**
 * ------------------
 * Configuration:
 * ------------------
 *
*/

/**
* Monitoring engine
* -----------------
* monitoring_engine: can be "nagios", "icinga", or "icinga2"
* monitoring_server: fqdn of the monitoring server
* monitoring_url:    url to your the Nagios CGIs / Icingaweb2 webinterface. Used for command-links inside the mail
*/
$config['monitoring_engine']    = "icinga2";
$config['monitoring_server']    = 'mymonitoringserver.mydomain';
$config['monitoring_url']       = 'http://mymonitoringserver.mydomain/icingaweb2';

if ($config['monitoring_engine'] == "nagios") {
	$config['env_var_prefix'] = 'NAGIOS_';
} else {
	$config['env_var_prefix'] = 'ICINGA_';
}


/**
* Grapher
* -------
* grapher: can be "pnp4nagios", "icinga", or "icinga2"
* grapher_url: url to your grapher webinterface. Used for command-links inside the mail.
*/
$config['grapher']              = "pnp4nagios";
$config['grapher_url']          = 'http://mymonitoringserver.mydomain/pnp4nagios';


/**
* Email settings
* --------------
* mail_from_address:    from-address of the notification mail
* mail_add_to_address:  additional to mail-to-address. this address is also used as reciepient while using the 
*                       test-mode from command-line (and not run from nagios)
* mail_subject_host:    mail subject for host notifications. you can use all Nagios/Icinga-vars here. if empty, 
*                       it will be automatically generated depending on the specified monitoring engine
* mail_subject_service: mail subject for service notifications. you can use all Nagios/Icinga-vars here. if empty, 
*                       it will be automatically generated depending on the specified monitoring engine
* mail_add_headers:     additional mail-headers. adds additional header-lines to the mail header. In the predefined
*                       example uncomment the lines to send outlook high-priority mails.
*/
$config['mail_from_address'] 	= 'icinga@mymonitoringserver.mydomain';
$config['mail_add_to_address']  = '';
$config['mail_subject_host']    = '';
$config['mail_subject_service'] = '';
$config['mail_add_headers'] = array(
	//'X-Priority: 1 (Highest)',
	//'X-MSMail-Priority: High',
	//'Importance: High',
	);

if (! $config['mail_subject_host']) {
	if ($config['monitoring_engine'] == "icinga2") {
		// Macro HOSTNOTIFICATIONNUMBER not available in Icinga2
		$config['mail_subject_host']    = '[I2] Host %%NOTIFICATIONTYPE%% %%HOSTNAME%% is %%HOSTSTATE%%';
		$config['mail_body_title']      = 'Icinga2 Monitoring Message';
	} elseif ($config['monitoring_engine'] == "icinga") {
		$config['mail_subject_host']    = '[I] Host %%NOTIFICATIONTYPE%% %%HOSTNAME%% is %%HOSTSTATE%% (%%NOTIFICATIONNUMBER%%)';
		$config['mail_body_title']      = 'Icinga Monitoring Message';
	} else {
		$config['mail_subject_host']    = '[N] Host %%NOTIFICATIONTYPE%% %%HOSTNAME%% is %%HOSTSTATE%% (%%NOTIFICATIONNUMBER%%)';		
		$config['mail_body_title']      = 'Nagios Monitoring Message';
	}
}

if (! $config['mail_subject_service']) {
	if ($config['monitoring_engine'] == "icinga2") {
		// Macro SERVICENOTIFICATIONNUMBER not available in Icinga2
		$config['mail_subject_service'] = '[I2] Service %%NOTIFICATIONTYPE%% %%HOSTNAME%% %%SERVICEDESC%% is %%SERVICESTATE%%';
	} elseif ($config['monitoring_engine'] == "icinga") {
		$config['mail_subject_service'] = '[I] Service %%NOTIFICATIONTYPE%% %%HOSTNAME%% %%SERVICEDESC%% is %%SERVICESTATE%% (%%NOTIFICATIONNUMBER%%)';
	} else {
		$config['mail_subject_service'] = '[I] Service %%NOTIFICATIONTYPE%% %%HOSTNAME%% %%SERVICEDESC%% is %%SERVICESTATE%% (%%NOTIFICATIONNUMBER%%)';
	}
}

/**
* debug mode
*
* enables the debug mode. All variables sent from nagios are printed at the end
* of the mail. Attention: only visible in the text-only variant of the mail!
*/
$config['debug'] = False;



/**
* Advanced configuration of mail bodies
*
* experts only ;)
* customize the view of the nagios-variables.
*/

// HOST DETAILS

$config['groups'][] = array(
	'name' => 'Host details',
	'active' => true,
	'branches' => array(
		array(
			'name'  => 'Host State',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'State',
					'nagios_env'    => 'HOSTSTATE',
					'required'      => false,
					),
				array(
					'name'          => 'State-Type',
					'nagios_env'    => 'HOSTSTATETYPE',
					'required'      => false,
					),
				array(
					'name'          => 'Attempt',
					'nagios_env'    => 'HOSTATTEMPT',
					'required'      => false,
					),
				array(
					'name'          => 'Duration',
					'nagios_env'    => 'HOSTDURATION',
					'required'      => false,
					),
				array(
					'name'          => 'Downtime',
					'nagios_env'    => 'HOSTDOWNTIME',
					'required'      => false,
					),
				),
			),
		array(
                        'name'  => 'Host Informations',
                        'active' => true,
                        'data'  => array(
                                array(
                                        'name'          => 'Hostname',
                                        'nagios_env'    => 'HOSTNAME',
                                        'required'      => true,
                                        ),
                                array(
                                        'name'          => 'Alias',
                                        'nagios_env'    => 'HOSTALIAS',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Address',
                                        'nagios_env'    => 'HOSTADDRESS',
                                        'required'      => true,
                                        'type'          => 'link',
                                        ),
                                array(
                                        'name'          => 'Description',
                                        'nagios_env'    => 'HOSTNOTES',
                                        'required'      => false,
                                        ),
                                //array(
                                //        'name'          => 'URL',
                                //        'nagios_env'    => 'HOSTNOTESURL',
                                //        'required'      => false,
                                //        'type'          => 'link',
                                //        ),
                                ),
                        ),
		array(
			'name'  => 'Host Group',
			'active' => false,
			'data'  => array(
				array(
					'name'          => 'Group',
					'nagios_env'    => 'HOSTGROUPNAME',
					'required'      => false,
					),
				array(
					'name'          => 'Group Alias',
					'nagios_env'    => 'HOSTGROUPALIAS',
					'required'      => false,
					),
				array(
					'name'          => 'Group Alias',
					'nagios_env'    => 'HOSTGROUPNOTES',
					'required'      => false,
					),
				array(
					'name'          => 'Group Alias',
					'nagios_env'    => 'HOSTGROUPNOTESURL',
					'required'      => false,
					'type'          => 'link',
					),
				),
			),
                array(
                        'name'  => 'Host Times',
                        'active' => true,
                        'data'  => array(
                                array(
                                        'name'          => 'Last Check',
                                        'nagios_env'    => 'LASTHOSTCHECK',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last State-Change',
                                        'nagios_env'    => 'LASTHOSTSTATECHANGE',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last Up',
                                        'nagios_env'    => 'LASTHOSTUP',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last Down',
                                        'nagios_env'    => 'LASTHOSTDOWN',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last Unreachable',
                                        'nagios_env'    => 'LASTHOSTUNREACHABLE',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                ),
                        ),
		array(
			'name'  => 'Host State Data',
			'active' => false,
			'data'  => array(
				array(
					'name'          => 'Command',
					'nagios_env'    => 'HOSTCHECKCOMMAND',
					'required'      => false,
					),
				array(
					'name'          => 'Type',
					'nagios_env'    => 'HOSTCHECKTYPE',
					'required'      => false,
					),
				array(
					'name'          => 'Latency',
					'nagios_env'    => 'HOSTLATENCY',
					'required'      => false,
					),
				array(
					'name'          => 'Percentage',
					'nagios_env'    => 'HOSTPERCENTAGE',
					'required'      => false,
					),
				),
			),
		array(
			'name'  => 'Host-Output',
			'active' => true,
			'data'  => array(
				array(
					'name'          => false,
					'nagios_env'    => 'HOSTOUTPUT',
					'required'      => false,
					),
				),
			),
                array(
                        'name'  => 'Host-Acknowledgement',
                        'active' => true,
                        'data'  => array(
                                array(
                                        'name'          => 'Author',
                                        'nagios_env'    => 'HOSTACKAUTHOR',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Comment',
                                        'nagios_env'    => 'HOSTACKCOMMENT',
                                        'required'      => false,
                                        ),
                                ),
                        )
		)
	);


// SERVICE DETAILS

$config['groups'][] = array(
	'name' => 'Service details',
	'active' => true,
	'branches' => array(
		array(
			'name'  => 'Service State',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'State',
					'nagios_env'    => 'SERVICESTATE',
					'required'      => false,
					),
				array(
					'name'          => 'State-Type',
					'nagios_env'    => 'SERVICESTATETYPE',
					'required'      => false,
					),
				array(
					'name'          => 'Attempt',
					'nagios_env'    => 'SERVICEATTEMPT',
					'required'      => false,
					),
				array(
					'name'          => 'Duration',
					'nagios_env'    => 'SERVICEDURATION',
					'required'      => false,
					),
				array(
					'name'          => 'Downtime',
					'nagios_env'    => 'SERVICEDOWNTIME',
					'required'      => false,
					),
				),
			),
                array(
                        'name'  => 'Service details',
                        'active' => true,
                        'data'  => array(
                                array(
                                        'name'          => 'Service',
                                        'nagios_env'    => 'SERVICEDESC',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Command',
                                        'nagios_env'    => 'SERVICECHECKCOMMAND',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Type',
                                        'nagios_env'    => 'SERVICECHECKTYPE',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Latency',
                                        'nagios_env'    => 'SERVICELATENCY',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Percentage',
                                        'nagios_env'    => 'SERVICEPERCENTCHANGE',
                                        'required'      => false,
                                        ),
                                ),
                        ),
		array(
                        'name'  => 'Service Group',
                        'active' => false,
                        'data'  => array(
                                array(
                                        'name'          => 'Group',
                                        'nagios_env'    => 'SERVICEGROUPNAME',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Group Alias',
                                        'nagios_env'    => 'SERVICEGROUPALIAS',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Notes',
                                        'nagios_env'    => 'SERVICEGROUPNOTES',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Notes',
                                        'nagios_env'    => 'SERVICEGROUPNOTESURL',
                                        'required'      => false,
                                        'type'          => 'link',
                                        ),
                                ),
                        ),
                array(
                        'name'  => 'Service Times',
                        'active' => true,
                        'data'  => array(
                                array(
                                        'name'          => 'Last Check',
                                        'nagios_env'    => 'LASTSERVICECHECK',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last State-Change',
                                        'nagios_env'    => 'LASTSERVICESTATECHANGE',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last OK',
                                        'nagios_env'    => 'LASTSERVICEOK',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last Critical',
                                        'nagios_env'    => 'LASTSERVICECRITICAL',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last Warning',
                                        'nagios_env'    => 'LASTSERVICEWARNING',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                array(
                                        'name'          => 'Last Unknown',
                                        'nagios_env'    => 'LASTSERVICEUNKNOWN',
                                        'required'      => false,
                                        'type'          => 'timestamp',
                                        ),
                                ),
                        ),
		array(
			'name'  => 'Service-Output',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'Output',
					'nagios_env'    => 'SERVICEOUTPUT',
					'required'      => false,
					),
                                array(
                                        'name'          => 'Details',
                                        'nagios_env'    => 'LONGSERVICEOUTPUT',
                                        'required'      => false,
                                        ),
                                ),
			),
                array(
                        'name'  => 'Service-Acknowledgement',
                        'active' => true,
                        'data'  => array(
                                array(
                                        'name'          => 'Author',
                                        'nagios_env'    => 'SERVICEACKAUTHOR',
                                        'required'      => false,
                                        ),
                                array(
                                        'name'          => 'Comment',
                                        'nagios_env'    => 'SERVICEACKCOMMENT',
                                        'required'      => false,
                                        ),
                                ),
			)
                )
	);



// CONTACT DETAILS

$config['groups'][] = array(
	'name' => 'Contact details',
	'active' => false,
	'branches' => array(
		array(
			'name'  => 'Contact Info',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'Name',
					'nagios_env'    => 'CONTACTNAME',
					'required'      => false,
					),
				array(
					'name'          => 'Alias',
					'nagios_env'    => 'CONTACTALIAS',
					'required'      => false,
					),
				array(
					'name'          => 'eMail',
					'nagios_env'    => 'CONTACTEMAIL',
					'required'      => false,
					'type'          => 'mail',
					),
				array(
					'name'          => 'Pager',
					'nagios_env'    => 'CONTACTPAGER',
					'required'      => false,
					),
				),
			),
		array(
			'name'  => 'Contactgroup Info',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'Name',
					'nagios_env'    => 'CONTACTGROUPNAME',
					'required'      => false,
					),
				array(
					'name'          => 'Alias',
					'nagios_env'    => 'CONTACTGROUPALIAS',
					'required'      => false,
					),
				array(
					'name'          => 'Members',
					'nagios_env'    => 'CONTACTGROUPMEMBERS',
					'required'      => false,
					),
				),
			)
		)
	);


// STATISTICS

$config['groups'][] = array(
	'name' => 'Statistics',
	'active' => false,
	'branches' => array(
		array(
			'name'  => 'Hosts-Totals',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'Problems',
					'nagios_env'    => 'TOTALHOSTPROBLEMS',
					'required'      => false,
					),
				array(
					'name'          => 'Problems Unhandled',
					'nagios_env'    => 'TOTALHOSTPROBLEMSUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'Down',
					'nagios_env'    => 'TOTALHOSTSDOWN',
					'required'      => false,
					),
				array(
					'name'          => 'Down Unhandled',
					'nagios_env'    => 'TOTALHOSTSDOWNUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'Unreachable',
					'nagios_env'    => 'TOTALHOSTSUNREACHABLE',
					'required'      => false,
					),
				array(
					'name'          => 'Unreachable Unhandled',
					'nagios_env'    => 'TOTALHOSTSUNREACHABLEUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'Up',
					'nagios_env'    => 'TOTALHOSTSUP',
					'required'      => false,
					),
				),
			),
		array(
			'name'  => 'Services-Totals',
			'active' => true,
			'data'  => array(
				array(
					'name'          => 'Problems',
					'nagios_env'    => 'TOTALSERVICEPROBLEMS',
					'required'      => false,
					),
				array(
					'name'          => 'Problems Unhandled',
					'nagios_env'    => 'TOTALSERVICEPROBLEMSUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'Critical',
					'nagios_env'    => 'TOTALSERVICESCRITICAL',
					'required'      => false,
					),
				array(
					'name'          => 'Critical Unhandled',
					'nagios_env'    => 'TOTALSERVICESCRITICALUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'Unknown',
					'nagios_env'    => 'TOTALSERVICESUNKNOWN',
					'required'      => false,
					),
				array(
					'name'          => 'Unknown Unhandled',
					'nagios_env'    => 'TOTALSERVICESUNKNOWNUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'Warning',
					'nagios_env'    => 'TOTALSERVICESWARNING',
					'required'      => false,
					),
				array(
					'name'          => 'Warning Unhandled',
					'nagios_env'    => 'TOTALSERVICESWARNINGUNHANDLED',
					'required'      => false,
					),
				array(
					'name'          => 'OK',
					'nagios_env'    => 'TOTALSERVICESOK',
					'required'      => false,
					),
				),
			)
		)
	);






class Nagios_Mail {

	var $config = array();
	var $nagios = array();
	var $replace = array();
	var $notification_type;
	var $notification_color;

	function setConfig($config) {
		$this->config = (array)$config;
	}

	function __construct() {

		$File = "/tmp/nee_debug.txt";
		$Handle = fopen($File, 'w');
		fwrite($Handle, "-------------------------\n");
		foreach ($_ENV as $key => $value) {
			fwrite($Handle, $key." => ".$value."\n");
			if (strpos($key, $this->config['env_var_prefix']) !== false) {
				if ((substr($key, 7) == "HOSTDURATION" or substr($key, 7) == "SERVICEDURATION") and is_numeric($value)) {
					$value = gmdate("H:i:s", $value);
				}
				$this->nagios[substr($key, 7)] = $value;
				$this->replace['%%' . substr($key, 7) . '%%'] = $value;
			}
		}
		fclose($Handle);
	}

	function build() {

		if (!empty($this->nagios['SERVICECHECKTYPE'])) {
			$this->notification_type = 'SERVICE';

			if (!empty($this->nagios['SERVICEACKAUTHOR'])) {
				$this->notification_type = 'SERVICE_ACK';
			}
		} else {
			$this->notification_type = 'HOST';

			if (!empty($this->nagios['HOSTACKAUTHOR'])) {
				$this->notification_type = 'HOST_ACK';
			}
		}
		
		if (!empty($this->nagios['LONGHOSTOUTPUT']) && ($this->nagios['LONGHOSTOUTPUT'] == '<br>' || $this->nagios['LONGHOSTOUTPUT'] == '\n')) {
		        $this->nagios['LONGHOSTOUTPUT'] = '';
		}
		if (!empty($this->nagios['LONGSERVICEOUTPUT']) && ($this->nagios['LONGSERVICEOUTPUT'] == '<br>' || $this->nagios['LONGSERVICEOUTPUT'] == '\n')) {
		        $this->nagios['LONGSERVICEOUTPUT'] = '';
		}
		$this->nagios['SERVICEOUTPUT'] = str_replace('<a href=/','<a href=http://'.$this->config['monitoring_server'].'/',$this->nagios['SERVICEOUTPUT']);
                
		$this->str_info = '';

		if (!count($this->nagios)) {

			// Test-Mode

			foreach ($this->config['groups'] as $group) {
				foreach ($group['branches'] as $branch) {
					foreach ($branch['data'] as $field) {

						if (!isset($field['type'])) {
							$field['type'] = false;
						}

						if ($field['type'] == 'timestamp') {
							$value = time();
						} else {
							$value = $field['nagios_env'];
						}
						$this->nagios[$field['nagios_env']] = (strlen($value) > 17) ? substr($value, 0, 17) . "..." : $value;
					}
				}
			}
			$this->str_info .= ' TESTMODE: ';
			$this->nagios['CONTACTEMAIL'] = '';
			$this->nagios['NOTIFICATIONTYPE'] = '';
			$this->nagios['NOTIFICATIONNUMBER'] = '';
		}


		if (!empty($this->nagios['CONTACTEMAIL']) || $this->config['mail_add_to_address']) {


			switch($this->notification_type) {
				case 'HOST' :
				case 'HOST_ACK' :

					$this->str_info .= strtr($this->config['mail_subject_host'], $this->replace);

					switch ($this->nagios['HOSTSTATE']) {
						case 'UP' :
							$this->notification_color = '#00CC33'; // green
						break;
						case 'DOWN' :
							$this->notification_color = '#FF0000'; // red
						break;
						case 'UNREACHABLE' :
							$this->notification_color = '#FF0000'; // red
						break;
						default :
							$this->notification_color = '#999999'; // grey
						break;
					}

				break;
				case 'HOST_ACK' :
					$this->str_info .= 'ACK FROM ' . $this->nagios['HOSTACKAUTHOR'];
				break;
				case 'SERVICE' :
				case 'SERVICE_ACK' :

					$this->str_info .= strtr($this->config['mail_subject_service'], $this->replace);

					switch ($this->nagios['SERVICESTATE']) {
						case 'OK' :
							$this->notification_color = '#00CC33'; // green
						break;
						case 'WARNING' :
							$this->notification_color = '#FF6600'; // orange
						break;
						break;
						case 'CRITICAL' :
							$this->notification_color = '#FF0000'; // red
						break;
						case 'UNKNOWN' :
						default :
							$this->notification_color = '#999999'; // grey
						break;
					}

				break;
				case 'SERVICE_ACK' :
					$this->str_info .= 'ACK FROM ' . $this->nagios['SERVICEACKAUTHOR'];
				break;
			}

			$info_types = array(
				'ACKNOWLEDGEMENT',
				'FLAPPINGSTART',
				'FLAPPINGSTOP',
				'FLAPPINGDISABLED',
				'DOWNTIMESTART',
				'DOWNTIMEEND',
				'DOWNTIMECANCELLED',
				);

			if (in_array($this->nagios['NOTIFICATIONTYPE'], $info_types)) {
				$this->notification_color = 'blue';
			}

			$boundary = '----------_' . md5('Nagios_Mail_' . microtime());

			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'From: ' . $this->config['mail_from_address'];

			foreach ($this->config['mail_add_headers'] as $header) {
				$headers[] = $header;
			}

			$headers[] = "Content-Type: multipart/alternative;\n boundary=\"" . $boundary . "\"";



			$body = "\n--" . $boundary . "\nContent-Transfer-Encoding: 8bit\nContent-Type: text/plain; charset=ISO-8859-15\n\n";
			$body .= $this->getBodyText();
			$body .= "\n--" . $boundary . "\nContent-Transfer-Encoding: 8bit\nContent-Type: text/html; charset=ISO-8859-15\n\n";
			$body .= $this->getBodyHTML();
			$body .= "\n--" . $boundary . "--\n";


			if ($this->nagios['CONTACTEMAIL'] && $this->config['mail_add_to_address']) {
				$this->nagios['CONTACTEMAIL'] .= ', ' . $this->config['mail_add_to_address'];
			} elseif (empty($this->nagios['CONTACTEMAIL'])) {
				$this->nagios['CONTACTEMAIL'] = $this->config['mail_add_to_address'];
			}

			mail($this->nagios['CONTACTEMAIL'], $this->str_info, $body, implode("\n", $headers));

		} else {

			die("\nCONTACTEMAIL env-var is empty (not run from Nagios?) or 'mail_add_to_address' not configured (Testmode)\n\n");
		}
	}


	function getBodyText() {

		$output_text[] = $this->str_info;

		if (strpos($this->notification_type, 'HOST') !== false) {
			$output_text[] = '*Output*: ' . $this->nagios['HOSTOUTPUT'];
			if (!empty($this->nagios['LONGHOSTOUTPUT'])) {
				$output_text[] = '\n*Details*: \n' . $this->nagios['LONGHOSTOUTPUT'];
			}
		} else {
			$output_text[] = '*Output*: ' . $this->nagios['SERVICEOUTPUT'];
			if (!empty($this->nagios['LONGSERVICEOUTPUT'])) {
				$output_text[] = '\n*Details*: \n' . $this->nagios['LONGSERVICEOUTPUT'];
			}
		}

		$output_text[] = '';

		foreach ($this->config['groups'] as $group) {

			if (!$group['active'] || $group['active'] != $this->notification_type) {
				continue;
			}

			$group_text = array();

			foreach ($group['branches'] as $branch) {

				if (!$branch['active'] || $branch['active'] != $this->notification_type) {
					continue;
				}

				$branch_active = false;

				$max_chars = 0;
				foreach ($branch['data'] as $value) {
					if (strlen($value['name']) > $max_chars && (!empty($this->nagios[$value['nagios_env']]) || $value['required']) || (!empty($this->nagios[$value['nagios_env']]) && empty($value['name']))) {
						$max_chars = strlen($value['name']);
						$branch_active = true;
					}
				}

				if ($branch_active) {

					$group_text[] = '*' . $branch['name'] . '*';

					foreach ($branch['data'] as $field) {

						$field['value'] = trim($this->nagios[$field['nagios_env']]);

						if (!empty($field['value']) || $field['required']) {

							if (!isset($field['type'])) {
								$field['type'] = false;
							}

							switch($field['type']) {
								case 'timestamp' :
									$field['value'] = date('d.m.Y H:i', $field['value']);
								break;

							}

							if ($field['name']) {
								$group_text[] = sprintf('%-'. ($max_chars+1) . 's: %s', $field['name'], $field['value']);
							} else {
								$group_text[] = $field['value'];
							}
						}
					}

					$group_text[] = '';
				}
			}

			if (count($group_text)) {
				$output_text[] = '*' . $group['name'] . '*';
				$output_text[] = '------------------------------------';
				$output_text[] = implode("\n", $group_text);
			}
		}

		if ($this->config['monitoring_url']) {

			$output_text[] = '/Nagios/:';
			$output_text[] = '<' . $this->config['monitoring_url'] . '>';

			if ($this->notification_type == 'HOST' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
				$output_text[] = '/Acknowledge this problem/:';
				$output_text[] = '<' . $this->config['monitoring_url'] . '/monitoring/host/acknowledge-problem?host=' . $this->nagios['HOSTNAME'] . '>';
			} elseif ($this->notification_type == 'SERVICE' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
				$output_text[] = '/Acknowledge this problem/:';
				$output_text[] = '<' . $this->config['monitoring_url'] . '/monitoring/service/acknowledge-problem?host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '>';
			}

			if ($this->notification_type == 'HOST') {
				$output_text[] = '/Add a comment/:';
				$output_text[] = '<' . $this->config['monitoring_url'] . '/monitoring/host/add-comment?host=' . $this->nagios['HOSTNAME'] . '>';
			} elseif ($this->notification_type == 'SERVICE') {
				$output_text[] = '/Add a comment/:';
				$output_text[] = '<' . $this->config['monitoring_url'] . '/monitoring/service/add-comment?host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '>';
			}

			$output_text[] = '';
		}

		if ($this->config['debug']) {
			ksort($this->nagios);
			ob_start();
			var_dump($this->nagios);
			$output_text[] = "*DEBUG-OUTPUT*";
			$output_text[] = ob_get_clean();
		}

		return implode("\n", $output_text);

	}



	function getBodyHTML() {

		$output_html[] = <<< END

		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<meta name="viewport" content="width=600" />
		</head>

		<body style="font-family: 'Courier New', Courier, monospace; font-size: 8pt;">

END;

		$output_html[] = '<table cellpadding="0" cellspacing="0" width="100%" style="font-family: \'Courier New\', Courier, monospace; font-size: 8pt;">';
		$output_html[] = '<tr>';
		$output_html[] = '<td width="16"><div style="height:16px;width:16px;background-color:' . $this->notification_color . ';">&nbsp;</div></td>';
		$output_html[] = '<td style="font-size:15pt;font-weight:bold;color:#666666;padding-left:4px">' . $this->config['mail_body_title'] . '</td>';
		$output_html[] = '</tr>';
		$output_html[] = '</table>';

		$output_html[] = '<div style="background-color:#CCC;padding:5px;clear:both;">';
		$output_html[] = '<div style="font-weight:bold;">';
		$output_html[] = $this->str_info;
		$output_html[] = '</div>';
		$output_html[] = '<div style="margin-bottom:10px;">';

		if (strpos($this->notification_type, 'HOST') !== false) {
			//$output_html[] = '<strong>Output:</strong> ' . str_replace('\n','<br>',$this->nagios['HOSTOUTPUT']);
			$output_html[] = '<strong>Output:</strong> ' . nl2br($this->nagios['HOSTOUTPUT'], true);
			if (!empty($this->nagios['LONGHOSTOUTPUT'])) {
				//$output_html[] = '<br><strong>Details:</strong><br>' . str_replace('\n','<br>',$this->nagios['LONGHOSTOUTPUT']);
				$output_html[] = '<br><strong>Details:</strong><br>' . nl2br($this->nagios['LONGHOSTOUTPUT'], true);
			}
		} else {
			//$output_html[] = '<strong>Output:</strong> ' . str_replace('\n','<br>',$this->nagios['SERVICEOUTPUT']);
			$output_html[] = '<strong>Output:</strong> ' . nl2br($this->nagios['SERVICEOUTPUT'], true);
			if (!empty($this->nagios['LONGSERVICEOUTPUT'])) {
				//$output_html[] = '<br><strong>Details:</strong><br>' . str_replace('\n','<br>',$this->nagios['LONGSERVICEOUTPUT']);
				$output_html[] = '<br><strong>Details:</strong><br>' . nl2br($this->nagios['LONGSERVICEOUTPUT'], true);
			}
		}

		$output_html[] = '</div>';

		if ($this->config['monitoring_url']) {
			$output_html[] = '<div>';

			if ($this->config['monitoring_engine'] == "icinga2") {
				#Links
				$output_html[] = 'Links: ';
				$output_html[] = '<a href="' . $this->config['monitoring_url'] . '">Icinga2 Dashboard</a>';
				$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/monitoring/host/show?host=' . $this->nagios['HOSTNAME'] . '#!/icingaweb2/monitoring/host/services?host=' . $this->nagios['HOSTNAME'] . '">Icinga2 host specific view</a>';
				if ($this->config['grapher'] == "pnp4nagios") {
					$output_html[] = ' &#124; <a href="' . $this->config['grapher_url'] . '/graph?host=' . $this->nagios['HOSTNAME'] . '&srv=_HOST_">Host graphs</a><br>';
				}
				
				#Actions
				$output_html[] = 'Actions: ';
				if ($this->notification_type == 'HOST') {
					$output_html[] = '<a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=1&host=' . $this->nagios['HOSTNAME'] . '">Add a comment</a>';
				} elseif ($this->notification_type == 'SERVICE') {
					$output_html[] = '<a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=3&host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '">Add a comment</a>';
				}
	
				if ($this->notification_type == 'HOST' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
					$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/monitoring/host/acknowledge-problem?host=' . $this->nagios['HOSTNAME'] . '">Acknowledge this problem</a>';
				} elseif ($this->notification_type == 'SERVICE' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
					$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/monitoring/service/acknowledge-problem?host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '">Acknowledge this problem</a>';
				}
			} elseif ($this->config['monitoring_engine'] == "icinga") {
				#Links
				$output_html[] = 'Links: ';
				$output_html[] = '<a href="' . $this->config['monitoring_url'] . '">Icinga Tactical Overview</a>';
				$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/cgi-bin/status.cgi?host=' . $this->nagios['HOSTNAME'] . '&nostatusheader">Icinga host specific view</a>';
				if ($this->config['grapher'] == "pnp4nagios") {
					$output_html[] = ' &#124; <a href="' . $this->config['grapher_url'] . '/graph?host=' . $this->nagios['HOSTNAME'] . '&srv=_HOST_">Host graphs</a><br>';
				}
				
				#Actions
				$output_html[] = 'Actions: ';
				if ($this->notification_type == 'HOST') {
						$output_html[] = '<a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=1&host=' . $this->nagios['HOSTNAME'] . '">Add a comment</a>';
				} elseif ($this->notification_type == 'SERVICE') {
						$output_html[] = '<a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=3&host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '">Add a comment</a>';
				}
				
				if ($this->notification_type == 'HOST' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
					$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=33&host=' . $this->nagios['HOSTNAME'] . '">Acknowledge this problem</a>';
				} elseif ($this->notification_type == 'SERVICE' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
					$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=34&host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '">Acknowledge this problem</a>';
				}
			} else {
				#Links
				$output_html[] = 'Links: ';
				$output_html[] = '<a href="' . $this->config['monitoring_url'] . '">Nagios Tactical Overview</a>';
				$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/cgi-bin/status.cgi?host=' . $this->nagios['HOSTNAME'] . '&nostatusheader">Nagios host specific view</a>';
				if ($this->config['grapher'] == "pnp4nagios") {
					$output_html[] = ' &#124; <a href="' . $this->config['grapher_url'] . '/graph?host=' . $this->nagios['HOSTNAME'] . '&srv=_HOST_">Host graphs</a><br>';
				}
				
				#Actions
				$output_html[] = 'Actions: ';
				if ($this->notification_type == 'HOST') {
						$output_html[] = '<a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=1&host=' . $this->nagios['HOSTNAME'] . '">Add a comment</a>';
				} elseif ($this->notification_type == 'SERVICE') {
						$output_html[] = '<a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=3&host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '">Add a comment</a>';
				}
				
				if ($this->notification_type == 'HOST' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
					$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=33&host=' . $this->nagios['HOSTNAME'] . '">Acknowledge this problem</a>';
				} elseif ($this->notification_type == 'SERVICE' && $this->nagios['NOTIFICATIONTYPE'] == 'PROBLEM') {
					$output_html[] = ' &#124; <a href="' . $this->config['monitoring_url'] . '/cgi-bin/cmd.cgi?cmd_typ=34&host=' . $this->nagios['HOSTNAME'] . '&service=' . $this->nagios['SERVICEDESC'] . '">Acknowledge this problem</a>';
				}
			}
			
			$output_html[] = '</div>';
		}

		$output_html[] = '</div>';

		foreach ($this->config['groups'] as $group) {

			if (!$group['active'] || $group['active'] != $this->notification_type) {
				continue;
			}

			$group_html = array();

			foreach ($group['branches'] as $branch) {



				if (!$branch['active'] || $branch['active'] != $this->notification_type) {
					continue;
				}

				$branch_active = false;

				$max_chars = 0;
				foreach ($branch['data'] as $value) {
					if (strlen($value['name']) > $max_chars && (!empty($this->nagios[$value['nagios_env']]) || $value['required']) || (!empty($this->nagios[$value['nagios_env']]) && empty($value['name']))) {
						$max_chars = strlen($value['name']);
						$branch_active = true;
					}
				}

				$branch_html = array();

				if ($branch_active) {

					$branch_html[] = '<table cellspacing="0" cellpadding="0" width="450" style="border:1px solid #CFCFCF; font-family: \'Courier New\', Courier, monospace; font-size: 8pt;">';
					$branch_html[] = '<thead style="font-weight:bold; color:#003399; background-color:#CFCFCF;"><tr><td colspan="2">' . $branch['name'] . '</td></tr></thead>';
					$branch_html[] = '<tbody>';

					foreach ($branch['data'] as $field) {

						$field['value'] = trim($this->nagios[$field['nagios_env']]);

						if (!empty($field['value']) || $field['required']) {

							if (!isset($field['type'])) {
								$field['type'] = false;
							}

							switch($field['type']) {
								case 'timestamp' :
									$field['value'] = date('d.m.Y H:i', $field['value']);
								break;
								case 'link' :

									if (strpos($field['value'], 'http://') === false) {
										$field['value'] = sprintf('<a href="http://%s">%s</a>', $field['value'], $field['value']);
									} else {
										$field['value'] = sprintf('<a href="%s">%s</a>', $field['value'], $field['value']);
									}

								break;
								case 'mail' :
									$field['value'] = sprintf('<a href="mailto:%s">%s</a>', $field['value'], $field['value']);
								break;

							}

							if ($field['name']) {
								//$branch_html[] = sprintf('<tr><td style="padding:1px 2px 1px 2px;width:125px; font-weight:bold;">%s</td><td>%s</td></tr>', $field['name'], str_replace('\n','<br>',$field['value']));
								$branch_html[] = sprintf('<tr><td style="padding:1px 2px 1px 2px;width:125px; font-weight:bold;">%s</td><td>%s</td></tr>', $field['name'], nl2br($field['value'],true));
							} else {
								//$branch_html[] = sprintf('<tr><td style="padding:1px 2px 1px 2px;" colspan="2">%s</td></tr>', str_replace('\n','<br>',$field['value']));
								$branch_html[] = sprintf('<tr><td style="padding:1px 2px 1px 2px;" colspan="2">%s</td></tr>', nl2br($field['value'],true));
							}
						}
					}

					$branch_html[] = '</tbody>';
					$branch_html[] = '</table>';

					$branch_html[] = '';
				}

				$group_html[] = implode("\n", $branch_html);

			}

			if (count($group_html)) {

				$output_html[] = '<h2 style="font-size:10pt;font-weight:bold;color:#666666;border-bottom:1px solid #CCCCCC;clear:both;margin-top:15px;">' . $group['name'] . '</h2>';

				$output_html[] = '<table width="100%" cellpadding="0" cellspacing="0">';

				if (count($group_html) % 2 !== 0) {
					$group_html[] = "";
				}

				foreach ($group_html as $i => $html) {

					if ($i % 2 == 0) {

						if ($i != 0){
							$output_html[] = '</tr>';
						}

						$output_html[] = '<tr>';
					}

					$output_html[] = '<td ' . (($i % 2 == 0) ? 'width="455" ' : '') . 'valign="top" align="left" style="padding: 0 0 5px 0">' . $html . '</td>';
				}
				$output_html[] = '</tr>';
				$output_html[] = '</table>';
			}
		}

		$output_html[] = '</body></html>';

		return implode("\n", $output_html);
	}
}


$nagios = new Nagios_Mail();
$nagios->setConfig($config);
$nagios->build();


?>
