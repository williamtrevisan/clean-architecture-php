<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreLibraryRequest;
use App\Http\Requests\UpdateLibraryRequest;
use App\Http\Resources\LibraryResource;
use Core\UseCase\Library\Create\CreateLibraryInputDTO;
use Core\UseCase\Library\Create\CreateLibraryUseCase;
use Core\UseCase\Library\Find\FindLibraryInputDTO;
use Core\UseCase\Library\Find\FindLibraryUseCase;
use Core\UseCase\Library\List\ListLibrariesUseCase;
use Core\UseCase\Library\Update\UpdateLibraryInputDTO;
use Core\UseCase\Library\Update\UpdateLibraryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LibraryController
{
    public function index(
        ListLibrariesUseCase $listLibrariesUseCase
    ): AnonymousResourceCollection {
        $libraries = $listLibrariesUseCase->execute();

        return LibraryResource::collection(collect($libraries->items));
    }

    public function store(
        StoreLibraryRequest $request,
        CreateLibraryUseCase $createLibraryUseCase
    ): JsonResponse {
        $createLibraryInputDTO = new CreateLibraryInputDTO(
            name: $request->name, email: $request->email,
        );

        $library = $createLibraryUseCase->execute(input: $createLibraryInputDTO);

        return (new LibraryResource($library))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(
        FindLibraryUseCase $findLibraryUseCase,
        string $id
    ): JsonResponse {
        $findLibraryInputDTO = new FindLibraryInputDTO(id: $id);

        $library = $findLibraryUseCase->execute(input: $findLibraryInputDTO);

        return (new LibraryResource($library))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        UpdateLibraryRequest $request,
        UpdateLibraryUseCase $updateLibraryUseCase,
        string $id
    ): JsonResponse {
        $updateLibraryInputDTO = new UpdateLibraryInputDTO(
            id: $id,
            name: $request->name,
            email: $request->email,
        );

        $library = $updateLibraryUseCase->execute(input: $updateLibraryInputDTO);

        return (new LibraryResource($library))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
