# ALTO Twig Code Highlight

Highlight source code directly from Twig templates with a tag or filter powered
by [ALTO Code Highlight](https://github.com/altophp/code-highlight).

&nbsp; ![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-00B7FF?logoColor=00B7FF&labelColor=050608)
&nbsp; ![CI](https://img.shields.io/github/actions/workflow/status/altophp/twig-code-highlight/CI.yml?branch=main&label=Tests&labelColor=050608&color=00B7FF)
&nbsp; [![Packagist](https://img.shields.io/packagist/v/alto/twig-code-highlight?label=Packagist&labelColor=050608&color=00B7FF)](https://packagist.org/packages/alto/twig-code-highlight)
&nbsp; ![License](https://img.shields.io/github/license/altophp/twig-code-highlight?label=License&labelColor=050608&color=00B7FF)
&nbsp; [![GitHub Sponsors](https://img.shields.io/github/sponsors/smnandre?logo=githubsponsors&logoColor=00B7FF&label=%20Sponsor&labelColor=050608&color=00B7FF)](https://github.com/sponsors/smnandre)

Use the block tag for literal template content or the filter for dynamic source:

```twig
{% code_highlight 'php' %}
<?php echo 'Hello, Alto!';
{% endcode_highlight %}

{{ source|code_highlight('javascript') }}
```

## Installation

Install ALTO Twig Code Highlight with Composer:

```bash
composer require alto/twig-code-highlight
```

The package requires PHP 8.4 or later, ALTO Code Highlight 1.x, and Twig 3.28
or later.

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

## Quick Start

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

Contributions of all kinds are welcome. Visit the
[project on GitHub](https://github.com/altophp/twig-code-highlight) to
[report a bug](https://github.com/altophp/twig-code-highlight/issues/new),
[suggest a feature](https://github.com/altophp/twig-code-highlight/issues/new), or
[open a pull request](https://github.com/altophp/twig-code-highlight/pulls).

Before submitting code, run:

```bash
# Runs PHP CS Fixer, PHPStan, and PHPUnit
composer qa
```

Changes to public behavior should include tests and documentation.

## Support

ALTO Twig Code Highlight is open source. You can support its continued development through
[GitHub Sponsors](https://github.com/sponsors/smnandre).

Sharing this package with others or
[starring it on GitHub](https://github.com/altophp/twig-code-highlight) is also much
appreciated.

## License

ALTO Twig Code Highlight is released by [ALTO PHP](https://altophp.com) under the
[MIT License](LICENSE).
