<?php

require '../vendor/autoload.php';

//$rl = new Hoa\Console\Readline\Readline();
//
//do {
//    $line = $rl->readLine('> ');
//    echo '< ', $line, "\n\n";
//} while (false !== $line && 'quit' !== $line);

//$input = 'command "some arg fdfsd';
$input = 'command some --name= --city="moscow"';


//var_dump(substr_count($input, "\""));

//$info = new \LTDBeget\Rush\InputInfo($input);




//$parser = new \Hoa\Console\Parser();
//$parser->
//$parser->parse($input);

//var_dump($parser->getInputs());
//var_dump($parser->getSwitches());

//$arr = [
//    'one' => "dn",
//    "two" => "str"
//];
//end($arr);
//$k = key($arr);
//
//var_dump($k);
////
////$last = end($arr);
////reset($arr);
////
////var_dump($last[0]);

echo
'abcdef', "\n",
'ghijkl', "\n",
'mnopqr', "\n",
'stuvwx';

sleep(3);
Hoa\Console\Cursor::move('↑');
sleep(1);
Hoa\Console\Cursor::move('↑ ←');
sleep(5);
Hoa\Console\Cursor::move('left', 3);
echo "inject";
sleep(2);
Hoa\Console\Cursor::move('DOWN');
sleep(1);
Hoa\Console\Cursor::move('→', 4);