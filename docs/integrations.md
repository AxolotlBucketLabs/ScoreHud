# Official optional integrations

These integrations are maintained alongside the NhanAZ-Plugins ScoreHud fork.
Install the provider plugin beside ScoreHud when you need its tags. ScoreHud
does not require any of these plugins to start.

## Supported plugins

| Plugin | Tags |
| --- | --- |
| [EcoAPIScore](https://github.com/NhanAZ-Plugins/EcoAPIScore) | `{ecoapi.balance}`, `{ecoapi.raw}`, `{ecoapi.money}`, `{ecoapi.formatted}`, `{ecoapi.provider}`, `{ecoapi.currency}` |
| [SimpleEconomy](https://github.com/NhanAZ-Plugins/SimpleEconomy) | `{simpleeconomy.balance}` formatted balance, `{simpleeconomy.raw}` raw integer balance, `{simpleeconomy.rank}` leaderboard position |
| [PurePerms](https://github.com/NhanAZ-Plugins/PurePerms) | `{ppscore.rank}`, `{ppscore.prefix}`, `{ppscore.suffix}` |
| [PureChat](https://github.com/NhanAZ-Plugins/PureChat) | `{purechat.prefix}`, `{purechat.suffix}`, `{purechat.faction_name}`, `{purechat.faction_rank}`; compatibility aliases `{pureperms.prefix}`, `{pureperms.suffix}`, `{pureperms.group}`, `{pureperms.rank}` |

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
