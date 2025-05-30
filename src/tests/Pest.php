<?php
// tests/Pest.php

use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Apply these to all Feature tests
uses(
    Tests\TestCase::class,
    RefreshDatabase::class
)->in('Feature');
