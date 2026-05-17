<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_session_id',
        'user_id',
        'role',
        'content',
        'attachment_path',
        'attachment_type',
        'attachment_name',
        'has_code',
        'code_content',
        'code_language',
        'token_used',
    ];

    protected $casts = [
        'has_code' => 'boolean',
    ];

    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
