<?php

namespace Album\Controller;

use Album\Model\Album;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel(array(
			'albums' => array((object)array('id' => 1, 'artist' => 'suoqin', 'title' => 'php book'))
		));
	}

	public function addAction()
	{
	}

	public function editAction()
	{
	}

	public function deleteAction()
	{
		return new ViewModel(array(
			'album' => (object)array('id' => 1, 'artist' => 'suoqin', 'title' => 'php book')
		));
	}
}