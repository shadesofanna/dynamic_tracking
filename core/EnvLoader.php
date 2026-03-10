<?php
/**
 * Environment Variables Loader
 * Safely loads .env file without exposing credentials
 */

class EnvLoader {
    private static $loaded = false;
    
    public static function load($path = null) {
        if (self::$loaded) {
            return;
        }
        
        if ($path === null) {
            $path = dirname(dirname(__FILE__)) . '/.env';
        }
        
        // Only load if file exists and we're not in production with env vars already set
        if (!file_exists($path)) {
            // In production, rely on server environment variables
            return;
        }
        
        // Load .env file
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments and empty lines
            if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
                continue;
            }
            
            // Parse line
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                    (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
                    $value = substr($value, 1, -1);
                }
                
                // Set environment variable only if not already set
                if (!getenv($key)) {
                    putenv("{$key}={$value}");
                }
            }
        }
        
        self::$loaded = true;
    }
}
