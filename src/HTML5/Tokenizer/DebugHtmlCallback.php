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
        }

        public function onTagOpen(string $name, array $attributes, $isEmpty, $ns=null)
        {
            $att = [];
            foreach ($attributes as $key => $val) {
                if ($val === null) {
                    $att[] = $key;
                    continue;
                }
                $att[] = $key . "=\"{$val}\"";
            }
            $att = implode(" ", $att);
            if (strlen ($att) > 0)
                $att = " " . $att;
            if ($ns !== null)
                $ns = "$ns:";
            $this->data .= "<{$ns}{$name}{$att}>";
        }

        public function onText(string $text)
        {
            $this->data .= $text;
        }

        public function onTagClose(string $name, $ns=null)
        {
            if ($ns !== null)
                $ns = "$ns:";
            $this->data .= "</{$ns}$name>";
        }

        public function onProcessingInstruction(string $data)
        {
            $this->data .= $data;
        }

        public function onComment(string $data)
        {
            $this->data .= "<!--" . $data . "-->";
        }
    }
