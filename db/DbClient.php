<?php

use Aws\Sts\StsClient;
use Aws\DynamoDb\DynamoDbClient;


class DbClient
{
	public function client()
	{
		if ($_SERVER['APPLICATION_ENV'] == 'development') {
			return DynamoDbClient::factory(array(
				'region'  => 'ap-southeast-2'
			));
		}
		return $this->stsClient();

	}

	private function stsClient() {
		$sts = StsClient::factory();

		$result = $sts->assumeRole(array(
			'RoleArn' => 'arn:aws:iam::226019795248:role/dynamodb_role', // REQUIRED
			'RoleSessionName' => 'remote_dynamo_client', // REQUIRED
		));

		$credentials = $result->get('Credentials');
		return DynamoDbClient::factory(array(
				'key'    => $credentials['AccessKeyId'],
				'secret' => $credentials['SecretAccessKey'],
				'token'  => $credentials['SessionToken'],
				'region'  => 'ap-southeast-2',
			)
		);
	}

}