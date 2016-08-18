<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 15.08.16
     * Time: 11:14
     */

    namespace HTML5\Test;


    use HTML5\HTMLReader;
    use HTML5\Tokenizer\DebugHtmlCallback;

    class HtmlReaderTest extends \PHPUnit_Framework_TestCase {

        
        
        public function testOutputLooksLikeInput () {
            
            $reader = new HTMLReader();
            $reader->loadHtml(__DIR__ . "/test.html");
            
            $reader->setHandler($mockHandler = new DebugHtmlCallback());
            $reader->parse();
            
            self::assertEquals(file_get_contents(__DIR__ . "/test.html"), $mockHandler->data);
            
        }
        
    }
