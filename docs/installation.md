# Installation

Install the Twig integration with Composer:

```sh
composer require alto/twig-code-highlight
```

Use PHP 8.4 or later, Twig 3.28 or later, and Code Highlight 1.x. Composer installs
the PHP dependencies. Code Highlight requires `mbstring` and `tokenizer`; verify
missing platform requirements with `composer check-platform-reqs`.

In a standalone script, load `vendor/autoload.php`. Register both the extension
and its runtime loader as shown in [Getting started](getting-started.md).
If Twig cannot load `CodeHighlightRuntime`, check that the loader is registered
on the same `Environment` that renders the template.
