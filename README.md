# AntiCheatLite

A lightweight, efficient anti-cheat plugin for PocketMine-MP servers.

## Features

- **Reach Detection** - Detects players hitting from beyond maximum attack distance
- **CPS Checker** - Monitors clicks per second to detect autoclickers
- **Hit Spam Detection** - Prevents abnormally rapid hits
- **Killaura Detection** - Identifies aimbot behavior at long range
- **VPN Detection** - Blocks connections from known VPN/proxy IPs
- **Progressive Punishment** - Warning → Kick → Temp Ban → Perm Ban

## Installation

1. Download the latest release
2. Place the plugin in your server's `plugins` folder
3. Restart your server

## Punishment System

- **1st offense**: Warning broadcast to server
- **2nd offense**: Kick from server
- **3rd offense**: 30-day temporary ban
- **4th+ offense**: Permanent ban

## Configuration

Whitelisted players bypass all anti-cheat checks. Edit the whitelist in `src/AntiCheatLite/PunishmentManager.php`.

## Requirements

- PocketMine-MP API 5.0.0 or higher

## Contributing

Forks and pull requests are welcome! Feel free to:
- Report issues
- Suggest new features
- Submit improvements
- Fix bugs

## Support

Text me on discord: @lynottt

## License

This project is open source. Feel free to fork and modify for your server's needs.

---

**Follow for more updates and improvements!**
