<?php

/**
 * Waveform generator
 *
 * @version 1.0
 * @author Oxmix
 * @link http://oxmix.net
 */
class waveform {
	private $result = [];

	public function __construct($file) {
		try {
			if (empty($file))
				throw new Exception('set path to mp3 file');

			if (!file_exists($file))
				throw new Exception('not found mp3 file');

			$data = shell_exec('lame --decode --silent '.$file.' -');

			$sampleRate = unpack('S', substr($data, 24, 4))[1];

			if ($sampleRate <= 0)
				throw new Exception('not correct sample rate');

			$samples = [];
			$temp = [];
			for ($i = 44, $j = 0; $i < strlen($data); $i += 4, $j++) {
				$temp[] = abs(unpack('s', $data[$i].$data[$i + 1])[1]);

				if ($j % $sampleRate == 0) {
					$samples[] = array_sum($temp) / count($temp);
					$temp = [];
				}
			}

			$max = max($samples);

			$this->result = array_map(function ($e) use ($max) {
				return round($e / $max, 3);
			}, $samples);

		} catch (Exception $e) {
			echo 'waveform error - '.$e->getMessage().PHP_EOL;
		}
	}

	public function json() {
		return json_encode($this->result);
	}
}