<?php

use Core\Domain\Author\Entity\Author;
use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Author\Find\FindAuthorInputDTO;
use Core\UseCase\Author\Find\FindAuthorOutputDTO;
use Core\UseCase\Author\Find\FindAuthorUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

it('should be throw an exception if cannot find a author by primary key', function () {
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findByPk')->andReturn(null);
    $findAuthorInputDTO = Mockery::mock(FindAuthorInputDTO::class, ['fakeId']);

    $findAuthorUseCase = new FindAuthorUseCase($authorRepository);
    $findAuthorUseCase->execute($findAuthorInputDTO);
})->throws(NotFoundException::class, 'Author with id: fakeId not found');

test('should be able to find a author by primary key', function () {
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $expectedAuthor = Mockery::mock(
        Author::class,
        ['Author name', new Uuid($expectedAuthorId)]
    );
    $expectedAuthor->shouldReceive('getId')->andReturn($expectedAuthorId);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findByPk')->andReturn($expectedAuthor);
    $findAuthorInputDTO = Mockery::mock(
        FindAuthorInputDTO::class, [$expectedAuthorId]
    );

    $findAuthorUseCase = new FindAuthorUseCase($authorRepository);
    $persistAuthor = $findAuthorUseCase->execute($findAuthorInputDTO);

    expect($persistAuthor)
        ->toBeInstanceOf(FindAuthorOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedAuthor->id,
            'name' => $expectedAuthor->name,
        ]);
});
