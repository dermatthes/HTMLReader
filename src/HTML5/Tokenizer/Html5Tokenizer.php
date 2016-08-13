<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.08.16
 * Time: 02:42
 */

    namespace HTML5\Tokenizer;


    class Html5Tokenizer {

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

                        if ($i->readAhead(4) == "<!--") {
                            $buf = $i->readUntilString("-->");
                            $buf .= $i->next(3);
                            $callback->onComment($buf);
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
                            $callback->onText($buf);
                        }

                        if ($i->readAhead(2) == "</") {
                            $i->next(2);
                            $buf = $i->readUntilChars(">");
                            $i->next();
                            $callback->onTagClose($buf);
                            continue;
                        }


                        $i->next();
                        $buf = $i->readUntilChars(">");
                        $i->next();
                        $callback->onTagOpen($buf, [], false);
                        continue;





                }
            }

        }


    }