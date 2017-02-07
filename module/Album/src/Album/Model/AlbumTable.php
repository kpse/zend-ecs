<?php

namespace Album\Model;

use Aws\DynamoDb\DynamoDbClient;

class AlbumTable {

	protected $client;

	public function __construct()
	{
		$this->client = DynamoDbClient::factory(array(
			'region'  => 'ap-southeast-2'
		));
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