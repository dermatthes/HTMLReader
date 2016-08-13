<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 04:05
 */

    namespace HTML5\Tokenizer;

    class DebugHtmlCallback implements HtmlCallback {

        public $data = "";

        public function onWhitespace(string $ws)
        {
            $this->data .= $ws;
            echo "\nWHITESPACE:" . $ws;
        }

        public function onTagOpen(string $name, array $attributes, $isEmpty)
        {
            $this->data .= "<$name>";
            echo "\nOP: $name";
        }

        public function onText(string $text)
        {
            $this->data .= $text;
            echo "\nTX: $text";
        }

        public function onTagClose(string $name)
        {
            $this->data .= "</$name>";
            echo "\nCL: $name";
        }

        public function onProcessingInstruction(string $data)
        {
            $this->data .= $data;
            echo "\nPI: $data";
        }

        public function onComment(string $data)
        {
            $this->data .= $data;
            echo "\nCM: $data";
        }
    }
