<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    public function assist(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:2000',
            'type' => 'required|in:assist,reformulate',
        ]);

        $apiKey = config('services.openai.key');

        if (!$apiKey) {
            Log::error('OPENAI_API_KEY not set in .env file.');
            return response()->json(['error' => 'La clé API pour l\'assistant IA n\'est pas configurée.'], 500);
        }

        $user = $request->user();
        $system_prompt = "Tu es un assistant IA pour une plateforme de gestion de projets de mémoire. Ton rôle est d\'aider les professeurs et les étudiants à communiquer de manière efficace et bienveillante. Réponds uniquement avec le texte du commentaire, sans phrases d\'introduction ou de conclusion.";

        $user_prompt = $validated['type'] === 'assist'
            ? "L\'utilisateur est un '{$user->role}' et demande de l\'aide pour rédiger un commentaire. Le but est de générer un commentaire constructif. Voici son idée de base : \"{$validated['prompt']}\""
            : "L\'utilisateur est un '{$user->role}' et demande de reformuler son commentaire pour le rendre plus clair et professionnel. Voici le texte original : \"{$validated['prompt']}\"";

        try {
            $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $system_prompt],
                    ['role' => 'user', 'content' => $user_prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 200,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API request failed', ['response' => $response->body()]);
                return response()->json(['error' => 'L\'assistant IA a rencontré une erreur.'], 500);
            }

            $suggestion = $response->json('choices.0.message.content');

            return response()->json(['suggestion' => trim($suggestion)]);

        } catch (\Exception $e) {
            Log::error('Exception during OpenAI API call', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur de communication avec l\'assistant IA.'], 500);
        }
    }
}
