<?php

namespace GithubRepoBundle\Services;

use Psr\Log\LoggerInterface;

class GithubRepoPublicApiClient
{
    /**
     * @var GuzzleHttp\Client
     */
    private $guzzleClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var obj
     */
    public $body;

    /**
     * @var string
     */
    public $statusCode;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(\GuzzleHttp\Client $guzzleClient, LoggerInterface $logger) 
    {
        $this->guzzleClient = $guzzleClient;
        $this->logger = $logger;
    }

    /**
     * Search Repos, default sort is by starts/desc
     *
     * @return void
     */
    public function searchRepos($q, $page = 1, $per_page = 100, $sort = 'stars', $order = 'desc' ) 
    {
        $query = [  'q' => $q,
                    'sort' => $sort,
                    'order' => $order,
                    'page' => $page,
                    'per_page' => $per_page
                ];

        $client = $this->guzzleClient->get('/search/repositories', ['query' => $query]);
        $this->statusCode = $client->getStatusCode();

        if($this->statusCode != '200') {
            //Guzzle handles the error - maybe I can add some other error handling
            $this->logger->debug('Received $statusCode', ['statusCode' => $this->statusCode]);
        }

        $this->body = json_decode($client->getBody()->getContents());
    }
}