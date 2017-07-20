
[![Downloads this Month](https://img.shields.io/packagist/dm/html5/htmlreader.svg)](https://packagist.org/packages/html5/htmlreader)
[<img src="https://travis-ci.org/dermatthes/HTMLReader.svg">](https://travis-ci.org/dermatthes/HTMLReader)
[![Latest Stable Version](https://poser.pugx.org/html5/htmlreader/v/stable)](https://github.com/dermatthes/HTMLReader/releases)

# HTMLReader 

HtmlReader is a very simple Html Parser NOT build on libxml. It is thought
as replacement for XMLReader which won't parse html5 input data
properly. It is faster than DOM and won't change a single whitespace.

It won't care about properly closed Elements etc. so you can / have to do
it your own.

## Installation

Use Composer to install the Package from Packagist.com:

```
composer require html5/htmlreader
```


## Usage

```php
$reader = new HtmlReader();
$reader->loadHtml("input.html")
// $reader->loadHtmlString("<html></html>");

$reader->setHandler(new HtmlCallback()); // <-- Write your own HtmlCallback
$reader->parse();
```


## Debugging

We have packed a DebugHtmlCallback Handler.


## New in Version 1.1.0

- Added Support for Namespaces 



## Credits

Written by Matthias Leuffen 
http://leuffen.de