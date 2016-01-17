<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Front end content element "download".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentBootstrapYoutubeResponsiveEmbed extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_bootstrap_youtube_responsive_embed';


	/**
	 * Extend the parent method
	 *
	 * @return string
	 */
	public function generate()
	{
		if ($this->youtube == '')
		{
			return '';
		}

		if (TL_MODE == 'BE')
		{
			return '<p><a href="http://youtu.be/' . $this->youtube . '" target="_blank">http://youtu.be/' . $this->youtube . '</a><br>Anzeigeverh&auml;ltnis: ' . $GLOBALS['TL_LANG']['tl_content'][$this->playerAspectRatio] . '</p>';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->Template->size = '';

		// Set the size
		if ($this->playerAspectRatio == '')
		{
			$this->playerAspectRatio = 'embed-responsive-16by9';
		}

		$this->Template->poster = false;

		// Optional poster
		if ($this->posterSRC != '')
		{
			if (($objFile = \FilesModel::findByUuid($this->posterSRC)) !== null)
			{
				$this->Template->poster = $objFile->path;
			}
		}

		//$objFile = new \stdClass();
		//$objFile->mime = 'video/x-youtube';
		//$objFile->path = '//www.youtube.com/watch?v=' . $this->youtube;
		//$this->Template->files = array($objFile);
		$this->Template->autoplay = $this->autoplay;
		$this->Template->isVideo = true;


	}
}