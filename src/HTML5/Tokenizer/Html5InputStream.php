<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 02:43
 */

    namespace HTML5\Tokenizer;



    class Html5InputStream {

        private $stream;

        private $index = 0;

        private $length;

        public function __construct(string $input)
        {
            $this->stream = $input;
            $this->length = strlen($input);
        }


        public function eos () : bool {
            if ($this->index >= $this->length)
                return true;
            return false;
        }


        public function readWhitespace() : string {
            $buf = "";
            for ($this->index; $this->index < $this->length; $this->index++) {
                $chr = $this->stream[$this->index];
                if ($chr !== " " && $chr !== "\n" && $chr !== "\t" && $chr !== "\r")
                    break;
                $buf .= $chr;
            }
            return $buf;
        }


        public function next($num = 1) : string {
            $buf = "";
            for ($i = 0; $i < $num; $i++) {
                if ($this->eos())
                    break;
                $buf .= $this->stream[$this->index++];
            }
            return $buf;
        }

        public function readUntilChars($chars) {
            $buf = "";
            for ($this->index; $this->index < $this->length; $this->index++) {
                $chr = $this->stream[$this->index];
                if (strpos($chars, $chr) !== false)
                    break;
                $buf .= $chr;
            }
            return $buf;
        }

        public function readAhead ($num) {
            $buf = "";
            $toOffset = $this->index + $num;
            if ($toOffset > $this->length)
                return "";
            for ($i = $this->index; $i < $toOffset; $i++) {
                $buf .= $this->stream[$i];
            }
            return $buf;
        }


        public function readUntilString($str) {
            $buf = "";
            while (true) {
                if ($this->eos())
                    break;
                $chr = $this->stream[$this->index];
                for ($i=0; $i<strlen($str); $i++) {
                    if ($str[$i] != $this->stream[$this->index + $i])
                        break;
                }
                if ($i == strlen($str))
                    break;
                $this->index++;
                $buf .= $chr;
            }
            return $buf;
        }


    }