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

namespace Alto\Twig\CodeHighlight\Node;

use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\CaptureNode;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;
use Twig\Node\NodeOutputInterface;

/**
 * Compiles {% code_highlight %} blocks.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
#[YieldReady]
final class CodeHighlightNode extends Node implements NodeOutputInterface
{
    public function __construct(
        Node $body,
        ?AbstractExpression $language,
        ?AbstractExpression $options,
        int $lineno,
    ) {
        $nodes = ['body' => $body];

        if (null !== $language) {
            $nodes['language'] = $language;
        }

        if (null !== $options) {
            $nodes['options'] = $options;
        }

        parent::__construct($nodes, [], $lineno);
    }

    public function compile(Compiler $compiler): void
    {
        $capture = new CaptureNode($this->getNode('body'), $this->getTemplateLine());
        $capture->setAttribute('raw', true);

        $compiler
            ->addDebugInfo($this)
            ->write('$code = ')
            ->subcompile($capture)
            ->raw("\n")
            ->write('yield $this->env->getRuntime(')
            ->string(CodeHighlightRuntime::class)
            ->raw(')->highlight($code, ')
        ;

        if ($this->hasNode('language')) {
            $compiler->subcompile($this->getNode('language'));
        } else {
            $compiler->raw('null');
        }

        $compiler->raw(', ');

        if ($this->hasNode('options')) {
            $compiler->subcompile($this->getNode('options'));
        } else {
            $compiler->raw('[]');
        }

        $compiler->raw(");\n");
    }
}
