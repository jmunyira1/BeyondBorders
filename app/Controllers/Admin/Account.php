<?php

namespace App\Controllers\Admin;

use CodeIgniter\Shield\Entities\User;

class Account extends AdminController
{
    public function index(): string
    {
        return view('admin/account/index', $this->layout([
            'title'       => 'My account',
            'heading'     => 'My account',
            'subheading'  => 'Your sign-in details.',
            'activeAdmin' => 'account',
            'user'        => auth()->user(),
        ]));
    }

    public function changePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|strong_password[]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        /** @var User $user */
        $user = auth()->user();

        // Re-check the current password before allowing a change, so a walk-up
        // on an unlocked session cannot take the account over.
        $result = auth('session')->check([
            'email'    => $user->email,
            'password' => $this->request->getPost('current_password'),
        ]);

        if (! $result->isOK()) {
            return redirect()->back()->with('error', 'Your current password is not correct.');
        }

        $users = auth()->getProvider();
        $user->password = $this->request->getPost('new_password');
        $users->save($user);

        return redirect()->to(site_url('admin/account'))->with('message', 'Password changed.');
    }
}
