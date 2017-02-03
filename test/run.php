<?php
require '../waveform.php';

try {

	$wf = new waveform('fairy-tail.mp3');

	print_r($wf->result);

} catch (Exception $e) {
	echo 'waveform error: '.$e->getMessage().PHP_EOL;
}