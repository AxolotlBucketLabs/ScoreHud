# Developer API

ScoreHud's public API is exposed under the `Ifera\ScoreHud` namespace.
The API lets another plugin resolve custom tags and refresh values after a
scoreboard has been created.

## Resolving a custom tag

Listen for `Ifera\ScoreHud\event\TagsResolveEvent`, inspect
`$event->getTag()->getName()` and set the resolved value with
`$event->getTag()->setValue($value)`:

```php
use Ifera\ScoreHud\event\TagsResolveEvent;

public function onTagResolve(TagsResolveEvent $event): void
{
    if ($event->getTag()->getName() !== "myplugin.rank") {
        return;
    }

    $event->getTag()->setValue("Admin");
}
```

The event also exposes the player through `$event->getPlayer()` because
`TagsResolveEvent` extends the player tag event. Keep the resolver quick:
ScoreHud calls it while it is building a scoreboard.

## Update events

Use update events when a value changes after the board has been created:

| Event | Use it for |
| --- | --- |
| `Ifera\ScoreHud\event\PlayerTagUpdateEvent` | Update one tag for one player |
| `Ifera\ScoreHud\event\PlayerTagsUpdateEvent` | Update several tags for one player |
| `Ifera\ScoreHud\event\ServerTagUpdateEvent` | Update one server-wide tag |
| `Ifera\ScoreHud\event\ServerTagsUpdateEvent` | Update several server-wide tags |

Create values with `Ifera\ScoreHud\scoreboard\ScoreTag`. Its main methods are
`getName()`, `getValue()` and `setValue(string $value)`.

For example, a plugin can refresh one player's tag like this:

```php
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;

$tag = new ScoreTag("myplugin.rank", "Admin");
$player->getServer()->getPluginManager()->callEvent(
    new PlayerTagUpdateEvent($player, $tag)
);
```

## API classes

- `Ifera\ScoreHud\event\TagsResolveEvent` resolves a tag while a scoreboard is
  built.
- `Ifera\ScoreHud\event\PlayerTagUpdateEvent` updates one player tag.
- `Ifera\ScoreHud\event\PlayerTagsUpdateEvent` updates several player tags.
- `Ifera\ScoreHud\event\ServerTagUpdateEvent` updates one server-wide tag.
- `Ifera\ScoreHud\event\ServerTagsUpdateEvent` updates several server-wide
  tags.
- `Ifera\ScoreHud\scoreboard\ScoreTag` carries a tag name and string value.
