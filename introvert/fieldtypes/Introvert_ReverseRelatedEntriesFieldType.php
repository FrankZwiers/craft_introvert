<?php
namespace Craft;

class Introvert_ReverseRelatedEntriesFieldType extends BaseFieldType implements IPreviewableFieldType
{

	public function getName()
	{
		return Craft::t('Reverse Related Entries');
	}

	/**
	 * @inheritDoc IPreviewableFieldType::getTableAttributeHtml()
	 *
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function getTableAttributeHtml($value)
	{
		$relatedElements = $relatedCategories = array();
		$allowedSections = $this->getSettings()->sections;
		$limit = $this->getSettings()->amountOfRelations != '' ? $this->getSettings()->amountOfRelations : 100;

		// these are all we're looking up for now.
		// Users will come later. Tags too I guess.
		$elementTypes = array(
			ElementType::MatrixBlock => '',
			ElementType::Entry => '',
			ElementType::Category => ''
		);

		// shortcut
		$introvert = craft()->introvert_relationship;

		foreach($elementTypes as $key => $value)
		{
			$relatedElements[$key] = $introvert->getRelationships($key, $this->element, $allowedSections, $limit);
		}

		// combine entries and matrix entries
		$relatedEntries = $relatedElements[ElementType::Entry] + $relatedElements[ElementType::MatrixBlock];

		// sort our entries by title
		$introvert->sortRelArray( $relatedEntries );

		return craft()->templates->render(
			'introvert/input_small', array(
				'entries' 	=> $relatedEntries
			)
		);
	}

	public function getInputHtml($name, $value)
	{

		$relatedElements = $relatedCategories = array();
		$allowedSections = $this->getSettings()->sections;
		$limit = $this->getSettings()->amountOfRelations != '' ? $this->getSettings()->amountOfRelations : 100;

		// these are all we're looking up for now.
		// Users will come later. Tags too I guess.
		$elementTypes = array(
			ElementType::MatrixBlock => '',
			ElementType::Entry => '',
			ElementType::Category => ''
		);

		// shortcut
		$introvert = craft()->introvert_relationship;

		foreach($elementTypes as $key => $value)
		{
			$relatedElements[$key] = $introvert->getRelationships($key, $this->element, $allowedSections, $limit);
		}

		// combine entries and matrix entries
		$relatedEntries = $relatedElements[ElementType::Entry] + $relatedElements[ElementType::MatrixBlock];
		$relatedCategories = $relatedElements[ElementType::Category];

		// sort our entries by title
		$introvert->sortRelArray( $relatedEntries );
		$introvert->sortRelArray( $relatedCategories );

		return craft()->templates->render(
			'introvert/input', array(
				'entries' 	=> $relatedEntries,
				'categories' => $relatedCategories,
				'elementType' => $this->element->elementType
			)
		);

	}

	protected function defineSettings()
	{
		return array(
			'sections' => AttributeType::Mixed,
			'amountOfRelations' => AttributeType::Number
		);
	}

	public function getSettingsHtml()
	{

		$sections = craft()->sections->getAllSections();

		$sectionOptions = array();
		foreach($sections as $section)
		{
			$sectionOptions[ $section->id ] = $section->name;
		}

		return craft()->templates->render(
			'introvert/settings', array(
				'settings' 			=> $this->getSettings(),
				'sectionOptions' 	=> $sectionOptions
			)
		);
	}


}

