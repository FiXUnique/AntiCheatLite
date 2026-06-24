<?php

namespace AntiCheatLite\checks;

class VPNCheck {
    
    /**
     * Simple VPN detection based on IP ranges
     * In production, consider using an API like ipinfo.io or similar
     */
    public static function isVPN(string $ip): bool {
        // Example: Block common VPN/proxy ranges
        // This is a basic implementation - expand as needed
        $vpnRanges = [
            "185.",
            "185.2",
            "185.3",
        ];
        
        foreach ($vpnRanges as $range) {
            if (str_starts_with($ip, $range)) {
                return true;
            }
        }
        
        return false;
    }
}
