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
use Twig\Loader\ArrayLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

#[CoversClass(CodeHighlightRuntime::class)]
#[UsesClass(CodeHighlightExtension::class)]
#[UsesClass(CodeHighlightTokenParser::class)]
#[UsesClass(CodeHighlightNode::class)]
final class FilterTest extends TestCase
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
    public function itHighlightsCodeWithAnExplicitLanguage(): void
    {
        $template = $this->twig->createTemplate("{{ code|code_highlight('php') }}");
        $output = $template->render(['code' => '<?php echo "Hello";']);

        self::assertNotSame('', trim($output));
        self::assertStringContainsString('echo', $output);
    }

    #[Test]
    public function itSupportsOptionsOnTheFilter(): void
    {
        $template = $this->twig->createTemplate(
            "{{ code|code_highlight('php', {line_numbers: true, start_line: 10}) }}",
        );
        $output = $template->render(['code' => '<?php echo "Hello";']);

        self::assertNotSame('', trim($output));
        self::assertStringContainsString('echo', $output);
    }

    #[Test]
    public function itSupportsADynamicLanguageParameter(): void
    {
        $template = $this->twig->createTemplate('{{ code|code_highlight(lang) }}');
        $output = $template->render([
            'code' => 'const answer = 42;',
            'lang' => 'javascript',
        ]);

        self::assertNotSame('', trim($output));
        self::assertStringContainsString('answer', $output);
    }
}
