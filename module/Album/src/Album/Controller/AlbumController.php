<?php

namespace Album\Controller;

use Album\Form\AlbumForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Zend\Debug\Debug;


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
		$form = new AlbumForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost()) {

			$album = new Album();
			$form->setInputFilter($album->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid()) {
				$album->exchangeArray($form->getData());
				//save it

				return $this->redirect()->toRoute('album');
			}
		}
		return array('form' => $form);
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