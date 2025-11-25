<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobFairResource;
use App\Models\JobFair;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobFairController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $jobFairs = JobFair::query()
            ->where('is_public', true)
            ->with(['dates', 'locations'])
            ->withCount('exhibitors')
            ->get();

        return JobFairResource::collection($jobFairs);
    }

    public function show(Request $request, JobFair $jobFair): JobFairResource
    {
        $includes = $this->parseIncludes($request);

        $jobFair->load(['dates', 'locations']);

        if (in_array('exhibitors', $includes)) {
            $jobFair->load('exhibitors');
        }

        if (in_array('school_registrations', $includes)) {
            $jobFair->load('schoolRegistrations');
        }

        return new JobFairResource($jobFair);
    }

    /**
     * @return array<int, string>
     */
    protected function parseIncludes(Request $request): array
    {
        $include = $request->query('include', '');

        if (blank($include)) {
            return [];
        }

        return array_map('trim', explode(',', $include));
    }
}
