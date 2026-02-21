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

namespace Alto\Twig\CodeHighlight;

use Alto\Code\Highlight\Highlighter;
use Alto\Code\Highlight\Theme\AltoTheme;
use Alto\Twig\CodeHighlight\Runtime\CodeHighlightRuntime;
use Alto\Twig\CodeHighlight\TokenParser\CodeHighlightTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Registers the Twig tag and filter that expose code highlighting.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final class CodeHighlightExtension extends AbstractExtension
{
    private readonly Highlighter $highlighter;

    /**
     * @param array<string, mixed> $defaultOptions
     */
    public function __construct(
        ?Highlighter $highlighter = null,
        private readonly array $defaultOptions = [],
    ) {
        $this->highlighter = $highlighter ?? new Highlighter(new AltoTheme());
    }

    public function getTokenParsers(): array
    {
        return [new CodeHighlightTokenParser()];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'code_highlight',
                [CodeHighlightRuntime::class, 'highlight'],
                ['is_safe' => ['html']],
            ),
        ];
    }

    public function getHighlighter(): Highlighter
    {
        return $this->highlighter;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefaultOptions(): array
    {
        return $this->defaultOptions;
    }
}
