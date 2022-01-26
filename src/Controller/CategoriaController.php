<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoriaController extends AbstractController
{
     /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CategoriaRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, CategoriaRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    #[Route('/categoria')]
    public function index(): Response
    {
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }

    #[Route('/categoria/create')]
    public function createAction(): Response
    {
        return $this->render('categoria/create.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }

    #[Route('/categoria/edit')]
    public function editAction(): Response
    {
        return $this->render('categoria/edit.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }

    #[Route('/api/categoria', methods: ['GET'])]
    public function buscarTodas()
    {
        $categoriaList = $this->repository->findAll();
        return new JsonResponse($categoriaList);
    }

    #[Route('/api/categoria/{id}', methods: ['GET'])]
    public function buscarPorId($id)
    {
        $categoria = $this->repository->find($id);
        return new JsonResponse($categoria);
    }

    #[Route('/api/categoria', methods: ['POST'])]
    public function criar(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        
        $dadosEmJson = json_decode($corpoRequisicao);

        $categoria = new Categoria();
        $categoria -> setNome($dadosEmJson->nome)
                   -> setCodigo($dadosEmJson->codigo);

        $this->entityManager-> persist($categoria);
        $this->entityManager-> flush();        

        return new JsonResponse($categoria);
    }

    #[Route('/api/categoria/{id}', methods: ['PUT'])]
    public function atualizar($id, Request $request)
    {
        $corpoRequisicao = $request->getContent();

        $categoriaEnviada = json_decode($corpoRequisicao);
        $categoriaExistente = $this->repository->find($id);

        $categoriaExistente -> setNome($categoriaEnviada->nome)
                            -> setCodigo($categoriaEnviada->codigo);

        $this->entityManager->flush();        

        return new JsonResponse($categoriaExistente);
    }

    #[Route('/api/categoria/{id}', methods: ['DELETE'])]
    public function deletar($id)
    {
        $categoria = $this->repository->find($id);
        $this->entityManager->remove($categoria);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

}
