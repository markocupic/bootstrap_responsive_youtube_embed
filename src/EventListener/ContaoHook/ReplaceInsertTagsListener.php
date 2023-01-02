<?php

declare(strict_types=1);

/*
 * This file is part of Bootstrap Responsive Youtube Embed.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/bootstrap_responsive_youtube_embed
 */

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\EventListener\ContaoHook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\FrontendTemplate;

#[AsHook('replaceInsertTags')]
class ReplaceInsertTagsListener
{
    public function __invoke(string $insertTag, bool $useCache, string $cachedValue, array $flags, array $tags, array $cache, int $_rit, int $_cnt)
    {

        if (str_contains($insertTag, 'bootstrapResponsiveYoutubeEmbed')) {
            $arrPieces = explode('::', $insertTag);
            $n = [];

            if (!str_contains($arrPieces[1], '?')) {
                $id = $arrPieces[1];
            } else {
                $m = explode('?', $arrPieces[1]);
                $id = $m[0];
                $n = explode('&', $m[1]);
            }

            if (empty($id)) {
                return false;
            }

            $objTemplate = new FrontendTemplate('ce_bootstrap_youtube_responsive_embed');
            $objTemplate->movieId = $id;
            $objTemplate->playerType = (int) $id ? 'vimeo' : 'youtube';
            $objTemplate->playerAspectRatio = '16x9';
            $objTemplate->autoplay = false;
            $objTemplate->caption = '';

            foreach ($n as $prop) {
                $pieces = explode('=', $prop);
                if($pieces[0] === 'autoplay')
                {
                    if(isset($pieces[1]) && ($pieces[1] === 'true' || $pieces[1] === '1'))
                    {
                        $objTemplate->autoplay = true;
                    }
                }else{
                    $objTemplate->{$pieces[0]} = $pieces[1];
                }
            }

            return $objTemplate->parse();
        }

        return false;
    }
}
