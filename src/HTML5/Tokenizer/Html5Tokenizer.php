<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Author: Matthias Leuffen <matthes@leuffen.de>
     *
     * Date: 13.08.16
     * Time: 02:42
     */

    namespace HTML5\Tokenizer;


    class Html5Tokenizer
    {


        private $parseProcessingInstruction = true;
        private $parseComment = true;
        private $parseOverTags = ["script", "style"];
        private $parseOnlyTagPrefix = "";

        public function __construct(array $opt = [])
        {
            foreach ($opt as $key => $value)
                $this->$key = $value;
        }


        private function _name2ns (&$name, &$ns)
        {
            $ns = null;
            if (strpos($name, ":") !== false) {
                $ns = substr ($name, 0, strpos ($name, ":"));
                $name = substr ($name, strpos($name, ":")+1);
            }
        }


        public function tokenize (Html5InputStream $i, HtmlCallback $callback)
        {

            $section = "pre";
            $parseOnlyLength = strlen($this->parseOnlyTagPrefix);

            while (true) {
                if ($i->eos()) {
                    break;
                }

                switch ($section) {
                    case "pre":
                        $buf = $i->readWhitespace();
                        if (strlen ($buf) > 0) {
                            $callback->onWhitespace($buf, $i->getCurLineNo());
                        }

                        if ($i->readAhead(4) == "<!--" && $this->parseComment) {
                            $i->next(4);
                            $buf = $i->readUntilString("-->");
                            $i->next(3);
                            $callback->onComment($buf, $i->getCurLineNo());
                            continue;
                        }

                        if ($i->readAhead(2) == "<!" && $this->parseProcessingInstruction) {
                            $buf = $i->readUntilChars(">");
                            $buf .= $i->next();
                            $callback->onProcessingInstruction($buf, $i->getCurLineNo());
                            continue;
                        }

                        $buf = $i->readUntilString("<{$this->parseOnlyTagPrefix}");
                        if (strlen($buf) > 0) {
                            $callback->onWhitespace($buf, $i->getCurLineNo());
                            continue;
                        }


                        if ($i->readAhead(1 + $parseOnlyLength) == "<{$this->parseOnlyTagPrefix}") {
                            $section = "tag";
                            continue;
                        }
                        break;

                    case "tag":
                        $buf = $i->readWhitespace();
                        if (strlen ($buf) > 0) {
                            $callback->onWhitespace($buf, $i->getCurLineNo());
                            continue;
                        }

                        $buf = $i->readUntilString("<{$this->parseOnlyTagPrefix}");
                        if (strlen($buf) > 0) {
                            $callback->onText(html_entity_decode($buf), $i->getCurLineNo());
                            continue;
                        }

                        if ($i->eos())
                            continue;

                        if ($i->readAhead(4) == "<!--" && $this->parseComment) {
                            $i->next(4);
                            $buf = $i->readUntilString("-->");
                            $i->next(3);
                            $callback->onComment($buf, $i->getCurLineNo());
                            continue;
                        }

                        if ($i->readAhead(2 + $parseOnlyLength) == "</{$this->parseOnlyTagPrefix}") {
                            $i->next(2 + $parseOnlyLength);
                            $i->readWhitespace();
                            $buf = $i->readUntilChars(">");
                            $i->next();
                            $buf = trim ($buf);
                            $this->_name2ns($buf, $ns);
                            $callback->onTagClose($buf, $ns, $i->getCurLineNo());
                            continue;
                        }


                        $i->next();
                        $name = $i->readUntilChars(" \n\t/>");



                        $empty = false;
                        $attrs = [];
                        $i->readWhitespace();

                        while (true) {
                            $i->readWhitespace();
                            if ($i->readAhead(2) == "/>") {
                                $empty = true;
                                $i->next(2);
                                break;
                            }
                            if ($i->readAhead(1) == ">") {
                                $i->next();
                                break;
                            }
                            $attr = $i->readUntilChars("= >");
                            $i->readWhitespace();
                            if ($i->readAhead(1) != "=") {
                                $val = null;
                                $attrs[$attr] = $val;
                                continue;
                            }
                            $i->next();
                            $i->readWhitespace();
                            $i->readUntilChars("'\"");
                            if ($i->eos())
                                break;
                            $strend = $i->next();
                            $val = $i->readUntilChars($strend);
                            $val = html_entity_decode($val);
                            $i->next();
                            $attrs[$attr] = $val;
                        }

                        $this->_name2ns($name, $ns);

                        $callback->onTagOpen($name, $attrs, $empty, $ns, $i->getCurLineNo());

                        $nextSection = "tag";
                        if (in_array($name, $this->parseOverTags) && $empty == false && $ns == null) {
                            $nextSection = "script";
                        }
                        $section = $nextSection;
                        break;

                    case "script":
                        $content = $i->readUntilString("</$name>");
                        $name = null;
                        $callback->onText(html_entity_decode($content), $i->getCurLineNo());
                        $section = "tag";
                        continue;


                }
            }
            $callback->onEos();
        }
    }