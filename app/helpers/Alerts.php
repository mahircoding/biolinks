<?php

namespace Altum;

class Alerts {
    
    /**
     * Output alerts/notifications
     * Wrapper for the display_notifications() function
     */
    public static function output_alerts() {
        display_notifications();
    }
}