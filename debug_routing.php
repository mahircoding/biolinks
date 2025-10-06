<?php

// Debug routing logic step by step
function debug_url($url) {
    echo "=== DEBUGGING URL: $url ===\n";
    
    // Parse URL
    $parsed = parse_url($url);
    $path = trim($parsed['path'] ?? '', '/');
    $segments = $path ? explode('/', $path) : [];
    
    echo "1. Segments: " . json_encode($segments) . "\n";
    
    // Simulate router logic step by step
    $params = $segments;
    $path = '';
    $controller_key = 'index';
    $controller = 'Index';
    
    echo "2. Initial state: path='$path', controller_key='$controller_key', params=" . json_encode($params) . "\n";
    
    // First check
    if(!empty($params[0])) {
        echo "3. First param exists: " . $params[0] . "\n";
        
        if(in_array($params[0], ['admin','superagency','agency','subagency','whitelabel','ecommerce','s','p'])) {
            echo "4. Matches admin paths\n";
            $path = $params[0];
            unset($params[0]);
            $params = array_values($params);
        } else {
            echo "4. Not admin path, checking if numeric\n";
            // Check if it's a user_id for digital products
            if(is_numeric($params[0])) {
                echo "5. IS NUMERIC - Setting user-products path\n";
                $path = 'user-products';
                $controller_key = 'index';
                // Don't unset params[0] - UserProducts controller needs user_id
            } else {
                echo "5. NOT NUMERIC\n";
            }
        }
    }
    
    echo "6. After first check: path='$path', controller_key='$controller_key', params=" . json_encode($params) . "\n";
    
    // Second check
    if(!empty($params[0])) {
        echo "7. Second check - params[0] exists: " . $params[0] . "\n";
        
        // Special handling for user-products path - check this first
        if($path == 'user-products') {
            echo "8. Path is user-products, checking for slug\n";
            // Check if second param exists for product slug
            if(!empty($params[1])) {
                echo "9. Second param exists (slug): " . $params[1] . " - Setting controller_key to 'view'\n";
                $controller_key = 'view';
                
                // Check if third param is 'checkout'
                if(!empty($params[2]) && $params[2] == 'checkout') {
                    echo "10. Third param is 'checkout' - Setting controller_key to 'checkout'\n";
                    $controller_key = 'checkout';
                    unset($params[2]);
                } else {
                    echo "10. No third param or not 'checkout'\n";
                }
                // Don't unset any params - UserProducts controller needs them
            } else {
                echo "9. No second param (slug)\n";
            }
        } else {
            echo "8. Path is not user-products: $path\n";
        }
    } else {
        echo "7. No params[0] for second check\n";
    }
    
    echo "11. FINAL RESULT: path='$path', controller_key='$controller_key', params=" . json_encode($params) . "\n";
    echo "=== END DEBUG ===\n\n";
}

// Test the problematic URLs
debug_url('https://demo.sekolahotakkananindonesia.sch.id/9017');
debug_url('https://demo.sekolahotakkananindonesia.sch.id/9017/uqy718lb');
debug_url('https://demo.sekolahotakkananindonesia.sch.id/9017/uqy718lb/checkout');

?>
