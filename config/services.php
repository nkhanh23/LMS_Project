<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_REDIRECT'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'timeout' => env('GEMINI_TIMEOUT', 30),
        'temperature' => env('GEMINI_TEMPERATURE', 0.2),
        'max_output_tokens' => env('GEMINI_MAX_OUTPUT_TOKENS', 1024),
        'enabled' => env('GEMINI_ENABLED', true),
        'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1'),
        'embedding_enabled' => env('GEMINI_EMBEDDING_ENABLED', true),
        'embedding_model' => env('GEMINI_EMBEDDING_MODEL', 'gemini-embedding-2-preview'),
        'embedding_dimension' => (int) env('GEMINI_EMBEDDING_DIMENSION', 768),
        'embedding_base_url' => env('GEMINI_EMBEDDING_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
        'embedding_task_type' => env('GEMINI_EMBEDDING_TASK_TYPE', 'RETRIEVAL_DOCUMENT'),
    ],

    'openai_transcription' => [
        'enabled' => env('OPENAI_TRANSCRIPTION_ENABLED', false),
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_TRANSCRIPTION_MODEL', 'gpt-4o-mini-transcribe'),
        'base_url' => env('OPENAI_TRANSCRIPTION_BASE_URL', 'https://api.openai.com/v1'),
        'timeout' => (int) env('OPENAI_TRANSCRIPTION_TIMEOUT', 600),
        'language' => env('OPENAI_TRANSCRIPTION_LANGUAGE', 'vi'),
    ],

    'transcription_provider' => env('TRANSCRIPTION_PROVIDER', 'openai'),

    'local_whisper' => [
        'enabled' => env('LOCAL_WHISPER_ENABLED', false),
        'bin' => env('LOCAL_WHISPER_BIN', 'whisper'),
        'model' => env('LOCAL_WHISPER_MODEL', 'base'),
        'model_dir' => env('LOCAL_WHISPER_MODEL_DIR'),
        'language' => env('LOCAL_WHISPER_LANGUAGE', 'Vietnamese'),
        'device' => env('LOCAL_WHISPER_DEVICE', 'auto'),
        'timeout' => (int) env('LOCAL_WHISPER_TIMEOUT', 3600),
    ],

    'transcript' => [
        'queue_connection' => env('TRANSCRIPT_QUEUE_CONNECTION', 'database'),
        'queue' => env('TRANSCRIPT_QUEUE', 'transcripts'),
        'document_queue' => env('AI_DOCUMENT_QUEUE', 'ai-documents'),
        'dispatch_delay_seconds' => (int) env('TRANSCRIPT_DISPATCH_DELAY_SECONDS', 30),
    ],

    'yt_dlp' => [
        'bin' => env('YT_DLP_BIN', 'yt-dlp'),
        'timeout' => (int) env('YT_DLP_TIMEOUT', 3600),
    ],

    'ffmpeg' => [
        'bin' => env('FFMPEG_BIN', 'ffmpeg'),
        'ffprobe' => env('FFPROBE_BIN', 'ffprobe'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],
];
