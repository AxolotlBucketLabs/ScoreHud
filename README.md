# ScoreHud

[![CI](https://github.com/NhanAZ-Plugins/ScoreHud/actions/workflows/build.yml/badge.svg)](https://github.com/NhanAZ-Plugins/ScoreHud/actions/workflows/build.yml)
[![License](https://img.shields.io/github/license/NhanAZ-Plugins/ScoreHud)](https://github.com/NhanAZ-Plugins/ScoreHud/blob/master/LICENSE)
[![Discord](https://img.shields.io/discord/986553214889517088?label=Discord&logo=discord&logoColor=white&color=5865F2)](https://discord.gg/j2X83ujT6c)

ScoreHud is an event-driven, customizable scoreboard plugin for Axolotl-PM. This repository is the maintained continuation under [NhanAZ-Plugins](https://github.com/NhanAZ-Plugins), with the original ScoreHud configuration and developer-facing tag system preserved.

## Features

- Configurable scoreboards with support for 1 to 15 lines.
- Event-driven updates without a repeating scoreboard task.
- Built-in tags and an API for custom integrations.
- Additional tags through [ScoreHudX](https://github.com/Ifera/ScoreHudX).

## Installation

1. Download the latest `ScoreHud.phar` from [Releases](https://github.com/NhanAZ-Plugins/ScoreHud/releases/latest).
2. Place the PHAR in the server's `plugins` directory.
3. Start the server, then edit `config.yml` and `scorehud.yml`.

## Tags and documentation

See the [available tags wiki](https://github.com/Ifera/ScoreHud/wiki/Available-Tags) for built-in tags. Refer to the [developer wiki](https://github.com/Ifera/ScoreHud/wiki) for integration details and the [ScoreHudX repository](https://github.com/Ifera/ScoreHudX) for additional tags.

## Development

The CI workflow validates the source and builds the plugin against the supported Axolotl-PM API. It also runs PHPStan at level max. Pin the server and DevTools revisions when reproducing a build locally.

## Support and license

Report bugs through [GitHub Issues](https://github.com/NhanAZ-Plugins/ScoreHud/issues) or ask in the [NhanAZ Discord](https://discord.gg/j2X83ujT6c). ScoreHud is distributed under the [MIT License](https://github.com/NhanAZ-Plugins/ScoreHud/blob/master/LICENSE).
