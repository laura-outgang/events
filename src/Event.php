<?php declare(strict_types=1);

namespace Outgang\Events;

use Outgang\Events\Contracts\EventInterface;
use Outgang\Events\Contracts\Subject;
use Outgang\Iterables\IterableList;
use Outgang\Iterables\StackIterator;


class Event implements EventInterface
{
	public State           $state;
	public array           $attributes;
    public readonly	mixed  $target;
    public readonly string $name;
	public readonly bool   $capture;


    public function __construct(mixed $target, string $name, array $attributes=[], bool $useCapture=true)
    {
		$this->target     = $target;
        $this->name       = $name;
		$this->capture    = $useCapture;
        $this->attributes = $attributes;
    }


	public function state(): State
	{
		return $this->state;
	}

	
    public function stopPropagation()
    {
        $this->state = State::STOPPED;
    }


    public function dispatch(iterable $nodes=[])
    {
		// dump('Dispatching: ' . $this->name);
		foreach ($this->nodes($nodes) as $node) {
			// dump($node->type);
			if (!isset($node->eventHandlers)) continue;

			foreach ($node->eventHandlers->get($this->name, $this->state) as $handler) {
				// dump($handler->name, $handler->state);
				$handler($this, $node);
				if ($this->state === State::STOPPED) break;
				if ($node instanceof Subject) $node->notify($this);
			}

			if ($this->state === State::STOPPED) break;
		}
    }


    private function nodes(iterable $nodes=[]): \Generator
    {
		if ($this->capture) {
			$this->state 	= State::CAPTURING;
			$list 			= new IterableList();
			$stack 			= new StackIterator($nodes);

			foreach ($stack as $node) {
				if ($node === $this->target) continue;
				$list->prepend($node);
				yield $node;
			}

			$nodes = $list;
		}

		$this->state = State::TARGETING;
		yield $this->target;
		
		$this->state = State::BUBBLING;

		foreach ($nodes as $node) {
			if ($node === $this->target) continue;
			yield $node;
		}
    }
}
