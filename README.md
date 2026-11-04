# ScoreHud

![ScoreHud banner](meta/ScoreHud.PNG)

ScoreHud is a configurable Bedrock scoreboard plugin for PocketMine-MP. This
repository is the maintained, self-contained build for current Axolotl-PM API 5
servers. The bundled ScoreFactory uses the current scoreboard packet API, so no
external ScoreHud library, wiki checkout or addon repository is required at
runtime.

[![CI](https://github.com/NhanAZ-Plugins/ScoreHud/actions/workflows/phpstan.yml/badge.svg)](https://github.com/NhanAZ-Plugins/ScoreHud/actions/workflows/phpstan.yml)
[![Build PHAR](https://github.com/NhanAZ-Plugins/ScoreHud/actions/workflows/build.yml/badge.svg)](https://github.com/NhanAZ-Plugins/ScoreHud/actions/workflows/build.yml)
[![Latest release](https://img.shields.io/github/v/release/NhanAZ-Plugins/ScoreHud)](https://github.com/NhanAZ-Plugins/ScoreHud/releases/latest)
[![License](https://img.shields.io/github/license/NhanAZ-Plugins/ScoreHud)](LICENSE)
[![Discord](https://img.shields.io/discord/986553214889517088?label=Discord&logo=discord&logoColor=white&color=5865F2)](https://discord.gg/j2X83ujT6c)

## Features

- Up to 15 configurable scoreboard lines.
- Per-world boards and disabled-world support.
- Optional flickering titles with a fixed, server-wide animation rate.
- Built-in player, server, world, health, experience, item, time and memory tags.
- Event-driven API for plugin integrations and timely tag updates.
- Self-contained PHAR build with no runtime virion dependency.
- PHPStan level max in CI and a DevTools PHAR build on every push and pull request.

## Installation

1. Download ScoreHud.phar from the latest release.
2. Copy it to the plugins directory of an Axolotl-PM API 5 server.
3. Start the server once, then edit plugin_data/ScoreHud/config.yml and
   plugin_data/ScoreHud/scorehud.yml.
4. Restart the server.

ScoreHud has no mandatory dependency. Optional integrations activate when their
plugin is installed and enabled.

## Quick configuration

The following example uses the official integrations maintained alongside this
fork:

~~~yaml
default-board:
  - ""
  - "§eRank"
  - "§8» §f{ppscore.rank}"
  - ""
  - "§ePlayers"
  - "§8» §f{scorehud.online}§8/§f{scorehud.max_online}"
  - ""
  - "§eCoins"
  - "§8» §f{simpleeconomy.balance}"
  - ""
~~~

Use {scorehud.online} and {scorehud.max_online} for the built-in player count
tags. The older {player.online}, {player.max_online} and {town.coins} names are
not ScoreHud tags and will remain unresolved.

## Tags

Tags are written between braces, for example {scorehud.name}. Unknown tags are
left available to other plugins through TagsResolveEvent.

### Built-in ScoreHud tags

| Tag | Description |
| --- | --- |
| {scorehud.name} / {scorehud.real_name} | Player name |
| {scorehud.display_name} | Player display name |
| {scorehud.online} | Current online-player count |
| {scorehud.max_online} | Server max-player setting |
| {scorehud.world_player_count} | Players in the current world |
| {scorehud.world_name} / {scorehud.level_name} | World display name |
| {scorehud.world_folder_name} / {scorehud.level_folder_name} | World folder name |
| {scorehud.x}, {scorehud.y}, {scorehud.z} | Integer player coordinates |
| {scorehud.health} / {scorehud.max_health} | Current and maximum health |
| {scorehud.xp_level} | Experience level |
| {scorehud.xp_progress} | Experience progress |
| {scorehud.xp_remainder} | Remaining experience to the next level |
| {scorehud.xp_current_total} | Total experience |
| {scorehud.item_name} / {scorehud.item_count} | Held item and count |
| {scorehud.ping} | Network ping |
| {scorehud.ip} | Player IP address |
| {scorehud.time} / {scorehud.date} | Configured server time and date |
| {scorehud.load} / {scorehud.tps} | Tick usage and ticks per second |
| {scorehud.memory_main_thread}, {scorehud.memory_total}, {scorehud.memory_virtual}, {scorehud.memory_heap}, {scorehud.memory_global} | Optional memory metrics; enable them in config.yml |

### Official optional integrations

These tags are part of the supported NhanAZ-Plugins integration set. Install the
corresponding plugin beside ScoreHud; ScoreHud does not require any of them to
start.

| Plugin | Tags |
| --- | --- |
| [EcoAPIScore](https://github.com/NhanAZ-Plugins/EcoAPIScore) | {ecoapi.balance}, {ecoapi.raw}, {ecoapi.money}, {ecoapi.formatted}, {ecoapi.provider}, {ecoapi.currency} |
| [SimpleEconomy](https://github.com/NhanAZ-Plugins/SimpleEconomy) | {simpleeconomy.balance} formatted balance, {simpleeconomy.raw} raw integer balance, {simpleeconomy.rank} leaderboard position |
| [PurePerms](https://github.com/NhanAZ-Plugins/PurePerms) | {ppscore.rank}, {ppscore.prefix}, {ppscore.suffix} |
| [PureChat](https://github.com/NhanAZ-Plugins/PureChat) | {purechat.prefix}, {purechat.suffix}, {purechat.faction_name}, {purechat.faction_rank}; compatibility aliases {pureperms.prefix}, {pureperms.suffix}, {pureperms.group}, {pureperms.rank} |

SimpleEconomy, PureChat and PurePerms resolve their own optional tags through
ScoreHud's public events. Their integration is deliberately soft: removing one
of those plugins does not make ScoreHud fail to load.

## Titles and animation

Enable title animation in plugin_data/ScoreHud/scorehud.yml:

~~~yaml
titles:
  title: "§l§aServer §dName"
  flicker: true
  period: 5
  lines:
    - "§8☆§eMyServer§8☆"
    - "§b☆§fMyServer§b☆"
~~~

period is measured in seconds. Each scheduled update selects one frame and
renders that same frame for every online player, so adding players does not
accelerate the animation.

## Developer API

The public API is exposed under the Ifera\ScoreHud namespace:

- Ifera\ScoreHud\event\TagsResolveEvent resolves a tag while a scoreboard is built.
- Ifera\ScoreHud\event\PlayerTagUpdateEvent updates one tag for one player.
- Ifera\ScoreHud\event\PlayerTagsUpdateEvent updates several tags for one player.
- Ifera\ScoreHud\event\ServerTagUpdateEvent updates one tag for all players.
- Ifera\ScoreHud\event\ServerTagsUpdateEvent updates several server tags.
- Ifera\ScoreHud\scoreboard\ScoreTag carries a tag name and string value.

For a resolver, listen to TagsResolveEvent, inspect
$event->getTag()->getName() and set the value with
$event->getTag()->setValue($value). Use the update events when a value changes
after the board has been created.

## Build and verification

Requirements: PHP 8.2 or newer, Composer, and an Axolotl-PM API 5 development
source tree.

~~~powershell
composer install --prefer-dist --no-interaction
composer test
composer phpstan
~~~

The GitHub Actions workflows also build the PHAR with
[PocketMine DevTools](https://github.com/pmmp/DevTools) and upload it as a CI
artifact. A release asset is generated from the same source set:
src, resources, plugin.yml, meta and changelogs.

## Troubleshooting

### The scoreboard shows red numbers on the right

Those numbers are Bedrock's score values. ScoreHud uses the score value to keep
line order stable; the line text is still the configured scoreboard content.

### A tag is shown literally

Check the exact tag spelling and confirm the optional provider plugin is enabled.
For example, use {scorehud.online} rather than {player.online}, and
{simpleeconomy.balance} rather than a tag from another economy plugin.

### The server crashes with TYPE_FAKE_PLAYER

That is an old ScoreFactory packet constant and indicates an outdated ScoreHud
build. Use the PHAR from this repository's latest release, then remove the old
ScoreHud PHAR before restarting the server.

## Support and changes

Open a reproducible bug report or feature request in the
[ScoreHud issue tracker](https://github.com/NhanAZ-Plugins/ScoreHud/issues). The
repository changelogs are kept with the source, so this README is the canonical
user and developer documentation; no external wiki is required.

## License and attribution

ScoreHud is distributed under the GNU General Public License v3.0. See
[LICENSE](LICENSE) for the complete terms. The maintained code preserves the
original ScoreHud attribution while providing a self-contained Axolotl-PM
maintenance line under the NhanAZ-Plugins organization.

Authors: NhanAZ, Ifera.
