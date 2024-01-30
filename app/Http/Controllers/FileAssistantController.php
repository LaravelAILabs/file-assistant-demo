<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileAssistantStoreRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use LaravelAILabs\FileAssistant\Facades\FileAssistant;

class FileAssistantController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

	function index() {
		$conversationId = Session::get('conversation_id');

		$dialog = FileAssistant::setConversation($conversationId)
			->initialize();

		return view('app', [
			'conversation_id' => $conversationId,
			'messages' => $dialog?->getConversation()?->messages,
			'files' => $dialog?->getConversation()?->files,
		]);
	}

	function store(FileAssistantStoreRequest $request) {
		$fileAssistant = FileAssistant::setConversation($request->get('conversation_id') ?? null);

		if ($request->has('file')) {
			$fileAssistant->addFile($request->file('file')->getPathname());
		}

		$dialog = $fileAssistant->initialize();

		// save the conversation_id
		Session::remember('conversation_id', fn () => $dialog->getConversation()->id);

		$dialog->prompt($request->get('message'));

		return redirect()->route('index');
	}

	function destroy() {
		Session::forget('conversation_id');

		return redirect()->route('index');
	}
}
