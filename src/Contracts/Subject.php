<?php declare(strict_types=1);

namespace Outgang\Events\Contracts;


interface Subject
{
	public function notify(EventInterface $event): void;
	public function attach(Observer $observer);
	public function detach(Observer $observer);
}
