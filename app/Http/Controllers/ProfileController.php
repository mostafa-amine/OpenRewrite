<?php

namespace App\Http\Controllers;

use Exception;
use Dotenv\Dotenv;
use Illuminate\Http\Request;
use App\Services\OpenaiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Set user token
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setToken(Request $request)
    {
        //  Get The environment File Path
        $envFile = app()->environmentFilePath();
        // Get the contenct of the .env file
        $contents = file_get_contents($envFile);
        // Replace the OPENAI_API_KEY value by the user value
        $contents = preg_replace("/OPENAI_API_KEY=(.*)/", "OPENAI_API_KEY=$request->token", $contents);
        // Check if the token valid

        try{
            OpenaiService::generate($request->token , "Test");
        }
        catch(Exception $e) {
            return redirect()->back()->withErrors(['token' => 'The API token is not valid']);
        }

        // Puth the new value in the .env file
        file_put_contents($envFile, $contents);

        // Flash message
        session()->flash('status' , 'token-valid');


        return Redirect::to('/profile');
    }
}
