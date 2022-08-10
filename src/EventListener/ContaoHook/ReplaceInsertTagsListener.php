<?php

declare(strict_types=1);

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\EventListener\ContaoHook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\FrontendTemplate;

#[AsHook('replaceInsertTags')]
class ReplaceInsertTagsListener extends \System
{
    public function __invoke($strTag): bool|string
    {
        if (str_contains($strTag, 'bootstrapResponsiveYoutubeEmbed')) {
            $arrPieces = explode('::', $strTag);
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
            $objTemplate->playerAspectRatio = 'embed-responsive-4by3';

            foreach ($n as $prop) {
                $pieces = explode('=', $prop);
                $objTemplate->{$pieces[0]} = $pieces[1];
            }

            return $objTemplate->parse();
        }

        return false;
    }
}
