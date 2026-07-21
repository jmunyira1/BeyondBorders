<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

/**
 * Creates the first admin account. Idempotent — re-running the seeder leaves an
 * existing account alone rather than duplicating or resetting it.
 */
class AdminUserSeeder extends Seeder
{
    public const EMAIL    = 'admin@beyondbordersadventures.co.ke';
    public const USERNAME = 'admin';

    /** Temporary. Change it on first login via Admin → My Account. */
    public const PASSWORD = 'Karibu#Mara2026';

    public function run(): void
    {
        $users = auth()->getProvider();

        if ($users->findByCredentials(['email' => self::EMAIL]) !== null) {
            echo "Admin user already exists — left unchanged.\n";

            return;
        }

        $user = new User([
            'username' => self::USERNAME,
            'email'    => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $users->save($user);
        $user = $users->findById($users->getInsertID());
        $user->addGroup('superadmin');

        echo "\n  Admin account created\n";
        echo '  Email:    ' . self::EMAIL . "\n";
        echo '  Password: ' . self::PASSWORD . "\n";
        echo "  Change this password after your first login.\n\n";
    }
}
