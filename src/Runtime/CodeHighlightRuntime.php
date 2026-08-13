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

namespace Alto\Twig\CodeHighlight\Runtime;

use Alto\Code\Highlight\Highlighter;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Transforms code snippets and options into highlighted HTML output.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final class CodeHighlightRuntime implements RuntimeExtensionInterface
{
    /**
     * @param array<string, mixed> $defaultOptions
     */
    public function __construct(
        private readonly Highlighter $highlighter,
        private readonly array $defaultOptions = [],
    ) {}

    /**
     * @param array<string, mixed> $options
     */
    public function highlight(string $code, ?string $language = null, array $options = []): string
    {
        if (null === $language || '' === trim($language)) {
            throw new \InvalidArgumentException('Language parameter is required. Auto-detection coming in v2.0.');
        }

        $mergedOptions = array_merge($this->defaultOptions, $options);
        $lineNumbers = (bool) ($mergedOptions['line_numbers'] ?? false);
        $rawHighlightLines = $mergedOptions['highlight_lines'] ?? [];
        if (!\is_array($rawHighlightLines)) {
            $rawHighlightLines = [];
        }

        $highlightLines = [];
        foreach ($rawHighlightLines as $line) {
            if (\is_int($line) && $line > 0) {
                $highlightLines[] = $line;
            }
        }

        return $this->highlighter->highlight(
            trim($code),
            trim($language),
            $lineNumbers,
            $highlightLines,
        );
    }
}
