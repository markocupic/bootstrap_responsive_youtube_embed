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

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(type: BootstrapYoutubeResponsiveEmbedController::TYPE, category: 'media')]
class BootstrapYoutubeResponsiveEmbedController extends AbstractContentElementController
{
    public const TYPE = 'bootstrap_youtube_responsive_embed';

    protected Adapter $stringUtil;

    public function __construct(
        private readonly ContaoFramework $contaoFramework,
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TranslatorInterface $translator,
    ) {
        $this->stringUtil = $this->contaoFramework->getAdapter(StringUtil::class);
    }

    public function __invoke(Request $request, ContentModel $model, string $section, array|null $classes = null): Response
    {
        if (empty($model->movieId)) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Set the size
        if (empty($model->playerAspectRatio)) {
            $model->playerAspectRatio = '16x9';
            ///$template->set('playerAspectRatio', $model->playerAspectRatio);
        }

        // Backend preview
        if ($this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest())) {
            $strAspectRatio = $this->translator->trans('tl_content.'.$model->playerAspectRatio, [], 'contao_default');
            $arrCssId = $this->stringUtil->deserialize($model->cssID, true);

            if ('youtube' === $model->playerType) {
                $strResponse = $this->translator->trans(
                    'MSC.brjeBackendPreviewYoutube',
                    [$model->movieId, $model->movieId, $strAspectRatio, $arrCssId[1]],
                    'contao_default',
                );

                return new Response($strResponse);
            }

            if ('vimeo' === $model->playerType) {
                $strResponse = $this->translator->trans(
                    'MSC.brjeBackendPreviewVimeo',
                    [$model->movieId, $model->movieId, $strAspectRatio, $arrCssId[1]],
                    'contao_default',
                );

                return new Response($strResponse);
            }

            if ('dropbox' === $model->playerType) {
                $strResponse = $this->translator->trans(
                    'MSC.brjeBackendPreviewDropbox',
                    [$model->movieId, $model->movieId, $strAspectRatio, $arrCssId[1]],
                    'contao_default',
                );

                return new Response($strResponse);
            }
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $template->setData(array_merge($model->row(), $template->getData()));
        $template->set('autoplay', (bool) $model->autoplay);

        return $template->getResponse();
    }
}
