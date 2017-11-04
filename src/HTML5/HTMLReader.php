<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 02:37
 */

    namespace HTML5;

    use HTML5\Tokenizer\Html5InputStream;
    use HTML5\Tokenizer\Html5Tokenizer;
    use HTML5\Tokenizer\HtmlCallback;

    class HTMLReader {

        private $inputStream = null;
        private $tokenizerOpts = [];
        private $callback = null;
        
        
        
        public function __construct(array $tokenzierOpts = []) {
            $this->tokenizerOpts = $tokenzierOpts;
        }


        public function loadHtml($filename) {
            $this->inputStream = new Html5InputStream(file_get_contents($filename));
            return true;
        }
        
        public function loadHtmlString($htmlData) {
            $this->inputStream = new Html5InputStream($htmlData);
            return true;
        }
        
        
        public function setHandler (HtmlCallback $callback) {
            $this->callback = $callback;
        }
        
        
        public function parse() : bool {
            if ($this->inputStream === null)
                throw new \InvalidArgumentException("No html data loaded. Call loadHtml() or loadHtmlString()");
            if ($this->callback === null)
                throw new \InvalidArgumentException("No Callback Handler set. Call setHandler() to set a proper callback handler");
            $t = new Html5Tokenizer($this->tokenizerOpts);
            $t->tokenize($this->inputStream, $this->callback);
            return true;
        }
        
        

    }