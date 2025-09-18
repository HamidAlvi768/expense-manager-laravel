<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;


class ForgotPasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $email = $request->email;
    
        // Check if a token exists for the email within the last 2 minutes
        $existingReset = DB::table('password_resets')
            ->where('email', $email)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->first();
    
        if ($existingReset) {
            return back()->with('status', 'A password reset link was recently sent. Please wait a few minutes before requesting again.');
        }
    
        // Generate a unique plain token
        $plainToken = bin2hex(random_bytes(32));
    
        // Store the plain token in the database (no hashing)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $plainToken,
                'created_at' => now(),
            ]
        );
    
        // Generate reset link with plain token
        $resetLink = route('reset-password.form', ['token' => $plainToken]);
    
        // Send reset email
        Mail::to($email)->send(new PasswordResetMail($resetLink));
    
        return back()->with('status', 'If the email exists, a password reset link has been sent.');
    }

    public function clearAndCache()
    {
        try {
            // Clear cache
            Artisan::call('cache:clear');

            // Cache configuration
            Artisan::call('config:cache');

            return response()->json([
                'status' => 'success',
                'message' => 'Cache cleared and configuration cached successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while executing commands.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        // Validate the password fields and token
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
    
        // Find the password reset record in the database
        $record = DB::table('password_resets')->where('token', $request->token)->first();
    
        // Check if the token exists and is not expired
        if (!$record || Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return back()->withErrors(['token' => 'Invalid or expired reset link.']);
        }
    
        // Find the user by email
        $user = User::where('email', $record->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }
    
        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

            // Log the user in after updating the password
        Auth::login($user);

    
        // Delete the reset token from the database
        DB::table('password_resets')->where('email', $record->email)->delete();
    
        // Redirect back to the reset page with a success message
        return redirect()->route('login')->with('status', 'Your password has been reset successfully. Please log in with your new password.');
    }
    
    
    
    
}
