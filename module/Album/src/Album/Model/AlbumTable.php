<?php

namespace Album\Model;

class AlbumTable {

	protected $client;

	const TABLE_NAME = 'louis-zend-album';

	public function __construct()
	{
		$dbClient = new \DbClient;
		$this->client = $dbClient->client();
	}

	public function fetchAll()
	{
		$iterator = $this->client->getIterator('Scan', array(
			'TableName' => self::TABLE_NAME
		));

		return array_map(function($value) {
			return (object) array(
				'id' => $value['id']['S'],
				'artist' => $value['artist']['S'],
				'title' => $value['title']['S'],
			);
		}, iterator_to_array($iterator));

	}

	public function save($album)
	{
		$this->client->putItem([
			'TableName' => self::TABLE_NAME,
			'Item' => [
				'id' => ['S' => uniqid()],
				'title'    => ['S' => $album->title],
				'artist'   => ['S' => $album->artist]
			]
		]);
	}


}