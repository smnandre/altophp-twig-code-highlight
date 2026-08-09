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

namespace Alto\Twig\CodeHighlight\Tests\Unit;

use Alto\Code\Highlight\Highlighter;
use Alto\Code\Highlight\Theme\AltoTheme;
use Alto\Twig\CodeHighlight\CodeHighlightExtension;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Alto\Twig\CodeHighlight\TokenParser\CodeHighlightTokenParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CodeHighlightExtension::class)]
#[UsesClass(CodeHighlightTokenParser::class)]
#[UsesClass(CodeHighlightRuntime::class)]
final class CodeHighlightExtensionTest extends TestCase
{
    #[Test]
    public function itRegistersTheCodeHighlightTokenParser(): void
    {
        $extension = new CodeHighlightExtension(new Highlighter(new AltoTheme()));

        $parsers = $extension->getTokenParsers();

        self::assertCount(1, $parsers);
        self::assertInstanceOf(CodeHighlightTokenParser::class, $parsers[0]);
    }

    #[Test]
    public function itRegistersTheCodeHighlightFilter(): void
    {
        $extension = new CodeHighlightExtension(new Highlighter(new AltoTheme()));

        $filters = $extension->getFilters();

        self::assertCount(1, $filters);
        self::assertSame('code_highlight', $filters[0]->getName());
        self::assertSame([CodeHighlightRuntime::class, 'highlight'], $filters[0]->getCallable());
    }

    #[Test]
    public function itExposesDefaultOptions(): void
    {
        $options = ['line_numbers' => true, 'highlight_lines' => [3]];
        $extension = new CodeHighlightExtension(new Highlighter(new AltoTheme()), $options);

        self::assertSame($options, $extension->getDefaultOptions());
    }

    #[Test]
    public function itReturnsTheConfiguredHighlighter(): void
    {
        $highlighter = new Highlighter(new AltoTheme());
        $extension = new CodeHighlightExtension($highlighter);

        self::assertSame($highlighter, $extension->getHighlighter());
    }
}
