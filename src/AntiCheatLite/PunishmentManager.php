<?php

namespace AntiCheatLite;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\Server;

class PunishmentManager {
    
    private array $offenses = [];
    private array $whitelist = ["Sqwuippy"];

    /**
     * Handle a player flag with appropriate punishment based on offense count
     */
    public function flag(Player $player, string $reason): void {
        $name = $player->getName();

        if (in_array($name, $this->whitelist)) {
            $message = TextFormat::YELLOW . "[AntiCheat] $name flagged for $reason (whitelisted, no punishment)";
            Server::getInstance()->getLogger()->info($message);
            Server::getInstance()->broadcastMessage($message);
            return;
        }

        $count = $this->offenses[$name] ?? 0;
        $this->offenses[$name] = $count + 1;
        $offenseCount = $this->offenses[$name];
        $banDate = date("Y-m-d H:i:s");
        $appeal = TextFormat::GRAY . "Appeal: Contact @lynottt on Discord";

        switch ($offenseCount) {
            case 1:
                $this->warn($name, $reason);
                break;

            case 2:
                $this->kick($player, $name, $reason);
                break;

            case 3:
                $this->tempBan($player, $name, $reason, $banDate, $appeal);
                break;

            default:
                $this->permBan($player, $name, $reason, $appeal);
                break;
        }
    }

    /**
     * First offense: Warning
     */
    private function warn(string $name, string $reason): void {
        $message = TextFormat::YELLOW . "[AntiCheat] WARNING: $name flagged for $reason";
        Server::getInstance()->getLogger()->warning($message);
        Server::getInstance()->broadcastMessage($message);
    }

    /**
     * Second offense: Kick
     */
    private function kick(Player $player, string $name, string $reason): void {
        $message = TextFormat::RED . "[AntiCheat] $name kicked for $reason";
        Server::getInstance()->getLogger()->warning($message);
        Server::getInstance()->broadcastMessage($message);
        $player->kick("Kicked by AntiCheat ($reason)");
    }

    /**
     * Third offense: Temporary ban (30 days)
     */
    private function tempBan(Player $player, string $name, string $reason, string $banDate, string $appeal): void {
        $unbanDate = new \DateTime("+30 days");
        $banReason = "Temporarily banned by AntiCheat ($reason)\n" .
            TextFormat::YELLOW . "Ban Date: $banDate\n" .
            TextFormat::GOLD . "This ban will be lifted on: " . $unbanDate->format("Y-m-d H:i:s") . "\n" .
            $appeal;
        $banList = Server::getInstance()->getNameBans();
        $banList->addBan($name, TextFormat::clean($banReason), $unbanDate, "AntiCheatLite");
        Server::getInstance()->getLogger()->warning("[AntiCheat] $name temp banned for $reason");
        $player->kick($banReason);
    }

    /**
     * Fourth+ offense: Permanent ban
     */
    private function permBan(Player $player, string $name, string $reason, string $appeal): void {
        $banReason = "Permanently banned by AntiCheat ($reason)\n" . $appeal;
        $banList = Server::getInstance()->getNameBans();
        $banList->addBan($name, TextFormat::clean($banReason), null, "AntiCheatLite");
        Server::getInstance()->getLogger()->warning("[AntiCheat] $name perm banned for $reason");
        $player->kick($banReason);
    }

    /**
     * Reset offenses for a player
     */
    public function resetOffenses(string $name): void {
        unset($this->offenses[$name]);
    }

    /**
     * Get current offense count for a player
     */
    public function getOffenseCount(string $name): int {
        return $this->offenses[$name] ?? 0;
    }

    /**
     * Add player to whitelist
     */
    public function addToWhitelist(string $name): void {
        if (!in_array($name, $this->whitelist)) {
            $this->whitelist[] = $name;
        }
    }

    /**
     * Remove player from whitelist
     */
    public function removeFromWhitelist(string $name): void {
        $this->whitelist = array_filter($this->whitelist, fn($n) => $n !== $name);
    }

    /**
     * Check if player is whitelisted
     */
    public function isWhitelisted(string $name): bool {
        return in_array($name, $this->whitelist);
    }
}
