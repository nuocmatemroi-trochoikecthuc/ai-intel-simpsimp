# ai-intel-simpsimp

Welcome to my smortest and secured chatbot, hope you guys go easy on him. Below is a quick documentation about this library.

## APIs

Currently, there are only 2 supported modules that allow you to chat with my SimpSimp bot and teach him to be nicer (i believe).

### chat

In this feature, you can simply chat with my SimpSimp bot in 4 different languages which are: **vietnamese (vn), english (en), chinese (zh)** and my favorite language **rushian (ru)**.

### teach

I integrated this feature allowing visitors to teach my bot with a question - answer set means that you can teach him more than 1 question - answer set at a time.

## configuration

Before hand, you need to have a `config.php` file including original SimSimi's API token so that my library can use it, the content is as below.

```php
<?php

return array(
    "St9LCoAioxTr-FFRydzge6uVupg0gog2" => "5Znnspk5k7HlzIdx3tFfQvArp~iAuZLyVcjY0Tlh"
);

?>
```

In the demo, i will generate the API so you are free to use it.

## installation

To use my library. Firstly, you need to create a new directory with a `composer.json` file including the content below.

```json
{
    "minimum-stability": "dev"
}
```

Then simply run this command then you are good to go.

```
composer require eejay/ai-intel-simpsimp
```

## usage

```php
<?php

require_once 'vendor/autoload.php';

$proxy = 'localhost:8080'; // this can be blank if you don't want to use proxy
$config = '/tmp/config.php';

$a = new \Eejay\Controllers\Api($proxy);
$rep = $a->chat($config, 'xin chào', 'vn');

$arr = 'a%3A1%3A%7Bs%3A3%3A%221%2B1%22%3Bs%3A17%3A%22hoi+z+cung+hoi%3F%3F%3F%22%3B%7D';

$rep2 = $a->teach($arr);

echo $rep;
echo $rep2;

```

## extra

There is a custom option that allows whoever wants to develop my project can use that is you can do stuffs such as garbage cleaning or closing database connection, etc... before destroy your object.

## license


MIT License

Copyright (c) 2021 nuocmatemroi-trochoikecthuc

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.