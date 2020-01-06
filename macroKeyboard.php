#!/usr/bin/php
<?php
// TODO: get rid of the python dependency by using libevent in PHP itself with ffi, and get rid of the privilege escalation by adding the user to the input group
function runCommand(string $command) {
	return shell_exec("$command >/dev/null 2>/dev/null &");
}
echo "#### Macro Keyboard #####\n";
while($buttonText = trim(fgets(STDIN))) {
	if(!preg_match("/[kK]ey event at [0-9\.]+, [0-9]+ \(([^)]+)\), (up|down|hold)/", $buttonText, $button))
		continue;
	list($button, $action) = [$button[1], $button[2]];
	if($action == "hold")
		$button = "@".$button;
	if($action == "up")
		$button = "^".$button;
	$macrosFile = file("/home/danny/Dotfiles/macros.txt");
	$macros = [];
	for($i = 0; $i < count($macrosFile); $i++) {
		if(!empty($macrosFile[$i])) {
			$macros[trim($macrosFile[$i])] = trim($macrosFile[$i + 1]);
			$i++;
		}
	}
	if(isset($macros[$button]))
		runCommand($macros[$button]);
	elseif($action == "down") {
		$text = "Unmatched macro: $buttonText. \nLooking for button: $button\n";
		echo $text;
		system("notify-send 'Macro Keyboard' ".escapeshellarg($text));
	}
}
