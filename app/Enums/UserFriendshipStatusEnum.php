<?php

namespace App\Enums;

enum UserFriendshipStatusEnum
{
    use Enum;

    case PENDING;
    case ACCEPTED;
    case BLOCKED;
}
