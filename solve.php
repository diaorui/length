<?php
$fin = fopen("input.txt", "r");
$fout = fopen("output.txt", "w");

fprintf($fout, "doraemonok@163.com\n\n");

$mode = 'info';
$data = array();

function to_std_unit($unit) {
	global $data;
	if (isset($data[$unit])) return $unit;
	$new_unit = preg_replace('/s$/', '', $unit);
	if (isset($data[$new_unit])) return $new_unit;
	$new_unit = preg_replace('/es$/', '', $unit);
	if (isset($data[$new_unit])) return $new_unit;
	$new_unit = preg_replace('/ee/', 'oo', $unit);
	if (isset($data[$new_unit])) return $new_unit;
	assert(false);
	return $unit;
}

while ($line = fgets($fin)) {
	if ($line == "\n") {
		$mode = 'calc';
		continue;
	}
	
	$line = substr($line, 0, strlen($line) - 1);
	if ($mode == 'info') {
		$segs = explode(" ", $line);
		assert($segs[4] == "m");
		assert($segs[2] == "=");
		assert($segs[0] == '1');
		$data[$segs[1]] = $segs[3];
	}
	else {
		$segs = explode(" ", $line);
		$pos = 0;
		$sum = 0;
		while ($pos < count($segs)) {
			$sgn = 1;
			if ($segs[$pos] == '+') {
				$pos++;
			}
			else if ($segs[$pos] == '-') {
				$pos++;
				$sgn = -1;
			}
			assert($pos < count($segs));
			assert(preg_match('/^\d*\.?\d*$/', $segs[$pos]));
			$val = $segs[$pos];
			$pos++;
			assert($pos < count($segs));
			$unit = $segs[$pos];
			$unit = to_std_unit($unit);
			assert(isset($data[$unit]));
			$sum += $data[$unit] * $val * $sgn;
			$pos++;
		}
		fprintf($fout, "%.2f m\n", $sum);
	}
}

fclose($fin);
fclose($fout);

