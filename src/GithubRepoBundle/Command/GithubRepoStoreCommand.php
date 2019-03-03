<?php

namespace GithubRepoBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use GithubRepoBundle\Services\GithubRepoPublicApiClient;
use GithubRepoBundle\Services\GithubRepoManager;
use GithubRepoBundle\Entity\Githubrepo;
use Psr\Log\LoggerInterface;

class GithubRepoStoreCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'github-repo:store-search-results';

    /**
     * @var GithubRepoPublicApiClient
     */
    private $githubPubApiClient;

    /**
     * @var GithubRepoManager
     */
    private $githubManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param GithubRepoPublicApiClient $githubPubApiClient
     */
    public function __construct(
        GithubRepoPublicApiClient $githubPubApiClient, 
        GithubRepoManager $githubManager,
        LoggerInterface $logger
        )
    {
        parent::__construct();
        $this->githubPubApiClient = $githubPubApiClient;
        $this->githubManager = $githubManager;
        $this->logger = $logger;
    }

    /**
     * Command configs
     *
     * @return void
     */
    protected function configure()
    {
        $this->setDescription('Searches a repository')
            ->setHelp('Prints to console the result count from a search to the API')
            ->addArgument('keyword', InputArgument::REQUIRED, 'The keyword for the search.')
            ;
    }

    /**
     * Command exec
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Store new copy of repo triggered. Search to store: $key',
            ['key' => $input->getArgument('keyword')]
        );

        $output->writeln('Search for keyword: '.$input->getArgument('keyword'));
        $output->writeln(['============',
            '',
        ]);
    
         /* GitHub Limit Rate: Only the first 1000 search results are available*/
         /* if that limitation wasn't there we could work this command in a automated schedule*/
         
        $execNextTen = $this->storeNextTenPages($input, $output);

        $pages = ceil($this->githubPubApiClient->body->total_count / 100);
        $output->writeln('Found repos: '. $this->githubPubApiClient->body->total_count);
        $output->writeln('Total Number of pages: '. $pages);
        $output->writeln('Storing to db execution time: '. $execNextTen['executionTime'] . ' secs');
    }

    /**
     * Makes the 10 allowed API calls to the repo & stores the valus into the db.
     *
     * @return void
     */
    private function storeNextTenPages($input, $output){
        /** github public API has a rate limit of 10 requests per minute */

         /* This would allow us to have more pages searched, GitHub limit is only to receive 1000 results. */
         /* $page = $this->githubManager->getHighestPage() ? $this->githubManager->getHighestPage()->getPage() : 1; */
        
        $page = 1;
        $per_page = 100;

        $time_start = microtime(true); 
        for ($x = $page; $x < ($page + 10); $x++) {
            $output->writeln('Processing page: ' .  $x );

            $this->githubPubApiClient->searchRepos($input->getArgument('keyword'), $x, $per_page);

            $store_time_start = microtime(true); 
            foreach ($this->githubPubApiClient->body->items as $repo) {
                $githubRepo = new Githubrepo();
                if ($record = $this->githubManager->getGithubrepoByGithubId($repo->id)) {
                    $githubRepo = $record;
                }

                $githubRepo->setGithubId($repo->id);
                $githubRepo->setFullName($repo->full_name);
                $githubRepo->setDescription($repo->description);
                $githubRepo->setStargazersCount($repo->stargazers_count);
                $githubRepo->setUrl($repo->html_url);
                $githubRepo->setDateCreated(new \DateTime($repo->created_at));
                $githubRepo->setLastPush(new \DateTime($repo->pushed_at));
                $githubRepo->setPage($x);
    
                $this->githubManager->saveGithubrepo($githubRepo);
                $this->githubManager->flush();

            }
        } 


        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        $this->logger->info('Stored 1000 entries from GitHubRepo. Total exec time: $time secs.', 
            ['time' => $execution_time]
        );
        $this->logger->info('Store new copy of repo ended.');

        return ['executionTime' => $execution_time];
    }
}