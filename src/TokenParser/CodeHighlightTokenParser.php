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

namespace Alto\Twig\CodeHighlight\TokenParser;

use Alto\Twig\CodeHighlight\Node\CodeHighlightNode;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Parses {% code_highlight %} blocks into CodeHighlightNode instances.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final class CodeHighlightTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();
        $lineno = $token->getLine();

        $language = null;
        if (!$stream->test(Token::BLOCK_END_TYPE) && !$stream->test(Token::NAME_TYPE, 'with')) {
            $language = $this->parser->parseExpression();
        }

        $options = null;
        if ($stream->nextIf(Token::NAME_TYPE, 'with')) {
            $options = $this->parser->parseExpression();
        }

        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new CodeHighlightNode($body, $language, $options, $lineno);
    }

    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endcode_highlight');
    }

    public function getTag(): string
    {
        return 'code_highlight';
    }
}
