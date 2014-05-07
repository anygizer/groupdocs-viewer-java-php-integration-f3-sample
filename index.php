<?php

$f3=require('lib/base.php');

$f3->set('CACHE',FALSE);
$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

$f3->config('config.ini');

$f3->route('GET /', 'view');
$f3->route('GET /index.php', 'view');
$f3->route('GET|POST /view', 'view');

define("DEF_USER_NAME", "Anonymous");
define("DEF_DOC_NAME", "Quick_Start_Guide_To_Using_GroupDocs.pdf");
function view($f3) {
    //Set variables and get POST data
    $f3->set('url', '');
    $f3->set('error', '');
    $url = $f3->get('POST["url"]');
    $header = $f3->get('POST["header"]');
    $userName = $f3->get('POST["userName"]');
    $documentName = $f3->get('POST["documentName"]');
    //Check is URL entered
    if (empty($url)) {
        $f3->set("userName", DEF_USER_NAME);
        $f3->set("documentName", DEF_DOC_NAME);
        $error = 'Please enter URL of installed GroupDocs.Annotation';
        $f3->set('error', $error);
    } else {
        //Remove HTML tags and spices from URL
        $url = trim(strip_tags($url));
        //Add backslash to end of the URL
        if (substr($url, -1) != "/") {
            $url = $url . "/";
        }
        //Check is Show header checkbox is checked if not set property to false
        if ($header == null) {
            $header = "false";
        }
        //Get user name from form
        $userName = trim(strip_tags($userName));
        if ($userName == "") {
            $userName = DEF_USER_NAME;
        }
        $ajaxPath = $url . "getIds/";
        $data = "un=" . $userName;
        //Remove spaces and tags from document name for view and annotate
        $documentName = trim(strip_tags($documentName));
        //Check if document name is empty set document name for default file
        if ($documentName == "") {
            $documentName = DEF_DOC_NAME;
        }
        $data = $data . "&amp;fp=" . $documentName;
        //Set variables for template
        $f3->set("url", $url);
        $f3->set("userName", $userName);
        $f3->set("header", $header);
        $f3->set("documentName", $documentName);
        $f3->set("ajaxPath", $ajaxPath);
        $f3->set("data", $data);
    }
    //Process template
    echo Template::instance()->render('view.htm');
}

$f3->run();
