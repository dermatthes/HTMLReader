<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 03:32
 */

namespace HTML5\Test;


use HTML5\Tokenizer\DebugHtmlCallback;
use HTML5\Tokenizer\Html5InputStream;
use HTML5\Tokenizer\Html5Tokenizer;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{


    public function testTokenizer () {
        $debugCb = new DebugHtmlCallback();
        $inputS = new Html5InputStream(file_get_contents(__DIR__ . "/bightml.html"));

        $tokenizer = new Html5Tokenizer();
        $tokenizer->tokenize($inputS, $debugCb);
        echo $debugCb->data;
    }

}
