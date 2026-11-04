<?php
declare(strict_types=1);

namespace jackmd\scorefactory;

use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;

/** @internal */
class ScoreCache{
	public const MIN_ENTRY_INDEX = 1;
	public const MAX_ENTRY_INDEX = 15;

	/** @var array<int, ScorePacketEntry> */
	private array $entries = [];

	private function __construct(
		private Player $player,
		private string $objective,
		private SetDisplayObjectivePacket $objectivePacket
	){}

	public static function init(Player $player, string $objective, SetDisplayObjectivePacket $objectivePacket): self{
		return new self($player, $objective, $objectivePacket);
	}

	public function getPlayer(): Player{
		return $this->player;
	}

	public function getObjective(): string{
		return $this->objective;
	}

	public function getObjectivePacket(): SetDisplayObjectivePacket{
		return $this->objectivePacket;
	}

	/** @return array<int, ScorePacketEntry> */
	public function getEntries(): array{
		return $this->entries;
	}

	/** @return list<ScorePacketEntry> */
	public function getEntriesList(): array{
		$entries = $this->entries;
		ksort($entries);
		return array_values($entries);
	}

	/** @param array<int, ScorePacketEntry> $entries */
	public function setEntries(array $entries): void{
		if(count($entries) > self::MAX_ENTRY_INDEX){
			throw new \InvalidArgumentException("No more than " . self::MAX_ENTRY_INDEX . " entries are allowed");
		}
		$this->entries = $entries;
	}

	public function setEntry(int $index, ScorePacketEntry $entry): void{
		if($index < self::MIN_ENTRY_INDEX || $index > self::MAX_ENTRY_INDEX){
			throw new \InvalidArgumentException("Entry index $index is out of bounds");
		}
		$this->entries[$index] = $entry;
	}

	public function removeEntry(int $index): void{
		unset($this->entries[$index]);
	}
}
