<?php

namespace Postyou\ContaoABRStreamingBundle;

class ContentAbrstreaming extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_abrstreaming';

	/**
	 * Files object
	 * @var Model\Collection|FilesModel
	 */
    protected $objFiles;
    
    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);       
    }

	/**
	 * Return if there are no files
	 *
	 * @return string
	 */
	public function generate()
	{
		if ($this->abrs_playerSRC == '')
		{
			return '';
		}
		$source = \StringUtil::deserialize($this->abrs_playerSRC);

		if (empty($source) || !\is_array($source))
		{
			return '';
		}

		$objFiles = \FilesModel::findMultipleByUuidsAndExtensions($source, array('mpd','mp4', 'm4v', 'mov', 'wmv', 'webm', 'ogv'));

		if ($objFiles === null)
		{
			return '';
		}

		// Display a list of files in the back end
		if (TL_MODE == 'BE')
		{
			$return = '<ul>';

			while ($objFiles->next())
			{
				$objFile = new \File($objFiles->path);
				$return .= '<li>' . \Image::getHtml($objFile->icon, '', 'class="mime_icon"') . ' <span>' . $objFile->name . '</span> <span class="size">(' . $this->getReadableSize($objFile->size) . ')</span></li>';
			}

			return $return . '</ul>';
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

		$objFiles = $this->objFiles;

		/** @var FilesModel $objFirst */
		$objFirst = $objFiles->current();

		// Pre-sort the array by preference
		if (\in_array($objFirst->extension, array('mpd','mp4', 'm4v', 'mov', 'wmv', 'webm', 'ogv')))
		{
			$arrFiles = array('mpd'=>null, 'mp4'=>null, 'm4v'=>null, 'mov'=>null, 'wmv'=>null, 'webm'=>null, 'ogv'=>null);
		}

		$objFiles->reset();

		// Convert the language to a locale (see #5678)
		$strLanguage = str_replace('-', '_', $objPage->language);

		// Pass File objects to the template
		while ($objFiles->next())
		{
			$arrMeta = \StringUtil::deserialize($objFiles->meta);

			if (\is_array($arrMeta) && isset($arrMeta[$strLanguage]))
			{
				$strTitle = $arrMeta[$strLanguage]['title'];
			}
			else
			{
				$strTitle = $objFiles->name;
			}

			$objFile = new \File($objFiles->path);
			$objFile->title = \StringUtil::specialchars($strTitle);

			$arrFiles[$objFile->extension] = $objFile;
		}

		$size = \StringUtil::deserialize($this->abrs_playerSize);

		if (!\is_array($size) || empty($size[0]) || empty($size[1]))
		{
			
			$this->Template->size = ' width="640" height="360"';		
		}
		else
		{
			$this->Template->size = ' width="' . $size[0] . '" height="' . $size[1] . '"';
		}

		$this->Template->files = array_values(array_filter($arrFiles));
		$this->Template->autoplay = $this->abrs_autoplay;
	}
}
