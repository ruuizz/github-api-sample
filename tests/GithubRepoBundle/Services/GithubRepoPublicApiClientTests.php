<?php

namespace GithubRepoBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\TestCase;
use GuzzleHttp\Client;
use GithubRepoBundle\Services\GithubRepoPublicApiClient;
use Psr\Log\LoggerInterface;

class GithubRepoPublicApiClientTest extends TestCase
{
    /**
     * @var GithubRepoPublicApiClient
     */
    private $publicApiClient;

    protected function setUp()
    {
        $client = new Client();
        $baseUri = 'http://api.postcodes.io/postcodes/';
        $logger = new LoggerInterface();

        $this->publicApiClient = new GithubRepoPublicApiClient($client, $logger);
    }

    protected function tearDown()
    {
        $this->publicApiClient = null;
    }

}
