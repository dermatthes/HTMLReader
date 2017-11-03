<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 15.08.16
 * Time: 11:14
 */

namespace HTML5\Test;
require __DIR__ . "/../../vendor/autoload.php";


use Tester\Assert;
\Tester\Environment::setup();

use HTML5\HTMLReader;
use HTML5\Tokenizer\DebugHtmlCallback;




$reader = new HTMLReader();
$reader->loadHtml(__DIR__ . "/test.html");
$reader->setHandler($mockHandler = new DebugHtmlCallback());
$reader->parse();
Assert::equal(file_get_contents(__DIR__ . "/test.html"), $mockHandler->data);
            
$reader = new HTMLReader();
$reader->loadHtml(__DIR__ . "/test.html");
$reader->setHandler($mockHandler = new DebugHtmlCallback(true));
$reader->parse();
Assert::equal(file_get_contents(__DIR__ . "/test.compare.lineno.txt"), $mockHandler->data);
