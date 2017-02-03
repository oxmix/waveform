<?php

/**
 * Waveform generator
 *
 * @version 1.1
 * @author Oxmix
 * @link http://oxmix.net
 */
class waveform {
	public $result = [];

	public function __construct($file) {
		$tempFileWav = '/tmp/waveform-'.uniqid().'.'.mt_rand().'.wav';
		try {
			if (empty($file))
				throw new Exception('set path to mp3 file');

			if (!file_exists($file))
				throw new Exception('not found mp3 file');

			shell_exec('lame --decode --silent '.$file.' '.$tempFileWav);

			$f = fopen($tempFileWav, 'r');

			$frameFirst = fread($f, 44);

			$sampleRate = unpack('S', substr($frameFirst, 24, 4))[1];

			if ($sampleRate <= 0)
				throw new Exception('not correct sample rate');

			$samples = [];
			$temp = [];
			$j = 0;
			while (!feof($f)) {
				$r = fread($f, 4);
				if (strlen($r) == 0)
					continue;
				$temp[] = abs(unpack('s', $r)[1]);

				if ($j++ % $sampleRate == 0) {
					$samples[] = array_sum($temp) / count($temp);
					$temp = [];
				}
			}

			$max = max($samples);

			$this->result = array_map(function ($e) use ($max) {
				return round($e / $max, 3);
			}, $samples);

		} catch (Exception $e) {
			if (file_exists($tempFileWav))
				unlink($tempFileWav);
			throw new Exception($e->getMessage());
		}
	}

	public function json() {
		return json_encode($this->result);
	}
}