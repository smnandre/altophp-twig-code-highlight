<?php

declare(strict_types=1);

/*
 * This file is part of the ALTO library.
 *
 * © 2026-present Simon André
 *
 * For full copyright and license information, please see
 * the LICENSE file distributed with this source code.
 */

namespace Alto\Twig\CodeHighlight\Tests\Unit\TokenParser;

use Alto\Twig\CodeHighlight\TokenParser\CodeHighlightTokenParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Twig\Token;

#[CoversClass(CodeHighlightTokenParser::class)]
final class CodeHighlightTokenParserTest extends TestCase
{
    #[Test]
    public function itExposesTheExpectedTagName(): void
    {
        $parser = new CodeHighlightTokenParser();

        self::assertSame('code_highlight', $parser->getTag());
    }

    #[Test]
    public function itDetectsTheEndOfACodeHighlightBlock(): void
    {
        $parser = new CodeHighlightTokenParser();
        $endToken = new Token(Token::NAME_TYPE, 'endcode_highlight', 1);
        $otherToken = new Token(Token::NAME_TYPE, 'endif', 1);

        self::assertTrue($parser->decideBlockEnd($endToken));
        self::assertFalse($parser->decideBlockEnd($otherToken));
    }
}
