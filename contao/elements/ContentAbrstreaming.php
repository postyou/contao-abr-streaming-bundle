<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle.
 *
 * (c) POSTYOU Werbeagentur
 *
 * @license MIT
 */

namespace Postyou\ContaoABRStreamingBundle;

use Contao\ContentElement;
use Contao\CoreBundle\Util\LocaleUtil;
use Contao\File;
use Contao\FilesModel;
use Contao\Image;
use Contao\Model\Collection;
use Contao\StringUtil;
use Contao\System;

class ContentAbrstreaming extends ContentElement
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_abrstreaming';

    /**
     * Files object.
     *
     * @var Collection|FilesModel
     */
    protected $objFiles;

    /**
     * Return if there are no files.
     *
     * @return string
     */
    public function generate()
    {
        if (!$this->playerSRC) {
            return '';
        }

        $source = StringUtil::deserialize($this->playerSRC);

        if (empty($source) || !\is_array($source)) {
            return '';
        }

        $objFiles = FilesModel::findMultipleByUuidsAndExtensions($source, ['mp4', 'm4v', 'mov', 'wmv', 'webm', 'ogv', 'm4a', 'mp3', 'wma', 'mpeg', 'wav', 'ogg', 'm3u8', 'mpd']);

        if (null === $objFiles) {
            return '';
        }

        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        // Display a list of files in the back end
        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $return = '<ul>';

            while ($objFiles->next()) {
                $objFile = new File($objFiles->path);
                $return .= '<li>'.Image::getHtml($objFile->icon, '', 'class="mime_icon"').' <span>'.$objFile->name.'</span> <span class="size">('.$this->getReadableSize($objFile->size).')</span></li>';
            }

            $return .= '</ul>';

            if ($this->headline) {
                $return = '<'.$this->hl.'>'.$this->headline.'</'.$this->hl.'>'.$return;
            }

            return $return;
        }

        $this->objFiles = $objFiles;

        return parent::generate();
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        // @var PageModel $objPage
        global $objPage;

        $this->Template->poster = false;

        // Optional poster
        if ($this->posterSRC && ($objFile = FilesModel::findByUuid($this->posterSRC)) !== null) {
            $this->Template->poster = $objFile->path;
        }

        $objFiles = $this->objFiles;

        /** @var FilesModel $objFirst */
        $objFirst = $objFiles->current();

        // Pre-sort the array by preference
        if (\in_array($objFirst->extension, ['mp4', 'm4v', 'mov', 'wmv', 'webm', 'ogv', 'm3u8', 'mpd'], true)) {
            $this->Template->isVideo = true;
            $this->Template->containerClass = 'video_container';

            $arrFiles = ['webm' => null, 'mp4' => null, 'm4v' => null, 'mov' => null, 'wmv' => null, 'ogv' => null, 'm3u8' => null, 'mpd' => null];
        } else {
            $this->Template->isVideo = false;
            $this->Template->containerClass = 'audio_container';

            $arrFiles = ['m4a' => null, 'mp3' => null, 'wma' => null, 'mpeg' => null, 'wav' => null, 'ogg' => null];
        }

        // Convert the language to a locale (see #5678)
        $strLanguage = LocaleUtil::formatAsLocale($objPage->language);
        $strCaption = $this->playerCaption;

        // Pass File objects to the template
        foreach ($objFiles as $objFileModel) {
            /** @var FilesModel $objFileModel */
            $objMeta = $objFileModel->getMetadata($strLanguage);
            $strTitle = null;

            if (null !== $objMeta) {
                $strTitle = $objMeta->getTitle();

                if (empty($strCaption)) {
                    $strCaption = $objMeta->getCaption();
                }
            }

            $objFile = new File($objFileModel->path);
            $objFile->title = StringUtil::specialchars($strTitle ?: $objFile->name);

            $arrFiles[$objFile->extension] = $objFile;
        }

        $size = StringUtil::deserialize($this->playerSize);

        if (\is_array($size) && !empty($size[0]) && !empty($size[1])) {
            $this->Template->size = ' width="'.$size[0].'" height="'.$size[1].'"';
        } else {
            // $this->size might contain image size data, therefore unset it (see #2351)
            $this->Template->size = '';
        }

        $this->Template->files = array_values(array_filter($arrFiles));

        $attributes = ['controls' => 'controls'];
        $options = StringUtil::deserialize($this->playerOptions);

        if (\is_array($options)) {
            foreach ($options as $option) {
                if ('player_nocontrols' === $option) {
                    unset($attributes['controls']);
                } else {
                    $attributes[substr($option, 7)] = substr($option, 7);
                }
            }
        }

        if ($this->playerSetup) {
            $attributes['player_setup'] = 'data-setup=\''.StringUtil::specialcharsAttribute($this->playerSetup).'\'';
        }

        $this->Template->attributes = $attributes;
        $this->Template->preload = $this->playerPreload;
        $this->Template->caption = $strCaption;

        if ($this->playerStart || $this->playerStop) {
            $range = '#t=';

            if ($this->playerStart) {
                $range .= $this->playerStart;
            }

            if ($this->playerStop) {
                $range .= ','.$this->playerStop;
            }

            $this->Template->range = $range;
        }
    }
}
