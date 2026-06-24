<?php

namespace AntiCheatLite\checks;

use pocketmine\player\Player;

class ReachCheck {
    
    private const MAX_REACH_DISTANCE = 4.5;

    /**
     * Checks if a player is hitting from beyond the maximum allowed distance
     */
    public static function check(Player $damager, Player $victim): ?string {
        $dPos = $damager->getPosition();
        $vPos = $victim->getPosition();
        
        $dx = $dPos->x - $vPos->x;
        $dy = $dPos->y - $vPos->y;
        $dz = $dPos->z - $vPos->z;
        $distance = sqrt($dx * $dx + $dy * $dy + $dz * $dz);

        if ($distance > self::MAX_REACH_DISTANCE) {
            return "Reach ($distance blocks)";
        }

        return null;
    }
}
