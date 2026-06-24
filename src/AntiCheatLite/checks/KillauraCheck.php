<?php

namespace AntiCheatLite\checks;

use pocketmine\player\Player;
use pocketmine\math\Vector3;

class KillauraCheck {
    
    /**
     * Checks if a player is using killaura by analyzing their aim direction
     * Only triggers when hitting from 4+ blocks away to reduce false positives
     */
    public static function check(Player $damager, Player $victim, float $distance): ?string {
        // Only check at long range to avoid false positives on close combat
        if ($distance < 4.0) {
            return null;
        }

        $eyePos = $damager->getEyePos();
        $direction = $damager->getDirectionVector()->normalize();
        
        // Calculate vector from damager to victim
        $toVictim = new Vector3(
            $victim->getPosition()->x - $eyePos->x,
            $victim->getPosition()->y - $eyePos->y,
            $victim->getPosition()->z - $eyePos->z
        );
        $toVictim = $toVictim->normalize();

        // Dot product: 1.0 = perfect aim, 0.0 = perpendicular, -1.0 = opposite direction
        // Lowered threshold from 0.5 to 0.3 to reduce false flags
        $dot = $direction->dot($toVictim);

        if ($dot < 0.3) {
            return "Killaura (not aiming at target, dot: " . round($dot, 2) . ")";
        }

        return null;
    }
}
