<?php

namespace Album\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Album
{
	public $id;
	public $artist;
	public $title;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->artist = (!empty($data['artist'])) ? $data['artist'] : null;
		$this->title  = (!empty($data['title'])) ? $data['title'] : null;
	}

	public function getArrayCopy()
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'artist' => $this->artist,
		];
	}

	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception("Not used");
	}

	public function getInputFilter(){
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name' => 'artist',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 100,
						),
					)
				)
			));
			$inputFilter->add(array(
				'name' => 'title',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 100,
						),
					)
				)
			));

			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
}