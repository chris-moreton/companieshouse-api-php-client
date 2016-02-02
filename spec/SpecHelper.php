<?php
include 'vendor/autoload.php';

function getApiKey() {
    $key = file_get_contents('.apiKey');
    
    return trim($key);
}

