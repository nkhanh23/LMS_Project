<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeminiSettingRequest;
use App\Http\Requests\GoogleRequest;
use App\Http\Requests\MailRequest;
use App\Http\Requests\StripeRequest;
use App\Models\GeminiSetting;
use App\Models\Google;
use App\Models\Smtp;
use App\Models\Striipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function mailSetting()
    {
        $mailSettings = Smtp::first();
        return view('backend.admin.setting.mail.index', compact('mailSettings'));
    }

    public function updateMailSettings(MailRequest $request)
    {
        Smtp::updateOrCreate(
            ['id' => 1],
            $request->validated()
        );
        return redirect()->back()->with('success', 'Cập nhật cài đặt Mail thành công');
    }

    public function stripeSetting()
    {
        $stripeSettings = Striipe::first();
        return view('backend.admin.setting.stripe.index', compact('stripeSettings'));
    }

    public function updateStripeSettings(StripeRequest $request)
    {
        Striipe::updateOrCreate(
            ['id' => 1],
            $request->validated()
        );
        return redirect()->back()->with('success', 'Cập nhật cài đặt Stripe thành công');
    }

    public function googleSetting()
    {
        $google = Google::first();
        return view('backend.admin.setting.google.index', compact('google'));
    }

    public function updateGoogleSettings(GoogleRequest $request)
    {
        Google::updateOrCreate(
            ['id' => 1],
            $request->validated()
        );
        return redirect()->back()->with('success', 'Cập nhật cài đặt Google thành công');
    }

    public function geminiSetting()
    {
        $geminiSettings = GeminiSetting::first();
        return view('backend.admin.setting.gemini.index', compact('geminiSettings'));
    }

    public function updateGeminiSettings(GeminiSettingRequest $request)
    {
        $current = GeminiSetting::first();

        $apiKey = $request->input('api_key');

        GeminiSetting::updateOrCreate(
            ['id' => 1],
            [
                'api_key' => $apiKey,
                'base_url' => $request->string('base_url')->toString() ?: null,
                'model_name' => $request->string('model_name')->toString(),
                'timeout_seconds' => $request->integer('timeout_seconds'),
                'temperature' => (float) $request->input('temperature'),
                'max_output_tokens' => $request->integer('max_output_tokens'),
                'is_enabled' => $request->boolean('is_enabled'),
                'updated_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Cập nhật cài đặt Gemini thành công');
    }
}
