AntiCheatLite is a lightweight and efficient anti-cheat plugin designed for PocketMine-MP servers. It provides comprehensive cheat detection with minimal performance impact, helping server administrators maintain fair gameplay.

## Features

### Combat Detection
- **Reach Detection** - Identifies players attacking from beyond the maximum allowed distance configurable threshold
- **CPS Checker** - Monitors clicks per second to detect autoclickers and macro users
- **Hit Spam Detection** - Prevents abnormally rapid hits that indicate killaura or similar hacks
- **Killaura Detection** - Advanced aim analysis that detects killaura/aimbot behavior at long range using vector mathematics

### Network Security
- **VPN Detection** - Blocks connections from known VPN and proxy IP ranges to prevent ban evasion
- Configurable IP range blocking for custom VPN/proxy lists

### Punishment System
- Progressive punishment system with escalating consequences:
  - First offense: Warning broadcast to server
  - Second offense: Kick from server
  - Third offense: 30-day temporary ban
  - Fourth+ offense: Permanent ban
- Customizable whitelist for trusted players who bypass all checks
- Detailed ban messages with ban date, expiry, and appeal information

## Configuration

### Whitelist Management
Edit the whitelist in `src/AntiCheatLite/PunishmentManager.php` to add trusted players who bypass all anti-cheat checks.

### Detection Thresholds
All detection thresholds are configurable in their respective check classes:
- `ReachCheck.php` - Maximum attack distance (default: 4.5 blocks)
- `CPSCheck.php` - Maximum clicks per second (default: 20 CPS)
- `HitDelayCheck.php` - Minimum time between hits (default: 0.1 seconds)
- `KillauraCheck.php` - Aim accuracy threshold (default: 0.3 dot product)
- `VPNCheck.php` - VPN/proxy IP ranges

### Permissions
- `anticheatatlite.bypass` - Allows players to bypass all anti-cheat checks (default: op)

## Technical Details

### Performance Optimized
- Lightweight checks with minimal server impact
- Efficient vector mathematics for aim detection
- State tracking only for active players
- Automatic cleanup on player disconnect

### Modular Architecture
- Separate check classes for easy maintenance
- Clean code structure following PHP best practices
- Well-documented code with inline comments
- Easy to extend with new detection methods

### Compatibility
- Requires PocketMine-MP API 5.0.0 or higher
- Compatible with most PocketMine plugins
- No conflicting dependencies

## Support

For support, bug reports, or feature requests, contact @lynottt on Discord.
