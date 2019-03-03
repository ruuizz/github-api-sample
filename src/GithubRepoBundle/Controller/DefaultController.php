<?php

namespace GithubRepoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use GithubRepoBundle\Services\GithubRepoPublicApiClient;
use GithubRepoBundle\Entity\Githubrepo;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Doctrine\ORM\QueryBuilder;

/**
 * @Route(service="github_repo.default_controller")
 */
class DefaultController extends Controller
{
    use DataTablesTrait;

    /**
     * @Route("/", name="default")
     *
     * @param Request $request
     * @return void
     */
     public function indexAction(Request $request, LoggerInterface $logger)
    {
        $githubRepo = new Githubrepo();
        $table = $this->createDataTable()
        ->add('fullName', 
            TextColumn::class, [
                'label' => 'Repository Name', 
                'className' => 'repo-name',
                'raw' => TRUE,
                'render' => function($value, Githubrepo $context){
                    $link = $context->getUrl();
                    return "<strong><a href='$link'>$value</a></strong>";
                }
            ])

        ->add('stargazersCount', 
            TextColumn::class, [
                'label' => 'Stargazers', 
                'render' => function($value, Githubrepo $context){
                    return "<strong>" . (int) (ceil($value / 1000)) . "k</strong>";
                }
            ])

        ->add('description', 
            TextColumn::class, [
                'label' => 'Repository Description', 
            ])
    
        ->add('buttons', 
            TextColumn::class, [
                'label' => 'More Information', 
                'render' => function($value, Githubrepo $context){
                    $id = $context->getId();
                    $data = json_encode($context, JSON_HEX_QUOT | JSON_HEX_TAG);
                    $data = base64_encode($data);
                    return "<a href='#appModal' data-toggle='modal' data-context=$data onclick='showDetails($id)' class='btn btn-info repo-details repo-details-$id'>Show Info</a>";
                }
            ])
        
        ->createAdapter(ORMAdapter::class, [
            'entity' => Githubrepo::class,
            'query' => function (QueryBuilder $builder) {
                $builder
                    ->select('g')
                    ->from(Githubrepo::class, 'g');
                }
            ])

        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('@GithubRepoBundle/Default/index.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/ajax-refresh-data", name="ajax-refresh-data", options={"expose"=true})
     *
     * @param KernelInterface $kernel
     * @return void
     */
    public function refreshDataAction(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
           'command' => 'github-repo:store-search-results',
           'keyword' => 'php',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();

        $response = new JsonResponse($content, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax-repo-details/{data}", name="ajax-repo-details", options={"expose"=true})
     *
     * @return void
     */
    public function getRepoDetails(Request $request, $data = NULL) {
        $template = '@GithubRepoBundle/Default/details.html.twig';
        if ( $request->getMethod() == 'POST') {
            $body = $request->getContent();
            $data = base64_decode($body);
            $data = json_decode($data);
            return $this->render('@GithubRepoBundle/Default/details.html.twig', ['data' => $data]);
        }

        return $this->render('@GithubRepoBundle/Default/details.html.twig', ['empty' => TRUE]);
    }
}