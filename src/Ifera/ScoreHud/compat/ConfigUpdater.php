<?php
declare(strict_types=1);

namespace Ifera\ScoreHud\compat;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;

/** Small self-contained replacement for the retired ConfigUpdater virion. */
final class ConfigUpdater{
	public static function checkUpdate(PluginBase $plugin, Config $config, string $configKey, int $latestVersion, string $updateMessage = ""): bool{
		$value = $config->get($configKey);
		$version = is_int($value) ? $value : (is_string($value) && is_numeric($value) ? (int) $value : null);
		if($config->exists($configKey) && $version === $latestVersion){
			return false;
		}
		$configPath = $config->getPath();
		$original = basename($configPath);
		$old = pathinfo($original, PATHINFO_FILENAME) . "_old." . pathinfo($original, PATHINFO_EXTENSION);
		if(trim($updateMessage) === ""){
			$updateMessage = "Your $original file is outdated. Your old file has been saved as $old and a new file has been generated.";
		}
		rename($configPath, dirname($configPath) . DIRECTORY_SEPARATOR . $old);
		$plugin->saveResource($original);
		$plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(static function() use ($plugin, $updateMessage): void{
			$plugin->getLogger()->critical($updateMessage);
		}), 60);
		return true;
	}
}
