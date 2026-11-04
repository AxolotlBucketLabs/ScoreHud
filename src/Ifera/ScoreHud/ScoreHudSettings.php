<?php
declare(strict_types = 1);

/**
 *     _____                    _   _           _
 *    /  ___|                  | | | |         | |
 *    \ `--.  ___ ___  _ __ ___| |_| |_   _  __| |
 *     `--. \/ __/ _ \| '__/ _ \  _  | | | |/ _` |
 *    /\__/ / (_| (_) | | |  __/ | | | |_| | (_| |
 *    \____/ \___\___/|_|  \___\_| |_/\__,_|\__,_|
 *
 * ScoreHud, a Scoreboard plugin for PocketMine-MP
 * Copyright (c) 2020 Ifera  < https://github.com/Ifera >
 *
 * Discord: Ifera#3717
 * Twitter: ifera_tr
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * ScoreHud is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * ------------------------------------------------------------------------
 */

namespace Ifera\ScoreHud;

use pocketmine\utils\Config;
use function in_array;

class ScoreHudSettings{

	public const PREFIX = "§8[§l§6S§eH§r§8]§r ";

	private static ?Config $config = null;
	private static ?Config $scorehud = null;

	private function __construct(){}

	public static function init(ScoreHud $plugin): void{
		self::$config = $plugin->getConfig();
		self::$scorehud = $plugin->getScoreConfig();
	}

	public static function destroy(): void{
		self::$config = null;
		self::$scorehud = null;
	}

	private static function config(): Config{
		return self::$config ?? throw new \LogicException("ScoreHud settings have not been initialized");
	}

	private static function scorehud(): Config{
		return self::$scorehud ?? throw new \LogicException("ScoreHud settings have not been initialized");
	}

	private static function boolValue(mixed $value, bool $default): bool{
		if(is_bool($value)) return $value;
		if(is_int($value)) return $value !== 0;
		if(is_string($value)) return in_array(strtolower($value), ["1", "true", "yes", "on"], true);
		return $default;
	}

	private static function intValue(mixed $value, int $default): int{
		if(is_int($value)) return $value;
		if(is_float($value)) return (int) $value;
		if(is_string($value) && is_numeric($value)) return (int) $value;
		return $default;
	}

	/*
	 * Settings from config.yml
	 */

	public static function getLineUpdateMode(): string{
		$value = self::config()->getNested("line-update-mode", "single");
		return is_string($value) ? strtolower($value) : "single";
	}

	public static function isSingleLineUpdateMode(): bool{
		return self::getLineUpdateMode() === "single";
	}

	public static function isTagFactoryEnabled(): bool {
		return self::boolValue(self::config()->getNested("tag-factory.enable", true), true);
	}

	public static function getTagFactoryUpdatePeriod(): int {
		return self::intValue(self::config()->getNested("tag-factory.update-period", 5), 5);
	}

	public static function areMemoryTagsEnabled(): bool {
		return self::boolValue(self::config()->getNested("tag-factory.enable-memory-tags", false), false);
	}

	public static function isMultiWorld(): bool{
		return self::boolValue(self::config()->getNested("multi-world.active", false), false);
	}

	/**
	 * If multi world support is enabled and scoreboard for a world is not found then
	 * check whether the user allows for using the default scoreboard instead.
	 */
	public static function useDefaultBoard(): bool{
		return self::isMultiWorld() && self::boolValue(self::config()->getNested("multi-world.use-default", false), false);
	}

	/** @return list<string> */
	public static function getDisabledWorlds(): array{
		$value = self::config()->get("disabled-worlds", []);
		if(!is_array($value)) return [];
		return array_values(array_filter($value, static fn(mixed $world): bool => is_string($world)));
	}

	public static function isInDisabledWorld(string $world): bool{
		return in_array($world, self::getDisabledWorlds());
	}

	public static function isTimezoneChanged(): bool{
		return self::config()->getNested("time.zone") !== false;
	}

	public static function getTimezone(): string{
		$value = self::config()->getNested("time.zone", "America/New_York");
		return is_string($value) ? $value : "America/New_York";
	}

	public static function getTimeFormat(): string{
		$value = self::config()->getNested("time.format.time", "H:i:s");
		return is_string($value) ? $value : "H:i:s";
	}

	public static function getDateFormat(): string{
		$value = self::config()->getNested("time.format.date", "d-m-Y");
		return is_string($value) ? $value : "d-m-Y";
	}

	/*
	 * Settings from scorehud.yml
	 */

	public static function areFlickeringTitlesEnabled(): bool{
		return self::boolValue(self::scorehud()->getNested("titles.flicker", false), false);
	}

	public static function getFlickerRate(): int{
		return self::intValue(self::scorehud()->getNested("titles.period", 5), 5) * 20;
	}

	/** @return list<string> */
	public static function getTitles(): array{
		$value = self::scorehud()->getNested("titles.lines", []);
		if(!is_array($value)) return [];
		return array_values(array_filter($value, static fn(mixed $title): bool => is_string($title)));
	}

	public static function getTitle(): string{
		$value = self::scorehud()->getNested("titles.title", "§l§aServer §dName");
		return is_string($value) ? $value : "§l§aServer §dName";
	}

	/** @return list<string> */
	public static function getDefaultBoard(): array{
		$value = self::scorehud()->get("default-board", []);
		if(!is_array($value)) return [];
		return array_values(array_filter($value, static fn(mixed $line): bool => is_string($line)));
	}

	/**
	 * Will return an array indexed by world name with their score lines.
	 */
	/** @return array<string, array<mixed>> */
	public static function getScoreboards(): array{
		$value = self::scorehud()->get("scoreboards", []);
		if(!is_array($value)) return [];
		$result = [];
		foreach($value as $world => $board){
			if(is_string($world) && is_array($board)) $result[$world] = $board;
		}
		return $result;
	}

	/** @return list<string> */
	public static function getScoreboard(string $world): array{
		$value = self::scorehud()->getNested("scoreboards." . $world . ".lines", []);
		if(!is_array($value)) return [];
		return array_values(array_filter($value, static fn(mixed $line): bool => is_string($line)));
	}

	public static function worldExists(string $world): bool{
		return !empty(self::getScoreboard($world));
	}
}
