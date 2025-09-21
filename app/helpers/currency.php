<?php
/**
 * Currency Helper Functions for IDR (Indonesian Rupiah)
 */

/**
 * Format price to IDR currency display
 * @param int|float $amount Amount in IDR
 * @param bool $show_symbol Whether to show Rp symbol
 * @return string Formatted currency string
 */
function format_idr($amount, $show_symbol = true) {
    $formatted = number_format((float)$amount, 0, ',', '.');
    return $show_symbol ? 'Rp ' . $formatted : $formatted;
}

/**
 * Format price with short notation (K, M, B)
 * @param int|float $amount Amount in IDR
 * @param bool $show_symbol Whether to show Rp symbol
 * @return string Formatted currency string
 */
function format_idr_short($amount, $show_symbol = true) {
    $amount = (float)$amount;
    $symbol = $show_symbol ? 'Rp ' : '';
    
    if ($amount >= 1000000000) {
        return $symbol . number_format($amount / 1000000000, 1, ',', '.') . 'M';
    } elseif ($amount >= 1000000) {
        return $symbol . number_format($amount / 1000000, 1, ',', '.') . 'Jt';
    } elseif ($amount >= 1000) {
        return $symbol . number_format($amount / 1000, 0, ',', '.') . 'K';
    }
    
    return $symbol . number_format($amount, 0, ',', '.');
}

/**
 * Clean and validate IDR input from forms
 * @param string $input Raw input from form
 * @return int Clean integer amount
 */
function clean_idr_input($input) {
    // Remove all non-numeric characters except dots and commas
    $cleaned = preg_replace('/[^0-9.,]/', '', $input);
    
    // Handle Indonesian number format (dots as thousands separator, comma as decimal)
    if (strpos($cleaned, ',') !== false) {
        $parts = explode(',', $cleaned);
        $main = str_replace('.', '', $parts[0]);
        $decimal = isset($parts[1]) ? $parts[1] : '0';
        $cleaned = $main . '.' . $decimal;
    } else {
        // Remove dots if used as thousands separator
        $cleaned = str_replace('.', '', $cleaned);
    }
    
    return (int)$cleaned;
}

/**
 * Convert to smallest currency unit for payment processors
 * For IDR, this is already the base unit (no conversion needed)
 * @param int|float $amount Amount in IDR
 * @return int Amount for payment processing
 */
function idr_to_payment_amount($amount) {
    return (int)$amount;
}

/**
 * Convert from payment processor amount to display amount
 * For IDR, this is already the base unit (no conversion needed)
 * @param int $amount Amount from payment processor
 * @return int Display amount
 */
function payment_amount_to_idr($amount) {
    return (int)$amount;
}

/**
 * Get currency symbol
 * @return string Currency symbol
 */
function get_currency_symbol() {
    return 'Rp';
}

/**
 * Get currency code
 * @return string Currency code
 */
function get_currency_code() {
    return 'IDR';
}

/**
 * Validate if amount is valid IDR amount
 * @param mixed $amount Amount to validate
 * @return bool True if valid
 */
function is_valid_idr_amount($amount) {
    $amount = clean_idr_input($amount);
    return is_numeric($amount) && $amount >= 0 && $amount <= 999999999999999; // Max 15 digits
}