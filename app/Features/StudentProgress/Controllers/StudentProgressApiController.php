<?php

namespace App\Features\StudentProgress\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Circle;
use App\Features\StudentProgress\Repositories\StudentProgressRepositoryInterface;
use App\Features\StudentProgress\DTOs\StudentProgressData;
use App\Features\StudentProgress\Resources\StudentProgressResource;
use App\Features\StudentProgress\Requests\StoreStudentProgressRequest;

class StudentProgressApiController extends Controller
{
    public function __construct(protected StudentProgressRepositoryInterface $repo)
    {
    }

    public function index(Circle $circle)
    {
        $list = $this->repo->listByCircle($circle->id);
        return StudentProgressResource::collection($list);
    }

    public function store(StoreStudentProgressRequest $request, Circle $circle)
    {
        $dto = StudentProgressData::fromRequest($request);
        $dto->circle_id = $circle->id;

        $progress = app(\App\Features\StudentProgress\Services\StudentProgressService::class)->create($dto);

        return new StudentProgressResource($progress);
    }
}
