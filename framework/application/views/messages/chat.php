<?php
session_start();
header('content-type', 'plain/text');
// activate full error reporting
//error_reporting(E_ALL & E_STRICT);

print "<pre>";

#Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
#If this doesn't work, are you running 64-bit PHP with < 5.2.6?
$conn = new XMPPHP_XMPP('jabber.tenantmls.com', 5222, '1000000000', 'sparta56tl', 'xmpphp', 'jabber.tenantmls.com', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_INFO);
$conn->autoSubscribe(); 

try {
    $conn->connect();   
    while($end_loop <=0) {
        $payloads = $conn->processUntil(array('end_stream', 'session_start','roster_received'));
        foreach($payloads as $event) {
            $pl = $event[1];
            switch($event[0]) {

                case 'session_start':
                    $conn->getRoster();
                    $conn->presence('I m presence'.time());
                break;

                case 'roster_received':
                $array_contact=$pl;

                foreach($array_contact as $user => $friends_name){
                    echo "<li>".$user.'_NAME_'.$friends_name['name'].'</li>';
                }
                $end_loop++;
                break;
            }
        }       
    }

    while(1)
    {
        $payloads = $conn->processUntil(array('presence'));
        echo "<li>".$payloads[0][1]['from']."_Show_". $payloads[0][1]['show']."</li>";

        $_SESSION[$payloads[0][1]['from']] = "~~";
    }

$conn->disconnect();

} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}

print "</pre>";
//print "<img src='http://xmpp.org/images/xmpp.png' onload='window.location.reload()' />";
?>
