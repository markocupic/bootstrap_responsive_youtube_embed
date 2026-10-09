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

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;
use Markocupic\BootstrapResponsiveYoutubeEmbed\Controller\ContentElement\BootstrapYoutubeResponsiveEmbedController;
use Twig\Environment;

/**
 * Renders a video player, e.g.:
 * {{bootstrapResponsiveYoutubeEmbed::a7D3A_wwl0g?autoplay=1&caption=Lorem ipsum&playerAspectRatio=4x3}}.
 *
 * Numeric ids are treated as Vimeo videos, all other ids as YouTube videos,
 * unless the playerType option is set.
 */
#[AsInsertTag('bootstrapResponsiveYoutubeEmbed')]
class BootstrapResponsiveYoutubeEmbedInsertTag implements InsertTagResolverNestedResolvedInterface
{
    public const TEMPLATE = '@Contao/component/_bootstrap_youtube_responsive_embed.html.twig';

    public function __construct(private readonly Environment $twig)
    {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $parameter = (string) $insertTag->getParameters()->get(0);
        [$movieId, $query] = array_pad(explode('?', $parameter, 2), 2, '');

        if ('' === $movieId) {
            return new InsertTagResult('');
        }

        $options = $this->parseOptions($query);

        $html = $this->twig->render(self::TEMPLATE, [
            'player_type' => $options['playerType'] ?? (ctype_digit($movieId) ? 'vimeo' : 'youtube'),
            'movie_id' => $movieId,
            'aspect_ratio' => $options['playerAspectRatio'] ?? BootstrapYoutubeResponsiveEmbedController::DEFAULT_ASPECT_RATIO,
            'autoplay' => \in_array($options['autoplay'] ?? '', ['1', 'true'], true),
            'caption' => $options['caption'] ?? '',
        ]);

        return new InsertTagResult($html, OutputType::html);
    }

    /**
     * Parses "autoplay=1&caption=Lorem ipsum" into an array. The values are not
     * URL-decoded, so that captions may contain spaces. "&amp;" is accepted as
     * separator as well, because the rich text editor encodes ampersands.
     *
     * @return array<string, string>
     */
    private function parseOptions(string $query): array
    {
        $options = [];

        foreach (explode('&', str_replace('&amp;', '&', $query)) as $pair) {
            if ('' === $pair) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
            $options[$key] = $value;
        }

        return $options;
    }
}
