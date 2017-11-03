<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 03:32
 */

namespace HTML5\Test;
require __DIR__ . "/../vendor/autoload.php";


use Tester\Assert;
\Tester\Environment::setup();

use HTML5\Tokenizer\DebugHtmlCallback;
use HTML5\Tokenizer\Html5InputStream;
use HTML5\Tokenizer\Html5Tokenizer;
use PHPUnit\Framework\TestCase;


$debugCb = new DebugHtmlCallback();
$inputS = new Html5InputStream(file_get_contents(__DIR__ . "/unit/test.html"));

$tokenizer = new Html5Tokenizer();
$tokenizer->tokenize($inputS, $debugCb);
echo $debugCb->data;




$debugCb = new DebugHtmlCallback();
$inputS = new Html5InputStream(file_get_contents(__DIR__ . "/testFileStartsWithComment.html"));

$tokenizer = new Html5Tokenizer();
$tokenizer->tokenize($inputS, $debugCb);
echo $debugCb->data;


Assert::equal(true, true);
