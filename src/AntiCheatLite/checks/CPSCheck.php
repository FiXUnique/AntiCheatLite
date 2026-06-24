<?php

namespace AntiCheatLite\checks;

use pocketmine\player\Player;

class CPSCheck {
    
    private const MAX_CPS = 20;
    
    private array $clicks = [];
    private array $lastClickTime = [];

    /**
     * Checks if a player is clicking too fast (CPS - Clicks Per Second)
     */
    public function check(Player $player, float $now): ?string {
        $name = $player->getName();
        
        $this->clicks[$name] = ($this->clicks[$name] ?? 0) + 1;
        $last = $this->lastClickTime[$name] ?? $now;
        
        if ($now - $last >= 1) {
            if ($this->clicks[$name] > self::MAX_CPS) {
                $cps = $this->clicks[$name];
                $this->clicks[$name] = 0;
                $this->lastClickTime[$name] = $now;
                return "High CPS ($cps clicks/sec)";
            }
            $this->clicks[$name] = 0;
            $this->lastClickTime[$name] = $now;
        }

        return null;
    }

    /**
     * Reset data for a specific player
     */
    public function resetPlayer(string $name): void {
        unset($this->clicks[$name]);
        unset($this->lastClickTime[$name]);
    }
}
