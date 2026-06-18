<?php

namespace App\Services;

use App\Services\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class WebsiteAssistantParserService
{
    public function __construct(
        protected AIProviderInterface $aiProvider
    ) {}

    public function parse(string $question, array $conversationHistory = []): array
    {
        try {
            $prompt = $this->buildPrompt($question, $conversationHistory);

            $payload = $this->aiProvider->generateAnswer($prompt, [
                'temperature' => 0.0,
                'max_output_tokens' => 900,
            ]);

            $rawAnswer = trim((string) ($payload['answer'] ?? ''));
            $decoded = $this->decodeJson($rawAnswer);
            $validated = $this->validateOutput($decoded, $question);

            // ghi log de test
            Log::info('Website assistant parser output', [
                'question' => $question,
                'primary_intent' => $validated['primary_intent'],
                'needs_clarification' => $validated['needs_clarification'],
                'entities' => $validated['entities'],
                'requested_fields' => $validated['requested_fields'],
                'reference_mode' => $validated['reference_mode'],
            ]);

            return $validated;
        } catch (\Throwable $e) {
            Log::warning('Website assistant parser provider unavailable', [
                'question' => $question,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException('AI đang quá tải, vui lòng thử lại sau.', previous: $e);
        }
    }

    protected function buildPrompt(string $question, array $conversationHistory): string
    {
        $featureList = implode(', ', $this->allowedFeatures());
        $referenceModes = implode(', ', $this->allowedReferenceModes());
        $requestedFields = implode(', ', $this->allowedRequestedFields());
        $intentSchema = implode(' | ', $this->allowedIntents());
        $intentList = implode("\n- ", $this->allowedIntents());
        $intentDetails = $this->buildIntentDetailsForPrompt();
        $conversation = $this->formatConversationHistory($conversationHistory);

        return <<<PROMPT
Ban la AI parser cho he thong LMS. Nhiem vu cua ban la phan tich cau hoi tieng Viet cua nguoi dung va tra ve JSON co cau truc.

Ban KHONG duoc tra loi cau hoi cua nguoi dung.
Ban KHONG duoc giai thich dai dong.
Ban CHI duoc tra ve JSON hop le, khong markdown, khong text thua.

Ban duoc phep suy luan reference tu hoi thoai truoc do. Vi du:
- "quiz do" co the tham chieu quiz vua nhac o tin nhan truoc
- "khoa do" co the tham chieu khoa hoc vua nhac o tin nhan truoc
- "ca 2" co the tham chieu hai truong du lieu da hoi o luot truoc

Cac intent hop le:
- {$intentList}

Nguyen tac:
1. Luon chon mot primary_intent.
2. Co the tra them 1 den 3 candidate_intents neu cau hoi mo ho.
3. Neu cau hoi mo ho hoac thieu du lieu quan trong, dat needs_clarification = true.
4. Neu user dang hoi du lieu ca nhan cua ho, uu tien cac intent du lieu ca nhan thay vi feature_how_to.
5. Neu user hoi "o dau", "lam sao", "cach", "nhu the nao", thuong nghieng ve feature_how_to, tru khi hoi ro ve trang thai du lieu ca nhan.
6. Neu user hoi "bao nhieu", "trang thai", "da co chua", "con bao nhieu", thuong nghieng ve intent du lieu ca nhan.
7. Khong tu bia entity ngoai nhung gi suy ra hop ly tu cau hien tai va hoi thoai gan day.
8. course_name chi la ten khoa hoc neu thuc su co trong cau hoac suy ra ro rang tu hoi thoai.
9. quiz_name chi la ten quiz neu thuc su co trong cau hoac suy ra ro rang tu hoi thoai.
10. feature_name chi duoc chon trong nhom: {$featureList}
11. requested_fields chi duoc chon trong nhom: {$requestedFields}
12. reference_mode chi duoc chon trong nhom: {$referenceModes}
13. Neu user dang tham chieu den quiz trong hoi thoai truoc do, uu tien reference_mode = contextual_quiz.
14. Neu user hoi so luot da lam quiz, them attempts_used.
15. Neu user hoi so luot con lai, them remaining_attempts.
16. Neu user hoi diem gan nhat, them latest_score.
17. Neu khong du co so de xac dinh, dung unknown hoac needs_clarification = true.
18. Tra null cho entity khong co.
19. Chi tra ve JSON hop le.
20. Neu cau hoi ve hoan tien mo ho giua "trang thai hoan tien" va "cach thuc yeu cau hoan tien", dat needs_clarification = true va dat clarification_question hoi lai ro rang.
21. Neu cau hoi quiz dang hoi so luot, diem, hoac thong tin cu the nhung khong du xac dinh quiz nao tu cau hien tai va hoi thoai gan day, dat needs_clarification = true.
22. Neu reference tu hoi thoai da du ro, tu dien quiz_name hoac course_name vao entities thay vi hoi lai.

Intent catalog:
{$intentDetails}

Hoi thoai gan day:
{$conversation}

Schema JSON bat buoc:
{
  "primary_intent": "{$intentSchema}",
  "candidate_intents": [
    {
      "intent": "{$intentSchema}",
      "confidence": 0.0
    }
  ],
  "entities": {
    "course_name": "string | null",
    "quiz_name": "string | null",
    "feature_name": "string | null",
    "order_reference": "string | null",
    "refund_reference": "string | null"
  },
  "requested_fields": [
    "{$requestedFields}"
  ],
  "reference_mode": "{$referenceModes}",
  "needs_clarification": true,
  "clarification_question": "string | null",
  "clarification_reason": "string | null",
  "reasoning_summary": "string"
}

Cau hoi hien tai: "{$question}"
PROMPT;
    }

    //hàm loại bỏ các ký tự đặc biệt (không phải JSON) ra khỏi chuỗi JSON
    protected function decodeJson(string $rawAnswer): array
    {
        $cleaned = preg_replace('/^```(?:json)?|```$/m', '', $rawAnswer) ?? $rawAnswer;
        $cleaned = trim($cleaned);

        $decoded = json_decode($cleaned, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $cleaned, $matches)) {
            $decoded = json_decode($matches[0], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        throw new RuntimeException('Website assistant parser returned invalid JSON.');
    }

    protected function validateOutput(array $decoded, string $question): array
    {
        $normalized = $this->normalizeOutput($decoded, $question);
        $allowedIntents = $this->allowedIntents();
        $allowedFeatures = $this->allowedFeatures();
        $allowedRequestedFields = $this->allowedRequestedFields();
        $allowedReferenceModes = $this->allowedReferenceModes();

        $validator = Validator::make($normalized, [
            //kiểm tra các trường trong JSON
            'primary_intent' => ['required', 'string', 'in:' . implode(',', $allowedIntents)], //kiểm tra intent chính có trong danh sách intent hợp lệ không
            'candidate_intents' => ['required', 'array', 'min:1', 'max:3'], //kiểm tra candidate_intents có phải là mảng và có ít nhất 1 phần tử
            'candidate_intents.*.intent' => ['required', 'string', 'in:' . implode(',', $allowedIntents)], //kiểm tra intent trong candidate_intents có trong danh sách intent hợp lệ không
            'candidate_intents.*.confidence' => ['required', 'numeric', 'min:0', 'max:1'], //kiểm tra confidence có phải là số và nằm trong khoảng từ 0 đến 1
            'entities' => ['required', 'array'], //kiểm tra entities có phải là mảng
            'entities.course_name' => ['nullable', 'string'], //kiểm tra course_name có phải là chuỗi
            'entities.quiz_name' => ['nullable', 'string'], //kiểm tra quiz_name có phải là chuỗi
            'entities.feature_name' => ['nullable', 'string', 'in:' . implode(',', $allowedFeatures)], //kiểm tra feature_name có phải là chuỗi và nằm trong danh sách feature hợp lệ không
            'entities.order_reference' => ['nullable', 'string'], //kiểm tra order_reference có phải là chuỗi
            'entities.refund_reference' => ['nullable', 'string'], //kiểm tra refund_reference có phải là chuỗi
            'requested_fields' => ['required', 'array'], //kiểm tra requested_fields có phải là mảng
            'requested_fields.*' => ['string', 'in:' . implode(',', $allowedRequestedFields)], //kiểm tra requested_fields có phải là chuỗi và nằm trong danh sách requested_fields hợp lệ không
            'reference_mode' => ['required', 'string', 'in:' . implode(',', $allowedReferenceModes)], //kiểm tra reference_mode có phải là chuỗi và nằm trong danh sách reference_mode hợp lệ không
            'needs_clarification' => ['required', 'boolean'], //kiểm tra needs_clarification có phải là boolean
            'clarification_question' => ['nullable', 'string'], //kiểm tra clarification_question có phải là chuỗi
            'clarification_reason' => ['nullable', 'string'], //kiểm tra clarification_reason có phải là chuỗi
            'reasoning_summary' => ['required', 'string'], //kiểm tra reasoning_summary có phải là chuỗi
        ]);

        if ($validator->fails()) {
            Log::warning('Website assistant parser validation failed', [
                'question' => $question,
                'errors' => $validator->errors()->toArray(),
                'normalized_output' => $normalized,
            ]);

            throw new RuntimeException('AI đang quá tải, vui lòng thử lại sau.');
        }

        return $validator->validated();
    }

    protected function normalizeOutput(array $decoded, string $question): array
    {
        $primaryIntent = (string) ($decoded['primary_intent'] ?? 'unknown');
        if (! in_array($primaryIntent, $this->allowedIntents(), true)) {
            $primaryIntent = 'unknown';
        }

        $entities = is_array($decoded['entities'] ?? null) ? $decoded['entities'] : [];
        $courseName = $this->normalizeNullableString($entities['course_name'] ?? null);
        $quizName = $this->normalizeNullableString($entities['quiz_name'] ?? null);
        $featureName = $this->normalizeFeatureName($entities['feature_name'] ?? null, $question);
        $orderReference = $this->normalizeNullableString($entities['order_reference'] ?? null);
        $refundReference = $this->normalizeNullableString($entities['refund_reference'] ?? null);
        $requestedFields = $this->normalizeRequestedFields($decoded['requested_fields'] ?? [], $question, $primaryIntent);
        $referenceMode = $this->normalizeReferenceMode($decoded['reference_mode'] ?? null, $question, $quizName);

        $candidateIntents = is_array($decoded['candidate_intents'] ?? null) ? $decoded['candidate_intents'] : [];
        $candidateIntents = collect($candidateIntents)
            ->map(function ($item) {
                $intent = (string) ($item['intent'] ?? 'unknown');
                $confidence = (float) ($item['confidence'] ?? 0);

                if (! in_array($intent, $this->allowedIntents(), true)) {
                    $intent = 'unknown';
                }

                return [
                    'intent' => $intent,
                    'confidence' => max(0, min(1, $confidence)),
                ];
            })
            ->filter(fn($item) => in_array($item['intent'], $this->allowedIntents(), true))
            ->take(3)
            ->values()
            ->all();

        if (empty($candidateIntents)) {
            $candidateIntents = [[
                'intent' => $primaryIntent,
                'confidence' => $primaryIntent === 'unknown' ? 0.3 : 0.9,
            ]];
        }

        return [
            'primary_intent' => $primaryIntent,
            'candidate_intents' => $candidateIntents,
            'entities' => [
                'course_name' => $courseName,
                'quiz_name' => $quizName,
                'feature_name' => $featureName,
                'order_reference' => $orderReference,
                'refund_reference' => $refundReference,
            ],
            'requested_fields' => $requestedFields,
            'reference_mode' => $referenceMode,
            'needs_clarification' => (bool) ($decoded['needs_clarification'] ?? false),
            'clarification_question' => $this->normalizeNullableString($decoded['clarification_question'] ?? null),
            'clarification_reason' => $this->normalizeNullableString($decoded['clarification_reason'] ?? null),
            'reasoning_summary' => trim((string) ($decoded['reasoning_summary'] ?? '')),
        ];
    }

    protected function formatConversationHistory(array $conversationHistory): string
    {
        if (empty($conversationHistory)) {
            return '- Khong co hoi thoai truoc do.';
        }

        return collect($conversationHistory)
            ->map(function ($message) {
                $role = strtoupper((string) ($message['role'] ?? 'UNKNOWN'));
                $content = trim((string) ($message['content'] ?? ''));

                return '- ' . $role . ': ' . ($content !== '' ? $content : '[empty]');
            })
            ->implode("\n");
    }

    protected function normalizeFeatureName(mixed $rawFeatureName, string $question): ?string
    {
        $value = mb_strtolower(trim((string) ($rawFeatureName ?? '')));
        $haystack = mb_strtolower($question . ' ' . $value);

        $map = [
            'wishlist' => ['wishlist', 'yeu thich', 'yêu thích', 'wish list'],
            'certificate' => ['certificate', 'chung chi', 'chứng chỉ'],
            'quiz_history' => ['quiz history', 'lich su quiz', 'lịch sử quiz', 'ket qua quiz', 'kết quả quiz'],
            'refund' => ['refund', 'hoan tien', 'hoàn tiền'],
            'profile' => ['profile', 'ho so', 'hồ sơ', 'tai khoan', 'tài khoản'],
            'continue_learning' => ['continue learning', 'tiep tuc hoc', 'tiếp tục học', 'hoc tiep', 'học tiếp'],
            'my_courses' => ['my courses', 'khoa hoc cua toi', 'khóa học của tôi', 'khoa hoc da mua', 'khóa học đã mua'],
            'orders' => ['orders', 'order', 'don hang', 'đơn hàng'],
        ];

        foreach ($map as $feature => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    return $feature;
                }
            }
        }

        return in_array($value, $this->allowedFeatures(), true) ? $value : null;
    }

    protected function normalizeNullableString(mixed $value): ?string
    {
        $normalized = trim((string) ($value ?? ''));

        return $normalized === '' ? null : $normalized;
    }

    protected function normalizeRequestedFields(mixed $rawFields, string $question, string $primaryIntent): array
    {
        $fields = is_array($rawFields) ? $rawFields : [];

        $normalized = collect($fields)
            ->map(fn($field) => trim((string) $field))
            ->filter(fn($field) => in_array($field, $this->allowedRequestedFields(), true))
            ->values()
            ->all();

        if (! empty($normalized)) {
            return $normalized;
        }

        if ($primaryIntent !== 'quiz_history') {
            return ['summary'];
        }

        $haystack = mb_strtolower($question);
        $defaults = [];

        if (
            str_contains($haystack, 'con may luot')
            || str_contains($haystack, 'còn mấy lượt')
            || str_contains($haystack, 'con bao nhieu luot')
            || str_contains($haystack, 'còn bao nhiêu lượt')
        ) {
            $defaults[] = 'remaining_attempts';
        }

        if (
            str_contains($haystack, 'may luot')
            || str_contains($haystack, 'mấy lượt')
            || str_contains($haystack, 'da lam bao nhieu')
            || str_contains($haystack, 'đã làm bao nhiêu')
        ) {
            $defaults[] = 'attempts_used';
        }

        if (str_contains($haystack, 'diem') || str_contains($haystack, 'điểm')) {
            $defaults[] = 'latest_score';
        }

        if (str_contains($haystack, 'lan gan nhat') || str_contains($haystack, 'lần gần nhất')) {
            $defaults[] = 'latest_attempt';
        }

        if (empty($defaults)) {
            $defaults[] = 'summary';
        }

        return array_values(array_unique($defaults));
    }

    protected function normalizeReferenceMode(mixed $rawReferenceMode, string $question, ?string $quizName): string
    {
        $referenceMode = trim((string) ($rawReferenceMode ?? ''));

        if (in_array($referenceMode, $this->allowedReferenceModes(), true)) {
            return $referenceMode;
        }

        if ($quizName !== null) {
            return 'explicit';
        }

        $haystack = mb_strtolower($question);
        if (
            str_contains($haystack, 'quiz do')
            || str_contains($haystack, 'quiz đó')
            || str_contains($haystack, 'quiz toi da lam')
            || str_contains($haystack, 'quiz tôi đã làm')
            || trim($haystack) === 'cả 2'
            || trim($haystack) === 'ca 2'
        ) {
            return 'contextual_quiz';
        }

        return 'none';
    }

    protected function catalog(): array
    {
        return (array) config('services.website_assistant', []);
    }

    protected function intentCatalog(): array
    {
        return (array) ($this->catalog()['intents'] ?? []);
    }

    protected function allowedIntents(): array
    {
        return array_keys($this->intentCatalog());
    }

    protected function allowedFeatures(): array
    {
        return array_values((array) ($this->catalog()['features'] ?? []));
    }

    protected function allowedReferenceModes(): array
    {
        return array_values((array) ($this->catalog()['reference_modes'] ?? []));
    }

    protected function allowedRequestedFields(): array
    {
        return array_values((array) ($this->catalog()['requested_fields'] ?? []));
    }

    protected function buildIntentDetailsForPrompt(): string
    {
        $lines = [];

        foreach ($this->intentCatalog() as $intent => $definition) {
            $entities = implode(', ', (array) ($definition['entities'] ?? []));
            $fields = implode(', ', (array) ($definition['requested_fields'] ?? []));
            $modes = implode(', ', (array) ($definition['reference_modes'] ?? []));
            $examples = implode(' | ', (array) ($definition['examples'] ?? []));

            $lines[] = "- {$intent}: " . ($definition['description'] ?? '');
            $lines[] = "  entities: " . ($entities !== '' ? $entities : 'none');
            $lines[] = "  requested_fields: " . ($fields !== '' ? $fields : 'none');
            $lines[] = "  reference_modes: " . ($modes !== '' ? $modes : 'none');
            $lines[] = "  examples: " . ($examples !== '' ? $examples : 'none');
        }

        return implode("\n", $lines);
    }
}
