<?php

namespace App;

enum ArticleAbilities: string
{
    case ARTICLE_CREATE = 'article:create';
    case ARTICLE_UPDATE = 'article:update';
    case ARTICLE_UPDATE_ANY = 'article:update-any';
    case ARTICLE_DELETE = 'article:delete';
    case ARTICLE_DELETE_ANY = 'article:delete-any';
    case ARTICLE_CREATE_DENY = 'article:create:deny';
    case ARTICLE_UPDATE_DENY = 'article:update:deny';
    case ARTICLE_DELETE_DENY = 'article:delete:deny';
    case ARTICLE_UPDATE_ANY_DENY = 'article:update-any:deny';
    case ARTICLE_DELETE_ANY_DENY = 'article:delete-any:deny';

}
