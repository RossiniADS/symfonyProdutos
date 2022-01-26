<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Repository\ProdutoRepository;
use App\Repository\CategoriaRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProdutoController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProdutoRepository
     */
    private $repository;
    /**
     * @var CategoriaRepository
     */
    private $categoriaRepository;


    public function __construct(EntityManagerInterface $entityManager, ProdutoRepository $repository, CategoriaRepository $categoriaRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->categoriaRepository = $categoriaRepository;
    }

    #[Route('/produto')]
    public function index(): Response
    {
        $produtoList = $this->repository->findAll();
        $categoriaList = $this->categoriaRepository->findAll();

        return $this->render('produto/index.html.twig', [
            'produtos' => $produtoList,
            'categorias' => $categoriaList
        ]);
    }

    #[Route('/produto/create')]
    public function createProduto(): Response
    {
        return $this->render('produto/create.html.twig', [
            'controller_name' => 'ProdutoController',
        ]);
    }

    #[Route('/produto/edit')]
    public function editProduto(): Response
    {
        return $this->render('produto/edit.html.twig', [
            'controller_name' => 'ProdutoController',
        ]);
    }

    #[Route('/api/produto', methods: ['GET'])]
    public function buscarTodas()
    {
        $produtoList = $this->repository->findAll();
        return new JsonResponse($produtoList);
    }

    #[Route('/api/produto/{id}', methods: ['GET'])]
    public function buscarPorId($id)
    {
        $produto = $this->repository->find($id);
        return new JsonResponse($produto);
    }

    #[Route('/api/produto', methods: ['POST'])]
    public function criar(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        
        $dadosEmJson = json_decode($corpoRequisicao);

        $categoriaId = $dadosEmJson->categoria_id;
        $categoria = $this->categoriaRepository->find($categoriaId);

        $produto = new Produto();
        $produto -> setNome($dadosEmJson->nome)
                 -> setValor($dadosEmJson->valor)
                 -> setCreateAt(new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')))
                 -> setCategoriaId($categoria)
                 -> setUpdateAt(new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

        $this->entityManager-> persist($produto);
        $this->entityManager-> flush();        

        return new JsonResponse($produto);
    }

    #[Route('/api/produto/{id}', methods: ['PUT'])]
    public function atualizar($id, Request $request)
    {
        $corpoRequisicao = $request->getContent();

        $produtoEnviado = json_decode($corpoRequisicao);
        $produtoExistente = $this->repository->find($id);

        $categoriaId = $produtoEnviado->categoria_id;
        $categoria = $this->categoriaRepository->find($categoriaId);

        $produtoExistente -> setNome($produtoEnviado->nome)
                          -> setValor($produtoEnviado->valor)
                          -> setCreateAt($produtoExistente->getCreateAt())
                          -> setCategoriaId($categoria)
                          -> setUpdateAt(new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

        $this->entityManager->flush();        

        return new JsonResponse($produtoExistente);
    }

    #[Route('/api/produto/{id}', methods: ['DELETE'])]
    public function deletar($id)
    {
        $produto = $this->repository->find($id);
        $this->entityManager->remove($produto);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
