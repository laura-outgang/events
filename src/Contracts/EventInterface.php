<?php declare(strict_types=1);

namespace Outgang\Events\Contracts;


interface EventInterface
{
	public function state();
	public function stopPropagation();
	public function dispatch();
}
