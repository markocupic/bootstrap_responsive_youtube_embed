<?php

declare(strict_types=1);

/*
 * This file is part of Bootstrap Responsive YouTube Embed.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/bootstrap_responsive_youtube_embed
 */

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\Tests\InsertTag;

use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\ResolvedParameters;
use Markocupic\BootstrapResponsiveYoutubeEmbed\InsertTag\BootstrapResponsiveYoutubeEmbedInsertTag;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class BootstrapResponsiveYoutubeEmbedInsertTagTest extends TestCase
{
    public function testUsesYoutubeAndDefaultsForSimpleId(): void
    {
        $this->assertSame(
            [
                'player_type' => 'youtube',
                'movie_id' => 'a7D3A_wwl0g',
                'aspect_ratio' => '16x9',
                'autoplay' => false,
                'caption' => '',
            ],
            $this->resolve('a7D3A_wwl0g'),
        );
    }

    public function testUsesVimeoForNumericId(): void
    {
        $this->assertSame('vimeo', $this->resolve('479750109')['player_type']);
    }

    public function testParsesOptions(): void
    {
        $this->assertSame(
            [
                'player_type' => 'youtube',
                'movie_id' => 'a7D3A_wwl0g',
                'aspect_ratio' => '4x3',
                'autoplay' => true,
                'caption' => 'Lorem ipsum',
            ],
            $this->resolve('a7D3A_wwl0g?autoplay=1&caption=Lorem ipsum&playerAspectRatio=4x3'),
        );
    }

    public function testAcceptsEncodedAmpersands(): void
    {
        $context = $this->resolve('a7D3A_wwl0g?autoplay=true&amp;caption=Lorem ipsum');

        $this->assertTrue($context['autoplay']);
        $this->assertSame('Lorem ipsum', $context['caption']);
    }

    public function testPlayerTypeOptionOverridesDetection(): void
    {
        $this->assertSame('dropbox', $this->resolve('123?playerType=dropbox')['player_type']);
    }

    public function testReturnsEmptyResultWithoutId(): void
    {
        $listener = new BootstrapResponsiveYoutubeEmbedInsertTag($this->createTwig());
        $result = $listener(new ResolvedInsertTag('bootstrapResponsiveYoutubeEmbed', new ResolvedParameters(['']), []));

        $this->assertSame('', $result->getValue());
    }

    /**
     * @return array<string, mixed>
     */
    private function resolve(string $parameter): array
    {
        $listener = new BootstrapResponsiveYoutubeEmbedInsertTag($this->createTwig());
        $result = $listener(new ResolvedInsertTag('bootstrapResponsiveYoutubeEmbed', new ResolvedParameters([$parameter]), []));

        $this->assertSame(OutputType::html, $result->getOutputType());

        return json_decode($result->getValue(), true, 512, JSON_THROW_ON_ERROR);
    }

    private function createTwig(): Environment
    {
        $template = '{{ {player_type: player_type, movie_id: movie_id, aspect_ratio: aspect_ratio, autoplay: autoplay, caption: caption}|json_encode|raw }}';

        return new Environment(new ArrayLoader([BootstrapResponsiveYoutubeEmbedInsertTag::TEMPLATE => $template]));
    }
}
