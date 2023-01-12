<?php namespace Webmaxx\Cache\Enums;

enum Path: string
{
    case CMS_CACHE = '/cms/cache';
    case CMS_COMBINER = '/cms/combiner';
    case CMS_TWIG = '/cms/twig';
    case FRAMEWORK_CACHE = '/framework/cache';
    case UPLOAD_IMAGES = '/app/uploads/public';
}
