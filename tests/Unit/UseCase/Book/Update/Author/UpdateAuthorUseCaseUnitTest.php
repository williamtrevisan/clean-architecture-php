<?php

use Core\Domain\Book\Entity\Author as AuthorEntity;
use Core\Domain\Book\Repository\AuthorRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Update\Author\UpdateAuthorInputDTO;
use Core\UseCase\Book\Update\Author\UpdateAuthorOutputDTO;
use Core\UseCase\Book\Update\Author\UpdateAuthorUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if cannot find a author for update', function () {
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findByPk')->andReturn(null);
    $updateAuthorInputDTO = Mockery::mock(UpdateAuthorInputDTO::class, [
        'authorId', 'Author name updated'
    ]);

    $updateAuthorUseCase = new UpdateAuthorUseCase($authorRepository);
    $updateAuthorUseCase->execute($updateAuthorInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

test('should be able to update a author', function () {
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $expectedAuthor = Mockery::mock(AuthorEntity::class, [
        'Author name', new Uuid($expectedAuthorId)
    ]);
    $expectedAuthor->shouldReceive('changeName')->once();
    $expectedAuthor->shouldReceive('getId')->andReturn($expectedAuthorId);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('update')->once()
        ->andReturn($expectedAuthor);
    $authorRepository->shouldReceive('findByPk')->once()
        ->with($expectedAuthorId)
        ->andReturn($expectedAuthor);
    $updateAuthorInputDTO = Mockery::mock(UpdateAuthorInputDTO::class, [
        $expectedAuthorId, 'Author name updated'
    ]);

    $updateAuthorUseCase = new UpdateAuthorUseCase($authorRepository);
    $actualAuthor = $updateAuthorUseCase->execute($updateAuthorInputDTO);

    expect($actualAuthor)->toBeInstanceOf(UpdateAuthorOutputDTO::class);
});
