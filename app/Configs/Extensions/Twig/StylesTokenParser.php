<?php

declare(strict_types=1);

namespace App\Configs\Extensions\Twig;

use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Does nothing but stop content between tags from outputting.
 * Exists purely as a means to make it clear where styles are defined.
 */
class StylesTokenParser extends AbstractTokenParser
{
    /**
     * @param Token $token
     * @return Node
     */
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        // Parse until end tag found as we're not doing anything with the styles in Twig.
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideStylesEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        // Empty node as we're not outputting anything.
        return new Node([], [], $lineno);
    }

    public function decideStylesEnd(Token $token): bool
    {
        return $token->test('endstyles');
    }

    /**
     * Tag name.
     *
     * @return string
     */
    public function getTag(): string
    {
        return 'styles';
    }
}
