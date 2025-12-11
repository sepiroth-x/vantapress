<?php

namespace Modules\TheVillainTerminal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\TheVillainTerminal\Services\TerminalExecutor;
use Illuminate\Support\Facades\Log;

class TerminalController extends Controller
{
    protected TerminalExecutor $executor;

    public function __construct(TerminalExecutor $executor)
    {
        $this->executor = $executor;
    }

    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'command' => 'required|string|max:500'
        ]);

        $command = $request->input('command');

        Log::info('[Terminal API] Command received', [
            'command' => $command,
            'user_id' => auth()->id()
        ]);

        $result = $this->executor->execute($command);

        return response()->json($result);
    }
}
