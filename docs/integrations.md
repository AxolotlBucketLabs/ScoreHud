# Official optional integrations

These integrations are maintained alongside the AxolotlBucketLabs ScoreHud fork.
Install the provider plugin beside ScoreHud when you need its tags. ScoreHud
does not require any of these plugins to start.

## At a glance

| Plugin | Tags |
| --- | --- |
| [EcoAPIScore](https://github.com/NhanAZ-Plugins/EcoAPIScore) | `{ecoapi.balance}`, `{ecoapi.raw}`, `{ecoapi.money}`, `{ecoapi.formatted}`, `{ecoapi.provider}`, `{ecoapi.currency}` |
| [SimpleEconomy](https://github.com/NhanAZ-Plugins/SimpleEconomy) | `{simpleeconomy.balance}` formatted balance, `{simpleeconomy.raw}` raw integer balance, `{simpleeconomy.rank}` leaderboard position |
| [PurePerms](https://github.com/AxolotlBucketLabs/PurePerms) | `{ppscore.rank}`, `{ppscore.prefix}`, `{ppscore.suffix}` |
| [PureChat](https://github.com/AxolotlBucketLabs/PureChat) | `{purechat.prefix}`, `{purechat.suffix}`, `{purechat.faction_name}`, `{purechat.faction_rank}`. Compatibility aliases are `{pureperms.prefix}`, `{pureperms.suffix}`, `{pureperms.group}`, and `{pureperms.rank}`. |

The sections below document every tag individually.

## EcoAPIScore

[EcoAPIScore](https://github.com/NhanAZ-Plugins/EcoAPIScore) exposes generic
economy tags through EcoAPI. It selects the first available provider, so these
tags can work with SimpleEconomy, EconomyAPI, BedrockEconomy or the EcoAPI XP
provider.

| Tag | Meaning | Example |
| --- | --- | --- |
| `{ecoapi.balance}` | Raw balance without a currency symbol. | `1000` |
| `{ecoapi.raw}` | Alias for `{ecoapi.balance}`. | `1000` |
| `{ecoapi.money}` | Formatted balance with the provider's currency symbol. | `$1,000` |
| `{ecoapi.formatted}` | Alias for `{ecoapi.money}`. | `$1,000` |
| `{ecoapi.provider}` | Active provider name. | `SimpleEconomy` |
| `{ecoapi.currency}` | Currency symbol returned by the active provider. | `$` |

Use EcoAPIScore tags when the same scoreboard should work with more than one
economy provider:

~~~yaml
default-board:
  - "§eBalance: §f{ecoapi.formatted}"
  - "§eProvider: §f{ecoapi.provider}"
~~~

The PHAR keeps a small per-player balance cache. If no provider is available,
the economy tags resolve to `N/A` until one becomes available. See the
[EcoAPIScore documentation](https://github.com/NhanAZ-Plugins/EcoAPIScore)
for provider selection and refresh settings.

## SimpleEconomy

[SimpleEconomy](https://github.com/NhanAZ-Plugins/SimpleEconomy) provides these
tags directly. The formatted value follows SimpleEconomy's configured currency
symbol and formatter.

| Tag | Meaning | Example |
| --- | --- | --- |
| `{simpleeconomy.balance}` | Formatted balance. | `$1,000` |
| `{simpleeconomy.raw}` | Raw integer balance without formatting. | `1000` |
| `{simpleeconomy.rank}` | Current leaderboard position. | `3` |

Example:

~~~yaml
default-board:
  - "§eMoney: §f{simpleeconomy.balance}"
  - "§eTop: §f#{simpleeconomy.rank}"
~~~

Use `{simpleeconomy.raw}` when another plugin or calculation needs the
numeric value. Use `{simpleeconomy.balance}` when the value is intended for
display.

## PurePerms

PurePerms tags expose the player's current permission rank and its prefix or
suffix. `{ppscore.rank}` is the rank/group name, not a numeric leaderboard
position.

| Tag | Meaning | Example |
| --- | --- | --- |
| `{ppscore.rank}` | Current PurePerms rank or group name. | `VIP` |
| `{ppscore.prefix}` | Prefix associated with the player's rank. | `[VIP] §f` |
| `{ppscore.suffix}` | Suffix associated with the player's rank. | ` ✦` |

Example:

~~~yaml
default-board:
  - "§eRank: §f{ppscore.rank}"
  - "§eName: §f{ppscore.prefix}{scorehud.name}{ppscore.suffix}"
~~~

The PurePerms provider must be installed and enabled. If the rank metadata is
not configured, the prefix or suffix can be empty even though the rank tag is
available.

## PureChat

PureChat tags expose chat and faction metadata for the player. The faction
values depend on the faction data available to the installed integration.

| Tag | Meaning | Example |
| --- | --- | --- |
| `{purechat.prefix}` | Player or group chat prefix. | `[VIP] §f` |
| `{purechat.suffix}` | Player or group chat suffix. | ` ✦` |
| `{purechat.faction_name}` | Player's faction name. | `Knights` |
| `{purechat.faction_rank}` | Player's rank within the faction. | `Officer` |

Compatibility aliases are available for older scoreboard configurations:

| Alias | Resolves to |
| --- | --- |
| `{pureperms.prefix}` | PurePerms/PureChat prefix |
| `{pureperms.suffix}` | PurePerms/PureChat suffix |
| `{pureperms.group}` | Player's permission group name |
| `{pureperms.rank}` | Player's permission rank name |

Example:

~~~yaml
default-board:
  - "§e{purechat.prefix}{scorehud.name}{purechat.suffix}"
  - "§eFaction: §f{purechat.faction_name}"
  - "§eFaction rank: §f{purechat.faction_rank}"
~~~

Use the `purechat.*` names for new configurations. Keep the
`pureperms.*` aliases when migrating an older scoreboard configuration.

## Integration behavior

SimpleEconomy, PureChat and PurePerms resolve their optional tags through
ScoreHud's public events. These integrations are deliberately soft: removing a
provider plugin does not prevent ScoreHud from loading.

If a tag appears literally on the scoreboard:

1. Check the tag spelling, including its prefix.
2. Confirm the provider plugin is installed and enabled.
3. Confirm ScoreHud can receive the provider's event-based resolver.

For built-in tags, use the [tags reference](tags.md). For resolver code, see
the [Developer API reference](developer-api.md).
