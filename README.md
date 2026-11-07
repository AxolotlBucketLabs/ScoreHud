# ScoreHud

ScoreHud is a configurable Bedrock scoreboard plugin for PocketMine-MP. This
repository is the maintained, self-contained build for current Axolotl-PM API 5
servers. The bundled ScoreFactory uses the current scoreboard packet API, so no
external ScoreHud library, wiki checkout or addon repository is required at
runtime.

[![CI](https://github.com/AxolotlBucketLabs/ScoreHud/actions/workflows/phpstan.yml/badge.svg)](https://github.com/AxolotlBucketLabs/ScoreHud/actions/workflows/phpstan.yml)
[![Build PHAR](https://github.com/AxolotlBucketLabs/ScoreHud/actions/workflows/build.yml/badge.svg)](https://github.com/AxolotlBucketLabs/ScoreHud/actions/workflows/build.yml)
[![Latest release](https://img.shields.io/github/v/release/AxolotlBucketLabs/ScoreHud)](https://github.com/AxolotlBucketLabs/ScoreHud/releases/latest)
[![License](https://img.shields.io/github/license/AxolotlBucketLabs/ScoreHud)](LICENSE)
[![Discord](https://img.shields.io/discord/1568295845374206052?label=Discord&logo=discord&logoColor=white&color=5865F2)](https://discord.gg/Pk4FrNvHnM)

## Features

- Up to 15 configurable scoreboard lines.
- Per-world boards and disabled-world support.
- Optional flickering titles with a fixed, server-wide animation rate.
- Built-in player, server, world, health, experience, item, time and memory tags.
- Event-driven API for plugin integrations and timely tag updates.
- Self-contained PHAR build with no runtime virion dependency.
- PHPStan level max in CI and a DevTools PHAR build on every push and pull request.

## Installation

1. Download `ScoreHud.phar` from the latest release.
2. Copy it to the `plugins` directory of an Axolotl-PM API 5 server.
3. Start the server once, then edit `plugin_data/ScoreHud/config.yml` and
   `plugin_data/ScoreHud/scorehud.yml`.
4. Restart the server.

ScoreHud has no mandatory dependency. Optional integrations activate when their
plugin is installed and enabled.

## Quick configuration

The following example shows a simple board in `plugin_data/ScoreHud/scorehud.yml`:

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

See the [configuration guide](docs/configuration.md) for per-world boards,
disabled worlds, title animation and tag-factory settings.

## Tags

Tags are written between braces, for example `{scorehud.name}`. See the
[tags reference](docs/tags.md) for the complete built-in catalogue, usage
examples and custom-tag guidance.

## Official optional integrations

See the [official integrations guide](docs/integrations.md) for supported
plugins, tags, installation expectations and troubleshooting.

## Titles and animation

See the [configuration guide](docs/configuration.md#titles-and-animation).

## Developer API

See the [Developer API reference](docs/developer-api.md) for custom tag
resolvers, update events and `ScoreTag` usage.

## Build and verification

Requirements: PHP 8.4 or newer, Composer, and an Axolotl-PM API 5 development
source tree.

~~~powershell
composer install --prefer-dist --no-interaction
composer test
composer phpstan
~~~

The GitHub Actions workflows also build the PHAR with the pinned PocketMine-MP
DevTools source and upload it as a CI artifact. A release asset is generated
from the same source set:
`src`, `resources`, `plugin.yml`, `meta` and `changelogs`.

## Troubleshooting

### The scoreboard shows red numbers on the right

Those numbers are Bedrock's score values. ScoreHud uses the score value to keep
line order stable. The line text is still the configured scoreboard content.

### A tag is shown literally

Check the exact tag spelling and confirm the optional provider plugin is enabled.
For example, use `{scorehud.online}` rather than `{player.online}`, and
`{simpleeconomy.balance}` rather than a tag from another economy plugin.

### The server crashes with `TYPE_FAKE_PLAYER`

That is an old ScoreFactory packet constant and indicates an outdated ScoreHud
build. Use the PHAR from this repository's latest release, then remove the old
ScoreHud PHAR before restarting the server.

## Support and changes

Open a reproducible bug report or feature request in the
[ScoreHud issue tracker](https://github.com/AxolotlBucketLabs/ScoreHud/issues). The
repository changelogs are kept with the source, so this README is the canonical
user and developer documentation. No external wiki is required.

## License and attribution

ScoreHud is distributed under the GNU General Public License v3.0. See
[LICENSE](LICENSE) for the complete terms. The maintained code preserves the
original ScoreHud attribution while providing a self-contained Axolotl-PM
maintenance line under the AxolotlBucketLabs organization.

Authors: AxolotlBucketLabs, Ifera.
