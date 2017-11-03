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

        private $includeLineNo;

        public function __construct($includeLineNo=false)
        {
            $this->includeLineNo = $includeLineNo;
        }


        public function onWhitespace(string $ws, int $lineNo)
        {
            $this->data .= $ws;
        }

        public function onTagOpen(string $name, array $attributes, $isEmpty, $ns=null, int $lineNo)
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
            if ($this->includeLineNo)
                $this->data .= "#$lineNo#";
            $this->data .= "<{$ns}{$name}{$att}>";
        }

        public function onText(string $text, int $lineNo)
        {
            $this->data .= $text;
        }

        public function onTagClose(string $name, $ns=null, int $lineNo)
        {
            if ($ns !== null)
                $ns = "$ns:";
            if ($this->includeLineNo)
                $this->data .= "#$lineNo#";
            $this->data .= "</{$ns}$name>";
        }

        public function onProcessingInstruction(string $data, int $lineNo)
        {
            $this->data .= $data;
        }

        public function onComment(string $data, int $lineNo)
        {
            $this->data .= "<!--" . $data . "-->";
        }
    }
