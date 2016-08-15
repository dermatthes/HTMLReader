# HTMLReader

HtmlReader is a very simple Html Parser not build on libxml. It is thought
as replacement for XMLReader which won't parse html5 input data
properly. It is faster than DOM and won't change a single whitespace.

It won't care about properly closed Elements etc.

## Installation

Use Composer to install the Package from Packagist.com:

```
composer add HTML5/HtmlReader
```


## Usage

```php
$reader = new HtmlReader();
$reader->loadHtml("input.html")
// $reader->loadHtmlString("<html></html>");

$reader->setHandler(new HtmlCallback()); // <-- Write your own HtmlCallback
$reader->parse();
```


## Debuggin

We have packed a DebugHtmlCallback Handler.


## Credits

Written by Matthias Leuffen 
http://leuffen.de