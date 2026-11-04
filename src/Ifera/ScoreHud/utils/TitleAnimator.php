<?php
declare(strict_types = 1);

namespace Ifera\ScoreHud\utils;

/**
 * Keeps one animation frame for the whole server tick.
 *
 * The scoreboard title is rendered once per online player, but the animation
 * itself must advance once per scheduled update. Keeping this state separate
 * makes that distinction explicit and testable.
 */
final class TitleAnimator{

	private int $index = 0;

	/**
	 * @param list<string> $titles
	 */
	public function current(array $titles, string $fallback): string{
		if($titles === []){
			return $fallback;
		}

		if(!isset($titles[$this->index])){
			$this->index = 0;
		}

		return $titles[$this->index];
	}

	/**
	 * @param list<string> $titles
	 */
	public function advance(array $titles): void{
		if($titles === []){
			$this->index = 0;
			return;
		}

		$this->index = ($this->index + 1) % count($titles);
	}
}
