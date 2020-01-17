<?php

// if (! function_exists('attachment_path')) {
//     /**
//      * @param string $path
//      *
//      * @return string
//      */
//     function attachment_path($path = '')
//     {
//         return public_path($path ? 'attachments'.DIRECTORY_SEPARATOR.$path : 'attachments');
//     }
// }

// function gravatar_profile_url($email)
// {
//     return sprintf("//www.gravatar.com/%s", md5($email));
// }

// function gravatar_url($email, $size = 72)
// {
//     return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
// }

if (! function_exists('markdown')) {
    /**
     * Compile the given text to markdown document.
     *
     * @param string $text
     * @return string
     */
    function markdown($text)
    {
        return app(ParsedownExtra::class)->text($text);
        //composer require erusev/parsedown-extra  이후 실행해야 함.
    }
}

if (! function_exists('icon')) {
    /**
     * Generate FontAwesome icon tag
     *
     * @param string $class    font-awesome class name
     * @param string $addition additional class
     * @param string $inline   inline style
     * @return string
     */
    function icon($class, $addition = 'icon', $inline = null)
    {
        $icon = config('icons.' . $class);
        $inline = $inline ? 'style="' . $inline . '"' : null;

        return sprintf('<i class="%s %s" %s></i>', $icon, $addition, $inline);
    }
}

if (! function_exists('attachment_path')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function attachment_path($path = '')
    {
        return public_path($path ? 'attachments' . DIRECTORY_SEPARATOR . $path : 'attachments');
    }
}

if (! function_exists('gravatar_profile_url')) {
    /**
     * Get gravatar profile page url
     *
     * @param  string $email
     * @return string
     */
    function gravatar_profile_url($email)
    {
        return sprintf("//www.gravatar.com/%s", md5($email));
    }
}

if (! function_exists('gravatar_url')) {
    /**
     * Get gravatar image url
     *
     * @param  string  $email
     * @param  integer $size
     * @return string
     */
    function gravatar_url($email, $size = 48)
    {
        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
    }
}

if (! function_exists('taggable')) {
    /**
     * Determine if the current cache driver has cacheTags() method
     *
     * @return bool
     */
    function taggable()
    {
        return ! in_array(config('cache.default'), ['file', 'database']);
    }
}

if (! function_exists('link_for_sort')) {
    /**
     * Build HTML anchor tag for sorting
     *
     * @param string $column
     * @param string $text
     * @param array  $params
     * @return string
     */
    function link_for_sort($column, $text, $params = [])
    {
        $config = config('project.params');

        $direction = request()->input($config['order']);
        $reverse = ($direction == 'asc') ? 'desc' : 'asc';

        if (request()->input($config['sort']) == $column) {
            // Update passed $text var, only if it is active sort
            $text = sprintf(
                "%s %s",
                $direction == 'asc' ? icon('asc') : icon('desc'),
                $text
            );
        }

        $queryString = http_build_query(array_merge(
            request()->except([$config['page'], $config['sort'], $config['order']]),
            [$config['sort'] => $column, $config['order'] => $reverse],
            $params
        ));

        return sprintf(
            '<a href="%s?%s">%s</a>',
            urldecode(request()->url()),
            $queryString,
            $text
        );
    }
}

if (! function_exists('current_url')) {
    /**
     * Build current url string, without return param.
     *
     * @return string
     */
    function current_url()
    {
        if (! request()->has('return')) {
            return request()->fullUrl();
        }

        return sprintf(
            '%s?%s',
            request()->url(),
            http_build_query(request()->except('return'))
        );
    }
}

if (! function_exists('is_api_request')) {
    /**
     * Determine if the current request is for HTTP api.
     *
     * @return bool
     */
    function is_api_request()
    {
        return starts_with(request()->getHttpHost(), config('project.api_domain'));
    }
}

if (! function_exists('optimus')) {
    /**
     * Create Optimus instance.
     *
     * @param int|null $id
     * @return int|\Jenssegers\Optimus\Optimus
     */
    function optimus($id = null)
    {
        $factory = app(\Jenssegers\Optimus\Optimus::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->encode($id);
    }
}

if (! function_exists('cache_key')) {
    /**
     * Generate key for caching.
     *
     * Note. Even though the request endpoints are the same,
     *       the response body should be different because of the query string.
     *
     * @param $base
     * @return string
     */
    function cache_key($base)
    {
        $key = ($uri = request()->fullUrl())
            ? $base . '.' . urlencode($uri)
            : $base;

        return md5($key);
    }
}