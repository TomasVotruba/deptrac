<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Core\Layer\Collector;

use Psr\Container\ContainerInterface;
use Qossmic\Deptrac\Contract\Layer\CollectorInterface;
use Qossmic\Deptrac\Contract\Layer\InvalidCollectorDefinitionException;
use Symfony\Component\DependencyInjection\ServiceLocator;

use function array_keys;

final class CollectorProvider implements ContainerInterface
{
    public function __construct(private readonly ServiceLocator $collectorLocator)
    {
    }

    public function get(string $id): CollectorInterface
    {
        $collector = $this->collectorLocator->get($id);

        if (!$collector instanceof CollectorInterface) {
            $exception = InvalidCollectorDefinitionException::unsupportedClass($id, $collector);
            throw new \Symfony\Component\DependencyInjection\Exception\RuntimeException($exception->getMessage(), 0, $exception);
        }

        return $collector;
    }

    public function has(string $id): bool
    {
        return $this->collectorLocator->has($id);
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return string[]
     */
    public function getKnownCollectors(): array
    {
        return array_keys($this->collectorLocator->getProvidedServices());
    }
}
