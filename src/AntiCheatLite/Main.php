<?php

namespace AntiCheatLite;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use AntiCheatLite\checks\ReachCheck;
use AntiCheatLite\checks\CPSCheck;
use AntiCheatLite\checks\HitDelayCheck;
use AntiCheatLite\checks\KillauraCheck;
use AntiCheatLite\checks\VPNCheck;

class Main extends PluginBase implements Listener {

    private PunishmentManager $punishmentManager;
    private CPSCheck $cpsCheck;
    private HitDelayCheck $hitDelayCheck;

    public function onEnable(): void {
        $this->punishmentManager = new PunishmentManager();
        $this->cpsCheck = new CPSCheck();
        $this->hitDelayCheck = new HitDelayCheck();
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "AntiCheatLite enabled.");
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $ip = $player->getNetworkSession()->getIp();

        if (VPNCheck::isVPN($ip)) {
            $banList = $this->getServer()->getIPBans();
            $banList->addBan($ip, "VPNs are not allowed", null, "AntiCheatLite");
            $player->kick("VPNs are not allowed.");
        }
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        
        // Clean up check data
        $this->cpsCheck->resetPlayer($name);
        $this->hitDelayCheck->resetPlayer($name);
    }

    public function onHit(EntityDamageByEntityEvent $event): void {
        $damager = $event->getDamager();
        $victim = $event->getEntity();

        if (!$damager instanceof Player || !$victim instanceof Player) return;

        $now = microtime(true);

        // Reach check
        $reachResult = ReachCheck::check($damager, $victim);
        if ($reachResult !== null) {
            $this->punishmentManager->flag($damager, $reachResult);
        }

        // CPS check
        $cpsResult = $this->cpsCheck->check($damager, $now);
        if ($cpsResult !== null) {
            $this->punishmentManager->flag($damager, $cpsResult);
        }

        // Hit delay check
        $hitDelayResult = $this->hitDelayCheck->check($damager, $now);
        if ($hitDelayResult !== null) {
            $this->punishmentManager->flag($damager, $hitDelayResult);
        }

        // Killaura check (only at long range)
        $dPos = $damager->getPosition();
        $vPos = $victim->getPosition();
        $dx = $dPos->x - $vPos->x;
        $dy = $dPos->y - $vPos->y;
        $dz = $dPos->z - $vPos->z;
        $distance = sqrt($dx * $dx + $dy * $dy + $dz * $dz);

        $killauraResult = KillauraCheck::check($damager, $victim, $distance);
        if ($killauraResult !== null) {
            $this->punishmentManager->flag($damager, $killauraResult);
        }
    }
}