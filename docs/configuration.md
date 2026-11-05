# Configuration

ScoreHud reads two YAML files from `plugin_data/ScoreHud`:

- `config.yml` contains server-wide settings such as tag updates, per-world
  boards, disabled worlds and time formatting.
- `scorehud.yml` contains scoreboard lines, per-world board definitions and
  title settings.

## Basic scoreboard

The `default-board` section in `scorehud.yml` is used when one board should
be shown in every world:

```yaml
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
```

ScoreHud supports a maximum of 15 scoreboard lines. Tags use the
`{tag.name}` format. See the [tags reference](tags.md) for the complete
catalogue.

## Per-world boards

Set `multi-world.active` to `true` in `config.yml`, then define a
`lines` list for each world under `scoreboards` in `scorehud.yml`:

```yaml
scoreboards:
  "world":
    lines:
      - "§dName: §5{scorehud.name}"
      - "§bOnline: §a{scorehud.online} §c/ §d{scorehud.max_online}"
  "world_nether":
    lines:
      - "§dName: §5{scorehud.name}"
      - "§dTPS: §5{scorehud.tps}"
      - "§bLoad: §a{scorehud.load}"
```

When `multi-world.use-default` is also `true`, worlds without their own
entry use `default-board`.

To hide the scoreboard in selected worlds, list their folder names under
`disabled-worlds` in `config.yml`:

```yaml
disabled-worlds:
  - "event_world"
  - "minigame_world"
```

## Titles and animation

Use `titles.title` for a fixed title. Set `titles.flicker` to `true` to
cycle through `titles.lines`:

```yaml
titles:
  title: "§l§aServer §dName"
  flicker: true
  period: 5
  lines:
    - "§8☆§eMyServer§8☆"
    - "§b☆§fMyServer§b☆"
```

`titles.period` is measured in seconds. Each scheduled update selects one
frame and renders that frame for every online player.

## Tag factory and time

The built-in tag factory is enabled by default. Its update interval is
controlled by `tag-factory.update-period`. Memory tags are opt-in because
they can add extra work:

```yaml
tag-factory:
  enable: true
  update-period: 5
  enable-memory-tags: false
```

The `time.zone`, `time.format.time` and `time.format.date` settings
control the values returned by `{scorehud.time}` and `{scorehud.date}`.
