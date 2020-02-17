<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:sa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating Super Admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::create([
            'username' => 'shahadat',
            'name' => 'Shahadat Hossen',
            'email' => 'shobuj@bansberrysg.com',
            'password' => Hash::make('sdkShobuj91')
        ]);

        $role = 'Super Admin';
        if($user->assignRole($role)){
            $this->info("Super admin created successfully");
        }
    }
}
