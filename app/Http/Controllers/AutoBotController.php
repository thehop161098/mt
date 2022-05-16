<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Core\Repositories\Eloquents\AutoBotRepository;
use Core\Repositories\Eloquents\HistoryBotRepository;
use Core\Services\BotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoBotController extends Controller
{
    private $botService;
    private $historyBotRepository;
    private $autoBotRepository;

    public function __construct(
        BotService $botService,
        AutoBotRepository $autoBotRepository,
        HistoryBotRepository $historyBotRepository
    ) {
        $this->botService = $botService;
        $this->autoBotRepository = $autoBotRepository;
        $this->historyBotRepository = $historyBotRepository;
    }

    public function index()
    {
        if (config('settings.publish_bot') !== '1') {
            return redirect('dashboard');
        }

        $user = Auth::user();
        $currentBot = $this->historyBotRepository->currentBotExpired($user->id);
        $bots = $this->autoBotRepository->findAll();
        return view('frontend.autoBot.index', [
            'currentBot' => $currentBot,
            'bots' => $bots
        ]);
    }

    public function buy(Request $request, $botId, $timeSelected)
    {
        if (config('settings.publish_bot') !== '1') {
            return redirect('dashboard');
        }

        $data = $this->botService->buyBot($botId, $timeSelected);
        if ($data['success'] == true) {
            return redirect('/auto-bots')->with(['success_verified' => $data['message']]);
        } else {
            return redirect('/auto-bots')->with(['error_verified' => $data['message']]);
        }
    }

    public function unLock(Request $request, $id)
    {
        if (config('settings.publish_bot') !== '1') {
            return redirect('dashboard');
        }
        $user = Auth::user();
        $currentBot = $this->historyBotRepository->find($id, $user->id);
        if (!$currentBot) {
            return redirect('/auto-bots')->with(['error_verified' => 'Not found bot']);
        }
        if ($currentBot->time_expired . ' 00:10' >= Carbon::now()->format('Y-m-d H:i')) {
            return redirect('/auto-bots')->with(['error_verified' => 'Cannot unlock']);
        }

        $data = $this->botService->unLock($currentBot, $user->id);
        if ($data['success'] == true) {
            return redirect('/auto-bots')->with(['success_verified' => $data['message']]);
        } else {
            return redirect('/auto-bots')->with(['error_verified' => $data['message']]);
        }
    }
}
