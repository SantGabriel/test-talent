<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case FINANCE = 'finance';
    case USER = 'user';
}
