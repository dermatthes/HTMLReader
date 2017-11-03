<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 03:37
 */

    namespace HTML5\Tokenizer;

    interface HtmlCallback {

        public function onWhitespace(string $ws, int $lineNo);

        public function onTagOpen(string $name, array $attributes, $isEmpty, $ns=null, int $lineNo);

        public function onText (string $text, int $lineNo);

        public function onTagClose(string $name, $ns=null, int $lineNo);

        public function onProcessingInstruction(string $data, int $lineNo);

        public function onComment (string $data, int $lineNo);

    }
