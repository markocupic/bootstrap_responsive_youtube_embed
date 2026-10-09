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
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(type: BootstrapYoutubeResponsiveEmbedController::TYPE, category: 'media')]
class BootstrapYoutubeResponsiveEmbedController extends AbstractContentElementController
{
    public const TYPE = 'bootstrap_youtube_responsive_embed';

    public const DEFAULT_ASPECT_RATIO = '16x9';

    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param array<string>|null $classes
     */
    public function __invoke(Request $request, ContentModel $model, string $section, array|null $classes = null): Response
    {
        if (empty($model->movieId)) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        if ($this->scopeMatcher->isBackendRequest($request)) {
            return $this->getBackendPreview($model);
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $template->set('player_type', (string) $model->playerType);
        $template->set('movie_id', (string) $model->movieId);
        $template->set('aspect_ratio', $this->getAspectRatio($model));
        $template->set('autoplay', (bool) $model->autoplay);
        $template->set('caption', (string) $model->caption);

        return $template->getResponse();
    }

    private function getBackendPreview(ContentModel $model): Response
    {
        $messageKeys = [
            'youtube' => 'MSC.brjeBackendPreviewYoutube',
            'vimeo' => 'MSC.brjeBackendPreviewVimeo',
            'dropbox' => 'MSC.brjeBackendPreviewDropbox',
        ];

        $messageKey = $messageKeys[$model->playerType] ?? null;

        if (null === $messageKey) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $movieId = htmlspecialchars((string) $model->movieId, ENT_QUOTES);
        $aspectRatio = $this->translator->trans('tl_content.'.$this->getAspectRatio($model), [], 'contao_default');
        $cssClass = StringUtil::deserialize($model->cssID, true)[1] ?? '';

        return new Response($this->translator->trans(
            $messageKey,
            [$movieId, $movieId, htmlspecialchars($aspectRatio, ENT_QUOTES), htmlspecialchars((string) $cssClass, ENT_QUOTES)],
            'contao_default',
        ));
    }

    private function getAspectRatio(ContentModel $model): string
    {
        return $model->playerAspectRatio ?: self::DEFAULT_ASPECT_RATIO;
    }
}
