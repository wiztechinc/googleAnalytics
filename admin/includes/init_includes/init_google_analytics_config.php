<?php
if (!defined('IS_ADMIN_FLAG'))
{
    die('Illegal Access');
}

function removeDirectory($path) {
	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDirectory($file) : unlink($file);
	}
	rmdir($path);
	return;
}
if (is_dir('includes/installers/google_analytics')){
  removeDirectory('includes/installers/google_analytics');
}
if (defined('GA_VERSION'))
{
	unlink('includes/auto_loaders/config.google_analytics.php');
	$messageStack->add('Deleted autoloader', 'success');
	unlink('includes/functions/extra_functions/google_analytics_functions.php');
	$messageStack->add('Deleted extra function', 'success');
}

elseif (defined('GOOGLE_ANALYTICS_UACCT'))
	{
	
$configuration = $db->Execute("SELECT @t4:=configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Google Analytics' OR configuration_group_title = 'Simple Google Analytics' OR configuration_group_title = 'Easy Google Analytics' or configuration_group_title = 'Google Analytics 2019' or configuration_group_title = 'Google Analytics Configuration';");
	
	$db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = @t4 AND `configuration_key` not in ('GOOGLE_ANALYTICS_UACCT');");
	$db->Execute("DROP TABLE IF EXISTS google_analytics_languages;");
	
	$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "  (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES (NULL, 'Enable Google Analytics', 'GA_ENABLED', 'False', 'Do you want to enable Google Analytics', @t4 , '1', NOW(), NOW(), '', 'zen_cfg_select_option(array(\'True\', \'False\'),');");	
	$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "  (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES (NULL, 'Google Analytics Version', 'GA_VERSION', 'Google Analytics 2019', 'This version', @t4 , '2', NOW(), NOW(), '', '');");
	$db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET configuration_group_title = 'Google Analytics 2019', configuration_group_description = 'Set up GA' WHERE configuration_group_id = @t4;");
// unlink('includes/auto_loaders/config.google_analytics.php');
	$messageStack->add('Updated to Google Analytics 2019', 'success');

}
else 
{
	$configuration = $db->Execute("SELECT @t4:=configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Google Analytics' OR configuration_group_title = 'Simple Google Analytics' OR configuration_group_title = 'Easy Google Analytics' or configuration_group_title = 'Google Analytics 2019' or configuration_group_title = 'Google Analytics Configuration';");
	
	$db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = @t4 AND `configuration_key` not in ('GOOGLE_ANALYTICS_UACCT');");	
	$db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES (NULL, 'Google Analytics 2019', 'Google Analytics Configuration', '1', '1');");

	
$db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET `sort_order`= last_insert_id() WHERE configuration_group_id=last_insert_id()");
	
$db->Execute("SELECT @t4:=configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Google Analytics 2019';");	
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "  (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES (NULL, 'Enable Google Analytics', 'GA_ENABLED', 'False', 'Do you want to enable Google Analytics', @t4 , '1', NOW(), NOW(), '', 'zen_cfg_select_option(array(\'True\', \'False\'),');");	
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "  (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES (NULL, 'Google Analytics Version', 'GA_VERSION', 'Google Analytics 2019', 'This version', @t4 , '2', NOW(), NOW(), '', '');");
	
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "  (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES (NULL, 'Google Analytics Acct Id', 'GOOGLE_ANALYTICS_UACCT', 'UA-XXXXX-Y', 'Enter your Google Analytics account number below', @t4 , '3', NOW(), NOW(), '', '');");

$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150 && function_exists('zen_page_key_exists') && function_exists('zen_register_admin_page'))
{
    if (!zen_page_key_exists('configGoogleAnalytics'))
    {
        $configuration          = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'GOOGLE_ANALYTICS_UACCT' LIMIT 1;");
        $configuration_group_id = $configuration->fields['configuration_group_id'];
        if ((int) $configuration_group_id > 0)
        {
            zen_register_admin_page('configGoogleAnalytics', 'BOX_CONFIGURATION_GOOGLE_ANALYTICS', 'FILENAME_CONFIGURATION', 'gID=' . $configuration_group_id, 'configuration', 'Y', $configuration_group_id);
            $messageStack->add('Easy Google Analytics added to Configuration menu.', 'success');
        }
    }
	
}
	 // unlink('includes/auto_loaders/config.google_analytics.php');
	$messageStack->add('Google Analytics 2019 installed', 'success');
}