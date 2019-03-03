<?php

namespace GithubRepoBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use GithubRepoBundle\Entity\Githubrepo;

class GithubRepoManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Githubrepo::class);
    }

    /**
     * Find repo by github githubId
     * @param  string $githubId 
     */
    public function getGithubrepoByGithubId($githubId)
    {
        return $this->repository->findOneBy(['githubId' => $githubId]);
    }

    /**
     * Saves githubRepo to DB
     *
     * @param Githubrepo $githubRepo
     * @return void
     */
    public function saveGithubrepo(Githubrepo $githubRepo)
    {
        try {                
            $this->em->persist($githubRepo);
            if($githubRepo->getId()){
                return $githubRepo;
            }
        }
        catch (Exception $e) {
            //should log it -- no logger in place
        }
    }

    /**
     * Flush persisted 
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * Finds what is the last page stored
     *
     * @return void
     */
    public function getHighestPage()
    {
        return $this->repository->findOneBy(array(),array('page'=>'DESC'),0,1);
    }
}