<?php

namespace App;

enum ArticlePermissions: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
