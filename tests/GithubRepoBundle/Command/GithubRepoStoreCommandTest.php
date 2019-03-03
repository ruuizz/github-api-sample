<?php

namespace GithubRepoBundle\Tests\Command;

use GithubRepoBundle\Command\GithubRepoStoreCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use GithubRepoBundle\Entity\Githubrepo;

class GithubRepoStoreCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('github-repo:store-search-results');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command'  => $command->getName(),
            'keyword' => 'php',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Processing page: 1', $output);
        $this->assertContains('Processing page: 2', $output);
        $this->assertContains('Processing page: 3', $output);
        $this->assertContains('Processing page: 4', $output);
        $this->assertContains('Processing page: 5', $output);
        $this->assertContains('Processing page: 6', $output);
        $this->assertContains('Processing page: 7', $output);
        $this->assertContains('Processing page: 8', $output);
        $this->assertContains('Processing page: 9', $output);
        $this->assertContains('Processing page: 10', $output);

        $this->assertContains('Found repos:', $output);
        $this->assertContains('Total Number of pages:', $output);
        $this->assertContains('Storing to db execution time:', $output);
    }
}


