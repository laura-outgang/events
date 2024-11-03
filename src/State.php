<?php declare(strict_types=1);

namespace Outgang\Events;

enum State {
    case STOPPED;
    case RUNNING;
    case CAPTURING;
    case BUBBLING;
    case TARGETING;
}