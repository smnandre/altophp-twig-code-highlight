# Getting started

Save this script as `highlight.php` beside `vendor` and run `php highlight.php`.
It creates a Twig environment, registers the extension and runtime, and renders
source supplied as data. Twig autoescaping remains enabled.

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Alto\Twig\CodeHighlight\CodeHighlightExtension;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

$twig = new Environment(new ArrayLoader([
    'example' => '{{ source|code_highlight(language) }}',
]), ['autoescape' => 'html']);
$extension = new CodeHighlightExtension();
$twig->addExtension($extension);
$twig->addRuntimeLoader(new FactoryRuntimeLoader([
    CodeHighlightRuntime::class => static fn (): CodeHighlightRuntime => new CodeHighlightRuntime(
        $extension->getHighlighter(),
        $extension->getDefaultOptions(),
    ),
]));
echo $twig->render('example', ['source' => '<b>Hello</b>', 'language' => 'html']), "\n";
```

Output:

```text
<pre class="alto-highlight language-html"><code class="language-html"><span class="alto-keyword">&lt;</span><span class="alto-keyword">b</span><span class="alto-keyword">&gt;</span><span class="alto-punctuation">Hello</span><span class="alto-keyword">&lt;/</span><span class="alto-keyword">b</span><span class="alto-keyword">&gt;</span></code></pre>
```

The generated tags remain HTML, while the original `<b>Hello</b>` source is
escaped inside them. The filter marks its own generated HTML safe; do not add
`raw` to the source or pre-escape it.

## Add the theme

To view syntax colors, replace the final `echo` with this complete-page output,
using the same `$extension` and `$twig` above:

```php
echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Example</title><style>';
echo $extension->getHighlighter()->getTheme()->getStylesheet();
echo '</style></head><body>';
echo $twig->render('example', ['source' => '<b>Hello</b>', 'language' => 'html']);
echo '</body></html>';
```

Run `php highlight.php > highlight.html` and open the file. You should see the
literal `<b>Hello</b>` text in a highlighted block, not a bold Hello element.
Emit the stylesheet once per page. See [Configuration](configuration.md) to
choose a different theme and [Usage](usage.md) for the block tag.
