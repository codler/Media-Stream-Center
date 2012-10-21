<?php
require_once dirname(__file__) . '/common.php';

if (file_exists(request_info()['log'])) {
	$log = file_get_contents(request_info()['log']);
	if (strlen($log) > 0) {
		$log = str_replace(array("\r\n", "\n", "\r"), "|", $log);
		$log = explode("|", $log);
		
		# Last line is a empty line
		if (end($log) == '') {
			array_pop($log);
		}
		
		$last_line = end($log);
		
		if (preg_match("/time=([\d:.]+)/", $last_line, $matches)) {
			echo $matches[1];
		} else {
			echo 'Unknown';
		}
	}
}
