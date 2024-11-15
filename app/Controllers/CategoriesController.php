<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\CreateCategoryRequestValidator;
use App\RequestValidators\UpdateCategoryRequestValidator;
use App\ResponseFormatter;
use App\Services\CategoryService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class CategoriesController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly CategoryService $categoryService,
        private readonly ResponseFormatter $responseFormatter,

    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render(
            $response,
            'categories/index.twig',
            ['categories' => $this->categoryService->getAll()]
        );
    }

    public function store(Request $request, Response $response): Response
    {

        $data =
            $this->requestValidatorFactory
            ->make(CreateCategoryRequestValidator::class)
            ->validate($request->getParsedBody());

        $user = $request->getAttribute('user');

        $this->categoryService->create($data['name'], $user);

        return $response->withHeader('Location', '/categories')->withStatus(302);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $categoryId = (int) $args['id'];

        $this->categoryService->delete($categoryId);

        return $response->withHeader('Location', '/categories')->withStatus(302);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $categoryId = (int) $args['id'];

        $category = $this->categoryService->findById($categoryId);

        if (! $category) {
            $response->getBody()->write('Category not found.');
            return $response->withStatus(404);
        }

        $data = ['id' => $category->getId(), 'name' => $category->getName()];

        return $this->responseFormatter->asJSON($response, $data);
    }

    public function update(Request $request, Response $response, array $args): Response
    {


        $data =
            $this->requestValidatorFactory
            ->make(UpdateCategoryRequestValidator::class)
            ->validate($args + $request->getParsedBody());

        $categoryId = (int) $data['id'];
        $category = $this->categoryService->findById($categoryId);

        if (! $category) {
            $response->getBody()->write('Category not found.');
            return $response->withStatus(404);
        }


        $this->categoryService->update($category, $data['name']);

        return $response;
    }
}
