<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoogleRequest;
use App\Http\Requests\MailRequest;
use App\Http\Requests\StripeRequest;
use App\Models\Google;
use App\Models\Smtp;
use App\Models\Striipe;
use Illuminate\Http\Request;

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
}
