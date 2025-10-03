<?php

/**
 * Language helper function for easy access to translations
 * 
 * @param string $key The language key in dot notation (e.g., 'products.header')
 * @param array $replace Optional array of values to replace in the translation
 * @return string The translated string
 */
function l($key, $replace = []) {
    $language = \Altum\Language::get();
    
    // Split the key by dots to navigate through the language object
    $keys = explode('.', $key);
    $value = $language;
    
    foreach ($keys as $k) {
        if (isset($value->{$k})) {
            $value = $value->{$k};
        } else {
            // Return the key if translation not found
            return $key;
        }
    }
    
    // If we have a string result, handle replacements
    if (is_string($value) && !empty($replace)) {
        foreach ($replace as $search => $replacement) {
            $value = str_replace('%' . $search . '%', $replacement, $value);
        }
    }
    
    return $value;
}