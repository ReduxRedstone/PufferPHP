# PufferPHP
PHP class to interact with your Puffer Panel server.

Tested only on Minecraft server, but should work for any server hosted with Puffer Panel.

Include class and create new instance.
```php
include 'puffer.class.php';

$puffer = new Puffer(X-Access-Server, X-Access-Token, Pannel-URL);
```
## Parameters
```
X-Access-Server = Your puffer panel Server-Key
X-Access-Token = Your puffer panel Token-Key
Pannel-URL = Your puffer panel url (with port)
```

## Example
```php
include 'puffer.class.php';

$puffer = new Puffer("08a980a8-107a-4a67-a800-d49839d17c3c", "8bad6493-f269-4ac9-8193-24264a320aff", "https://static.pufferpanel.com:5656");
```

## Functions

1. 'sendCommand()' => Sends command
2. 'status()' => Changes server status
3. 'log()' => Gets specified log lines
4. 'download()' => Downloads file based on hash
5. 'directory()' => Gets specified directory
6. 'file()' => Gets specified file
7. 'server()' => Server data

## Example usage:

```php
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
```
