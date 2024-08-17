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

use Markocupic\BootstrapResponsiveYoutubeEmbed\Controller\ContentElement\BootstrapYoutubeResponsiveEmbedController;

/*
 * Content element
 */
$GLOBALS['TL_LANG']['CTE'][BootstrapYoutubeResponsiveEmbedController::TYPE] = ['Bootstrap Youtube Responsive Video Player', 'Fügen Sie dem Artikel einen Videoplayer hinzu.'];

/*
 * Miscellaneous Backend
 */
$GLOBALS['TL_LANG']['MSC']['brjeBackendPreviewYoutube'] = '<p><a href="//youtu.be/%s" target="_blank">https://youtu.be/%s</a><br>Aspect ratio: %s<br>CSS-class: %s</p>';
$GLOBALS['TL_LANG']['MSC']['brjeBackendPreviewVimeo'] = '<p><a href="//player.vimeo.com/video/%s" target="_blank">https://player.vimeo.com/video/%s</a><br>Aspect ratio: %s<br>CSS-class: %s</p>';
$GLOBALS['TL_LANG']['MSC']['brjeBackendPreviewDropbox'] = '<p><a href="//dl.dropbox.com/%s" target="_blank">https://dl.dropbox.com/%s</a><br>Aspect ratio: %s<br>CSS-class: %s</p>';
