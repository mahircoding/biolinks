<?php

namespace Altum\Helpers;

use Altum\Database\Database;

class FacebookPixel {

    /**
     * Generate Facebook Pixel base code
     */
    public static function get_base_code($pixel_id) {
        if (empty($pixel_id)) {
            return '';
        }

        return "
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{$pixel_id}');
fbq('track', 'PageView');
</script>
<noscript><img height=\"1\" width=\"1\" style=\"display:none\"
src=\"https://www.facebook.com/tr?id={$pixel_id}&ev=PageView&noscript=1\"
/></noscript>
<!-- End Facebook Pixel Code -->";
    }

    /**
     * Generate Facebook Pixel event tracking code
     */
    public static function track_event($event_name, $parameters = []) {
        $pixel_id = self::get_user_pixel_id();
        
        if (empty($pixel_id)) {
            return '';
        }

        $params_json = !empty($parameters) ? json_encode($parameters) : '{}';
        
        return "
<script>
fbq('track', '{$event_name}', {$params_json});
</script>";
    }

    /**
     * Get Facebook Pixel ID for current user
     */
    public static function get_user_pixel_id() {
        // Check if we're in a user context
        if (isset($GLOBALS['current_user_pixel_id'])) {
            return $GLOBALS['current_user_pixel_id'];
        }

        // Try to get from current user if available
        if (isset($GLOBALS['user']) && !empty($GLOBALS['user']->facebook_pixel_id)) {
            return $GLOBALS['user']->facebook_pixel_id;
        }

        return null;
    }

    /**
     * Set user pixel ID for current context
     */
    public static function set_user_pixel_id($pixel_id) {
        $GLOBALS['current_user_pixel_id'] = $pixel_id;
    }

    /**
     * Generate ViewContent event for product view
     */
    public static function track_view_content($product) {
        $parameters = [
            'content_type' => 'product',
            'content_ids' => [$product->product_id],
            'content_name' => $product->name,
            'value' => $product->price_cents / 100,
            'currency' => 'IDR'
        ];

        return self::track_event('ViewContent', $parameters);
    }

    /**
     * Generate InitiateCheckout event for checkout start
     */
    public static function track_initiate_checkout($product) {
        $parameters = [
            'content_type' => 'product',
            'content_ids' => [$product->product_id],
            'content_name' => $product->name,
            'value' => $product->price_cents / 100,
            'currency' => 'IDR'
        ];

        return self::track_event('InitiateCheckout', $parameters);
    }

    /**
     * Generate Purchase event for completed purchase
     */
    public static function track_purchase($order, $product) {
        $parameters = [
            'content_type' => 'product',
            'content_ids' => [$product->product_id],
            'content_name' => $product->name,
            'value' => $order->amount_cents / 100,
            'currency' => 'IDR',
            'order_id' => $order->order_id
        ];

        return self::track_event('Purchase', $parameters);
    }
}
