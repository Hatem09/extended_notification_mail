
# Extended Notification Mail

## TABLE OF CONTENTS

- [INSTALL](#install)
  * [For Nagios/Icinga](#for-nagiosicinga)
  * [For Icinga2](#for-icinga2)
- [CONFIGURATION](#configuration)
- [TEST](#test)


## INSTALL

### For Nagios/Icinga

* Make sure that PHP is installed and the path to the PHP-binary is correct.
* Register environment variables in PHP, so you can access them:
Edit /etc/php.ini and add "E" to variables_order
```
variables_order = "EGPCS"
```
* Put the extended_notification_mail.php script in your Nagios/Icinga plugin directory
* Add the notifying definitions in the misccomands.cfg
Example misccommands.cfg
```
define command {
      command_name    notify-host-by-email-extended
      command_line    /usr/bin/php -q $USER1$/extended_notification_mail.php
      }
define command {
      command_name    notify-service-by-email-extended
      command_line    /usr/bin/php -q $USER1$/extended_notification_mail.php
      }
```
* Use the new notifying commands in your contact definitions:
```
host_notification_commands            notify-host-by-email-extended
service_notification_commands         notify-service-by-email-extended
```

### For Icinga2

* Make sure that PHP is installed and the path to the PHP-binary is correct.
* Register environment variables in PHP, so you can access them:
Edit /etc/php.ini and add "E" to variables_order
```
variables_order = "EGPCS"
```
* Put the extended_notification_mail.php script in the /etc/icinga2/scripts/ directory
* Create the mail-host-notification-extended and mail-service-notification-extended NotificationCommand objects as following:
```
/* NotificationCommand objects for Extended Notification Mail*/

object NotificationCommand "mail-host-notification-extended" {
  import "plugin-notification-command"

  command = [ SysconfDir + "/icinga2/scripts/extended_notification_mail.php" ]

  env = {
    ICINGA_CONTACTALIAS = "$user.display_name$"
    ICINGA_CONTACTEMAIL = "$user.email$"
    ICINGA_CONTACTGROUPALIAS = ""
    ICINGA_CONTACTGROUPMEMBERS = ""
    ICINGA_CONTACTGROUPNAME = ""
    ICINGA_CONTACTNAME = "$user.name$"
    ICINGA_CONTACTPAGER = "$user.pager$"
    ICINGA_HOSTACKAUTHOR = ""
    ICINGA_HOSTACKCOMMENT = ""
    ICINGA_HOSTADDRESS = "$address$"
    ICINGA_HOSTALIAS = "$host.display_name$"
    ICINGA_HOSTATTEMPT = "$host.check_attempt$"
    ICINGA_HOSTCHECKCOMMAND = "$host.check_command$"
    ICINGA_HOSTCHECKTYPE = "$notification.type$"
    ICINGA_HOSTDISPLAYNAME = "$host.display_name$"
    ICINGA_HOSTDOWNTIME = "$host.downtime_depth$"
    ICINGA_HOSTDURATION = "$host.duration_sec$"
    ICINGA_HOSTGROUPALIAS = ""
    ICINGA_HOSTGROUPNAME = ""
    ICINGA_HOSTGROUPNOTES = ""
    ICINGA_HOSTGROUPNOTESURL = ""
    ICINGA_HOSTLATENCY = "$host.latency$"
    ICINGA_HOSTNAME = "$host.name$"
    ICINGA_HOSTNOTES = "$host.notes$"
    ICINGA_HOSTNOTESURL = "$host.notes_url$"
    ICINGA_HOSTOUTPUT = "$host.output$"
    ICINGA_HOSTPERCENTAGE = ""
    ICINGA_HOSTSTATE = "$host.state$"
    ICINGA_HOSTSTATETYPE = "$host.state_type$"
    ICINGA_LASTHOSTCHECK = "$host.last_check$"
    ICINGA_LASTHOSTDOWN = ""
    ICINGA_LASTHOSTSTATECHANGE = "$host.last_state_change$"
    ICINGA_LASTHOSTUNREACHABLE = ""
    ICINGA_LASTHOSTUP = ""
    //ICINGA_LASTSERVICECHECK = ""
    //ICINGA_LASTSERVICECRITICAL = ""
    //ICINGA_LASTSERVICEOK = ""
    //ICINGA_LASTSERVICESTATECHANGE = "$service.last_state_change$"
    //ICINGA_LASTSERVICEUNKNOWN = ""
    //ICINGA_LASTSERVICEWARNING = ""
    ICINGA_LONGDATETIME = "$icinga.long_date_time$"
    //ICINGA_LONGSERVICEOUTPUT = ""
    ICINGA_NOTIFICATIONAUTHORNAME = "$notification.author$"
    ICINGA_NOTIFICATIONCOMMENT = "$notification.comment$"
    ICINGA_NOTIFICATIONTYPE = "$notification.type$"
    //ICINGA_SERVICEACKAUTHOR = ""
    //ICINGA_SERVICEACKCOMMENT = ""
    //ICINGA_SERVICEATTEMPT = ""
    //ICINGA_SERVICECHECKCOMMAND = "$service.check_command$"
    //ICINGA_SERVICECHECKTYPE = ""
    //ICINGA_SERVICEDESC = "$service.name$"
    //ICINGA_SERVICEDISPLAYNAME = "$service.display_name$"
    //ICINGA_SERVICEDOWNTIME = "$service.downtime_depth$"
    //ICINGA_SERVICEDURATION = "$service.duration_sec$"
    //ICINGA_SERVICEGROUPALIAS = ""
    //ICINGA_SERVICEGROUPNAME = ""
    //ICINGA_SERVICEGROUPNOTES = ""
    //ICINGA_SERVICEGROUPNOTESURL = ""
    //ICINGA_SERVICELATENCY = "$service.latency$"
    //ICINGA_SERVICEOUTPUT = "$service.output$"
    //ICINGA_SERVICEPERCENTCHANGE = ""
    //ICINGA_SERVICESTATE = "$service.state$"
    //ICINGA_SERVICESTATETYPE = "$service.state_type$"
    //ICINGA_TOTALHOSTPROBLEMS = "$down$"
    //ICINGA_TOTALHOSTPROBLEMSUNHANDLED = "$down-(downtime+acknowledged)$"
    ICINGA_TOTALHOSTSDOWN = "$icinga.num_hosts_down$"
    ICINGA_TOTALHOSTSDOWNUNHANDLED = ""
    ICINGA_TOTALHOSTSUNREACHABLE = "$icinga.num_hosts_unreachable$"
    ICINGA_TOTALHOSTSUNREACHABLEUNHANDLED = ""
    ICINGA_TOTALHOSTSUP = "$icinga.num_hosts_up$"
    //ICINGA_TOTALSERVICEPROBLEMS = "$ok+warning+critical+unknown$"
    //ICINGA_TOTALSERVICEPROBLEMSUNHANDLED = "$warning+critical+unknown-(downtime+acknowledged)$"
    ICINGA_TOTALSERVICESCRITICAL = "$host.num_services_critical$"
    ICINGA_TOTALSERVICESCRITICALUNHANDLED = ""
    ICINGA_TOTALSERVICESOK = "$host.num_services_ok$"
    ICINGA_TOTALSERVICESUNKNOWN = "$host.num_services_unknown$"
    ICINGA_TOTALSERVICESUNKNOWNUNHANDLED = ""
    ICINGA_TOTALSERVICESWARNING = "$host.num_services_warning$"
    ICINGA_TOTALSERVICESWARNINGUNHANDLED = ""
    ICINGA_USEREMAIL = "$user.email$"
  }
}

object NotificationCommand "mail-service-notification-extended" {
  import "plugin-notification-command"

  command = [ SysconfDir + "/icinga2/scripts/extended_notification_mail.php" ]

  env = {
    ICINGA_CONTACTALIAS = "$user.display_name$"
    ICINGA_CONTACTEMAIL = "$user.email$"
    ICINGA_CONTACTGROUPALIAS = ""
    ICINGA_CONTACTGROUPMEMBERS = ""
    ICINGA_CONTACTGROUPNAME = ""
    ICINGA_CONTACTNAME = "$user.name$"
    ICINGA_CONTACTPAGER = "$user.pager$"
    ICINGA_HOSTACKAUTHOR = ""
    ICINGA_HOSTACKCOMMENT = ""
    ICINGA_HOSTADDRESS = "$address$"
    ICINGA_HOSTALIAS = "$host.display_name$"
    ICINGA_HOSTATTEMPT = "$host.check_attempt$"
    ICINGA_HOSTCHECKCOMMAND = "$host.check_command$"
    ICINGA_HOSTCHECKTYPE = ""
    ICINGA_HOSTDISPLAYNAME = "$host.display_name$"
    ICINGA_HOSTDOWNTIME = "$host.downtime_depth$"
    ICINGA_HOSTDURATION = "$host.duration_sec$"
    ICINGA_HOSTGROUPALIAS = ""
    ICINGA_HOSTGROUPNAME = ""
    ICINGA_HOSTGROUPNOTES = ""
    ICINGA_HOSTGROUPNOTESURL = ""
    ICINGA_HOSTLATENCY = "$host.latency$"
    ICINGA_HOSTNAME = "$host.name$"
    ICINGA_HOSTNOTES = "$host.notes$"
    ICINGA_HOSTNOTESURL = "$host.notes_url$"
    ICINGA_HOSTOUTPUT = "$host.output$"
    ICINGA_HOSTPERCENTAGE = ""
    ICINGA_HOSTSTATE = "$host.state$"
    ICINGA_HOSTSTATETYPE = "$host.state_type$"
    ICINGA_LASTHOSTCHECK = "$host.last_check$"
    ICINGA_LASTHOSTDOWN = ""
    ICINGA_LASTHOSTSTATECHANGE = "$host.last_state_change$"
    ICINGA_LASTHOSTUNREACHABLE = ""
    ICINGA_LASTHOSTUP = ""
    ICINGA_LASTSERVICECHECK = ""
    ICINGA_LASTSERVICECRITICAL = ""
    ICINGA_LASTSERVICEOK = ""
    ICINGA_LASTSERVICESTATECHANGE = "$service.last_state_change$"
    ICINGA_LASTSERVICEUNKNOWN = ""
    ICINGA_LASTSERVICEWARNING = ""
    ICINGA_LONGDATETIME = "$icinga.long_date_time$"
    ICINGA_LONGSERVICEOUTPUT = ""
    ICINGA_NOTIFICATIONAUTHORNAME = "$notification.author$"
    ICINGA_NOTIFICATIONCOMMENT = "$notification.comment$"
    ICINGA_NOTIFICATIONTYPE = "$notification.type$"
    ICINGA_SERVICEACKAUTHOR = ""
    ICINGA_SERVICEACKCOMMENT = ""
    ICINGA_SERVICEATTEMPT = ""
    ICINGA_SERVICECHECKCOMMAND = "$service.check_command$"
    ICINGA_SERVICECHECKTYPE = "$notification.type$"
    ICINGA_SERVICEDESC = "$service.name$"
    ICINGA_SERVICEDISPLAYNAME = "$service.display_name$"
    ICINGA_SERVICEDOWNTIME = "$service.downtime_depth$"
    ICINGA_SERVICEDURATION = "$service.duration_sec$"
    ICINGA_SERVICEGROUPALIAS = ""
    ICINGA_SERVICEGROUPNAME = ""
    ICINGA_SERVICEGROUPNOTES = ""
    ICINGA_SERVICEGROUPNOTESURL = ""
    ICINGA_SERVICELATENCY = "$service.latency$"
    ICINGA_SERVICEOUTPUT = "$service.output$"
    ICINGA_SERVICEPERCENTCHANGE = ""
    ICINGA_SERVICESTATE = "$service.state$"
    ICINGA_SERVICESTATETYPE = "$service.state_type$"
    //ICINGA_TOTALHOSTPROBLEMS = "$down$"
    //ICINGA_TOTALHOSTPROBLEMSUNHANDLED = "$down-(downtime+acknowledged)$"
    ICINGA_TOTALHOSTSDOWN = "$icinga.num_hosts_down$"
    ICINGA_TOTALHOSTSDOWNUNHANDLED = ""
    ICINGA_TOTALHOSTSUNREACHABLE = "$icinga.num_hosts_unreachable$"
    ICINGA_TOTALHOSTSUNREACHABLEUNHANDLED = ""
    ICINGA_TOTALHOSTSUP = "$icinga.num_hosts_up$"
    //ICINGA_TOTALSERVICEPROBLEMS = "$ok+warning+critical+unknown$"
    //ICINGA_TOTALSERVICEPROBLEMSUNHANDLED = "$warning+critical+unknown-(downtime+acknowledged)$"
    ICINGA_TOTALSERVICESCRITICAL = "$host.num_services_critical$"
    ICINGA_TOTALSERVICESCRITICALUNHANDLED = ""
    ICINGA_TOTALSERVICESOK = "$host.num_services_ok$"
    ICINGA_TOTALSERVICESUNKNOWN = "$host.num_services_unknown$"
    ICINGA_TOTALSERVICESUNKNOWNUNHANDLED = ""
    ICINGA_TOTALSERVICESWARNING = "$host.num_services_warning$"
    ICINGA_TOTALSERVICESWARNINGUNHANDLED = ""
    ICINGA_USEREMAIL = "$user.email$"
  }
}
```

## CONFIGURATION

* configure your domain address etc. at the top of the extended_notification_mail.php file.

## TEST

* put your email-address in $config["mail_add_to_address"] statement.
* Then run the script from command-line:
```
/usr/bin/php -q /opt/extended_notification_mail.php
```
* You should recieve a test-email to the specified address.

## Screenshots

![id_img_example3_service_ok_yahoo.png](https://github.com/Tontonitch/extended_notification_mail/raw/master/screenshots/example3_service_ok_yahoo.png)

## License

```
@authors    Otto Berger <otto@bergerdata.de>
            Yannick Charton <tontonitch-pro@yahoo.fr>
@copyright  Copyright (c) 2009, Otto Berger
            Copyright (c) 2017, Yannick Charton
@license    http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
```

