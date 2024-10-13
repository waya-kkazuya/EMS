<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        Gate::authorize('user-higher');

        $user = Auth::user();
        $profile_image = $user->profile_image;

        // dd($profile_image);
        
        if (is_null($profile_image)) {
            $profile_image_path = asset('storage/profile/profile_default_image.png');
        } else {
            if (Storage::disk('public')->exists('profile/' . $profile_image)) {
                $profile_image_path = asset('storage/profile/' . $profile_image);
            } else {
                $profile_image_path = asset('storage/profile/profile_default_image.png');
            }
        }
        
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'profile_image_path' => $profile_image_path
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        Gate::authorize('user-higher');

        DB::beginTransaction();

        try{
            $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            // Storageへの画像の保存
            $profileImagefileNameToStore = null;
            if(!is_null($request->profile_image_file) && $request->profile_image_file->isValid() ){
                // 古い画像があれば削除
                if ($request->user()->profile_image) {
                    Storage::disk('public')->delete('profile/' . $request->user()->profile_image);
                }

                // 画像ファイルのアップロードとDBのimage1のファイル名更新
                $profileImagefileNameToStore = ImageService::profileImageResizeUpload($request->profile_image_file);
                $request->user()->profile_image = $profileImagefileNameToStore;
            }
            
            $request->user()->save();

            DB::commit();

            return Redirect::route('profile.edit')
            ->with([
                'message' => 'プロフィールを更新しました。',
                'status' => 'success'
            ]);
        } catch(ValidationException $e) {
            DB::rollBack();

            // アップロードした備品の画像の削除
            $profileImagePath = 'profile/' . $profileImagefileNameToStore;
            if (Storage::disk('public')->exists($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }

            return redirect()->back()
            ->with([
                'message' => 'プロフィールの更新中にエラーが発生しました',
                'status' => 'danger'
            ]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Gate::authorize('staff-higher');

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
