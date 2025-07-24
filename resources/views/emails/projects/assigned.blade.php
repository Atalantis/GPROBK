<x-mail::message>
# Nouveau Projet Assigné

Bonjour {{ $studentName }},

Bonne nouvelle ! Un nouveau projet de mémoire vous a été assigné sur la plateforme INSURACTIO.

**Projet :** {{ $project->title }}

Vous pouvez consulter les détails du projet et commencer à travailler sur vos tâches en cliquant sur le bouton ci-dessous.

<x-mail::button :url="$url">
Voir le Projet
</x-mail::button>

N'hésitez pas à contacter votre professeur via la section des commentaires si vous avez des questions.

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
