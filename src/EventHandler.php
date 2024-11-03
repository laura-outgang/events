<?php declare(strict_types=1);

namespace Outgang\Events;

use Outgang\Events\Contracts\EventHandlerInterface;
use Outgang\Events\Contracts\EventInterface;

class EventHandler implements EventHandlerInterface
{
	public readonly string   $name;
	public State             $state;
	public readonly \Closure $callback;
	public int 				 $priority;
	

	public function __construct(string $name, State $state, callable $callback, int $priority=50)
	{
		$this->name 	= $name;
		$this->state 	= $state;
		$this->priority = $priority;
		$this->callback = \Closure::fromCallable($callback)->bindTo(null);
	}

	
	public function __invoke(EventInterface $event, $node): void
	{
		($this->callback)($event, $node);
	}
}
