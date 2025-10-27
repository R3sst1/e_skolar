<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SystemSettingsController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        $categories = [
            'limits' => 'Scholar Limits',
            'disbursement' => 'Disbursement Settings',
            'retention' => 'Retention Requirements',
            'renewal' => 'Renewal Criteria',
            'notifications' => 'Email Notifications',
            'general' => 'General Settings'
        ];

        $settings = [];
        foreach ($categories as $category => $label) {
            $settings[$category] = SystemSetting::getByCategory($category);
        }

        return view('dashboardcontent.super-admin.system-settings', compact('settings', 'categories'));
    }

    public function update(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->settings as $setting) {
                $systemSetting = SystemSetting::where('key', $setting['key'])->first();
                
                if ($systemSetting) {
                    // Convert value based on type
                    $value = $this->convertValueByType($setting['value'], $systemSetting->type);
                    $systemSetting->update(['value' => $value]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'System settings updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByCategory($category)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $settings = SystemSetting::getByCategory($category);
        return response()->json($settings);
    }

    public function getValue($key)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $value = SystemSetting::getValue($key);
        return response()->json(['value' => $value]);
    }

    public function testEmail(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $fromEmail = SystemSetting::getValue('notification_email_from', 'scholarship@example.com');
            $fromName = SystemSetting::getValue('notification_email_name', 'Scholarship System');

            \Mail::raw('This is a test email from the Scholarship System to verify email configuration.', function ($message) use ($request, $fromEmail, $fromName) {
                $message->from($fromEmail, $fromName)
                        ->to($request->email)
                        ->subject('Test Email - Scholarship System');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    private function convertValueByType($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'decimal':
                return (float) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return is_string($value) ? json_decode($value, true) : $value;
            default:
                return $value;
        }
    }
}
