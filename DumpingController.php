<?php

namespace App\Http\Controllers\Admin;

use App\Files\Repositories\DumpSqlRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DumpingController extends Controller
{
    private $dumpRepository;
    private $service;

    public function __construct(DumpSqlRepository $dumpSqlRepository, BaseService $service)
    {
        parent::__construct();

        $this->dumpRepository = $dumpSqlRepository;
        $this->service        = $service;
    }

    public function index(Request $request)
    {
        $dumps = $this->dumpRepository->files(null, true, true);

        
        if ($request->ajax()) {
            return response()->view('admin.dumps.items', compact('dumps'));
        }

        return view('admin.dumps.index', compact( 'dumps'));
    }

    public function download(Request $request)
    {
        $dump = $this->dumpRepository->path($request->get('dump_path'));

        return response()->download($dump);
    }
}
