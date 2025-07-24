<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notifications_enabled' => 'boolean',
        ];
    }

    /**
     * Get the projects for the user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'student_id');
    }

    /**
     * Get the comments for the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the notification preferences for the user.
     */
    public function notificationPreferences(): HasMany
    {
        return $this->hasMany(NotificationPreference::class);
    }

    /**
     * Get the professor of the student.
     *
     * This is a simplified implementation. It assumes the first professor is the one.
     * A more robust implementation would involve a direct relationship.
     */
    public function getProfesseurAttribute(): ?User
    {
        if ($this->role === 'etudiant') {
            return User::where('role', 'professeur')->first();
        }
        return null;
    }

    /**
     * Check if a specific notifiable item is muted for the user.
     *
     * @param string $notifiableType
     * @param int $notifiableId
     * @return bool
     */
    public function isMuted(string $notifiableType, int $notifiableId): bool
    {
        // First, check the global switch
        if (!$this->notifications_enabled) {
            return true;
        }

        // Then, check for a specific mute rule
        return $this->notificationPreferences()
            ->where('notifiable_type', $notifiableType)
            ->where('notifiable_id', $notifiableId)
            ->where('is_muted', true)
            ->exists();
    }
}
