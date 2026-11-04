<?php
declare(strict_types=1);

namespace Ifera\ScoreHud\compat;

/** Update checks are intentionally disabled for this maintained local build. */
final class UpdateNotifier{
	public static function checkUpdate(string $pluginName, string $pluginVersion): void{}
}
