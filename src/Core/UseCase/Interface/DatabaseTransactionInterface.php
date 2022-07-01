<?php

namespace Core\UseCase\Interface;

interface DatabaseTransactionInterface
{
    public function commit(): void;
    public function rollBack(): void;
}
