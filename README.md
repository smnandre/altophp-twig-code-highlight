# Alto Twig Code Highlight

Twig extension for [alto/code-highlight](https://github.com/phpalto/code-highlight) that adds a block tag and a filter for rendering syntax-highlighted code in templates.

## Requirements

- PHP `^8.4` (>= 8.4.0, < 9.0.0)
- Twig `^3.23` (>= 3.23.0, < 4.0.0)

## Installation

```bash
composer require alto/twig-code-highlight
```

## Register extension

```php
use Alto\Twig\CodeHighlight\CodeHighlightExtension;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

$extension = new CodeHighlightExtension();
$twig->addExtension($extension);

$twig->addRuntimeLoader(new FactoryRuntimeLoader([
    CodeHighlightRuntime::class => fn () => new CodeHighlightRuntime(
        $extension->getHighlighter(),
        $extension->getDefaultOptions(),
    ),
]));
```

## Tag `{% code_highlight %}`

This tag highlights the enclosed code block and renders highlighted HTML output.

Syntax:

```twig
{% code_highlight language_expression [with options_expression] %}
...code body...
{% endcode_highlight %}
```

### Example

```twig
{% code_highlight 'php' %}
<?php
echo "Hello world";
{% endcode_highlight %}
```

### Options (arguments)

| Option            | Type       | Default | Behavior                                                  |
|-------------------|------------|---------|-----------------------------------------------------------|
| `line_numbers`    | bool       | `false` | Enables line numbers in output                            |
| `highlight_lines` | array<int> | `[]`    | Only positive integers are used; other values are ignored |

Theme:

`theme` is not passable as a tag argument; configure the `Highlighter` theme when registering the extension.

See theme docs in the core library: <https://github.com/phpalto/code-highlight>.

## Filter `|code_highlight`

This filter highlights a code string and returns highlighted HTML output.

Syntax:

```twig
{{ code|code_highlight(language, options) }}
```

### Example

```twig
{{ snippet|code_highlight('javascript') }}
```

### Options (arguments)

| Option            | Type       | Default | Behavior                                                  |
|-------------------|------------|---------|-----------------------------------------------------------|
| `line_numbers`    | bool       | `false` | Enables line numbers in output                            |
| `highlight_lines` | array<int> | `[]`    | Only positive integers are used; other values are ignored |

Theme:

`theme` is not passable as a filter argument; configure the `Highlighter` theme when registering the extension.

See theme docs in the core library: <https://github.com/phpalto/code-highlight>.

## Contributing

Issues and pull requests are welcome.

## License

MIT License. See [LICENSE](LICENSE) for details.
