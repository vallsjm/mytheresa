<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Hateoas;

use Shared\Infrastructure\Filter\Paginate;
use Shared\Infrastructure\Filter\FilterResponse;
use Symfony\Component\HttpFoundation\Request;

class Representation
{
    private function __construct(
        private string $routeName,
        private array $routeParams,
        public PageResume $resume,
        public FilterResponse $response
    ) {}

    public static function create(
        Request $request,
        FilterResponse $response,
        Paginate $paginate
    ): self {
        $routeParams = $request->attributes->get('_route_params');
        $routeParams = $routeParams + $request->query->all();
        $routeName = $request->attributes->get('_route');

        return new self(
            $routeName,
            $routeParams,
            PageResume::create(
                $response,
                $paginate
            ),
            $response
        );
    }

    public function routeName(): string
    {
        return $this->routeName;
    }

    public function routeParams(): array
    {
        return $this->routeParams;
    }

    public function response(): FilterResponse
    {
        return $this->response;
    }

    public function resume(): PageResume
    {
        return $this->resume;
    }
}