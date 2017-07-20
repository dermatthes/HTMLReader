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


    class Html5Tokenizer {


        private function _name2ns (&$name, &$ns) {
            $ns = null;
            if (strpos($name, ":") !== false) {
                $ns = substr ($name, 0, strpos ($name, ":"));
                $name = substr ($name, strpos($name, ":")+1);
            }
        }


        public function tokenize (Html5InputStream $i, HtmlCallback $callback) {

            $section = "pre";

            while (true) {
                if ($i->eos())
                    break;

                switch ($section) {
                    case "pre":
                        $buf = $i->readWhitespace();
                        if (strlen ($buf) > 0) {
                            $callback->onWhitespace($buf);
                        }

                        if ($i->readAhead(4) == "<!--") {
                            $i->next(4);
                            $buf = $i->readUntilString("-->");
                            $i->next(3);
                            $callback->onComment($buf);
                            continue;
                        }

                        if ($i->readAhead(2) == "<!") {
                            $buf = $i->readUntilChars(">");
                            $buf .= $i->next();
                            $callback->onProcessingInstruction($buf);
                            continue;
                        }

                        $buf = $i->readUntilChars("<");
                        if (strlen($buf) > 0) {
                            $callback->onWhitespace($buf);
                            continue;
                        }


                        if ($i->readAhead(1) == "<") {
                            $section = "tag";
                            continue;
                        }
                        break;

                    case "tag":
                        $buf = $i->readWhitespace();
                        if (strlen ($buf) > 0) {
                            $callback->onWhitespace($buf);
                            continue;
                        }

                        $buf = $i->readUntilChars("<");
                        if (strlen($buf) > 0) {
                            $callback->onText(html_entity_decode($buf));
                            continue;
                        }

                        if ($i->eos())
                            continue;

                        if ($i->readAhead(4) == "<!--") {
                            $i->next(4);
                            $buf = $i->readUntilString("-->");
                            $i->next(3);
                            $callback->onComment($buf);
                            continue;
                        }

                        if ($i->readAhead(2) == "</") {
                            $i->next(2);
                            $i->readWhitespace();
                            $buf = $i->readUntilChars(">");
                            $i->next();
                            $buf = trim ($buf);
                            $this->_name2ns($buf, $ns);
                            $callback->onTagClose($buf, $ns);
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

                        $callback->onTagOpen($name, $attrs, $empty, $ns);

                        $nextSection = "tag";
                        if (in_array($name, ["script", "style"]) && $empty == false) {
                            $nextSection = "script";
                        }
                        $section = $nextSection;
                        break;

                    case "script":
                        $content = $i->readUntilString("</$name>");
                        $name = null;
                        $callback->onText(html_entity_decode($content));
                        $section = "tag";
                        continue;


                }
            }

        }


    }