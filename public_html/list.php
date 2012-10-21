<?php 
require_once dirname(__file__) . '/common.php';

// Create recursive dir iterator which skips dot folders
$dir = new RecursiveDirectoryIterator(config('media_dir'),
    FilesystemIterator::SKIP_DOTS);

// Flatten the recursive iterator, folders come before their files
$it  = new RecursiveIteratorIterator($dir,
    RecursiveIteratorIterator::SELF_FIRST);

echo '<table>';
	
// Basic loop displaying different messages based on file or folder
foreach ($it as $fileinfo) {
    if ($fileinfo->isDir()) {
    } elseif ($fileinfo->isFile()) {
		# filename without extension
		$filename = pathinfo($fileinfo->getFilename())['filename'];
		$extension = pathinfo($fileinfo->getFilename())['extension'];
		
		# Only list mp4, avi files
		if (!in_array($extension, config('extensions')) || pathinfo($filename)['extension'] == 'iphone') {
			continue;
		}
		
		$path = ($it->getSubPath() != '') ? $it->getSubPath() . '/' : '';
		$path .= $fileinfo->getFilename();
		
		$convert = !file_exists(request_info($path)['log']);
		
		$watch = false;
		if (!$convert) {
			$log = file_get_contents(request_info($path)['log']);
			if (strlen($log) > 0) {			
				$log = str_replace(array("\r\n", "\n", "\r"), "|", $log);
				$log = explode("|", $log);
				
				# Last line is a empty line
				if (end($log) == '') {
					array_pop($log);
				}
				
				# has a empty line
				$watch = array_search('', $log) !== false;
			}
		}
		
		# File
		echo '<tr><td>';
		echo $path;
		
		# Convert
		echo '</td><td>';
		if ($convert) {
			echo '<a href="convert_iphone.php/' . $path . '">Convert to iPhone</a>';
		} elseif (!$watch) {
		# Progress
			echo '<span class="fetch-progress" data-path="' . $path . '">In progress</span>';
		}
		
		# Watch
		echo '</td><td>';
		if ($watch) {
			echo '<a href="watch.php/' . request_info($path)['iphone'] . '">Ready to watch</a>';
		}
		
		echo '</td></tr>';
    }
}

echo '</table>';
?>
<script src="js/jquery-1.8.2.min.js"></script>
<script>
jQuery(function ($) {
	$('.fetch-progress').each(function() {
		var self = $(this);		
		$.get('progress.php/' + self.data('path'), function (data) {
			self.text(data);
		});
	});
});
</script>