<?php

namespace Core\Repositories;

use App\Models\User;
use Core\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{

    public function update($request)
    {
        if(! empty($request->all())) {
            $id = Auth::user()->id;
            $item = User::find($id);
            $item->full_name = $request->fullname;
            $item->phone = $request->phone;
            $item->save();
            
            return true;
        }
    }
    public function changePassword($request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:9'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
        
    }
    
}
