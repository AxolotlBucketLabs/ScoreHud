<?php
declare(strict_types=1);

namespace jackmd\scorefactory;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntryAction;
use pocketmine\player\Player;

class ScoreFactory{
	private const OBJECTIVE_NAME = "objective";
	private const CRITERIA_NAME = "dummy";

	/** @var array<string, ScoreCache> */
	private static array $cache = [];

	public static function setObjective(
		Player $player,
		string $displayName,
		int $slotOrder = SetDisplayObjectivePacket::SORT_ORDER_ASCENDING,
		string $displaySlot = SetDisplayObjectivePacket::DISPLAY_SLOT_SIDEBAR,
		string $objectiveName = self::OBJECTIVE_NAME,
		string $criteriaName = self::CRITERIA_NAME
	): void{
		self::assertConnected($player);
		$packet = new SetDisplayObjectivePacket();
		$packet->displaySlot = $displaySlot;
		$packet->objectiveName = $objectiveName;
		$packet->displayName = $displayName;
		$packet->criteriaName = $criteriaName;
		$packet->sortOrder = $slotOrder;
		self::$cache[self::key($player)] = ScoreCache::init($player, $objectiveName, $packet);
	}

	public static function sendObjective(Player $player): void{
		self::assertConnected($player);
		$cache = self::getCache($player);
		self::removeObjective($player);
		$player->getNetworkSession()->sendDataPacket($cache->getObjectivePacket());
	}

	public static function removeObjective(Player $player, bool $removeCache = false): void{
		self::assertConnected($player);
		$packet = new RemoveObjectivePacket();
		$packet->objectiveName = self::hasObjective($player) ? self::getCache($player)->getObjective() : self::OBJECTIVE_NAME;
		$player->getNetworkSession()->sendDataPacket($packet);
		if($removeCache){
			unset(self::$cache[self::key($player)]);
		}
	}

	public static function hasObjective(Player $player): bool{
		return isset(self::$cache[self::key($player)]);
	}

	/** @return list<Player> */
	public static function getActivePlayers(): array{
		return array_values(array_map(static fn(ScoreCache $cache): Player => $cache->getPlayer(), self::$cache));
	}

	public static function setScoreLine(
		Player $player,
		int $line,
		string $message,
		ScorePacketEntryAction $type = ScorePacketEntryAction::CHANGE_FAKE_PLAYER
	): ScorePacketEntry{
		self::assertConnected($player);
		if(!self::hasObjective($player)){
			throw new ScoreFactoryException("Cannot set a score line without a scoreboard objective");
		}
		if($line < ScoreCache::MIN_ENTRY_INDEX || $line > ScoreCache::MAX_ENTRY_INDEX){
			throw new ScoreFactoryException("Line: $line is out of range, expected value between 1 and 15");
		}

		$entry = new ScorePacketEntry();
		$entry->action = $type;
		$entry->objectiveName = self::getCache($player)->getObjective();
		$entry->customName = $message;
		$entry->score = $line;
		$entry->scoreboardId = $line;
		self::getCache($player)->setEntry($line, $entry);
		return $entry;
	}

	public static function sendLine(Player $player, int $line, ScorePacketEntry $entry): void{
		self::assertReady($player);
		self::removeScoreLine($player, $line, false);
		$entry->action = ScorePacketEntryAction::CHANGE_FAKE_PLAYER;
		$player->getNetworkSession()->sendDataPacket(SetScorePacket::create([$entry]));
	}

	public static function sendLines(Player $player): void{
		self::assertReady($player);
		self::removeScoreLines($player, false);
		$entries = array_map(static function(ScorePacketEntry $entry): ScorePacketEntry{
			$entry->action = ScorePacketEntryAction::CHANGE_FAKE_PLAYER;
			return $entry;
		}, self::getCache($player)->getEntriesList());
		$player->getNetworkSession()->sendDataPacket(SetScorePacket::create($entries));
	}

	public static function removeScoreLine(Player $player, int $line, bool $removeFromCache = true): void{
		self::assertReady($player);
		if($line < ScoreCache::MIN_ENTRY_INDEX || $line > ScoreCache::MAX_ENTRY_INDEX){
			throw new ScoreFactoryException("Line: $line is out of range, expected value between 1 and 15");
		}
		$cache = self::getCache($player);
		if($removeFromCache){
			$cache->removeEntry($line);
		}
		$entry = new ScorePacketEntry();
		$entry->action = ScorePacketEntryAction::REMOVE;
		$entry->objectiveName = $cache->getObjective();
		$entry->score = $line;
		$entry->scoreboardId = $line;
		$player->getNetworkSession()->sendDataPacket(SetScorePacket::create([$entry]));
	}

	public static function removeScoreLines(Player $player, bool $removeFromCache = false): void{
		self::assertReady($player);
		$cache = self::getCache($player);
		$entries = array_map(static function(ScorePacketEntry $entry): ScorePacketEntry{
			$entry->action = ScorePacketEntryAction::REMOVE;
			return $entry;
		}, $cache->getEntriesList());
		if($removeFromCache){
			$cache->setEntries([]);
		}
		$player->getNetworkSession()->sendDataPacket(SetScorePacket::create($entries));
	}

	private static function assertConnected(Player $player): void{
		if(!$player->isConnected()){
			throw new ScoreFactoryException("Player is not connected.");
		}
	}

	private static function assertReady(Player $player): void{
		self::assertConnected($player);
		if(!self::hasObjective($player)){
			throw new ScoreFactoryException("Cannot use score lines without a scoreboard objective");
		}
	}

	private static function key(Player $player): string{
		return $player->getUniqueId()->getBytes();
	}

	private static function getCache(Player $player): ScoreCache{
		return self::$cache[self::key($player)];
	}
}
