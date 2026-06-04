<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\RememberToken;
use Illuminate\Console\Command;

class PruneExpiredRememberTokens extends Command
{
    protected $signature = 'auth:prune-remember-tokens';
    protected $description = 'Remove expired remember tokens from the database';

    public function handle(): int
    {
        $deleted = RememberToken::where('expires_at', '<', now())->delete();

        $this->info("Deleted {$deleted} expired remember token(s).");

        return self::SUCCESS;
    }
}
