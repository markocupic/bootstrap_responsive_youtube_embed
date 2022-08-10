<?php

declare(strict_types=1);

/*
 * This file is part of Bootstrap Responsive Youtube Embed.
 *
 * (c) Marko Cupic 2022 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/bootstrap_responsive_youtube_embed
 */

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @ContentElement(BootstrapYoutubeReponsiveEmbedController::TYPE, category="media", template="ce_bootstrap_youtube_reponsive_embed")
 */
class BootstrapYoutubeReponsiveEmbedController extends AbstractContentElementController
{
    public const TYPE = 'bootstrapYoutubeReponsiveEmbed';

    // Services
    protected ContaoFramework $contaoFramework;
    protected RequestStack $requestStack;
    protected ScopeMatcher $scopeMatcher;
    protected TranslatorInterface $translator;

    // Adapters
    protected Adapter $stringUtil;

    public function __construct(ContaoFramework $contaoFramework, RequestStack $requestStack, ScopeMatcher $scopeMatcher, TranslatorInterface $translator)
    {
        $this->contaoFramework = $contaoFramework;
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
        $this->translator = $translator;

        // Adapters
        $this->stringUtil = $this->contaoFramework->getAdapter(StringUtil::class);
    }

    public function __invoke(Request $request, ContentModel $model, string $section, array $classes = null, PageModel $pageModel = null): Response
    {
        if (empty($model->movieId)) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Set the size
        if (empty($model->playerAspectRatio)) {
            $model->playerAspectRatio = 'embed-responsive-16by9';
        }

        // Backend preview
        if ($this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest())) {
            $strAspectRatio = $this->translator->trans('tl_content.'.$model->playerAspectRatio, [], 'contao_default');
            $arrCssId = $this->stringUtil->deserialize($model->csssId, true);

            if ('youtube' === $model->playerType) {
                $strResponse = $this->translator->trans(
                    'MSC.brjeBackendPrevievYoutube',
                    [$model->movieId, $model->movieId, $strAspectRatio, $arrCssId[1]],
                    'contao_default',
                );

                return new Response($strResponse);
            }

            if ('vimeo' === $model->playerType) {
                $strResponse = $this->translator->trans(
                    'MSC.brjeBackendPrevievVimeo',
                    [$model->movieId, $model->movieId, $strAspectRatio, $arrCssId[1]],
                    'contao_default',
                );

                return new Response($strResponse);
            }

            if ('dropbox' === $model->playerType) {
                $strResponse = $this->translator->trans(
                    'MSC.brjeBackendPrevievDropbox',
                    [$model->movieId, $model->movieId, $strAspectRatio, $arrCssId[1]],
                    'contao_default',
                );

                return new Response($strResponse);
            }
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response|null
    {
        $template->autoplay = (bool) $model->autoplay;

        return $template->getResponse();
    }
}
