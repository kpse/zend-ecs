<?php

namespace Album\Model;

use Aws\Sts\StsClient;
use Aws\DynamoDb\DynamoDbClient;


class AlbumTable {

	protected $client;

	public function __construct()
	{
		$this->client = $this->client();
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

	public function client()
	{
		if ($_SERVER['APPLICATION_ENV'] == 'development') {
			return DynamoDbClient::factory(array(
				'region'  => 'ap-southeast-2'
			));
		}
		return $this->stsClient();

	}

	public function stsClient() {
		$sts = StsClient::factory();

		$result = $sts->assumeRole([
			'RoleArn' => 'arn:aws:iam::226019795248:role/dynamodb_role', // REQUIRED
			'RoleSessionName' => 'remote_dynamo_client', // REQUIRED
		]);

		$credentials = $result->get('Credentials');
		return DynamoDbClient::factory([
			'key'    => $credentials['AccessKeyId'],
			'secret' => $credentials['SecretAccessKey'],
			'token'  => $credentials['SessionToken'],
			'region'  => 'ap-southeast-2',
		]
	);
	}


}