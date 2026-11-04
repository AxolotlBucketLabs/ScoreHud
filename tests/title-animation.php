<?php
declare(strict_types = 1);

require_once __DIR__ . '/../src/Ifera/ScoreHud/utils/TitleAnimator.php';

use Ifera\ScoreHud\utils\TitleAnimator;

$animator = new TitleAnimator();
$titles = ['A', 'B', 'C'];

if($animator->current($titles, 'fallback') !== 'A' || $animator->current($titles, 'fallback') !== 'A'){
	throw new RuntimeException('Reading a frame must not advance the animation.');
}

$animator->advance($titles);
if($animator->current($titles, 'fallback') !== 'B'){
	throw new RuntimeException('The first scheduled update must advance one frame.');
}

// Multiple viewers read the same frame; their count must not affect progress.
$viewerFrames = [
	$animator->current($titles, 'fallback'),
	$animator->current($titles, 'fallback'),
	$animator->current($titles, 'fallback'),
	$animator->current($titles, 'fallback'),
	$animator->current($titles, 'fallback'),
];
if($viewerFrames !== ['B', 'B', 'B', 'B', 'B']){
	throw new RuntimeException('All viewers must receive the same animation frame.');
}

$animator->advance($titles);
if($animator->current($titles, 'fallback') !== 'C'){
	throw new RuntimeException('The second scheduled update must advance one frame.');
}

$animator->advance($titles);
if($animator->current($titles, 'fallback') !== 'A'){
	throw new RuntimeException('The animation must wrap at the end.');
}

fwrite(STDOUT, "Title animation regression test passed.\n");
