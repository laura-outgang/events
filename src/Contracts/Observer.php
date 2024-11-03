<?php declare(strict_types=1);

namespace Outgang\Events\Contracts;


interface Observer
{
	public function update(Subject $subject, $data): void;
}