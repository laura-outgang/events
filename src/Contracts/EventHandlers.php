<?php declare(strict_types=1);

namespace Outgang\Events\Contracts;

use Outgang\Events\State;
use Outgang\Iterables\Contracts\FilterableInterface;


interface EventHandlersInterface extends \IteratorAggregate, \Countable
{
	public function attach(EventHandlerInterface $eventHandler): void;
	public function detach(EventHandlerInterface $eventHandler): void;
	public function get(string $name, ?State $state=null): FilterableInterface;
	public function remove(string $name, ?callable $callback=null);
	public function has(string $name, ?Callable $function=null): bool;
	public function clear(): void;
	public function __invoke(string $name, string $state=''): FilterableInterface;
}