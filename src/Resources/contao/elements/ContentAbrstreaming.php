<?php

/**
 *
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2018-2019 POSTYOU
 *
 * @package
 * @author  Markus Nestmann
 * @link    http://www.postyou.de
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Postyou\ContaoABRStreamingBundle;

use Contao\ContentElement;
use Contao\StringUtil;
use Contao\FilesModel;
use Contao\System;
use Contao\Image;
use Contao\File;

class ContentAbrstreaming extends ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_abrstreaming';

    /**
     * Files object
     * @var \Contao\Model\Collection|FilesModel
     */
    protected $objFiles;

    /**
     * Return if there are no files
     *
     * @return string
     */
    public function generate()
    {
        if (!$this->abrs_playerSRC)
        {
            return '';
        }

        $source = StringUtil::deserialize($this->abrs_playerSRC);

        if (empty($source) || !\is_array($source))
        {
            return '';
        }

        $objFiles = FilesModel::findMultipleByUuidsAndExtensions($source, array('mpd', 'm3u8'));

        if ($objFiles === null)
        {
            return '';
        }

        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        // Display a list of files in the back end
		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$return = '<ul>';

			while ($objFiles->next())
			{
				$objFile = new File($objFiles->path);
				$return .= '<li>' . Image::getHtml($objFile->icon, '', 'class="mime_icon"') . ' <span>' . $objFile->name . '</span> <span class="size">(' . $this->getReadableSize($objFile->size) . ')</span></li>';
			}

			$return .= '</ul>';

			if ($this->headline)
			{
				$return = '<' . $this->hl . '>' . $this->headline . '</' . $this->hl . '>' . $return;
			}

			return $return;
		}

        $this->objFiles = $objFiles;

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        /** @var PageModel $objPage */
        global $objPage;

        $this->Template->poster = false;

        // Optional poster
		if ($this->posterSRC && ($objFile = FilesModel::findByUuid($this->posterSRC)) !== null)
		{
			$this->Template->poster = $objFile->path;
		}

        $objFiles = $this->objFiles;

        /** @var FilesModel $objFirst */
        $objFirst = $objFiles->current();

        // Pre-sort the array by preference
        if (\in_array($objFirst->extension, array('mpd','m3u8')))
        {
            $this->Template->containerClass = 'video_container';
            
            $arrFiles = array('mpd'=>null, 'm3u8'=>null);
        }

        $objFiles->reset();

		// Convert the language to a locale (see #5678)
		$strLanguage = str_replace('-', '_', $objPage->language);

		// Pass File objects to the template
		while ($objFiles->next())
		{
			$arrMeta = StringUtil::deserialize($objFiles->meta);

			if (\is_array($arrMeta) && isset($arrMeta[$strLanguage]))
			{
				$strTitle = $arrMeta[$strLanguage]['title'];
			}
			else
			{
				$strTitle = $objFiles->name;
			}

			$objFile = new File($objFiles->path);
			$objFile->title = StringUtil::specialchars($strTitle);

			$arrFiles[$objFile->extension] = $objFile;
		}

        $size = StringUtil::deserialize($this->playerSize);

		if (\is_array($size) && !empty($size[0]) && !empty($size[1]))
		{
			$this->Template->size = ' width="' . $size[0] . '" height="' . $size[1] . '"';
		}
		else
		{
			// $this->size might contain image size data, therefore unset it (see #2351)
			$this->Template->size = '';
		}

        $this->Template->files = array_values(array_filter($arrFiles));

        $attributes = array('controls' => 'controls');
		$options = StringUtil::deserialize($this->playerOptions);

		if (\is_array($options))
		{
			foreach ($options as $option)
			{
				if ($option == 'player_nocontrols')
				{
					unset($attributes['controls']);
				}
				else
				{
					$attributes[substr($option, 7)] = substr($option, 7);
				}
			}
		}

		$this->Template->attributes = $attributes;
		$this->Template->caption = $this->playerCaption;

		if ($this->playerStart || $this->playerStop)
		{
			$range = '#t=';

			if ($this->playerStart)
			{
				$range .= $this->playerStart;
			}

			if ($this->playerStop)
			{
				$range .= ',' . $this->playerStop;
			}

			$this->Template->range = $range;
		}        
    }
}
