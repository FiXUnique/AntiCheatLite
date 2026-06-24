<?php

namespace AntiCheatLite\checks;

use pocketmine\player\Player;

class HitDelayCheck {
    
    private const MIN_HIT_DELAY = 0.1; // Minimum time between hits in seconds
    
    private array $hitTimes = [];

    /**
     * Checks if a player is hitting too rapidly (hit spam)
     */
    public function check(Player $player, float $now): ?string {
        $name = $player->getName();
        
        $lastHit = $this->hitTimes[$name] ?? 0;
        
        if ($now - $lastHit < self::MIN_HIT_DELAY) {
            $this->hitTimes[$name] = $now;
            return "Hit spam (<0.1s between hits)";
        }
        
        $this->hitTimes[$name] = $now;
        return null;
    }

    /**
     * Reset data for a specific player
     */
    public function resetPlayer(string $name): void {
        unset($this->hitTimes[$name]);
    }
}
