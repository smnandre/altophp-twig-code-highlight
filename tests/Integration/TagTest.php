<?php

declare(strict_types=1);

/*
 * This file is part of the ALTO library.
 *
 * © 2026–present Simon André
 *
 * For full copyright and license information, please see
 * the LICENSE file distributed with this source code.
 */

namespace Alto\Twig\CodeHighlight\Tests\Integration;

use Alto\Code\Highlight\Highlighter;
use Alto\Code\Highlight\Theme\AltoTheme;
use Alto\Twig\CodeHighlight\CodeHighlightExtension;
use Alto\Twig\CodeHighlight\Node\CodeHighlightNode;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Alto\Twig\CodeHighlight\TokenParser\CodeHighlightTokenParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Loader\ArrayLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

#[CoversClass(CodeHighlightNode::class)]
#[UsesClass(CodeHighlightRuntime::class)]
#[UsesClass(CodeHighlightExtension::class)]
#[CoversClass(CodeHighlightTokenParser::class)]
final class TagTest extends TestCase
{
    private Environment $twig;

    protected function setUp(): void
    {
        $this->twig = new Environment(new ArrayLoader(), ['strict_variables' => true]);

        $highlighter = new Highlighter(new AltoTheme());
        $extension = new CodeHighlightExtension($highlighter);

        $this->twig->addExtension($extension);
        $this->twig->addRuntimeLoader(new FactoryRuntimeLoader([
            CodeHighlightRuntime::class => static fn (): CodeHighlightRuntime => new CodeHighlightRuntime(
                $highlighter,
                $extension->getDefaultOptions(),
            ),
        ]));
    }

    #[Test]
    public function itHighlightsBlockContentWithAnExplicitLanguage(): void
    {
        $template = $this->twig->createTemplate(<<<'TWIG'
{% code_highlight 'php' %}
<?php
echo "Hello";
{% endcode_highlight %}
TWIG);

        $output = $template->render();

        self::assertNotSame('', trim($output));
        self::assertStringContainsString('echo', $output);
    }

    #[Test]
    public function itSupportsOptionsOnTheBlockTag(): void
    {
        $template = $this->twig->createTemplate(<<<'TWIG'
{% code_highlight 'php' with {line_numbers: true, highlight_lines: [2]} %}
<?php
echo "Hello";
{% endcode_highlight %}
TWIG);

        $output = $template->render();

        self::assertStringContainsString('alto-line-number', $output);
        self::assertStringContainsString('alto-highlighted', $output);
    }

    #[Test]
    public function itSupportsADynamicLanguageForTheBlockTag(): void
    {
        $template = $this->twig->createTemplate(<<<'TWIG'
{% code_highlight language %}
const answer = 42;
{% endcode_highlight %}
TWIG);

        $output = $template->render(['language' => 'javascript']);

        self::assertNotSame('', trim($output));
        self::assertStringContainsString('answer', $output);
    }

    #[Test]
    public function itThrowsWhenLanguageIsMissingForTheBlockTag(): void
    {
        $template = $this->twig->createTemplate(<<<'TWIG'
{% code_highlight %}
echo "Hello";
{% endcode_highlight %}
TWIG);

        $this->expectException(RuntimeError::class);
        $this->expectExceptionMessage('Language parameter is required');

        $template->render();
    }
}
