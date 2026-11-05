# Tags reference

ScoreHud tags are written between braces, for example
`{scorehud.name}`. A tag name is resolved when the scoreboard is built.
Unknown tags remain available to other plugins through
`TagsResolveEvent`.

The built-in tag factory can be disabled in `config.yml`. Memory tags also
require `tag-factory.enable-memory-tags: true`. See the
[configuration guide](configuration.md) for the related settings.

## Built-in ScoreHud tags

| Tag | Description |
| --- | --- |
| `{scorehud.name}` / `{scorehud.real_name}` | Player name |
| `{scorehud.display_name}` | Player display name |
| `{scorehud.online}` | Current online-player count |
| `{scorehud.max_online}` | Server max-player setting |
| `{scorehud.world_player_count}` | Players in the current world |
| `{scorehud.world_name}` / `{scorehud.level_name}` | World display name |
| `{scorehud.world_folder_name}` / `{scorehud.level_folder_name}` | World folder name |
| `{scorehud.x}`, `{scorehud.y}`, `{scorehud.z}` | Integer player coordinates |
| `{scorehud.health}` / `{scorehud.max_health}` | Current and maximum health |
| `{scorehud.xp_level}` | Experience level |
| `{scorehud.xp_progress}` | Experience progress |
| `{scorehud.xp_remainder}` | Remaining experience to the next level |
| `{scorehud.xp_current_total}` | Total experience |
| `{scorehud.item_name}` / `{scorehud.item_count}` | Held item and count |
| `{scorehud.ping}` | Network ping |
| `{scorehud.ip}` | Player IP address |
| `{scorehud.time}` / `{scorehud.date}` | Configured server time and date |
| `{scorehud.load}` / `{scorehud.tps}` | Tick usage and ticks per second |
| `{scorehud.memory_main_thread}`, `{scorehud.memory_total}`, `{scorehud.memory_virtual}`, `{scorehud.memory_heap}`, `{scorehud.memory_global}` | Optional memory metrics; enable them in `config.yml` |

## Using tags in a board

Put a tag directly in a scoreboard line:

```yaml
default-board:
  - "§ePlayer: §f{scorehud.name}"
  - "§eHealth: §f{scorehud.health}/§f{scorehud.max_health}"
  - "§eWorld: §f{scorehud.world_name}"
```

Use `{scorehud.online}` and `{scorehud.max_online}` for the built-in
player-count tags. Names such as `{player.online}`,
`{player.max_online}` and `{town.coins}` are not built-in ScoreHud tags and
remain unresolved unless another plugin provides them.

For custom tags, see the [Developer API reference](developer-api.md).

