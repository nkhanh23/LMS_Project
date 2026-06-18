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

    'website_assistant' => [
        'features' => [
            'wishlist',
            'certificate',
            'quiz_history',
            'refund',
            'profile',
            'continue_learning',
            'my_courses',
            'orders',
        ],
        'reference_modes' => [
            'explicit',
            'contextual_quiz',
            'none',
        ],
        'requested_fields' => [
            'summary',
            'attempts_used',
            'remaining_attempts',
            'latest_score',
            'latest_attempt',
        ],
        'intents' => [
            'course_progress' => [
                'description' => 'Hoi ve tien do hoc tap cua mot khoa hoc cu the.',
                'entities' => ['course_name'],
                'requested_fields' => ['summary'],
                'reference_modes' => ['explicit', 'none'],
                'examples' => [
                    'Khoa A toi hoc duoc bao nhieu phan tram roi?',
                    'Tien do khoa hoc cua toi la bao nhieu?',
                ],
            ],
            'unfinished_courses' => [
                'description' => 'Hoi ve danh sach khoa hoc chua hoan thanh, chua hoc hoac dang hoc.',
                'entities' => ['course_name'],
                'requested_fields' => ['summary'],
                'reference_modes' => ['none'],
                'examples' => [
                    'Toi con khoa nao chua hoc khong?',
                    'Liet ke cac khoa hoc chua hoan thanh cua toi.',
                ],
            ],
            'quiz_history' => [
                'description' => 'Hoi ve lich su quiz, so luot da lam, so luot con lai, diem gan nhat.',
                'entities' => ['course_name', 'quiz_name'],
                'requested_fields' => ['summary', 'attempts_used', 'remaining_attempts', 'latest_score', 'latest_attempt'],
                'reference_modes' => ['explicit', 'contextual_quiz', 'none'],
                'examples' => [
                    'Lich su quiz cua toi',
                    'Quiz do co may luot?',
                    'Toi con may luot lam quiz nay?',
                    'Diem quiz gan nhat cua toi la bao nhieu?',
                ],
            ],
            'certificate_status' => [
                'description' => 'Hoi ve trang thai dat chung chi cua user.',
                'entities' => ['course_name'],
                'requested_fields' => ['summary'],
                'reference_modes' => ['explicit', 'none'],
                'examples' => [
                    'Toi da co chung chi nao roi?',
                    'Khoa hoc nay da du dieu kien lay chung chi chua?',
                ],
            ],
            'refund_status' => [
                'description' => 'Hoi ve trang thai hoan tien cua user.',
                'entities' => ['course_name', 'order_reference', 'refund_reference'],
                'requested_fields' => ['summary'],
                'reference_modes' => ['explicit', 'none'],
                'examples' => [
                    'Trang thai hoan tien cua toi la gi?',
                    'Yeu cau refund cua khoa A dang o dau?',
                ],
            ],
            'feature_how_to' => [
                'description' => 'Hoi cach dung he thong, huong dan thao tac, FAQ, policy.',
                'entities' => ['feature_name'],
                'requested_fields' => ['summary'],
                'reference_modes' => ['none'],
                'examples' => [
                    'Cach dung wishlist',
                    'Lam sao de hoan tien khoa hoc?',
                    'Lich su quiz o dau?',
                ],
            ],
            'unknown' => [
                'description' => 'Khong du co so de xac dinh intent hoac cau hoi ngoai pham vi support.',
                'entities' => [],
                'requested_fields' => ['summary'],
                'reference_modes' => ['none'],
                'examples' => [
                    'Ban co the giup toi khong?',
                ],
            ],
        ],
        'intent_handlers' => [
            'course_progress' => 'getCourseProgressPayload',
            'unfinished_courses' => 'getUnfinishedCoursesPayload',
            'quiz_history' => 'getQuizHistoryPayload',
            'certificate_status' => 'getCertificateStatusPayload',
            'refund_status' => 'getRefundStatusPayload',
            'feature_how_to' => 'getFeatureHowToPayload',
        ],
        'parser_rules' => [
            'always_pick_primary_intent',
            'candidate_intents_max_3',
            'clarify_when_ambiguous_or_missing_required_data',
            'prefer_personal_data_intents_over_feature_how_to_for_personal_questions',
            'how_to_phrasing_often_maps_to_feature_how_to',
            'quantity_status_phrasing_often_maps_to_personal_data_intents',
            'use_unknown_when_not_confident',
            'do_not_invent_entities',
            'course_name_only_when_explicit_or_strongly_inferred',
            'quiz_name_only_when_explicit_or_clearly_contextual',
            'feature_name_must_be_from_allowed_features',
            'requested_fields_must_be_from_allowed_requested_fields',
            'reference_mode_must_be_from_allowed_reference_modes',
            'contextual_quiz_for_quiz_do_quiz_toi_da_lam_ca_2',
            'attempts_used_for_quiz_attempt_count_questions',
            'remaining_attempts_for_quiz_remaining_attempt_questions',
            'latest_score_for_latest_quiz_score_questions',
            'return_null_when_entity_missing',
            'return_valid_json_only',
        ],
    ],
];
