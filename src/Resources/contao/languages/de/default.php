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

use Markocupic\BootstrapResponsiveYoutubeEmbed\Controller\ContentElement\BootstrapYoutubeReponsiveEmbedController;

/*
 * Content element
 */
$GLOBALS['TL_LANG']['CTE'][BootstrapYoutubeReponsiveEmbedController::TYPE] = ['Bootstrap Youtube Responsive Video Player', 'Fügen Sie dem Artikel einen Videoplayer hinzu.'];

/*
 * Miscellaneous Backend
 */
$GLOBALS['TL_LANG']['MSC']['brjeBackendPrevievYoutube'] = '<p><a href="//youtu.be/%s" target="_blank">https://youtu.be/%s</a><br>Anzeigeverhältnis: %s<br>CSS-Klasse: %s</p>';
$GLOBALS['TL_LANG']['MSC']['brjeBackendPrevievVimeo'] = '<p><a href="//player.vimeo.com/video/%s" target="_blank">https://player.vimeo.com/video/%s</a><br>Anzeigeverhältnis: %s<br>CSS-Klasse: %s</p>';
$GLOBALS['TL_LANG']['MSC']['brjeBackendPrevievDropbox'] = '<p><a href="//dl.dropbox.com/%s" target="_blank">https://dl.dropbox.com/%s</a><br>Anzeigeverhältnis: %s<br>CSS-Klasse: %s</p>';
