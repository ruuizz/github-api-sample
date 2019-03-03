<?php

namespace GithubRepoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->enableProfiler();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertContains('PHP Repositories on GitHub', $crawler->filter('.navbar-brand')->text());
        $this->assertContains('Yamel Hall', $crawler->filter('#me')->text());
        $this->assertContains('LinkedIn Page', $crawler->filter('#contact-me')->text());
        $this->assertContains('Most starred public PHP projects', $crawler->filter('h1.jumbotron-heading')->text());
        $this->assertContains('Data is obtained from the GitHub API. ', $crawler->filter('p.lead')->text());

        $this->assertCount(1, $crawler->filter('#php-repos'));

        if ($profile = $client->getProfile()) {
            $this->assertLessThan(
                10,
                $profile->getCollector('db')->getQueryCount()
            );
            $this->assertLessThan(
                500,
                $profile->getCollector('time')->getDuration()
            );
        }
    }
}


