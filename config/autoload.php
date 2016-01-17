<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Elements
	'Contao\ContentBootstrapYoutubeResponsiveEmbed' => 'system/modules/bootstrap_responsive_youtube_embed/elements/ContentBootstrapYoutubeResponsiveEmbed.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_bootstrap_youtube_responsive_embed' => 'system/modules/bootstrap_responsive_youtube_embed/templates',
));
