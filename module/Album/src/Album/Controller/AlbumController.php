<?php

namespace Album\Controller;

use Album\Form\AlbumForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Model\AlbumTable;


class AlbumController extends AbstractActionController
{
	protected $showDB;

	public function indexAction()
	{
		$albumTable = new AlbumTable();

		return new ViewModel(array(
			'albums' => $albumTable->fetchAll()
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
				$albumTable = new AlbumTable();
				$albumTable->save($album);
				return $this->redirect()->toRoute('album');
			}
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		$id = $this->params()->fromRoute('id', '0');
		if (!$id) {
			return $this->redirect()->toRoute('album', ['action' => 'add']);
		}

		try {
			$albumTable = new AlbumTable();
			$album = $albumTable->get($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('album', ['action' => 'index']);
		}

		$form = new AlbumForm();
		$form->bind($album);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->request;
		if($request->isPost()) {
			$form->setInputFilter($album->getInputFilter());
			$form->setData($request->getPost());
			if($form->isValid()) {
				$albumTable = new AlbumTable();
				$albumTable->save($album);
				return $this->redirect()->toRoute('album');
			}
		}
		return ['id' => $id, 'form' => $form];
	}

	public function deleteAction()
	{
		$id = $this->params()->fromRoute('id', '0');
		if (!$id) {
			return $this->redirect()->toRoute('album');
		}
		$request = $this->request;
		if($request->isPost()) {
			$del = $request->getPost('del', 'No');
			debug_zval_dump($del);
			if($del == 'Yes') {
				$id = $request->getPost('id');
				$albumTable = new AlbumTable();
				$albumTable->remove($id);
			}
			return $this->redirect()->toRoute('album');
		}
		$albumTable = new AlbumTable();
		$album = $albumTable->get($id);
		return ['id' => $id, 'album' => $album];
	}
}