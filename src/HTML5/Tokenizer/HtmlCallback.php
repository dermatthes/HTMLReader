<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 03:37
 */

    namespace HTML5\Tokenizer;

    interface HtmlCallback {

        public function onWhitespace(string $ws);

        public function onTagOpen(string $name, array $attributes, $isEmpty, $ns=null);

        public function onText (string $text);

        public function onTagClose(string $name, $ns=null);

        public function onProcessingInstruction(string $data);

        public function onComment (string $data);

    }
