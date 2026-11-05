# Tags reference

ScoreHud tags are written between braces, for example
`{scorehud.name}`. The built-in tag factory resolves a tag while ScoreHud is
building a player's scoreboard and stores the result as a string.

The built-in tag factory is enabled by default. Disable it with
`tag-factory.enable: false` in `config.yml`. Memory tags are disabled by
default and require `tag-factory.enable-memory-tags: true`. See the
[configuration guide](configuration.md) for the related settings.

## Player identity

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.name}` | Player's username. | `Steve` |
| `{scorehud.real_name}` | Alias for `{scorehud.name}`. | `Steve` |
| `{scorehud.display_name}` | Player's display name, including formatting codes when configured. | `§aSteve` |

## Server and world

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.online}` | Number of players currently online. | `12` |
| `{scorehud.max_online}` | Server's configured maximum player count. | `50` |
| `{scorehud.world_player_count}` | Number of players in the viewing player's current world. | `4` |
| `{scorehud.world_name}` | Display name of the current world. | `world` |
| `{scorehud.level_name}` | Alias for `{scorehud.world_name}`. | `world` |
| `{scorehud.world_folder_name}` | Folder name of the current world. | `world_nether` |
| `{scorehud.level_folder_name}` | Alias for `{scorehud.world_folder_name}`. | `world_nether` |

The display name can differ from the folder name. Use the folder-name tags when
you need a stable identifier for configuration or integrations.

## Coordinates and health

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.x}` | Integer X coordinate of the player. | `128` |
| `{scorehud.y}` | Integer Y coordinate of the player. | `64` |
| `{scorehud.z}` | Integer Z coordinate of the player. | `-32` |
| `{scorehud.health}` | Current health, truncated to an integer. | `18` |
| `{scorehud.max_health}` | Maximum health of the player. | `20` |

Coordinate tags update when the player's integer block position changes.
Health tags update when the player takes or regains damage.

## Experience

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.xp_level}` | Current experience level. | `10` |
| `{scorehud.xp_progress}` | Current-level progress from PocketMine-MP. The current implementation casts the native `0.0–1.0` fraction to an integer, so it usually outputs `0` or `1`. | `0` |
| `{scorehud.xp_remainder}` | XP points progressed into the current level. | `37` |
| `{scorehud.xp_current_total}` | Current total XP calculated from the level and current-level progress. This is not the player's lifetime XP total. | `617` |

Experience tags update on `PlayerExperienceChangeEvent`.

## Held item

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.item_name}` | Name of the item in the player's hand. | `Diamond Sword` |
| `{scorehud.item_count}` | Number of items in the player's hand stack. | `1` |

These tags update when the player changes the held hotbar slot. An empty hand
uses the item name returned by PocketMine-MP for its air item.

## Network

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.ping}` | Player network latency in milliseconds. | `45` |
| `{scorehud.ip}` | Player's connected IP address. | `192.0.2.10` |

`{scorehud.ping}` is refreshed by the periodic tag-factory task. The IP
address is resolved when the scoreboard is built.

## Time and server performance

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.time}` | Current server time using `time.format.time`. | `14:30:05` |
| `{scorehud.date}` | Current server date using `time.format.date`. | `05-11-2026` |
| `{scorehud.load}` | Tick usage reported by PocketMine-MP. | `12.5` |
| `{scorehud.tps}` | Ticks per second reported by PocketMine-MP. | `20` |

Set `time.zone` to a PHP timezone identifier when the server should use a
specific timezone. Set `time.format.time` and `time.format.date` in
`config.yml` to change the output format. The default formats are `H:i:s`
and `d-m-Y`.

`{scorehud.load}`, `{scorehud.tps}`, `{scorehud.time}` and
`{scorehud.date}` are refreshed by the periodic tag-factory task.

## Memory

Memory tags are only populated when `tag-factory.enable-memory-tags` is
`true`. Values are formatted in megabytes with two decimal places.

| Tag | Meaning | Example |
| --- | --- | --- |
| `{scorehud.memory_main_thread}` | PHP memory currently used by the main process. | `128.00 MB` |
| `{scorehud.memory_total}` | Resident memory currently used by the process. | `256.00 MB` |
| `{scorehud.memory_virtual}` | Virtual memory size reported by the process. | `512.00 MB` |
| `{scorehud.memory_heap}` | Real process heap size where the platform exposes it. | `64.00 MB` |
| `{scorehud.memory_global}` | PocketMine-MP global memory limit, or `MAX` when no positive limit is configured. | `256.00 MB` |

Memory values are refreshed by the periodic tag-factory task. Memory reporting
depends on the platform and operating-system data available to PocketMine-MP.

## Using tags in a board

Put tags directly in a scoreboard line:

~~~yaml
default-board:
  - "§ePlayer: §f{scorehud.name}"
  - "§eHealth: §f{scorehud.health}/§f{scorehud.max_health}"
  - "§eWorld: §f{scorehud.world_name}"
  - "§eCoords: §f{scorehud.x}, §f{scorehud.y}, §f{scorehud.z}"
  - "§ePing: §f{scorehud.ping}ms"
~~~

Names such as `{player.online}`, `{player.max_online}` and
`{town.coins}` are not built-in ScoreHud tags. They remain unresolved unless
another plugin provides them through `TagsResolveEvent`.

## Update behavior

The tag factory uses immediate event-driven updates for player and world state:

| Change | Tags updated |
| --- | --- |
| Player joins or leaves | `{scorehud.online}`, `{scorehud.world_player_count}` |
| Player moves to another integer position | `{scorehud.x}`, `{scorehud.y}`, `{scorehud.z}` |
| Player changes world | World name, folder name and player-count tags |
| Player takes or regains health | `{scorehud.health}`, `{scorehud.max_health}` |
| Player gains or changes XP | All experience tags |
| Player changes held item | `{scorehud.item_name}`, `{scorehud.item_count}` |

The periodic task refreshes ping, tick usage, TPS, time, date and enabled memory
tags. Its interval is controlled by `tag-factory.update-period`, in seconds,
and defaults to `5`.

## Custom tags

Unknown tags remain available to other plugins through
`Ifera\ScoreHud\event\TagsResolveEvent`. Listen for that event, check
`$event->getTag()->getName()`, and set the value with
`$event->getTag()->setValue($value)`.

See the [Developer API reference](developer-api.md) for resolver and update
event examples.
