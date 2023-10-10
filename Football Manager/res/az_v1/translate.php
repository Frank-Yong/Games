<?php
/**
 * @param string $key
 * @return string
 */
function translate($key)
{
    $language = $GLOBALS['language'];
    $defaultLanguage = $GLOBALS['defaultLanguage'];

    if(!isset($language[$key]) || !$language[$key]){
        $language[$key] = $defaultLanguage[$key];
    }


    return $language[$key];
}
?>