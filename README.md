# Alto Twig Code Highlight

Twig extension for [Alto Code Highlight](https://github.com/altophp/code-highlight). It provides a `{% code_highlight %}` tag and a `code_highlight` filter.

## Requirements

- PHP `^8.4`
- Alto Code Highlight `^1.0`
- Twig `^3.28`

## Installation

```bash
composer require alto/twig-code-highlight
```

## Setup

Register the extension and its runtime:

```php
use Alto\Twig\CodeHighlight\CodeHighlightExtension;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

$extension = new CodeHighlightExtension();
$twig->addExtension($extension);

$twig->addRuntimeLoader(new FactoryRuntimeLoader([
    CodeHighlightRuntime::class => static fn (): CodeHighlightRuntime => new CodeHighlightRuntime(
        $extension->getHighlighter(),
        $extension->getDefaultOptions(),
    ),
]));
```

Default options may be configured on the extension:

```php
$extension = new CodeHighlightExtension(defaultOptions: [
    'line_numbers' => true,
]);
```

## Tag

```twig
{% code_highlight 'php' with {line_numbers: true, highlight_lines: [2]} %}
<?php

echo 'Hello world';
{% endcode_highlight %}
```

The language may be a Twig expression:

```twig
{% code_highlight language %}
const answer = 42;
{% endcode_highlight %}
```

## Filter

```twig
{{ source|code_highlight('javascript') }}
```

Options are passed as the second argument:

```twig
{{ source|code_highlight('php', {line_numbers: true, highlight_lines: [1, 3]}) }}
```

## Options

| Option | Type | Default |
| --- | --- | --- |
| `line_numbers` | `bool` | `false` |
| `highlight_lines` | `array<int>` | `[]` |

A language is required for both the tag and filter. Configure themes through the core `Highlighter`; see the [theme guide](https://github.com/altophp/code-highlight/blob/main/docs/themes.md).

## Contributing

Issues and pull requests are welcome on [GitHub](https://github.com/altophp/twig-code-highlight).

## License

Released under the [MIT License](LICENSE).
