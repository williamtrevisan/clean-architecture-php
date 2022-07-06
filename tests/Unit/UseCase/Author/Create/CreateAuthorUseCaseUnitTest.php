<?php

use Core\Domain\Author\Entity\Author;
use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Author\Create\CreateAuthorInputDTO;
use Core\UseCase\Author\Create\CreateAuthorOutputDTO;
use Core\UseCase\Author\Create\CreateAuthorUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

test('should be able to create a new author', function () {
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $expectedAuthor = Mockery::mock(
        Author::class, ['Author name', new Uuid($expectedAuthorId)]
    );
    $expectedAuthor->shouldReceive('getId')->andReturn($expectedAuthorId);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('create')->andReturn($expectedAuthor);
    $createAuthorInputDTO = Mockery::mock(
        CreateAuthorInputDTO::class, ['Author name']
    );

    $createAuthorUseCase = new CreateAuthorUseCase($authorRepository);
    $persistAuthor = $createAuthorUseCase->execute($createAuthorInputDTO);

    expect($persistAuthor)
        ->toBeInstanceOf(CreateAuthorOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedAuthor->id,
            'name' => $expectedAuthor->name,
        ]);
});
