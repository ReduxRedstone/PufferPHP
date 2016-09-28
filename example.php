<?php

include 'puffer.class.php';

$puffer = new Puffer("08a980a8-107a-4a67-a800-d49839d17c3c", "8bad6493-f269-4ac9-8193-24264a320aff", "https://static.pufferpanel.com:5656");

echo $puffer->sendCommand("say hello");
echo "<br>";
echo $puffer->status("restart");
echo "<br>";
echo $puffer->log(10);
echo "<br>";
//echo $puffer->download(); //Currently unaware of how Puffer makes these hashes. This function should still work, though
echo "<br>";
echo "<pre>".print_r(json_decode($puffer->directory("world/playerdata"), true), true)."</pre>"; //aliased as getDirectory()
echo "<br>";
echo "<pre>".print_r(json_decode($puffer->file("ops.json"), true), true)."</pre>"; //aliased as getFile()
echo "<br>";
echo $puffer->server(); //uses `get` or `post` as a type. Defaults to `get`
echo "<br>";
