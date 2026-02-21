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

namespace Alto\Twig\CodeHighlight\Tests\Unit\Runtime;

use Alto\Code\Highlight\Highlighter;
use Alto\Code\Highlight\Theme\AltoTheme;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(CodeHighlightRuntime::class)]
final class CodeHighlightRuntimeTest extends TestCase
{
    #[Test]
    public function itThrowsWhenLanguageIsMissing(): void
    {
        $runtime = new CodeHighlightRuntime(new Highlighter(new AltoTheme()));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Language parameter is required');

        $runtime->highlight('<?php echo "Hello";');
    }

    #[Test]
    public function itIgnoresNonArrayHighlightLines(): void
    {
        $runtime = new CodeHighlightRuntime(new Highlighter(new AltoTheme()));

        $output = $runtime->highlight('<?php echo "Hello";', 'php', [
            'line_numbers' => true,
            'highlight_lines' => 'invalid',
        ]);

        self::assertStringContainsString('alto-line-number', $output);
        self::assertStringNotContainsString('alto-highlighted', $output);
    }

    #[Test]
    public function itOnlyKeepsPositiveIntegerHighlightLines(): void
    {
        $runtime = new CodeHighlightRuntime(new Highlighter(new AltoTheme()));

        $output = $runtime->highlight("<?php\necho \"Hello\";", 'php', [
            'line_numbers' => true,
            'highlight_lines' => [1, '2', 0, -1],
        ]);

        self::assertStringContainsString('alto-highlighted', $output);
    }
}
