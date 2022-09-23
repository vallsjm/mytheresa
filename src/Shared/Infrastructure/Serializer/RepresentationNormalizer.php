<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Serializer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Shared\Infrastructure\Hateoas\Representation;

class RepresentationNormalizer implements NormalizerInterface
{
    private ObjectNormalizer $normalizer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->normalizer = $normalizer;
        $this->urlGenerator = $urlGenerator;
    }

    private function getUrl(Representation $obj, int $page): array
    {
        $routeParams = $obj->routeParams();
        $routeParams['page'] = $page;
        return ['href' => $this->urlGenerator->generate(
            $obj->routeName(),
            $routeParams,
            UrlGeneratorInterface::ABSOLUTE_URL
        )];
    }
    private function getLinks(Representation $obj): array
    {
        $links = [
            'first' => $this->getUrl($obj, 1)
        ];

        $pageResume = $obj->resume();
        if ($pageResume->page() > 1) {
            $links['prev'] = $this->getUrl($obj, $pageResume->page() - 1);
        }
        $links['self'] = $this->getUrl($obj, $pageResume->page());
        if ($pageResume->page() < $pageResume->totalPages()) {
            $links['next'] = $this->getUrl($obj, $pageResume->page() + 1);
        }
        $links['last'] = $this->getUrl($obj, $pageResume->totalPages());

        return ['_links' => $links];
    }

    public function normalize($obj, string $format = null, array $context = [])
    {
        return $this->getLinks($obj) + $this->normalizer->normalize($obj, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Representation;
    }
}