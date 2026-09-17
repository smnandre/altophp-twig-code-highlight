# Tag and filter

Use the filter for source provided by your application. Use the block tag when
the source belongs in the template. Both require an explicit language identifier
from [Code Highlight](https://altophp.com/code-highlight/languages/).

## Filter

```twig
{{ source|code_highlight(language, {line_numbers: true, highlight_lines: [1]}) }}
```

`source` is plain, unescaped text. `language` may be a variable or a literal such
as `'php'`. The generated HTML is safe for HTML output; surrounding Twig variables
still follow normal autoescaping rules.

## Block tag

```twig
{% code_highlight language with {line_numbers: true, highlight_lines: [1]} %}
const answer = 42;
{% endcode_highlight %}
```

With `language` set to `'javascript'`, this highlights the declaration and marks
the first line number. Leading and trailing whitespace is trimmed by the runtime,
so the initial newline does not create an extra numbered line. Content inside the
block is literal source; use the filter when supplying dynamic source as a variable.

## Options

| Option | Default | Meaning |
| --- | --- | --- |
| `line_numbers` | `false` | Render a numbered span for every source line. |
| `highlight_lines` | `[]` | Positive integer line numbers whose numbered span receives `alto-highlighted`. |

Per-call values replace matching [configured defaults](configuration.md). A
`highlight_lines` array replaces the default array; it is not appended. Non-array
values become an empty list; only positive integer entries are retained. Set
`line_numbers: true` for the selected line-number styling to be visible.

The runtime trims the source and language. An empty or missing language raises
`InvalidArgumentException`; an unsupported identifier raises the core highlighter's
`LanguageNotFoundException`. Use a supported identifier or render an escaped plain
code block in the application when the language is unknown.
