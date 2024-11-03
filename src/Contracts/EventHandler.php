<?php declare(strict_types=1);

namespace Outgang\Events\Contracts;


interface EventHandlerInterface
{
	public function __invoke(EventInterface $event, $node): void;
}
