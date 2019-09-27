<?php
/*
 * !!! THIS SCRIPT IS FOR DEBUG PURPOSE ONLY AND COMES WITH NO WARRANTY !!!
 * !!! USE IT AT YOUR OWN RISK !!!
 * NOTE THAT IT WILL DUMP YOUR BLACKFIRE CREDENTIALS IN THE LOG FILE.
 * YOU MAY RESET YOUR BLACKFIRE SERVER CREDENTIALS FROM YOUR BLACKFIRE ACCOUNT AT https://blackfire.io
 *
 * DON'T FORGET TO REMOVE THIS FILE ONCE YOUR DEBUG SESSION IS FINISHED.
 */

// Change the value of this constant to something "really" secret.
// The value of SECRET will be needed to trigger this script.
// https://foo.tld/bar?blackfire-debug-secret=<value_of_SECRET>
const SECRET = 'thisisnotsosecretchangeit';
// The log file you want to dump the request into.
$logFile = sys_get_temp_dir().'/blackfire-debug-request.log';

if (!(isset($_GET['blackfire-debug-secret']) && SECRET === $_GET['blackfire-debug-secret'])) {
    die('KO');
}

ob_start();
if (\extension_loaded('blackfire')) {
   var_dump(\BlackfireProbe::getMainInstance());
}
$info = strip_tags(ob_get_contents());
ob_end_clean();

$varsToDump = $_SERVER + [
        'BLACKFIRE_SERVER_ID' => getenv('BLACKFIRE_SERVER_ID'),
        'BLACKFIRE_SERVER_TOKEN' => getenv('BLACKFIRE_SERVER_TOKEN'),
        'PHP_INFO' => $info,
    ];
error_log(date('Y-m-d h:i:s - ').print_r($varsToDump, true), 3, $logFile);

echo 'OK';
