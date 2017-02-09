<?php

namespace Album\Model;

class AlbumTable {

	protected $client;

	public function __construct()
	{
		$dbClient = new \DbClient;
		$this->client = $dbClient->client();
	}

	public function fetchAll()
	{
		$iterator = $this->client->getIterator('Scan', array(
			'TableName' => 'louis-zend-album'
		));

		return array_map(function($value) {
			return (object) array(
				'id' => $value['id']['S'],
				'artist' => $value['artist']['S'],
				'title' => $value['title']['S'],
			);
		}, iterator_to_array($iterator));

	}



}