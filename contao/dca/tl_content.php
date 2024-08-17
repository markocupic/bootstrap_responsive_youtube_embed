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

$GLOBALS['TL_DCA']['tl_content']['palettes'][BootstrapYoutubeResponsiveEmbedController::TYPE] = '
{type_legend},type,headline;
{source_legend},playerType,movieId;
{poster_legend:hide},posterSRC;
{player_legend},playerAspectRatio,caption,autoplay;
{template_legend:hide},customTpl;
{protected_legend:hide},protected;
{expert_legend:hide},cssID;
{invisible_legend:hide},invisible,start,stop
';

/*
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['playerAspectRatio'] = [
    'default'   => 'text',
    'exclude'   => true,
    'filter'    => true,
    'inputType' => 'select',
    'options'   => ['1x1', '4x3', '16x9', '21x9'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval'      => ['helpwizard' => false, 'chosen' => true],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['playerType'] = [
    'default'   => 'text',
    'exclude'   => true,
    'filter'    => true,
    'inputType' => 'select',
    'options'   => ['youtube', 'vimeo', 'dropbox'],
    'eval'      => ['helpwizard' => false, 'chosen' => true],
    'sql'       => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['movieId'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'url', 'mandatory' => true],
    'sql'       => "varchar(512) NOT NULL default ''",
];
