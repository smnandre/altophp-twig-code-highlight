# Configuration

Configure the highlighter and default options before registering the Twig
extension. Pass the same highlighter and defaults to the runtime loader.

```php
use Alto\Code\Highlight\Highlighter;
use Alto\Code\Highlight\Theme\AltoTheme;
use Alto\Twig\CodeHighlight\CodeHighlightExtension;

$extension = new CodeHighlightExtension(
    highlighter: new Highlighter(new AltoTheme()),
    defaultOptions: ['line_numbers' => true, 'highlight_lines' => []],
);
```

Use the full [Getting started](getting-started.md) setup to register this instance.
If your dependency injection container supplies `CodeHighlightRuntime`, wire its
highlighter and defaults consistently instead of registering a second runtime.

See [built-in themes](https://altophp.com/code-highlight/theming/),
[theme adapters](https://altophp.com/code-highlight/theming/adapters/), and
[creating a theme](https://altophp.com/code-highlight/theming/creating/) for theme
selection. The extension has no separate theme catalog and needs no browser-side
highlighter. Include the chosen stylesheet once in the document.

Line-number appearance is application CSS. Style `.alto-line-number` and
`.alto-line-number.alto-highlighted`; the highlighted class belongs to the number,
not a wrapper around the entire source line.
